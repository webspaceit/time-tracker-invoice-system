@extends('layouts.app')

@section('content')
<div class="card shadow-sm" style="border:1px solid #dee2e6;">
    <div class="card-header brand-header"><h5 class="mb-0"><i class="fas fa-user-plus me-2"></i>Add Customer</h5></div>
    <div class="card-body">
        <form method="POST" action="{{ route('customers.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Name *</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Email *</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Company</label>
                    <input type="text" name="company" class="form-control" value="{{ old('company') }}">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Address</label>
                <textarea name="address" class="form-control" rows="2">{{ old('address') }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Tax Number</label>
                <input type="text" name="tax_number" class="form-control" value="{{ old('tax_number') }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Currency *</label>
                <select name="currency" class="form-control @error('currency') is-invalid @enderror" required>
                    @foreach(\App\Helpers\Currency::OPTIONS as $code)
                        <option value="{{ $code }}" @selected(old('currency', 'USD') === $code)>
                            {{ \App\Helpers\Currency::label($code) }}
                        </option>
                    @endforeach
                </select>
                @error('currency')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="btn btn-brand"><i class="fas fa-save me-1"></i>Save</button>
            <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
