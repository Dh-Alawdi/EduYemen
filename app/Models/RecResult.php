<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecResult extends Model
{
    protected $table = 'rec_results';
    protected $primaryKey = 'result_id';
    public $timestamps = false;

    protected $fillable = [
        'r_d_id',
        'university_id',
        'specialization_id',
        'univ_name',
        'spec_name',
        'match_percentage',
        'result_order',
        'notes',
    ];

    protected $casts = [
        'match_percentage' => 'decimal:2',
    ];

    public function recData(): BelongsTo
    {
        return $this->belongsTo(RecData::class, 'r_d_id', 'r_d_id');
    }

    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class, 'university_id', 'university_id');
    }

    public function specialization(): BelongsTo
    {
        return $this->belongsTo(Specialization::class, 'specialization_id', 'specialization_id');
    }
}
