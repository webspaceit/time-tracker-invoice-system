<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'phone', 'address', 'company', 'tax_number', 'currency'
    ];

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function getTotalInvoicedAttribute(): float
    {
        return $this->invoices()->sum('total_amount');
    }

    public function getTotalPaidAttribute(): float
    {
        $paidInvoices = $this->invoices()->where('status', 'paid')->get();
        return $paidInvoices->sum('total_amount');
    }
}