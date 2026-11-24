<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailVerificationCode extends Model
{
    protected $table = 'email_verification_codes';

    protected $fillable = [
        'email',
        'code',
        'type',
        'user_id',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
        ];
    }

    /**
     * هل الرمز صالح وغير منتهي الصلاحية؟
     */
    public function isValid(): bool
    {
        return $this->expires_at && $this->expires_at->isFuture();
    }
}
