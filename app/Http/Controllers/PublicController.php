<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\College;
use App\Models\Specialization;
use App\Models\University;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PublicController extends Controller
{
    public function home()
    {
        $universities = University::whereNotIn('status', ['معلقة', 'محظورة'])
            ->withCount([
                'colleges' => fn($q) => $q->where('colleges.status', 'نشطة')->whereDoesntHave('activeModeration'),
                'specializations' => fn($q) => $q->where('specializations.status', 'نشط')->whereDoesntHave('activeModeration')
            ])
            ->with('activeModerations')
            ->get();

        $stats = [
            'universities_count' => $universities->count(),
            'colleges_count' => College::where('colleges.status', 'نشطة')->whereDoesntHave('activeModeration')->count(),
            'specializations_count' => Specialization::where('specializations.status', 'نشط')->whereDoesntHave('activeModeration')->count(),
        ];

        // جلب إعلانات الصفحة الرئيسية (كل الباقات المعتمدة والنشطة)
        $activeAds = Advertisement::with('university')
            ->where('review_status', 'معتمد')
            ->where('status', 'نشط')
            ->where(function($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->orderBy('duration', 'desc') // الإعلانات الأطول مدة أولاً (أعلى أولوية)
            ->take(5)
            ->get();

        $activeAdsData = $activeAds->map(function($ad) {
            $media = $ad->mediaFilesArray();
            $formattedMedia = array_values(array_filter(array_map(function($item) {
                if (!$item) return null;
                if (str_starts_with($item, 'http://') || str_starts_with($item, 'https://')) {
                    return $item;
                }
                return asset(ltrim($item, '/'));
            }, is_array($media) ? $media : [])));

            $videoUrl = $ad->video_url ? (str_starts_with($ad->video_url, 'http://') || str_starts_with($ad->video_url, 'https://') ? $ad->video_url : asset(ltrim($ad->video_url, '/'))) : null;

            return [
                'uni'       => $ad->university?->university_name ?? '',
                'title'     => $ad->adv_title,
                'desc'      => $ad->adv_content ?? '',
                'img'       => $formattedMedia[0] ?? null,
                'gallery'   => $formattedMedia,
                'video'     => $videoUrl,
                'mediaType' => $ad->media_type ?? 'image',
                'link'      => route('universities.show', $ad->university_id),
                'package'   => $ad->package,
            ];
        });

        return view('home', compact('universities', 'stats', 'activeAds', 'activeAdsData'));
    }

    public function universities(Request $request)
    {
        $query = University::whereNotIn('status', ['معلقة', 'محظورة'])
            ->withCount([
                'colleges' => fn($q) => $q->where('colleges.status', 'نشطة')->whereDoesntHave('activeModeration'),
                'specializations' => fn($q) => $q->where('specializations.status', 'نشط')->whereDoesntHave('activeModeration')
            ])
            ->with('activeModerations');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('university_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type') && in_array($request->input('type'), ['حكومية', 'أهلية'])) {
            $query->where('university_type', $request->input('type'));
        }

        if ($request->filled('district')) {
            $query->where('district', 'like', "%{$request->input('district')}%");
        }

        $universities = $query->paginate(9)->withQueryString();
        $districts = University::whereNotIn('status', ['معلقة', 'محظورة'])->select('district')->distinct()->pluck('district');

        // جلب إعلانات دليل الجامعات (مميزة وشاملة فقط)
        $directoryAds = Advertisement::with('university')
            ->where('review_status', 'معتمد')
            ->where('status', 'نشط')
            ->whereIn('package', ['مميزة', 'شاملة'])
            ->where(function($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->orderBy('duration', 'desc')
            ->take(3)
            ->get();

        return view('universities.index', compact('universities', 'districts', 'directoryAds'));
    }

    public function showUniversity($id)
    {
        $isPreview = false;
        $loggedUniv = null;
        if (Auth::check() && Auth::user()->user_type === 'university') {
            $loggedUniv = Auth::user()->university;
            // في وضع المعاينة لمسؤول الجامعة: يتم قصر العرض على بيانات الجامعة التي سجلت الدخول فقط
            if ($loggedUniv) {
                $id = $loggedUniv->university_id;
                $isPreview = true;
            }
        }

        $university = University::with([
            'colleges' => function ($q) use ($isPreview) {
                if (!$isPreview) {
                    $q->where('status', 'نشطة')->whereDoesntHave('activeModeration');
                }
                $q->with([
                    'specializations' => function ($sq) use ($isPreview) {
                        if (!$isPreview) {
                            $sq->where('status', 'نشط')->whereDoesntHave('activeModeration');
                        }
                        $sq->with(['admissionData', 'activeModeration']);
                    },
                    'activeModeration'
                ]);
            },
            'activeModerations'
        ])->findOrFail($id);

        $allUniversitiesQuery = University::whereNotIn('status', ['معلقة', 'محظورة'])
            ->select('university_id', 'university_name', 'university_type')
            ->with([
                'colleges' => function ($cq) {
                    $cq->where('status', 'نشطة')->select('college_id', 'university_id', 'college_name')
                        ->with(['specializations' => function ($sq) {
                            $sq->where('status', 'نشط')->whereDoesntHave('activeModeration')->select('specialization_id', 'college_id', 'specialization_name', 'tuition_fees', 'duration_years');
                        }]);
                }
            ])
            ->orderBy('university_type', 'asc')
            ->orderBy('university_name', 'asc');

        if ($isPreview && $loggedUniv) {
            $allUniversities = $allUniversitiesQuery->where('university_id', $loggedUniv->university_id)->get();
        } else {
            $allUniversities = $allUniversitiesQuery->get();
        }

        return view('universities.show', compact('university', 'allUniversities', 'isPreview'));
    }

    public function compare()
    {
        $universities = University::whereNotIn('status', ['معلقة', 'محظورة'])
            ->with(['colleges.specializations.admissionData'])
            ->get();

        $allUnivsData = [];
        foreach ($universities as $u) {
            $collegesList = [];
            foreach ($u->colleges as $c) {
                $specsList = [];
                foreach ($c->specializations as $s) {
                    $specsList[] = [
                        'name' => $s->specialization_name,
                        'fees' => number_format((float) $s->tuition_fees),
                        'min_avg' => $s->admissionData->minimum_average ?? '65',
                        'years' => str_contains($s->specialization_name, 'طب') ? '5' : '4'
                    ];
                }
                $collegesList[] = [
                    'name' => $c->college_name,
                    'specs' => $specsList
                ];
            }
            $allUnivsData[$u->university_id] = [
                'id' => $u->university_id,
                'name' => $u->university_name,
                'colleges' => $collegesList
            ];
        }

        return view('compare', compact('universities', 'allUnivsData'));
    }

    public function aiAdvisor()
    {
        return view('ai_advisor');
    }

    /**
     * جلب بيانات القبول والتسجيل المعتمدة والوثائق لتخصص محدد (عرض الطالب)
     */
    public function getSpecializationAdmissionData($id)
    {
        $spec = Specialization::with(['admissionData', 'college.university'])
            ->where('status', 'نشط')
            ->whereDoesntHave('activeModeration')
            ->findOrFail($id);

        $admission = $spec->admissionData;
        $docs = $admission ? $admission->docs : collect();

        return response()->json([
            'success' => true,
            'specialization' => [
                'id' => $spec->specialization_id,
                'name' => $spec->specialization_name,
                'college_name' => $spec->college->college_name,
                'university_name' => $spec->college->university->university_name,
                'tuition_fees' => (float)$spec->tuition_fees,
                'duration_years' => $spec->duration_years ?? 4,
                'language' => $spec->language ?? 'العربية',
                'status' => $spec->status,
                'description' => $spec->description,
            ],
            'admission' => [
                'minimum_average' => (float)($admission->minimum_average ?? 65.0),
                'secondary_last_year' => (int)($admission->secondary_last_year ?? 2025),
                'admission_data' => $admission->admission_data ?? 'شروط وضوابط القبول المعتمدة',
                'description' => $admission->description ?? '',
                'tuition_fees' => (float)($admission->tuition_fees ?? $spec->tuition_fees),
                'registration_fee' => (float)($admission->registration_fee ?? 0.0),
                'admission_start_date' => $admission && $admission->admission_start_date ? $admission->admission_start_date->format('Y-m-d') : null,
                'admission_end_date' => $admission && $admission->admission_end_date ? $admission->admission_end_date->format('Y-m-d') : null,
                'admission_status' => $admission->admission_status ?? 'مفتوح',
                'documents' => $docs->map(fn($d) => [
                    'doc_id' => $d->doc_id,
                    'name' => $d->document_name,
                    'document_name' => $d->document_name,
                    'description' => $d->description,
                    'is_required' => (bool)$d->is_required,
                ]),
            ]
        ]);
    }
}
