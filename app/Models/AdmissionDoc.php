<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdmissionDoc extends Model
{
    use HasFactory;

    protected $table = 'admission_docs';
    protected $primaryKey = 'doc_id';
    public $timestamps = false;

    protected $fillable = [
        'admission_id',
        'document_name',
        'description',
        'is_required',
    ];

    protected $casts = [
        'is_required' => 'boolean',
    ];

    public function admissionData(): BelongsTo
    {
        return $this->belongsTo(AdmissionData::class, 'admission_id', 'admission_id');
    }
}
