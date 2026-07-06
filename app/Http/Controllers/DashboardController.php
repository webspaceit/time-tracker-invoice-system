<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
    public function index()
    {
        // Statistics
        $totalCustomers = Customer::count();
        $totalInvoices = Invoice::count();
        $totalRevenue = Invoice::where('status', 'paid')->sum('total_amount');
        $pendingAmount = Invoice::whereIn('status', ['sent', 'draft'])->sum('total_amount');
        
        // Monthly revenue for chart
        $monthlyRevenue = DB::table('invoices')
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('SUM(total_amount) as total')
            )
            ->where('status', 'paid')
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
            
        // Recent invoices
        $recentInvoices = Invoice::with('customer')
            ->latest()
            ->take(5)
            ->get();
            
        // Top customers
        $topCustomers = Customer::withCount('invoices')
            ->withSum('invoices', 'total_amount')
            ->orderBy('invoices_sum_total_amount', 'desc')
            ->take(5)
            ->get();
            
        // Invoice status distribution
        $statusDistribution = Invoice::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();
            
        // Overdue invoices
        $overdueInvoices = Invoice::where('status', '!=', 'paid')
            ->where('due_date', '<', now())
            ->where('status', '!=', 'cancelled')
            ->with('customer')
            ->get();
            
        return view('dashboard', compact(
            'totalCustomers',
            'totalInvoices',
            'totalRevenue',
            'pendingAmount',
            'monthlyRevenue',
            'recentInvoices',
            'topCustomers',
            'statusDistribution',
            'overdueInvoices'
        ));
    }

    public function updateTimezone(Request $request)
    {
        $request->validate(['timezone' => 'required|string|timezone']);

        Auth::user()->update(['timezone' => $request->timezone]);

        return back()->with('success', 'Timezone updated to ' . $request->timezone);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        $data = ['name' => $request->name, 'email' => $request->email];
        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }
        $user->update($data);

        return back()->with('success', 'Profile updated successfully.');
    }
}