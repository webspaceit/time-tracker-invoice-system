<?php

namespace App\Models;

use App\Helpers\Duration;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number', 'customer_id', 'issue_date', 'due_date',
        'subtotal', 'tax_rate', 'tax_amount', 'total_amount',
        'status', 'notes', 'currency', 'hourly_rate',
        'project_title', 'work_details', 'total_duration',
        'terms', 'typography',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'typography' => 'array',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function getPaidAmountAttribute(): float
    {
        return $this->payments()->sum('amount');
    }

    public function getBalanceDueAttribute(): float
    {
        return $this->total_amount - $this->paid_amount;
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->status !== 'paid' && 
               $this->due_date < now() && 
               $this->status !== 'cancelled';
    }

    public function getDisplayTotalDurationAttribute(): string
    {
        if ($this->total_duration) {
            return $this->total_duration;
        }

        if ($this->relationLoaded('items')) {
            return Duration::sum($this->items->pluck('duration')->all());
        }

        return Duration::sum($this->items()->pluck('duration')->all());
    }

    protected static function booted()
    {
        static::creating(function ($invoice) {
            if (!$invoice->invoice_number) {
                $invoice->invoice_number = 'INV-' . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
            }
        });
    }
}