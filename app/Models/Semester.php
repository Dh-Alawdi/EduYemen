<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    protected $table = 'semesters';
    protected $primaryKey = 'semester_id';
    public $timestamps = false;

    protected $fillable = [
        'study_plan_id',
        'semester_number',
    ];

    public function studyPlan()
    {
        return $this->belongsTo(StudyPlan::class, 'study_plan_id', 'study_plan_id');
    }

    public function courses()
    {
        return $this->hasMany(Course::class, 'semester_id', 'semester_id');
    }
}
