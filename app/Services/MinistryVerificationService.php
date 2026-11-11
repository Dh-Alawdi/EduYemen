<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class MinistryVerificationService
{
    protected string $apiUrl;

    public function __construct()
    {
        $this->apiUrl = config('services.ministry_exams.url', env('MINISTRY_EXAMS_API_URL', 'http://127.0.0.1:8080/ministry_exams_service/public/api/verify.php'));
    }

    /**
     * الاستعلام عن نتيجة الطالب من واقع خادم وزارة التربية والتعليم
     *
     * @param string $seatNumber رقم الجلوس
     * @param int $year عام التخرج
     * @param string $section القسم العلمي أو الأدبي
     * @return array
     */
    public function verifySeatNumber(string $seatNumber, int $year = 2024, string $section = 'علمي'): array
    {
        $seatNumber = trim($seatNumber);

        if (empty($seatNumber)) {
            return [
                'success' => false,
                'message' => 'يرجى إدخال رقم الجلوس للاستعلام عن النتيجة.'
            ];
        }

        try {
            $response = Http::timeout(5)->get($this->apiUrl, [
                'seat_number' => $seatNumber,
                'grad_year' => $year,
                'section' => $section
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'message' => $data['message'] ?? 'تم جلب وتوثيق النتيجة بنجاح من سجلات الوزارة.',
                    'data' => $data['data'] ?? []
                ];
            }

            if ($response->status() === 404) {
                return [
                    'success' => false,
                    'message' => "رقم الجلوس [{$seatNumber}] غير مسجل في قاعدة بيانات الوزارة للعام الدراسي {$year}."
                ];
            }

            return [
                'success' => false,
                'message' => 'تعذر العثور على النتيجة في سجلات الاختبارات الوزارية.'
            ];

        } catch (Exception $e) {
            Log::warning('Ministry Verification API Exception: ' . $e->getMessage());

            // في حال كان خادم أباتشي يعمل بمسار محلي بديل
            try {
                $altUrl = 'http://localhost:8080/ministry_exams_service/public/api/verify.php';
                $altResponse = Http::timeout(4)->get($altUrl, [
                    'seat_number' => $seatNumber,
                    'grad_year' => $year,
                    'section' => $section
                ]);

                if ($altResponse->successful()) {
                    $altData = $altResponse->json();
                    return [
                        'success' => true,
                        'message' => 'تم جلب وتوثيق النتيجة بنجاح.',
                        'data' => $altData['data'] ?? []
                    ];
                }
            } catch (Exception $e2) {
                // فشل الرابط البديل أيضاً
            }

            return [
                'success' => false,
                'message' => 'تعذر الاتصال بخادم محاكاة الوزارة حالياً، يرجى التأكد من تشغيل الخادم على المنفذ 8080.'
            ];
        }
    }
}
