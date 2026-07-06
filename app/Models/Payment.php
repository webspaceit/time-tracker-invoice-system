<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id', 'payment_date', 'amount', 'payment_method', 'transaction_id', 'notes'
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    protected static function booted()
    {
        static::created(function ($payment) {
            $paidTotal = $payment->invoice->payments()->sum('amount');
            $invoice = $payment->invoice;
            
            if ($paidTotal >= $invoice->total_amount) {
                $invoice->update(['status' => 'paid']);
            } elseif ($paidTotal > 0) {
                $invoice->update(['status' => 'sent']);
            }
        });
    }
}