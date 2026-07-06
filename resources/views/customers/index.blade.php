@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold" style="color:var(--brand);"><i class="fas fa-users me-2"></i>Customers</h4>
    <a href="{{ route('customers.create') }}" class="btn btn-brand"><i class="fas fa-plus me-1"></i> New Customer</a>
</div>
<div class="card shadow-sm" style="border:1px solid #dee2e6;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Company</th>
                        <th>Invoices</th>
                        <th>Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                    <tr>
                        <td class="fw-medium">{{ $customer->name }}</td>
                        <td>{{ $customer->email }}</td>
                        <td>{{ $customer->company ?? '—' }}</td>
                        <td>{{ $customer->invoices_count }}</td>
                        <td>{{ format_money($customer->invoices_sum_total_amount ?? 0) }}</td>
                        <td>
                            <a href="{{ route('customers.show', $customer) }}" class="btn btn-sm btn-brand-outline">View</a>
                            <a href="{{ route('customers.edit', $customer) }}" class="btn btn-sm btn-brand-outline">Edit</a>
                            @if($customer->invoices_count === 0)
                            <form method="POST" action="{{ route('customers.destroy', $customer) }}" class="d-inline"
                                  onsubmit="return confirm('Delete this customer permanently?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">Delete</button>
                            </form>
                            @else
                            <button type="button" class="btn btn-sm btn-outline-secondary" disabled
                                    title="Cannot delete — customer has invoices">Delete</button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-4">No customers yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($customers->hasPages())
    <div class="card-footer">{{ $customers->links() }}</div>
    @endif
</div>
@endsection
