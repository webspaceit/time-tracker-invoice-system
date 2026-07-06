<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Project;
use App\Models\TimeEntry;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TimeEntryController extends Controller
{
    public function index(Request $request)
    {
        $tz = Auth::user()->timezone ?? 'UTC';
        $period = $request->query('period', 'today');
        $periodApplied = $request->has('period');
        $from = $request->query('from');
        $to = $request->query('to');
        $archiveMonth = $request->query('month');

        $now = Carbon::now($tz);
        $startOf = match ($period) {
            'yesterday' => $now->copy()->subDay()->startOfDay(),
            'this_week' => $now->copy()->startOfWeek(),
            'last_week' => $now->copy()->subWeek()->startOfWeek(),
            'past_two_weeks' => $now->copy()->subWeeks(2)->startOfDay(),
            'this_month' => $now->copy()->startOfMonth(),
            'last_month' => $now->copy()->subMonth()->startOfMonth(),
            'this_year' => $now->copy()->startOfYear(),
            'last_year' => $now->copy()->subYear()->startOfYear(),
            'month' => $archiveMonth ? Carbon::parse($archiveMonth . '-01', $tz)->startOfMonth() : $now->copy()->startOfMonth(),
            'custom' => $from ? Carbon::parse($from, $tz)->startOfDay() : $now->copy()->startOfDay(),
            default => $now->copy()->startOfDay(),
        };

        $endOf = match ($period) {
            'yesterday' => $now->copy()->subDay()->endOfDay(),
            'this_week' => $now->copy()->endOfWeek(),
            'last_week' => $now->copy()->subWeek()->endOfWeek(),
            'past_two_weeks' => $now->copy()->endOfDay(),
            'this_month' => $now->copy()->endOfMonth(),
            'last_month' => $now->copy()->subMonth()->endOfMonth(),
            'this_year' => $now->copy()->endOfYear(),
            'last_year' => $now->copy()->subYear()->endOfYear(),
            'month' => $archiveMonth ? Carbon::parse($archiveMonth . '-01', $tz)->endOfMonth() : $now->copy()->endOfMonth(),
            'custom' => $to ? Carbon::parse($to, $tz)->endOfDay() : $now->copy()->endOfDay(),
            default => $now->copy()->endOfDay(),
        };

        // Determine archive month label and prev/next
        $archiveLabel = null;
        $prevMonth = null;
        $nextMonth = null;
        if ($period === 'month') {
            $archiveDate = $archiveMonth ? Carbon::parse($archiveMonth . '-01', $tz) : $now->copy()->startOfMonth();
            $archiveLabel = $archiveDate->format('F Y');
            $prevMonth = $archiveDate->copy()->subMonth()->format('Y-m');
            $nextMonth = $archiveDate->copy()->addMonth()->format('Y-m');
        }

        $entries = TimeEntry::with('customer', 'project')
            ->where('user_id', Auth::id())
            ->whereBetween('start_time', [$startOf->setTimezone('UTC'), $endOf->setTimezone('UTC')])
            ->orderBy('start_time', 'desc')
            ->get();

        $running = TimeEntry::with('customer', 'project')
            ->where('user_id', Auth::id())
            ->running()
            ->first();

        $nowUtc = Carbon::now('UTC');
        $totalSeconds = $entries->sum(function ($e) use ($nowUtc) {
            return max(0, $e->end_time ? ($e->total_seconds ?? 0) : $nowUtc->diffInSeconds($e->start_time));
        });
        $totalEarn = $entries->sum(function ($e) use ($nowUtc) {
            $secs = max(0, $e->end_time ? ($e->total_seconds ?? 0) : $nowUtc->diffInSeconds($e->start_time));
            return ($secs / 3600) * ($e->hourly_rate ?? 20);
        });

        // Current Month Totals
        $startOfMonth = $now->copy()->startOfMonth()->setTimezone('UTC');
        $endOfMonth = $now->copy()->endOfMonth()->setTimezone('UTC');
        $monthEntries = TimeEntry::where('user_id', Auth::id())
            ->whereBetween('start_time', [$startOfMonth, $endOfMonth])
            ->get();
        $monthTotalSeconds = $monthEntries->sum(function ($e) use ($nowUtc) {
            return max(0, $e->end_time ? ($e->total_seconds ?? 0) : $nowUtc->diffInSeconds($e->start_time));
        });
        $monthTotalEarn = $monthEntries->sum(function ($e) use ($nowUtc) {
            $secs = max(0, $e->end_time ? ($e->total_seconds ?? 0) : $nowUtc->diffInSeconds($e->start_time));
            return ($secs / 3600) * ($e->hourly_rate ?? 20);
        });

        $customers = Customer::orderBy('name')->get();
        $projects = Project::with('customer')->where('is_active', true)->orderBy('name')->get();

        return view('time-tracker.index', compact(
            'entries', 'running', 'totalSeconds', 'totalEarn',
            'customers', 'projects', 'period', 'from', 'to', 'archiveMonth',
            'archiveLabel', 'prevMonth', 'nextMonth', 'periodApplied',
            'monthTotalSeconds', 'monthTotalEarn'
        ));
    }

    public function current()
    {
        $running = TimeEntry::with('customer', 'project')
            ->where('user_id', Auth::id())
            ->running()
            ->first();

        return response()->json(['running' => $running]);
    }

    public function today()
    {
        $entries = TimeEntry::with('customer', 'project')
            ->where('user_id', Auth::id())
            ->whereDate('start_time', today())
            ->orderBy('start_time', 'desc')
            ->get();

        $todayTotal = $entries->sum(function ($e) {
            return max(0, $e->end_time ? $e->total_seconds : now()->diffInSeconds($e->start_time));
        });

        return response()->json([
            'entries' => $entries->load('customer', 'project'),
            'todayTotal' => $todayTotal,
            'todayTotalFormatted' => sprintf(
                '%02d:%02d:%02d',
                intdiv($todayTotal, 3600),
                intdiv($todayTotal % 3600, 60),
                $todayTotal % 60
            ),
        ]);
    }

    public function summary(Request $request)
    {
        $data = $request->validate([
            'from' => 'required|date',
            'to' => 'required|date|after_or_equal:from',
        ]);

        $tz = Auth::user()->timezone ?? 'UTC';
        $from = Carbon::parse($data['from'], $tz)->startOfDay()->setTimezone('UTC');
        $to = Carbon::parse($data['to'], $tz)->endOfDay()->setTimezone('UTC');

        $entries = TimeEntry::with('customer', 'project')
            ->where('user_id', Auth::id())
            ->whereBetween('start_time', [$from, $to])
            ->orderBy('start_time', 'desc')
            ->get();

        $totalSeconds = $entries->sum(fn($e) => max(0, $e->total_seconds ?? 0));
        $totalEarn = $entries->sum(function ($e) {
            $hours = max(0, $e->total_seconds ?? 0) / 3600;
            return $hours * ($e->hourly_rate ?? 20);
        });

        $grouped = $entries->groupBy(fn($e) => $e->description ?: '(no description)');
        $entryList = $grouped->map(function ($group, $label) {
            $total = $group->sum(fn($e) => max(0, $e->total_seconds ?? 0));
            $duration = sprintf('%02d:%02d:%02d', intdiv($total, 3600), intdiv($total % 3600, 60), $total % 60);
            return compact('label', 'duration');
        })->values();

        return response()->json([
            'totalSeconds' => $totalSeconds,
            'totalEarn' => round($totalEarn, 2),
            'totalDurationFormatted' => sprintf(
                '%02d:%02d:%02d',
                intdiv($totalSeconds, 3600),
                intdiv($totalSeconds % 3600, 60),
                $totalSeconds % 60
            ),
            'entryCount' => $entries->count(),
            'entries' => $entryList,
        ]);
    }

    public function start(Request $request)
    {
        $data = $request->validate([
            'description' => 'nullable|string|max:255',
            'customer_id' => 'nullable|exists:customers,id',
            'project_id' => 'nullable|exists:projects,id',
            'billable' => 'boolean',
        ]);

        $running = TimeEntry::where('user_id', Auth::id())->running()->first();
        if ($running) {
            return response()->json(['error' => 'A timer is already running.'], 409);
        }

        $customer = $data['customer_id'] ? Customer::find($data['customer_id']) : null;
        $project = $data['project_id'] ? Project::find($data['project_id']) : null;

        $entry = TimeEntry::create([
            'user_id' => Auth::id(),
            'customer_id' => $data['customer_id'] ?? null,
            'project_id' => $data['project_id'] ?? null,
            'description' => $data['description'] ?? null,
            'start_time' => now(),
            'billable' => $data['billable'] ?? true,
            'hourly_rate' => $project?->hourly_rate ?? $customer?->invoices()->latest()->first()?->hourly_rate ?? 20.00,
        ]);

        $entry->load('customer', 'project');

        return response()->json(['entry' => $entry]);
    }

    public function stop(Request $request)
    {
        $entry = TimeEntry::where('user_id', Auth::id())->running()->first();

        if (!$entry) {
            return response()->json(['error' => 'No running timer found.'], 404);
        }

        $seconds = abs(now()->diffInSeconds($entry->start_time));

        $entry->update([
            'end_time' => now(),
            'total_seconds' => $seconds,
        ]);

        return response()->json(['entry' => $entry->fresh()->load('customer', 'project')]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'description' => 'nullable|string|max:255',
            'customer_id' => 'nullable|exists:customers,id',
            'project_id' => 'nullable|exists:projects,id',
            'date' => 'required|date',
            'duration' => 'required|string|regex:/^\d{1,2}:\d{2}:\d{2}$/',
            'billable' => 'boolean',
        ]);

        $parts = explode(':', $data['duration']);
        $totalSeconds = abs(((int)$parts[0] * 3600) + ((int)$parts[1] * 60) + (int)$parts[2]);

        $customer = $data['customer_id'] ? Customer::find($data['customer_id']) : null;
        $project = $data['project_id'] ? Project::find($data['project_id']) : null;

        $entry = TimeEntry::create([
            'user_id' => Auth::id(),
            'customer_id' => $data['customer_id'] ?? null,
            'project_id' => $data['project_id'] ?? null,
            'description' => $data['description'] ?? null,
            'start_time' => $data['date'] . ' 00:00:00',
            'end_time' => $data['date'] . ' 23:59:59',
            'total_seconds' => $totalSeconds,
            'billable' => $data['billable'] ?? true,
            'hourly_rate' => $project?->hourly_rate ?? $customer?->invoices()->latest()->first()?->hourly_rate ?? 20.00,
        ]);

        return response()->json(['entry' => $entry->load('customer', 'project')]);
    }

    public function update(Request $request, TimeEntry $timeEntry)
    {
        if ($timeEntry->user_id !== Auth::id()) {
            abort(403);
        }

        $data = $request->validate([
            'description' => 'nullable|string|max:255',
            'customer_id' => 'nullable|exists:customers,id',
            'project_id' => 'nullable|exists:projects,id',
            'billable' => 'boolean',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
        ]);

        $tz = Auth::user()->timezone ?? 'UTC';

        if (isset($data['start_time'])) {
            $carbon = $timeEntry->start_time->copy()->setTimezone($tz);
            $parts = explode(':', $data['start_time']);
            $carbon->setTime((int)$parts[0], (int)$parts[1], 0);
            $data['start_time'] = $carbon->setTimezone('UTC');
        }

        if (isset($data['end_time'])) {
            $carbon = $timeEntry->end_time?->copy()->setTimezone($tz) ?? Carbon::now($tz);
            $parts = explode(':', $data['end_time']);
            $carbon->setTime((int)$parts[0], (int)$parts[1], 0);
            $data['end_time'] = $carbon->setTimezone('UTC');
        }

        $timeEntry->update($data);
        $timeEntry->refresh();

        if ($timeEntry->end_time) {
            $timeEntry->update([
                'total_seconds' => abs($timeEntry->end_time->diffInSeconds($timeEntry->start_time)),
            ]);
            $timeEntry->refresh();
        }

        return response()->json(['entry' => $timeEntry->load('customer', 'project')]);
    }

    public function destroy(TimeEntry $timeEntry)
    {
        if ($timeEntry->user_id !== Auth::id()) {
            abort(403);
        }

        $timeEntry->delete();

        return response()->json(['success' => true]);
    }
}
