<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::withCount('invoices')
            ->withSum('invoices', 'total_amount')
            ->latest()
            ->paginate(10);
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'company' => 'nullable|string|max:255',
            'tax_number' => 'nullable|string|max:50',
            'currency' => 'required|string|size:3',
        ]);

        Customer::create($validated);

        return redirect()->route('customers.index')
            ->with('success', 'Customer created successfully!');
    }

    public function show(Customer $customer)
    {
        $customer->load('invoices.items', 'invoices.payments');
        $invoices = $customer->invoices()->latest()->paginate(10);
        return view('customers.show', compact('customer', 'invoices'));
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $customer->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'company' => 'nullable|string|max:255',
            'tax_number' => 'nullable|string|max:50',
            'currency' => 'required|string|size:3',
        ]);

        $customer->update($validated);

        return redirect()->route('customers.show', $customer)
            ->with('success', 'Customer updated successfully!');
    }

    public function destroy(Customer $customer)
    {
        if ($customer->invoices()->exists()) {
            return back()->with('error', 'Cannot delete customer with existing invoices.');
        }
        
        $customer->delete();
        
        return redirect()->route('customers.index')
            ->with('success', 'Customer deleted successfully!');
    }
}