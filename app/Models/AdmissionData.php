<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdmissionData extends Model
{
    use HasFactory;

    protected $table = 'admission_data';
    protected $primaryKey = 'admission_id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'specialization_id',
        'admission_data',
        'minimum_average',
        'description',
        'secondary_last_year',
        'document_name',
        'is_required',
        'registration_fee',
        'tuition_fees',
        'admission_start_date',
        'admission_end_date',
        'admission_status',
        'required_documents',
    ];

    protected function casts(): array
    {
        return [
            'minimum_average' => 'float',
            'secondary_last_year' => 'integer',
            'is_required' => 'boolean',
            'registration_fee' => 'float',
            'tuition_fees' => 'float',
            'admission_start_date' => 'date',
            'admission_end_date' => 'date',
            'required_documents' => 'array',
        ];
    }

    /**
     * سجل بيانات القبول ينتمي حصرياً إلى تخصص محدد (علاقة 1:1)
     */
    public function specialization(): BelongsTo
    {
        return $this->belongsTo(Specialization::class, 'specialization_id', 'specialization_id');
    }

    /**
     * الوثائق المطلوبة المرتبطة بسجل القبول والتسجيل
     */
    public function admissionDocs(): HasMany
    {
        return $this->hasMany(AdmissionDoc::class, 'admission_id', 'admission_id');
    }

    /**
     * علاقة docs لمطابقة متطلبات الاختبارات والربط المباشر
     */
    public function docs(): HasMany
    {
        return $this->hasMany(AdmissionDoc::class, 'admission_id', 'admission_id');
    }

    /**
     * مصفوفة أسماء الوثائق المطلوبة
     */
    public function getRequiredDocumentsAttribute()
    {
        $docs = $this->admissionDocs()->pluck('document_name')->toArray();
        if (!empty($docs)) {
            return $docs;
        }
        if (!empty($this->document_name)) {
            return [$this->document_name];
        }
        return ['أصل استمارة الثانوية العامة', 'صورة البطاقة الشخصية أو جواز السفر', '4 صور شخصية 4x6'];
    }

    /**
     * قائمة الوثائق المطلوبة كـ Collection متوافق مع العرض
     */
    public function getDocsAttribute()
    {
        $docs = $this->admissionDocs;
        if ($docs && $docs->isNotEmpty()) {
            return $docs->map(fn($d) => (object)[
                'doc_id' => $d->doc_id,
                'document_name' => $d->document_name,
                'name' => $d->document_name,
                'description' => $d->description,
                'is_required' => (bool)$d->is_required,
            ]);
        }

        $fallback = collect();
        if (!empty($this->document_name)) {
            $fallback->push((object)[
                'doc_id' => $this->admission_id,
                'document_name' => $this->document_name,
                'name' => $this->document_name,
                'description' => $this->description,
                'is_required' => (bool)$this->is_required,
            ]);
        } else {
            $fallback->push((object)[
                'doc_id' => $this->admission_id ?: 1,
                'document_name' => 'أصل استمارة الثانوية العامة',
                'name' => 'أصل استمارة الثانوية العامة',
                'description' => 'مصدقة رسمياً أو صورة طبق الأصل للمطابقة',
                'is_required' => true,
            ]);
        }
        return $fallback;
    }

    /**
     * مزامنة وتحديث الوثيقة في جدول admission_docs وحقل document_name
     */
    public function syncDocuments(array $documents): void
    {
        AdmissionDoc::where('admission_id', $this->admission_id)->delete();
        foreach ($documents as $doc) {
            $name = is_array($doc) ? ($doc['name'] ?? $doc['document_name'] ?? null) : (string)$doc;
            if (!empty($name)) {
                AdmissionDoc::create([
                    'admission_id' => $this->admission_id,
                    'document_name' => $name,
                    'description' => is_array($doc) ? ($doc['description'] ?? null) : null,
                    'is_required' => is_array($doc) ? (!empty($doc['is_required'])) : true,
                ]);
            }
        }

        $first = reset($documents);
        if ($first) {
            $name = is_array($first) ? ($first['name'] ?? $first['document_name'] ?? null) : (string)$first;
            $isReq = is_array($first) ? (!empty($first['is_required'])) : true;
            $this->update([
                'document_name' => $name,
                'is_required' => (bool)$isReq,
            ]);
        }
    }
}
