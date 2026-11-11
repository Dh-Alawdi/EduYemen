<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';
    protected $primaryKey = 'payment_id';
    public $timestamps = false;

    protected $fillable = [
        'req_id',
        'method_id',
        'amount',
        'pay_date',
        'reference',
        'proof_file',
        'ver_status',
        'ver_date',
        'ver_result',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'pay_date' => 'date',
        'ver_date' => 'date',
    ];

    public function regRequest()
    {
        return $this->belongsTo(RegRequest::class, 'req_id', 'req_id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(UniversityPaymentMethod::class, 'method_id', 'method_id');
    }
}
