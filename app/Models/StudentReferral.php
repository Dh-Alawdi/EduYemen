<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class StudentReferral extends Model
{
    use HasFactory;

    protected $table = 'referrals';
    protected $primaryKey = 'referral_id';
    public $timestamps = false;

    protected $fillable = [
        'student_id',
        'university_id',
        'ref_code',
        'referral_code',
        'ref_date',
        'ref_status',
        'status',
        'notes',
        'commission_paid_at',
        'used_at',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->ref_code) && empty($model->referral_code)) {
                $code = 'REF-' . strtoupper(substr(uniqid(), -6));
                $model->ref_code = $code;
                $model->referral_code = $code;
            } elseif (empty($model->ref_code)) {
                $model->ref_code = $model->referral_code;
            } elseif (empty($model->referral_code)) {
                $model->referral_code = $model->ref_code;
            }

            if (empty($model->ref_status) && empty($model->status)) {
                $model->ref_status = 'نشط';
                $model->status = 'نشط';
            }

            if (empty($model->ref_date)) {
                $model->ref_date = now();
            }
        });
    }

    protected function casts(): array
    {
        return [
            'ref_date'           => 'date',
            'commission_paid_at' => 'datetime',
            'used_at'            => 'datetime',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class, 'university_id', 'university_id');
    }

    public function proof(): HasOne
    {
        return $this->hasOne(RegProof::class, 'referral_id', 'referral_id');
    }

    public function getReferralCodeAttribute()
    {
        return $this->ref_code;
    }

    public function setReferralCodeAttribute($value)
    {
        $this->attributes['ref_code'] = $value;
    }

    public function getStatusAttribute()
    {
        return $this->ref_status;
    }

    public function setStatusAttribute($value)
    {
        $this->attributes['ref_status'] = $value;
    }
}
