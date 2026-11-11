<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecData extends Model
{
    protected $table = 'rec_data';
    protected $primaryKey = 'r_d_id';
    public $timestamps = false;

    protected $fillable = [
        'student_id',
        'average',
        'interests',
        'hobbies',
        'abilities',
        'preferences',
        'location',
    ];

    protected $casts = [
        'average' => 'decimal:2',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    public function results()
    {
        return $this->hasMany(RecResult::class, 'r_d_id', 'r_d_id')->orderBy('result_order');
    }
}
