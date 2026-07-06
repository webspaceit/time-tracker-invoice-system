@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold" style="color:var(--brand);"><i class="fas fa-user me-2"></i>{{ $customer->name }}</h4>
    <div>
        <a href="{{ route('customers.edit', $customer) }}" class="btn btn-brand"><i class="fas fa-edit me-1"></i>Edit</a>
        @if(!$customer->invoices()->exists())
        <form method="POST" action="{{ route('customers.destroy', $customer) }}" class="d-inline"
              onsubmit="return confirm('Delete this customer permanently?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger">Delete</button>
        </form>
        @endif
        <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>Back</a>
    </div>
</div>
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card shadow-sm" style="border:1px solid #dee2e6;">
            <div class="card-header brand-header"><h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Details</h5></div>
            <div class="card-body">
                <div class="row mb-2"><div class="col-4 text-muted">Email:</div><div class="col-8">{{ $customer->email }}</div></div>
                <div class="row mb-2"><div class="col-4 text-muted">Phone:</div><div class="col-8">{{ $customer->phone ?? '—' }}</div></div>
                <div class="row mb-2"><div class="col-4 text-muted">Company:</div><div class="col-8">{{ $customer->company ?? '—' }}</div></div>
                <div class="row mb-0"><div class="col-4 text-muted">Address:</div><div class="col-8">{{ $customer->address ?? '—' }}</div></div>
            </div>
        </div>
    </div>
</div>
<div class="card shadow-sm" style="border:1px solid #dee2e6;">
    <div class="card-header brand-header"><h5 class="mb-0"><i class="fas fa-file-invoice me-2"></i>Invoices</h5></div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Invoice #</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $invoice)
                <tr>
                    <td>{{ $invoice->invoice_number }}</td>
                    <td>{{ $invoice->issue_date->format('d M Y') }}</td>
                    <td>{{ format_money($invoice->total_amount, $invoice->currency) }}</td>
                    <td><span class="badge bg-{{ $invoice->status == 'paid' ? 'success' : ($invoice->status == 'overdue' ? 'danger' : ($invoice->status == 'sent' ? 'warning text-dark' : 'secondary')) }}">{{ ucfirst($invoice->status) }}</span></td>
                    <td><a href="{{ route('invoices.show', $invoice) }}" class="btn btn-sm btn-brand-outline">View</a></td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-3">No invoices for this customer.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($invoices->hasPages())
    <div class="card-footer">{{ $invoices->links() }}</div>
    @endif
</div>
@endsection
