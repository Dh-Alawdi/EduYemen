<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    protected $table = 'advertisements';
    protected $primaryKey = 'adv_id';
    public $timestamps = false;

    protected $fillable = [
        'university_id',
        'adv_title',
        'adv_content',
        'package',
        'duration',
        'amount',
        'pay_method',
        'pay_reference',
        'receipt_url',
        'media_type',
        'media_files',
        'video_url',
        'wizard_step',
        'review_status',
        'status',
        'notes',
        'published_at',
        'expires_at',
        'created_at',
    ];

    protected $casts = [
        'amount'       => 'decimal:2',
        'wizard_step'  => 'integer',
        'media_files'  => 'array',
        'created_at'   => 'datetime',
        'published_at' => 'datetime',
        'expires_at'   => 'datetime',
    ];

    public function university()
    {
        return $this->belongsTo(University::class, 'university_id', 'university_id');
    }

    // ------------------------------------------------------------------ //
    //  Accessors / Mutators
    // ------------------------------------------------------------------ //

    public function getTitleAttribute()
    {
        return $this->adv_title;
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['adv_title'] = $value;
    }

    public function getContentAttribute()
    {
        return $this->adv_content;
    }

    public function setContentAttribute($value)
    {
        $this->attributes['adv_content'] = $value;
    }

    public function getIdAttribute()
    {
        return $this->adv_id;
    }

    // ------------------------------------------------------------------ //
    //  Helper Methods — Status
    // ------------------------------------------------------------------ //

    /**
     * هل الإعلان نشطاً الآن؟
     * الشرط: status == 'نشط' وتاريخ الانتهاء لم يحِن بعد.
     */
    public function isActive(): bool
    {
        return $this->status === 'نشط'
            && $this->expires_at !== null
            && $this->expires_at->isFuture();
    }

    /**
     * هل انتهت مدة الإعلان؟
     */
    public function isExpired(): bool
    {
        return $this->status === 'منتهي'
            || ($this->expires_at !== null && $this->expires_at->isPast());
    }

    /**
     * عدد الأيام المتبقية حتى انتهاء الإعلان (0 إذا انتهى أو غير محدد).
     */
    public function remainingDays(): int
    {
        if ($this->expires_at === null || $this->isExpired()) {
            return 0;
        }

        return max(0, (int) now()->diffInDays($this->expires_at, false));
    }

    // ------------------------------------------------------------------ //
    //  Helper Methods — Package
    // ------------------------------------------------------------------ //

    /**
     * إعدادات الباقات المتاحة.
     */
    public static function packagesConfig(): array
    {
        return [
            'اقتصادية' => [
                'label'               => 'الباقة الاقتصادية',
                'duration'            => 2,           // يومان
                'amount'              => 5000,
                'media_type_allowed'  => ['image'],
                'max_images'          => 1,
                'max_video_seconds'   => null,
                'locations'           => ['home'],
                'priority'            => 1,
            ],
            'مميزة' => [
                'label'               => 'الباقة المميزة',
                'duration'            => 4,           // 4 أيام
                'amount'              => 9000,
                'media_type_allowed'  => ['image', 'gallery'],
                'max_images'          => 3,
                'max_video_seconds'   => null,
                'locations'           => ['home', 'universities'],
                'priority'            => 2,
            ],
            'شاملة' => [
                'label'               => 'الباقة الشاملة',
                'duration'            => 10,          // 10 أيام
                'amount'              => 20000,
                'media_type_allowed'  => ['image', 'gallery', 'video'],
                'max_images'          => 5,
                'max_video_seconds'   => 10,
                'locations'           => ['home', 'universities'],
                'priority'            => 3,
            ],
        ];
    }

    /**
     * تعيد إعدادات الباقة المختارة حسب قيمة `package`.
     */
    public function getPackageConfig(): array
    {
        return self::packagesConfig()[$this->package] ?? [];
    }

    /**
     * اسم الباقة بالعربي المقروء.
     */
    public function getPackageLabel(): string
    {
        $config = $this->getPackageConfig();
        return $config['label'] ?? ($this->package ?? '');
    }

    // ------------------------------------------------------------------ //
    //  Helper Methods — Media
    // ------------------------------------------------------------------ //

    /**
     * تحويل media_files من JSON إلى مصفوفة PHP آمنة.
     *
     * @return array<string>
     */
    public function mediaFilesArray(): array
    {
        $value = $this->getRawOriginal('media_files') ?? $this->attributes['media_files'] ?? null;

        if (empty($value)) {
            return [];
        }

        if (is_array($value)) {
            return $value;
        }

        $decoded = json_decode($value, true);

        if (is_array($decoded)) {
            return $decoded;
        }

        if (is_string($value) && trim($value) !== '') {
            return [trim($value)];
        }

        return [];
    }

    /**
     * رابط أول وسيط (صورة) من مصفوفة الوسائط
     */
    public function firstMediaUrl(): ?string
    {
        $files = $this->mediaFilesArray();
        if (empty($files)) {
            return null;
        }

        // البحث عن أول ملف موجود فعلياً
        foreach ($files as $file) {
            if (empty($file)) continue;
            if (str_starts_with($file, 'http://') || str_starts_with($file, 'https://')) {
                return $file;
            }
            $cleanPath = ltrim($file, '/');
            if (file_exists(public_path($cleanPath))) {
                return asset($cleanPath);
            }
        }

        // في حال عدم العثور على ملف محلي موجود، إرجاع الرابط الافتراضي للأول
        $first = $files[0];
        if (str_starts_with($first, 'http://') || str_starts_with($first, 'https://')) {
            return $first;
        }
        return asset(ltrim($first, '/'));
    }

    /**
     * رابط الفيديو بصيغة URL صالحة
     */
    public function formattedVideoUrl(): ?string
    {
        if (!$this->video_url) {
            return null;
        }
        if (str_starts_with($this->video_url, 'http://') || str_starts_with($this->video_url, 'https://')) {
            return $this->video_url;
        }
        return asset(ltrim($this->video_url, '/'));
    }
}
