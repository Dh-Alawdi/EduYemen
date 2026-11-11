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
        'review_status',
        'status',
        'notes',
        'created_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    public function university()
    {
        return $this->belongsTo(University::class, 'university_id', 'university_id');
    }

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
}
