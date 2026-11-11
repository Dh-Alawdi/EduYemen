<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegProof extends Model
{
    protected $table = 'reg_proofs';
    protected $primaryKey = 'reg_proof_id';
    public $timestamps = false;

    protected $fillable = [
        'referral_id',
        'receipt_number',
        'registration_date',
        'student_name',
        'submited_at',
        'verification_status',
        'verification_date',
        'verification_result',
    ];

    protected $casts = [
        'registration_date' => 'date',
        'verification_date' => 'date',
        'submited_at' => 'datetime',
    ];

    public function referral()
    {
        return $this->belongsTo(StudentReferral::class, 'referral_id', 'referral_id');
    }

    public function getSubmittedAtAttribute()
    {
        return $this->submited_at;
    }

    public function setSubmittedAtAttribute($value)
    {
        $this->attributes['submited_at'] = $value;
    }
}
