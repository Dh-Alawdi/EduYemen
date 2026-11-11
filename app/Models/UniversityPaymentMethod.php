<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UniversityPaymentMethod extends Model
{
    use HasFactory;

    protected $table = 'pay_methods';
    protected $primaryKey = 'method_id';
    public $timestamps = false;

    protected $fillable = [
        'university_id',
        'method_name',
        'method_type',
        'account_name',
        'account_number',
        'alternative_number',
        'instructions',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class, 'university_id', 'university_id');
    }

    public function getProviderNameAttribute()
    {
        return $this->method_name;
    }

    public function setProviderNameAttribute($value)
    {
        $this->attributes['method_name'] = $value;
    }

    public function getIdAttribute()
    {
        return $this->method_id;
    }
}
