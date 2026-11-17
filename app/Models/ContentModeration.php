<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentModeration extends Model
{
    use HasFactory;

    protected $table = 'content_moderations';
    protected $primaryKey = 'moderation_id';

    protected $fillable = [
        'university_id',
        'item_type',
        'item_id',
        'item_title',
        'status',
        'violation_reason',
        'admin_guidance',
        'correction_notes',
        'suspended_by',
        'resolved_by',
        'suspended_at',
        'resolved_at',
    ];

    protected $casts = [
        'suspended_at' => 'datetime',
        'resolved_at'  => 'datetime',
    ];

    public function university()
    {
        return $this->belongsTo(University::class, 'university_id', 'university_id');
    }

    public function suspendedByUser()
    {
        return $this->belongsTo(User::class, 'suspended_by', 'user_id');
    }

    public function resolvedByUser()
    {
        return $this->belongsTo(User::class, 'resolved_by', 'user_id');
    }

    /**
     * Scope for currently active quarantines (محجوب أو قيد المراجعة)
     */
    public function scopeActiveQuarantine($query)
    {
        return $query->whereIn('status', ['محجوب', 'قيد_المراجعة']);
    }
}
