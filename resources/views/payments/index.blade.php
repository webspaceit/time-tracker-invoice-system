@extends('layouts.app')

@section('content')
<h4 class="fw-bold mb-4" style="color:var(--brand);"><i class="fas fa-credit-card me-2"></i>Payments</h4>
<div class="card shadow-sm" style="border:1px solid #dee2e6;">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>Invoice</th>
                    <th>Customer</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                <tr>
                    <td>{{ $payment->payment_date->format('d M Y') }}</td>
                    <td class="fw-medium">{{ $payment->invoice->invoice_number }}</td>
                    <td>{{ $payment->invoice->customer->name }}</td>
                    <td>{{ format_money($payment->amount, $payment->invoice->currency) }}</td>
                    <td>{{ str_replace('_', ' ', ucfirst($payment->payment_method)) }}</td>
                    <td>
                        <a href="{{ route('invoices.show', $payment->invoice) }}" class="btn btn-sm btn-brand-outline">Invoice</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-4">No payments recorded.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($payments->hasPages())
    <div class="card-footer">{{ $payments->links() }}</div>
    @endif
</div>
@endsection
