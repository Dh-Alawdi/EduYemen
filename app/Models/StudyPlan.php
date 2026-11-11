<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudyPlan extends Model
{
    protected $table = 'study_plans';
    protected $primaryKey = 'study_plan_id';
    public $timestamps = false;

    protected $fillable = [
        'specialization_id',
        'plan_name',
        'duration_years',
        'total_hours',
        'status',
    ];

    public function specialization()
    {
        return $this->belongsTo(Specialization::class, 'specialization_id', 'specialization_id');
    }

    public function semesters()
    {
        return $this->hasMany(Semester::class, 'study_plan_id', 'study_plan_id')->orderBy('semester_number');
    }
}
