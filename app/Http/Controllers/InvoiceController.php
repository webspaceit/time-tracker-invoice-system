<?php

namespace App\Http\Controllers;

use App\Helpers\Duration;
use App\Models\Customer;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with('customer')
            ->latest()
            ->paginate(10);

        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();

        return view('invoices.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $request->validate($this->validationRules());

        try {
            DB::beginTransaction();

            [$itemsData, $subtotal, $totalDuration] = $this->parseItems($request->items);
            $taxAmount = $subtotal * ($request->tax_rate / 100);
            $totalAmount = $subtotal + $taxAmount;

            $invoice = Invoice::create([
                'customer_id' => $request->customer_id,
                'issue_date' => $request->issue_date,
                'due_date' => $request->due_date,
                'subtotal' => $subtotal,
                'tax_rate' => $request->tax_rate,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
                'status' => 'draft',
                'notes' => $request->notes,
                'currency' => $request->currency,
                'hourly_rate' => $request->hourly_rate,
                'project_title' => $request->project_title,
                'work_details' => $request->work_details,
                'total_duration' => $totalDuration,
                'terms' => $request->terms,
                'typography' => $request->typography,
            ]);

            foreach ($itemsData as $item) {
                $invoice->items()->create($item);
            }

            DB::commit();

            return redirect()->route('invoices.show', $invoice)
                ->with('success', 'Invoice created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()->with('error', 'Failed to create invoice: '.$e->getMessage());
        }
    }

    public function show(Invoice $invoice)
    {
        $invoice->load('customer', 'items', 'payments');

        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        if ($invoice->status === 'paid') {
            return back()->with('error', 'Cannot edit a paid invoice.');
        }

        $invoice->load('items');
        $customers = Customer::orderBy('name')->get();

        return view('invoices.edit', compact('invoice', 'customers'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        if ($invoice->status === 'paid') {
            return back()->with('error', 'Cannot edit a paid invoice.');
        }

        $request->validate($this->validationRules());

        try {
            DB::beginTransaction();

            [$itemsData, $subtotal, $totalDuration] = $this->parseItems($request->items);
            $taxAmount = $subtotal * ($request->tax_rate / 100);
            $totalAmount = $subtotal + $taxAmount;

            $invoice->update([
                'customer_id' => $request->customer_id,
                'issue_date' => $request->issue_date,
                'due_date' => $request->due_date,
                'subtotal' => $subtotal,
                'tax_rate' => $request->tax_rate,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
                'notes' => $request->notes,
                'currency' => $request->currency,
                'hourly_rate' => $request->hourly_rate,
                'project_title' => $request->project_title,
                'work_details' => $request->work_details,
                'total_duration' => $totalDuration,
                'terms' => $request->terms,
                'typography' => $request->typography,
            ]);

            $invoice->items()->delete();
            foreach ($itemsData as $item) {
                $invoice->items()->create($item);
            }

            DB::commit();

            return redirect()->route('invoices.show', $invoice)
                ->with('success', 'Invoice updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()->with('error', 'Failed to update invoice: '.$e->getMessage());
        }
    }

    public function destroy(Invoice $invoice)
    {
        if ($invoice->status === 'paid') {
            return back()->with('error', 'Cannot delete a paid invoice.');
        }

        $invoice->delete();

        return redirect()->route('invoices.index')
            ->with('success', 'Invoice deleted successfully!');
    }

    public function updateStatus(Invoice $invoice, $status)
    {
        $allowedStatuses = ['draft', 'sent', 'paid', 'cancelled'];

        if (! in_array($status, $allowedStatuses)) {
            return back()->with('error', 'Invalid status.');
        }

        $invoice->update(['status' => $status]);

        return back()->with('success', "Invoice status updated to {$status}!");
    }

    public function download(Invoice $invoice)
    {
        $invoice->load('customer', 'items');

        $month = $invoice->issue_date->format('F');
        $year = $invoice->issue_date->format('Y');
        $filename = "wilderness-explorers-website-maintenance-invoice-for-the-month-of-{$month}-{$year}.pdf";

        return Pdf::loadView('invoices.pdf', compact('invoice'))
            ->setPaper('a4', 'portrait')
            ->setOption(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => false, 'defaultFont' => 'DejaVu Sans'])
            ->download($filename);
    }

    private function validationRules(): array
    {
        return [
            'customer_id' => 'required|exists:customers,id',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'hourly_rate' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'currency' => 'required|string|size:3',
            'project_title' => 'nullable|string|max:255',
            'work_details' => 'nullable|string',
            'total_duration' => 'nullable|string|max:20',
            'terms' => 'nullable|string',
            'typography' => 'nullable|array',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.amount' => 'nullable|numeric|min:0',
            'items.*.duration' => 'nullable|string|max:20',
        ];
    }

  /**
     * @return array{0: array<int, array<string, mixed>>, 1: float, 2: string}
     */
    private function parseItems(array $items): array
    {
        $itemsData = [];
        $subtotal = 0;
        $durations = [];

        foreach ($items as $item) {
            $amount = (float) ($item['amount'] ?? 0);
            $subtotal += $amount;
            $durations[] = $item['duration'] ?? null;

            $itemsData[] = [
                'description' => $item['description'],
                'duration' => $item['duration'] ?? null,
                'quantity' => 1,
                'unit_price' => $amount,
                'discount' => 0,
                'total' => $amount,
            ];
        }

        return [$itemsData, $subtotal, Duration::sum($durations)];
    }
}
