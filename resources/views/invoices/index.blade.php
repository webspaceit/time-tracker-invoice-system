@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold" style="color:var(--brand);"><i class="fas fa-file-invoice me-2"></i>Invoices</h4>
    <a href="{{ route('invoices.create') }}" class="btn btn-brand"><i class="fas fa-plus me-1"></i> New Invoice</a>
</div>
<div class="card shadow-sm" style="border:1px solid #dee2e6;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Invoice #</th>
                        <th>Customer</th>
                        <th>Issue Date</th>
                        <th>Due Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices as $invoice)
                    <tr>
                        <td class="fw-medium">{{ $invoice->invoice_number }}</td>
                        <td>{{ $invoice->customer->name }}</td>
                        <td>{{ $invoice->issue_date->format('d M Y') }}</td>
                        <td>{{ $invoice->due_date->format('d M Y') }}</td>
                        <td>{{ format_money($invoice->total_amount, $invoice->currency) }}</td>
                        <td><span class="badge bg-{{ $invoice->status == 'paid' ? 'success' : ($invoice->status == 'overdue' ? 'danger' : ($invoice->status == 'sent' ? 'warning text-dark' : 'secondary')) }}">{{ ucfirst($invoice->status) }}</span></td>
                        <td>
                            <a href="{{ route('invoices.pdf', $invoice) }}" class="btn btn-sm btn-danger" title="Download PDF"><i class="fas fa-file-pdf"></i></a>
                            <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-sm btn-brand-outline">View</a>
                            @if($invoice->status !== 'paid')
                            <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-sm btn-brand-outline">Edit</a>
                            <form method="POST" action="{{ route('invoices.destroy', $invoice) }}" class="d-inline"
                                  onsubmit="return confirm('Delete this invoice permanently?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">Delete</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-4">No invoices yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($invoices->hasPages())
    <div class="card-footer">{{ $invoices->links() }}</div>
    @endif
</div>
@endsection
