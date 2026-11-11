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

    /**
     * رابط الشعار الرسمي للجامعة
     */
    public function getLogoUrlAttribute(): string
    {
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
     * رابط صورة الحرم الجامعي
     */
    public function getCampusImageUrlAttribute(): ?string
    {
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
}
