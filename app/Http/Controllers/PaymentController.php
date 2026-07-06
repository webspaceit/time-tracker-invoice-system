<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('invoice.customer')
            ->latest()
            ->paginate(15);
        return view('payments.index', compact('payments'));
    }

    public function create(Invoice $invoice)
    {
        return view('payments.create', compact('invoice'));
    }

    public function store(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:0.01|max:' . $invoice->balance_due,
            'payment_method' => 'required|in:cash,bank_transfer,credit_card,check',
            'transaction_id' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        $payment = $invoice->payments()->create($validated);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Payment recorded successfully!');
    }

    public function destroy(Payment $payment)
    {
        $invoice = $payment->invoice;
        $payment->delete();
        
        // Update invoice status
        $paidTotal = $invoice->payments()->sum('amount');
        if ($paidTotal == 0) {
            $invoice->update(['status' => 'draft']);
        } elseif ($paidTotal < $invoice->total_amount) {
            $invoice->update(['status' => 'sent']);
        }
        
        return back()->with('success', 'Payment deleted successfully!');
    }
}