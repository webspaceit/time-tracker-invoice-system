<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id', 'description', 'duration', 'quantity', 'unit_price', 'discount', 'total',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    // Auto-calculate total before saving
    protected static function booted()
    {
        static::saving(function ($item) {
            $subtotal = $item->quantity * $item->unit_price;
            $item->total = $subtotal - $item->discount;
        });
    }
}