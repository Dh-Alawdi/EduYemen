<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class University extends Model
{
    use HasFactory;

    protected $table = 'universities';
    protected $primaryKey = 'university_id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'university_name',
        'university_type',
        'description',
        'logo',
        'campus_image',
        'district',
        'contact_phone',
        'address',
        'latitude',
        'longitude',
        'phone',
        'email',
        'website',
        'status',
        'year_establ',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'float',
            'longitude' => 'float',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function colleges(): HasMany
    {
        return $this->hasMany(College::class, 'university_id', 'university_id');
    }

    public function specializations(): HasManyThrough
    {
        return $this->hasManyThrough(
            Specialization::class,
            College::class,
            'university_id', // Foreign key on colleges table...
            'college_id',    // Foreign key on specializations table...
            'university_id', // Local key on universities table...
            'college_id'     // Local key on colleges table...
        );
    }

    public function regRequests(): HasMany
    {
        return $this->hasMany(RegRequest::class, 'university_id', 'university_id');
    }

    public function referrals(): HasMany
    {
        return $this->hasMany(StudentReferral::class, 'university_id', 'university_id');
    }

    public function paymentMethods(): HasMany
    {
        return $this->hasMany(UniversityPaymentMethod::class, 'university_id', 'university_id');
    }

    public function advertisements(): HasMany
    {
        return $this->hasMany(Advertisement::class, 'university_id', 'university_id');
    }

    public function announcements(): HasMany
    {
        return $this->hasMany(Advertisement::class, 'university_id', 'university_id');
    }

    public function contentModerations(): HasMany
    {
        return $this->hasMany(ContentModeration::class, 'university_id', 'university_id');
    }

    public function activeModerations(): HasMany
    {
        return $this->hasMany(ContentModeration::class, 'university_id', 'university_id')
                    ->whereIn('status', ['محجوب', 'قيد_المراجعة']);
    }

    public function isItemQuarantined(string $itemType, $itemId = null): bool
    {
        $targetId = $itemId !== null ? (int)$itemId : (int)$this->university_id;

        $checkCollection = function($collection) use ($itemType, $targetId) {
            return $collection->filter(function($m) use ($itemType, $targetId) {
                if ($m->item_type !== $itemType) return false;
                if (!in_array($m->status, ['محجوب', 'قيد_المراجعة'])) return false;
                if (in_array($itemType, ['logo', 'campus_image', 'description', 'contact_info'])) {
                    return (int)$m->item_id === $targetId || (int)$m->university_id === (int)$this->university_id;
                }
                return (int)$m->item_id === $targetId;
            })->isNotEmpty();
        };

        if ($this->relationLoaded('activeModerations')) {
            return $checkCollection($this->activeModerations);
        }
        if ($this->relationLoaded('contentModerations')) {
            return $checkCollection($this->contentModerations);
        }

        $query = $this->contentModerations()->where('item_type', $itemType)->activeQuarantine();
        if (in_array($itemType, ['logo', 'campus_image', 'description', 'contact_info'])) {
            $query->where(function($q) use ($targetId) {
                $q->where('item_id', $targetId)->orWhere('university_id', $this->university_id);
            });
        } else {
            $query->where('item_id', $targetId);
        }

        return $query->exists();
    }

    public function getActiveModeration(string $itemType, $itemId = null)
    {
        $targetId = $itemId !== null ? (int)$itemId : (int)$this->university_id;

        $filterCollection = function($collection) use ($itemType, $targetId) {
            return $collection->first(function($m) use ($itemType, $targetId) {
                if ($m->item_type !== $itemType) return false;
                if (!in_array($m->status, ['محجوب', 'قيد_المراجعة'])) return false;
                if (in_array($itemType, ['logo', 'campus_image', 'description', 'contact_info'])) {
                    return (int)$m->item_id === $targetId || (int)$m->university_id === (int)$this->university_id;
                }
                return (int)$m->item_id === $targetId;
            });
        };

        if ($this->relationLoaded('activeModerations')) {
            return $filterCollection($this->activeModerations);
        }
        if ($this->relationLoaded('contentModerations')) {
            return $filterCollection($this->contentModerations);
        }

        $query = $this->contentModerations()->where('item_type', $itemType)->activeQuarantine();
        if (in_array($itemType, ['logo', 'campus_image', 'description', 'contact_info'])) {
            $query->where(function($q) use ($targetId) {
                $q->where('item_id', $targetId)->orWhere('university_id', $this->university_id);
            });
        } else {
            $query->where('item_id', $targetId);
        }

        return $query->first();
    }

    /**
     * رابط الشعار الرسمي للجامعة (مع استبداله برمز الحجب إذا كان هناك قرار رقابي نشط)
     */
    public function getLogoUrlAttribute(): string
    {
        if ($this->isItemQuarantined('logo')) {
            return asset('images/quarantined-logo.svg');
        }

        if (!empty($this->logo)) {
            $path = ltrim($this->logo, '/');
            if (file_exists(public_path($path))) {
                return asset($path);
            }
            return asset($this->logo);
        }

        $png = 'img/' . $this->university_name . '.png';
        if (file_exists(public_path($png))) {
            return asset($png);
        }
        $jfif = 'img/' . $this->university_name . '.jfif';
        if (file_exists(public_path($jfif))) {
            return asset($jfif);
        }

        return asset('logo.png');
    }

    /**
     * رابط صورة الحرم الجامعي (مع استبدالها بصورة الحجب إذا كانت تخضع لحجر رقابي)
     */
    public function getCampusImageUrlAttribute(): ?string
    {
        if ($this->isItemQuarantined('campus_image')) {
            return asset('images/quarantined-campus.svg');
        }

        if (!empty($this->campus_image)) {
            $path = ltrim($this->campus_image, '/');
            if (file_exists(public_path($path))) {
                return asset($path);
            }
            return asset($this->campus_image);
        }

        $campusJpg = 'images/campus/' . $this->university_id . '.jpg';
        if (file_exists(public_path($campusJpg))) {
            return asset($campusJpg);
        }

        $campusDefault = 'images/campus-default.svg';
        if (file_exists(public_path($campusDefault))) {
            return asset($campusDefault);
        }

        return null;
    }

    /**
     * النبذة التعريفية الآمنة مع استبعاد المحتوى المحجوب رقابياً
     */
    public function getSafeDescriptionAttribute(): ?string
    {
        if ($this->isItemQuarantined('description')) {
            return 'تنبيه رقابي: تم إيقاف عرض النبذة والتعريف الخاص بالجامعة مؤقتاً بقرار إشرافي صادر من إدارة المنظومة لمراجعتها وتصحيحها وفق المعايير واللوائح الرسمية المعتمدة.';
        }
        return $this->description;
    }
}
