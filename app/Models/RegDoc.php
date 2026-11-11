<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegDoc extends Model
{
    use HasFactory;

    protected $table = 'reg_docs';
    protected $primaryKey = 'reg_doc_id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'req_id',
        'file_name',
        'file_path',
        'up_at',
        'ver_status',
        'ver_note',
        'verified_at',
    ];

    protected function casts(): array
    {
        return [
            'up_at' => 'datetime',
            'verified_at' => 'datetime',
        ];
    }

    public function regRequest(): BelongsTo
    {
        return $this->belongsTo(RegRequest::class, 'req_id', 'req_id');
    }

    public function getDocumentNameAttribute()
    {
        if (isset($this->attributes['document_name']) && !empty($this->attributes['document_name'])) {
            return $this->attributes['document_name'];
        }
        $name = $this->file_name ?? '';
        if (str_contains($name, ' - ')) {
            return explode(' - ', $name, 2)[0];
        }
        if (str_contains(strtolower($name), 'thanawiya') || str_contains($name, 'ثانوية')) {
            return 'أصل استمارة الثانوية العامة';
        }
        if (str_contains(strtolower($name), 'id_card') || str_contains($name, 'بطاقة') || str_contains(strtolower($name), 'identity')) {
            return 'صورة البطاقة الشخصية أو جواز السفر';
        }
        return !empty($name) ? $name : 'أصل استمارة الثانوية العامة';
    }

    public function setDocumentNameAttribute($value)
    {
        if (\Illuminate\Support\Facades\Schema::hasColumn('reg_docs', 'document_name')) {
            $this->attributes['document_name'] = $value;
        } else {
            $this->attributes['file_name'] = $value;
        }
    }
}
