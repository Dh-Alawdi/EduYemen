<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;

    protected $table = 'students';
    protected $primaryKey = 'student_id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'full_name',
        'gender',
        'birth_date',
        'city',
        'address',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function regRequests(): HasMany
    {
        return $this->hasMany(RegRequest::class, 'student_id', 'student_id');
    }

    public function referrals(): HasMany
    {
        return $this->hasMany(StudentReferral::class, 'student_id', 'student_id');
    }

    public function recData(): HasMany
    {
        return $this->hasMany(RecData::class, 'student_id', 'student_id');
    }
}
