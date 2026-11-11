<?php

namespace Database\Seeders;

use App\Models\AdmissionData;
use App\Models\College;
use App\Models\RegDoc;
use App\Models\RegRequest;
use App\Models\Specialization;
use App\Models\Student;
use App\Models\University;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. حساب مدير النظام (Admin)
        $adminUser = User::create([
            'username' => 'admin',
            'password' => 'admin123',
            'user_type' => 'admin',
            'phone' => '770000000',
            'email' => 'admin@eduyemen.ye',
            'account_status' => 'active',
        ]);

        // 2. حساب وتفاصيل الطالب التجريبي (Student)
        $studentUser = User::create([
            'username' => 'student1',
            'password' => 'student123',
            'user_type' => 'student',
            'phone' => '771234567',
            'email' => 'student@gmail.com',
            'account_status' => 'active',
        ]);

        $student = Student::create([
            'user_id' => $studentUser->user_id,
            'full_name' => 'ضياء محمد علي العودي',
            'gender' => 'ذكر',
            'birth_date' => '2004-06-15',
            'address' => 'صنعاء - شارع الستين الغربي',
        ]);

        // قائمة الوثائق القياسية لكل تخصص
        $commonDocuments = [
            [
                'name' => 'أصل استمارة الثانوية العامة',
                'description' => 'صورة طبق الأصل أو الأصل للمطابقة',
                'is_required' => true,
            ],
            [
                'name' => 'صورة البطاقة الشخصية أو جواز السفر',
                'description' => 'وثيقة إثبات الهوية سارية المفعول',
                'is_required' => true,
            ],
            [
                'name' => 'صور شخصية حديثة (4×6)',
                'description' => 'خلفية بيضاء عدد 4 صور',
                'is_required' => true,
            ],
            [
                'name' => 'شهادة اللياقة الطبية والفحص المخبري',
                'description' => 'مطلوبة للتخصصات الطبية والصحية',
                'is_required' => false,
            ],
        ];

        // 3. الجامعات والكليات والتخصصات وشروط القبول
        $universitiesData = [
            [
                'username' => 'univ_sanaa',
                'email' => 'info@su.edu.ye',
                'name' => 'جامعة صنعاء',
                'type' => 'حكومية',
                'district' => 'معين',
                'address' => 'صنعاء - مذبح - الدائري الغربي',
                'contact_phone' => '01-464471',
                'phone' => '01-464470',
                'year_establ' => 1970,
                'website' => 'https://su.edu.ye',
                'description' => 'جامعة صنعاء هي أول جامعة حكومية في الجمهورية اليمنية وتعد الصرح الأكاديمي الأم في البلاد.',
                'colleges' => [
                    [
                        'name' => 'كلية الحاسوب وتكنولوجيا المعلومات',
                        'description' => 'تعنى بعلوم الحاسوب والبرمجيات وتقنيات الذكاء الاصطناعي والشبكات.',
                        'specs' => [
                            ['name' => 'علوم الحاسوب (CS)', 'fees' => 350000, 'min_avg' => 78.00, 'admission' => 'القسم العلمي بمعدل لا يقل عن 78% واجتياز اختبار المفاضلة.'],
                            ['name' => 'تقنية المعلومات (IT)', 'fees' => 380000, 'min_avg' => 75.00, 'admission' => 'ثانوية عامة علمي بمعدل لا يقل عن 75% مع المفاضلة.'],
                            ['name' => 'الأمن السيبراني والشبكات', 'fees' => 400000, 'min_avg' => 80.00, 'admission' => 'القسم العلمي بمعدل 80% فما فوق واختبار كفاءة باللغة والرياضيات.'],
                        ]
                    ],
                    [
                        'name' => 'كلية الطب والعلوم الصحية',
                        'description' => 'كلية رائدة في تدريب الكوادر الطبية على أعلى المستويات الأكاديمية.',
                        'specs' => [
                            ['name' => 'الطب والجراحة العامة', 'fees' => 950000, 'min_avg' => 85.00, 'admission' => 'القسم العلمي 85% كحد أدنى واجتياز اختبار المفاضلة الشامل المقر من الوزارة.'],
                            ['name' => 'الصيدلة السريرية', 'fees' => 650000, 'min_avg' => 80.00, 'admission' => 'القسم العلمي بمعدل 80% واختبار المفاضلة التنافسي.'],
                        ]
                    ]
                ]
            ],
            [
                'username' => 'univ_ust',
                'email' => 'admission@ust.edu.ye',
                'name' => 'جامعة العلوم والتكنولوجيا',
                'type' => 'أهلية',
                'district' => 'الستين الغربي',
                'address' => 'صنعاء - شارع الستين الغربي - مذبح',
                'contact_phone' => '01-373237',
                'phone' => '01-373238',
                'year_establ' => 1994,
                'website' => 'https://ust.edu.ye',
                'description' => 'أول جامعة أهلية معتمدة في الجمهورية اليمنية وتتميز بتصنيف أكاديمي متقدم وبيئة تعليمية حديثة.',
                'colleges' => [
                    [
                        'name' => 'كلية الهندسة وتكنولوجيا المعلومات',
                        'description' => 'تضم تخصصات هندسية وتقنية متقدمة وفق معايير الجودة الدولية.',
                        'specs' => [
                            ['name' => 'هندسة البرمجيات', 'fees' => 520000, 'min_avg' => 70.00, 'admission' => 'ثانوية عامة قسم علمي بمعدل لا يقل عن 70% للعام 2020 فما بعد.'],
                            ['name' => 'الذكاء الاصطناعي وعلم البيانات', 'fees' => 550000, 'min_avg' => 72.00, 'admission' => 'ثانوية علمي لا تقل عن 72% واستيفاء الوثائق المطلوبة.'],
                            ['name' => 'الأمن السيبراني والتحري الرقمي', 'fees' => 540000, 'min_avg' => 70.00, 'admission' => 'ثانوية عامة قسم علمي بمعدل لا يقل عن 70% وتعبئة طلب التقديم.'],
                        ]
                    ],
                    [
                        'name' => 'كلية العلوم الإدارية والإنسانية',
                        'description' => 'إعداد قادة الأعمال والمصارف والمحاسبين المعتمدين.',
                        'specs' => [
                            ['name' => 'المحاسبة ونظم المعلومات المالية', 'fees' => 380000, 'min_avg' => 65.00, 'admission' => 'يقبل خريجي القسمين العلمي والأدبي بمعدل لا يقل عن 65%.'],
                            ['name' => 'إدارة الأعمال الدولية', 'fees' => 400000, 'min_avg' => 65.00, 'admission' => 'القسم العلمي أو الأدبي بنسبة 65% كحد أدنى.'],
                        ]
                    ]
                ]
            ],
            [
                'username' => 'univ_saeeda',
                'email' => 'info@alsaeeda-univ.net',
                'name' => 'جامعة السعيدة',
                'type' => 'أهلية',
                'district' => 'شارع الجزائر',
                'address' => 'صنعاء - شارع الجزائر - تقاطع عمان',
                'contact_phone' => '01-449830',
                'phone' => '01-449831',
                'year_establ' => 2011,
                'website' => 'https://alsaeeda-univ.net',
                'description' => 'جامعة السعيدة تسعى لتوفير تعليم أكاديمي متميز يربط المعرفة النظرية بالتطبيق العملي.',
                'colleges' => [
                    [
                        'name' => 'كلية الهندسة وتقنية المعلومات',
                        'description' => 'تضم أقسام تقنية المعلومات وهندسة الشبكات والأنظمة الذكية.',
                        'specs' => [
                            ['name' => 'تقنية المعلومات (IT)', 'fees' => 420000, 'min_avg' => 65.00, 'admission' => 'ثانوية عامة قسم علمي بنسبة 65% فما فوق.'],
                            ['name' => 'نظم المعلومات الإدارية (MIS)', 'fees' => 360000, 'min_avg' => 60.00, 'admission' => 'يقبل القسمين العلمي والأدبي بمعدل 60% فما فوق.'],
                        ]
                    ],
                    [
                        'name' => 'كلية العلوم الطبية',
                        'description' => 'تأهيل كوادر طبية متميزة في مجالات المختبرات والصيدلة والتمريض.',
                        'specs' => [
                            ['name' => 'دكتور صيدلة (Pharm D)', 'fees' => 700000, 'min_avg' => 75.00, 'admission' => 'القسم العلمي بنسبة لا تقل عن 75% واستيفاء الفحوصات الطبية.'],
                            ['name' => 'طب وجراحة الفم والأسنان', 'fees' => 850000, 'min_avg' => 80.00, 'admission' => 'القسم العلمي بنسبة 80% فما فوق مع اجتياز المقابلة الشخصية.'],
                        ]
                    ]
                ]
            ],
            [
                'username' => 'univ_arwa',
                'email' => 'reg@qau.edu.ye',
                'name' => 'جامعة الملكة أروى',
                'type' => 'أهلية',
                'district' => 'الستين الجنوبي',
                'address' => 'صنعاء - تقاطع شارع 60 مع شارع أروى - فج عطان',
                'contact_phone' => '01-211400',
                'phone' => '01-211401',
                'year_establ' => 1996,
                'website' => 'https://qau.edu.ye',
                'description' => 'رائدة التعليم العالي الأهلي في اليمن، وتضم كليات متميزة في العلوم الإنسانية والهندسة والطب.',
                'colleges' => [
                    [
                        'name' => 'كلية العلوم الطبية والصحية',
                        'description' => 'إعداد كوادر طبية ذات كفاءة عالية وتأهيل علمي وعملي رصين.',
                        'specs' => [
                            ['name' => 'المختبرات الطبية', 'fees' => 480000, 'min_avg' => 70.00, 'admission' => 'شهادة الثانوية العامة القسم العلمي بنسبة 70% كحد أدنى.'],
                            ['name' => 'صيدلة سريرية', 'fees' => 680000, 'min_avg' => 75.00, 'admission' => 'القسم العلمي 75% فما فوق.'],
                        ]
                    ]
                ]
            ],
            [
                'username' => 'univ_razi',
                'email' => 'info@alraziuni.edu.ye',
                'name' => 'جامعة الرازي',
                'type' => 'أهلية',
                'district' => 'شارع الرباط',
                'address' => 'صنعاء - شارع الرباط خلف محطة القدس',
                'contact_phone' => '01-406760',
                'phone' => '01-406761',
                'year_establ' => 2009,
                'website' => 'https://alraziuni.edu.ye',
                'description' => 'جامعة متخصصة ورائدة في العلوم الطبية والصحية والعلوم الإدارية والتقنية.',
                'colleges' => [
                    [
                        'name' => 'كلية الحاسوب وتكنولوجيا المعلومات',
                        'description' => 'برامج تقنية نوعية تلبي متطلبات التحول الرقمي.',
                        'specs' => [
                            ['name' => 'الأمن السيبراني والتحري الرقمي', 'fees' => 490000, 'min_avg' => 68.00, 'admission' => 'الثانوية العامة علمي بمعدل لا يقل عن 68%.'],
                        ]
                    ]
                ]
            ]
        ];

        $targetUniversityForRequest = null;
        $targetSpecNameForRequest = '';

        foreach ($universitiesData as $uData) {
            $user = User::create([
                'username' => $uData['username'],
                'password' => 'password123',
                'user_type' => 'university',
                'phone' => $uData['phone'],
                'email' => $uData['email'],
                'account_status' => 'active',
            ]);

            $univ = University::create([
                'user_id' => $user->user_id,
                'university_name' => $uData['name'],
                'university_type' => $uData['type'],
                'description' => $uData['description'],
                'district' => $uData['district'],
                'contact_phone' => $uData['contact_phone'],
                'address' => $uData['address'],
                'latitude' => 15.3694,
                'longitude' => 44.1910,
                'phone' => $uData['phone'],
                'email' => $uData['email'],
                'website' => $uData['website'],
                'status' => 'نشطة',
                'year_establ' => $uData['year_establ'],
            ]);

            if ($uData['username'] === 'univ_saeeda') {
                $targetUniversityForRequest = $univ;
            }

            foreach ($uData['colleges'] as $cData) {
                $college = College::create([
                    'university_id' => $univ->university_id,
                    'college_name' => $cData['name'],
                    'description' => $cData['description'],
                    'status' => 'نشطة',
                ]);

                foreach ($cData['specs'] as $sData) {
                    $spec = Specialization::create([
                        'college_id' => $college->college_id,
                        'specialization_name' => $sData['name'],
                        'description' => 'دراسة شاملة لمتطلبات تخصص ' . $sData['name'] . ' والمهارات العملية وسوق العمل.',
                        'status' => 'نشط',
                        'tuition_fees' => $sData['fees'],
                    ]);

                    if ($univ->university_id === ($targetUniversityForRequest->university_id ?? null) && empty($targetSpecNameForRequest)) {
                        $targetSpecNameForRequest = $sData['name'];
                    }

                    AdmissionData::create([
                        'specialization_id' => $spec->specialization_id,
                        'admission_data' => $sData['admission'],
                        'minimum_average' => $sData['min_avg'],
                        'description' => 'التقديم مفتوح وفق المقاعد المحددة من وزارة التعليم العالي والبحث العلمي.',
                        'secondary_last_year' => 2020,
                        'required_documents' => $commonDocuments,
                    ]);
                }
            }
        }

        // 4. طلب تسجيل تجريبي للطالب لاختبار العلاقات والمفاتيح الخارجية والوثائق
        if ($targetUniversityForRequest) {
            $regRequest = RegRequest::create([
                'student_id' => $student->student_id,
                'university_id' => $targetUniversityForRequest->university_id,
                'full_name' => $student->full_name,
                'nationality' => 'يمني',
                'gender' => $student->gender,
                'birth_date' => $student->birth_date,
                'birth_place' => 'صنعاء',
                'province' => 'أمانة العاصمة',
                'secondary_school' => 'ثانوية جمال عبد الناصر للمتفوقين',
                'seat_number' => '104829',
                'grad_year' => 2023,
                'sec_section' => 'علمي',
                'residence' => 'صنعاء - معين',
                'total_score' => 670,
                'sec_avg' => 89.25,
                'req_date' => now(),
                'phone' => '771234567',
                'email' => 'student@gmail.com',
                'guardian_name' => 'محمد علي العودي',
                'guardian' => 'أب',
                'guardian_rel' => 'أب',
                'guardian_occup' => 'مهندس',
                'home_address' => 'صنعاء - شارع الستين',
                'gu_phone' => '777001122',
                'desired_spec' => $targetSpecNameForRequest,
                'status' => 'قيد المراجعة',
                'decision_date' => null,
                'rejection_reason' => null,
            ]);

            // إضافة مستند تجريبي مرفق بالطلب
            RegDoc::create([
                'req_id' => $regRequest->req_id,
                'document_name' => 'أصل استمارة الثانوية العامة',
                'file_name' => 'thanawiya_certificate.pdf',
                'file_path' => 'uploads/docs/thanawiya_certificate.pdf',
                'up_at' => now(),
                'ver_status' => 'قيد المراجعة',
                'ver_note' => null,
                'verified_at' => null,
            ]);
        }
    }
}
