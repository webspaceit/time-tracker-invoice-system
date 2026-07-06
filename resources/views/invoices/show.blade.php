@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h4 class="mb-0 fw-bold" style="color:var(--brand);"><i class="fas fa-file-invoice me-2"></i>Invoice {{ $invoice->invoice_number }}</h4>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('invoices.pdf', $invoice) }}" class="btn btn-danger">
            <i class="fas fa-file-pdf me-1"></i> Download PDF
        </a>
        <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-brand">Edit</a>
        <form method="POST" action="{{ route('invoices.destroy', $invoice) }}" class="d-inline"
              onsubmit="return confirm('Delete this invoice permanently?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger">Delete</button>
        </form>
        @if($invoice->balance_due > 0)
            <a href="{{ route('payments.create', $invoice) }}" class="btn btn-brand">Record Payment</a>
        @endif
        <a href="{{ route('invoices.index') }}" class="btn btn-outline-secondary">Back</a>
    </div>
</div>

@if($invoice->status !== 'paid')
<div class="mb-3">
    <span class="badge bg-{{ $invoice->status == 'paid' ? 'success' : ($invoice->status == 'overdue' ? 'danger' : ($invoice->status == 'sent' ? 'warning text-dark' : 'secondary')) }} me-1">{{ ucfirst($invoice->status) }}</span>
    @foreach(['draft', 'sent', 'paid', 'cancelled'] as $status)
        @if($status !== $invoice->status)
        <form method="POST" action="{{ route('invoices.update-status', [$invoice, $status]) }}" class="d-inline">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn btn-sm btn-brand-outline">{{ ucfirst($status) }}</button>
        </form>
        @endif
    @endforeach
</div>
@endif

<div class="card shadow-sm mb-4" style="border:1px solid #dee2e6;">
    <div class="card-body p-4">
        @include('invoices.partials.document-styles')
        @include('invoices.partials.document', ['editable' => false])
    </div>
</div>

@if($invoice->payments->count())
<div class="card shadow-sm" style="border:1px solid #dee2e6;">
    <div class="card-header brand-header"><h5 class="mb-0"><i class="fas fa-credit-card me-2"></i>Payments</h5></div>
    <table class="table mb-0">
        <thead class="table-light">
            <tr><th>Date</th><th>Amount</th><th>Method</th><th></th></tr>
        </thead>
        <tbody>
            @foreach($invoice->payments as $payment)
            <tr>
                <td>{{ $payment->payment_date->format('d M Y') }}</td>
                <td>{{ format_money($payment->amount, $invoice->currency) }}</td>
                <td>{{ str_replace('_', ' ', ucfirst($payment->payment_method)) }}</td>
                <td>
                    <form method="POST" action="{{ route('payments.destroy', $payment) }}" class="d-inline" onsubmit="return confirm('Delete this payment?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif
@endsection
