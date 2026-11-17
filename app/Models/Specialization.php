<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Specialization extends Model
{
    use HasFactory;

    protected $table = 'specializations';
    protected $primaryKey = 'specialization_id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'college_id',
        'specialization_name',
        'status',
        'description',
        'duration_years',
        'tuition_fees',
        'language',
    ];

    protected function casts(): array
    {
        return [
            'tuition_fees' => 'float',
            'duration_years' => 'integer',
        ];
    }

    public function college(): BelongsTo
    {
        return $this->belongsTo(College::class, 'college_id', 'college_id');
    }

    public function admissionData(): HasOne
    {
        return $this->hasOne(AdmissionData::class, 'specialization_id', 'specialization_id');
    }

    public function studyPlans(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(StudyPlan::class, 'specialization_id', 'specialization_id');
    }

    public function studyPlan(): HasOne
    {
        return $this->hasOne(StudyPlan::class, 'specialization_id', 'specialization_id')->latest('study_plan_id');
    }

    public function activeModeration(): HasOne
    {
        return $this->hasOne(ContentModeration::class, 'item_id', 'specialization_id')
            ->where('item_type', 'specialization')
            ->whereIn('status', ['محجوب', 'قيد_المراجعة']);
    }
}
