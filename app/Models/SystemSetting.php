<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SystemSetting extends Model
{
    use HasFactory;

    protected $table = 'system_settings';

    protected $fillable = [
        'key',
        'value',
        'group',
    ];

    /**
     * القيم الافتراضية لكافة إعدادات المنصة
     */
    public static function defaults(): array
    {
        return [
            // 1. الجامعات
            'university_manual_approval'     => ['val' => '1', 'group' => 'university'],
            'university_edit_review'         => ['val' => '1', 'group' => 'university'],
            'university_data_validation'     => ['val' => '1', 'group' => 'university'],
            'university_suspend_services'     => ['val' => '1', 'group' => 'university'],
            'university_review_days'         => ['val' => '3', 'group' => 'university'],

            // 2. المحتوى الأكاديمي
            'academic_hide_incomplete'       => ['val' => '1', 'group' => 'academic'],
            'academic_admission_review'      => ['val' => '1', 'group' => 'academic'],
            'academic_per_page'              => ['val' => '12', 'group' => 'academic'],
            'academic_default_sort'          => ['val' => 'latest', 'group' => 'academic'],

            // 3. الطلاب والتسجيل
            'student_multiple_applications'  => ['val' => '1', 'group' => 'student'],
            'student_require_documents'      => ['val' => '1', 'group' => 'student'],
            'student_max_active_apps'        => ['val' => '3', 'group' => 'student'],
            'student_max_upload_size'        => ['val' => '5', 'group' => 'student'],
            'student_allowed_file_types'     => ['val' => 'pdf,jpg,jpeg,png', 'group' => 'student'],
            'student_comparison_tool'        => ['val' => '1', 'group' => 'student'],

            // 4. إعدادات الإحالات
            'referral_auto_code'             => ['val' => '1', 'group' => 'referrals'],
            'referral_require_proof'         => ['val' => '1', 'group' => 'referrals'],
            'referral_tracking'              => ['val' => '1', 'group' => 'referrals'],
            'referral_proof_window_days'     => ['val' => '7', 'group' => 'referrals'],
            'referral_notify_university'     => ['val' => '1', 'group' => 'referrals'],

            // 5. الإعلانات والنشر
            'ads_manual_approval'            => ['val' => '1', 'group' => 'ads'],
            'ads_active_universities_only'   => ['val' => '1', 'group' => 'ads'],
            'ads_auto_hide_expired'          => ['val' => '1', 'group' => 'ads'],
            'ads_default_duration_days'      => ['val' => '30', 'group' => 'ads'],
            'ads_default_price'              => ['val' => '50000', 'group' => 'ads'],
            'ads_allowed_media'              => ['val' => 'images_gifs', 'group' => 'ads'],

            // 6. الرسوم والعمولات
            'finance_commission_per_registration' => ['val' => '5000', 'group' => 'finance'],
            'finance_payment_proof_review'   => ['val' => '1', 'group' => 'finance'],
            'finance_default_currency'       => ['val' => 'YER', 'group' => 'finance'],

            // 7. الإشعارات والتنبيهات
            'notify_admin_university'        => ['val' => '1', 'group' => 'notifications'],
            'notify_admin_ads'               => ['val' => '1', 'group' => 'notifications'],
            'notify_student_status'          => ['val' => '1', 'group' => 'notifications'],
            'notify_university_apps'         => ['val' => '1', 'group' => 'notifications'],
            'notify_default_channel'         => ['val' => 'all', 'group' => 'notifications'],

            // 8. النظام والأمان
            'security_admin_audit_log'       => ['val' => '1', 'group' => 'security'],
            'security_sensitive_action_confirm' => ['val' => '1', 'group' => 'security'],
            'security_session_timeout'       => ['val' => '60', 'group' => 'security'],
            'security_max_login_attempts'    => ['val' => '5', 'group' => 'security'],
            'security_recommendation_engine' => ['val' => '1', 'group' => 'security'],
        ];
    }

    /**
     * استرجاع قيمة إعداد معين مع قيمة افتراضية اختيارية
     */
    public static function get(string $key, $default = null)
    {
        $all = static::getAllKeyValues();
        if (array_key_exists($key, $all)) {
            return $all[$key];
        }

        $defaults = static::defaults();
        if (isset($defaults[$key])) {
            return $defaults[$key]['val'];
        }

        return $default;
    }

    /**
     * حفظ أو تحديث إعداد
     */
    public static function set(string $key, $value, string $group = 'general'): self
    {
        $valString = is_bool($value) ? ($value ? '1' : '0') : (string) $value;

        $setting = static::updateOrCreate(
            ['key' => $key],
            ['value' => $valString, 'group' => $group]
        );

        Cache::forget('eduyemen_system_settings');
        return $setting;
    }

    /**
     * جلب جميع الإعدادات كمصفوفة مفتاح-قيمة مدمجة مع القيم الافتراضية
     */
    public static function getAllKeyValues(): array
    {
        return Cache::remember('eduyemen_system_settings', 3600, function () {
            $defaults = static::defaults();
            $result = [];
            foreach ($defaults as $k => $item) {
                $result[$k] = $item['val'];
            }

            try {
                $dbSettings = static::all();
                foreach ($dbSettings as $row) {
                    $result[$row->key] = $row->value;
                }
            } catch (\Throwable $e) {
                // في حال عدم توفر الجدول بعد
            }

            return $result;
        });
    }

    /**
     * حفظ حزمة إعدادات دفعة واحدة
     */
    public static function setMany(array $settings): void
    {
        $defaults = static::defaults();

        foreach ($settings as $key => $val) {
            $group = $defaults[$key]['group'] ?? 'general';
            $valString = is_bool($val) ? ($val ? '1' : '0') : (string) $val;

            static::updateOrCreate(
                ['key' => $key],
                ['value' => $valString, 'group' => $group]
            );
        }

        Cache::forget('eduyemen_system_settings');
    }
}
