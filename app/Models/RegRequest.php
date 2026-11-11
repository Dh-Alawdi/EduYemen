<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RegRequest extends Model
{
    use HasFactory;

    protected $table = 'reg_requests';
    protected $primaryKey = 'req_id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'student_id',
        'university_id',
        'full_name',
        'nationality',
        'gender',
        'birth_date',
        'birth_place',
        'province',
        'secondary_school',
        'seat_number',
        'grad_year',
        'sec_section',
        'residence',
        'total_score',
        'sec_avg',
        'req_date',
        'phone',
        'email',
        'guardian_name',
        'guardian',
        'guardian_rel',
        'guardian_occup',
        'home_address',
        'gu_phone',
        'desired_spec',
        'status',
        'can_cancel',
        'is_replacement',
        'decision_date',
        'cancelled_at',
        'rejection_reason',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->nationality = $model->nationality ?? 'يمني';
            $model->gender = $model->gender ?? 'ذكر';
            $model->birth_date = $model->birth_date ?? '2005-01-01';
            $model->birth_place = $model->birth_place ?? 'صنعاء';
            $model->province = $model->province ?? 'أمانة العاصمة';
            $model->secondary_school = $model->secondary_school ?? 'ثانوية عامة';
            $model->seat_number = $model->seat_number ?? ('S-' . rand(10000, 99999));
            $model->grad_year = $model->grad_year ?? 2024;
            $model->sec_section = $model->sec_section ?? 'علمي';
            $model->residence = $model->residence ?? 'صنعاء';
            $model->total_score = $model->total_score ?? 700;
            $model->sec_avg = $model->sec_avg ?? 80.00;
            $model->req_date = $model->req_date ?? now();
            $model->guardian_name = $model->guardian_name ?? ('ولي أمر ' . ($model->full_name ?? 'الطالب'));
            $model->guardian_rel = $model->guardian_rel ?? 'أب';
            $model->home_address = $model->home_address ?? 'صنعاء';
            $model->gu_phone = $model->gu_phone ?? ($model->phone ?? '770000000');
            $model->desired_spec = $model->desired_spec ?? 'تخصص عام';
        });
    }

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'req_date' => 'datetime',
            'decision_date' => 'datetime',
            'cancelled_at' => 'datetime',
            'can_cancel' => 'boolean',
            'is_replacement' => 'boolean',
            'sec_avg' => 'float',
            'total_score' => 'integer',
            'grad_year' => 'integer',
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

    public function regDocs(): HasMany
    {
        return $this->hasMany(RegDoc::class, 'req_id', 'req_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'req_id', 'req_id')->latest('payment_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'req_id', 'req_id')->latestOfMany('payment_id');
    }

    public function latestPayment()
    {
        return $this->hasOne(Payment::class, 'req_id', 'req_id')->latestOfMany('payment_id');
    }

    public function getPaymentStatusAttribute(): string
    {
        $p = $this->relationLoaded('payments') ? $this->payments->first() : $this->latestPayment;
        if (!$p) {
            return 'بانتظار السداد';
        }
        if ($p->ver_status === 'معتمد ومؤكد') {
            return 'مسدد ومعتمد';
        }
        if ($p->ver_status === 'مرفوض') {
            return 'سند مرفوض';
        }
        return $p->ver_status ?? 'قيد التحقق';
    }
}
