@extends('layouts.app')

@section('content')
<div class="card shadow-sm" style="border:1px solid #dee2e6;">
    <div class="card-header brand-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-credit-card me-2"></i>Record Payment — {{ $invoice->invoice_number }}</h5>
            <span class="badge bg-light text-dark">Balance due: {{ format_money($invoice->balance_due, $invoice->currency) }}</span>
        </div>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('payments.store', $invoice) }}">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Payment Date *</label>
                    <input type="date" name="payment_date" class="form-control @error('payment_date') is-invalid @enderror" value="{{ old('payment_date', date('Y-m-d')) }}" required>
                    @error('payment_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Amount *</label>
                    <input type="number" step="0.01" name="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount', $invoice->balance_due) }}" max="{{ $invoice->balance_due }}" required>
                    @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Payment Method *</label>
                <select name="payment_method" class="form-select @error('payment_method') is-invalid @enderror" required>
                    @foreach(['cash', 'bank_transfer', 'credit_card', 'check'] as $method)
                    <option value="{{ $method }}" @selected(old('payment_method') === $method)>{{ str_replace('_', ' ', ucfirst($method)) }}</option>
                    @endforeach
                </select>
                @error('payment_method')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Transaction ID</label>
                <input type="text" name="transaction_id" class="form-control" value="{{ old('transaction_id') }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Notes</label>
                <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
            </div>
            <button type="submit" class="btn btn-brand"><i class="fas fa-save me-1"></i>Save Payment</button>
            <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
