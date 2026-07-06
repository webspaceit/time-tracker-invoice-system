@extends('layouts.app')

@push('styles')
<style>
    :root {
        --brand-green: #1D560B;
        --brand-green-dark: #143d08;
        --brand-green-light: #2a7e10;
    }
    .btn-brand {
        background-color: var(--brand-green);
        border-color: var(--brand-green);
        color: #fff;
    }
    .btn-brand:hover, .btn-brand:focus {
        background-color: var(--brand-green-dark);
        border-color: var(--brand-green-dark);
        color: #fff;
    }
    .btn-brand-outline {
        color: var(--brand-green);
        border-color: var(--brand-green);
        background: transparent;
    }
    .btn-brand-outline:hover {
        background-color: var(--brand-green);
        color: #fff;
    }
    .tt-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-bottom: 3px solid var(--brand-green);
        padding: 1rem 1.25rem;
        border-radius: 0.5rem 0.5rem 0 0;
    }
    .tt-stats-card {
        background: #fff;
        border: 1px solid #dee2e6;
        border-radius: 0.5rem;
        padding: 0.75rem 1rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
    }
    .tt-stats-card .stat-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
    }
    .tt-stats-card .stat-value {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--brand-green);
    }
    .tt-filter-bar {
        background: #fff;
        border: 1px solid #dee2e6;
        border-radius: 0.5rem;
        padding: 0.75rem 1rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
    }
    .tt-card {
        border: 1px solid #dee2e6;
        border-radius: 0.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        overflow: hidden;
    }
    .tt-card .card-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-bottom: 2px solid var(--brand-green);
        font-weight: 600;
        padding: 0.75rem 1rem;
    }
    .tt-timer-display {
        font-size: 2.8rem;
        font-weight: 700;
        font-variant-numeric: tabular-nums;
        color: var(--brand-green);
    }
    .tt-timer-label {
        font-size: 0.85rem;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
</style>
@endpush

@section('content')
<div class="tt-card mb-4">
    <div class="tt-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0 fw-bold" style="color: var(--brand-green);">
            <i class="fas fa-stopwatch me-2"></i>Time Tracker
        </h4>
        <div class="d-flex gap-3">
            <div class="tt-stats-card">
                <div class="stat-label">Period Total</div>
                <div class="stat-value">@if($periodApplied){{ sprintf('%02d:%02d:%02d', intdiv($totalSeconds, 3600), intdiv($totalSeconds % 3600, 60), $totalSeconds % 60) }}@else 00:00:00 @endif</div>
            </div>
            <div class="tt-stats-card">
                <div class="stat-label">Period Earn</div>
                <div class="stat-value">@if($periodApplied)${{ number_format($totalEarn, 2) }}@else $0.00 @endif</div>
            </div>
            <div class="tt-stats-card border-success">
                <div class="stat-label">Current Month Total Hours</div>
                <div class="stat-value" id="page-period-total">{{ sprintf('%02d:%02d:%02d', intdiv($monthTotalSeconds, 3600), intdiv($monthTotalSeconds % 3600, 60), $monthTotalSeconds % 60) }}</div>
            </div>
            <div class="tt-stats-card border-success">
                <div class="stat-label">Current Month Total Earning</div>
                <div class="stat-value" id="page-period-earn">${{ number_format($monthTotalEarn, 2) }}</div>
            </div>
        </div>
    </div>

    <div class="p-3">
        <form method="GET" action="{{ route('time-tracker.index') }}" class="tt-filter-bar row g-2 align-items-center" id="filter-form">
            <div class="col-auto">
                <select name="period" class="form-select form-select-sm" onchange="this.form.submit()" id="period-select">
                    <option value="today" {{ $period === 'today' ? 'selected' : '' }}>Today</option>
                    <option value="yesterday" {{ $period === 'yesterday' ? 'selected' : '' }}>Yesterday</option>
                    <option value="this_week" {{ $period === 'this_week' ? 'selected' : '' }}>This Week</option>
                    <option value="last_week" {{ $period === 'last_week' ? 'selected' : '' }}>Last Week</option>
                    <option value="past_two_weeks" {{ $period === 'past_two_weeks' ? 'selected' : '' }}>Past 2 Weeks</option>
                    <option value="this_month" {{ $period === 'this_month' ? 'selected' : '' }}>This Month</option>
                    <option value="last_month" {{ $period === 'last_month' ? 'selected' : '' }}>Last Month</option>
                    <option value="month" {{ $period === 'month' ? 'selected' : '' }}>Month Archive</option>
                    <option value="this_year" {{ $period === 'this_year' ? 'selected' : '' }}>This Year</option>
                    <option value="last_year" {{ $period === 'last_year' ? 'selected' : '' }}>Last Year</option>
                    <option value="custom" {{ $period === 'custom' ? 'selected' : '' }}>Custom Range</option>
                </select>
            </div>
            <div class="col-auto" id="archive-month-group" @if($period !== 'month') style="display:none;" @endif>
                <input type="month" name="month" value="{{ $archiveMonth ?? date('Y-m') }}" class="form-control form-control-sm">
            </div>
            <div class="col-auto" id="custom-date-group" @if($period !== 'custom') style="display:none;" @endif>
                <input type="date" name="from" value="{{ $from ?? '' }}" class="form-control form-control-sm">
            </div>
            <div class="col-auto" id="custom-to-label" @if($period !== 'custom') style="display:none;" @endif>
                <span class="mx-1 text-muted">to</span>
            </div>
            <div class="col-auto" id="custom-date-to-group" @if($period !== 'custom') style="display:none;" @endif>
                <input type="date" name="to" value="{{ $to ?? '' }}" class="form-control form-control-sm">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-sm btn-brand"><i class="fas fa-filter me-1"></i>Filter</button>
            </div>
            <div class="col-auto">
                <a href="{{ route('time-tracker.index') }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-undo me-1"></i>Reset</a>
            </div>
        </form>
        <script>
        document.getElementById('period-select').addEventListener('change', function() {
            const val = this.value;
            document.getElementById('archive-month-group').style.display = val === 'month' ? '' : 'none';
            document.getElementById('custom-date-group').style.display = val === 'custom' ? '' : 'none';
            document.getElementById('custom-to-label').style.display = val === 'custom' ? '' : 'none';
            document.getElementById('custom-date-to-group').style.display = val === 'custom' ? '' : 'none';
        });
        </script>
    </div>
</div>

<div class="row">
    {{-- Active Timer Card --}}
    <div class="col-md-4 mb-4">
        <div class="tt-card">
            <div class="card-header">
                <i class="fas fa-stopwatch me-2" style="color: var(--brand-green);"></i>
                {{ $running ? 'Timer Running' : 'Timer Stopped' }}
            </div>
            <div class="card-body text-center py-4">
                <div id="page-timer-display" class="tt-timer-display my-3">
                    @if($running)
                        {{ $running->formatted_duration }}
                    @else
                        00:00:00
                    @endif
                </div>
                <div class="tt-timer-label mb-1">{{ $running ? 'Currently tracking' : 'No active timer' }}</div>
                <div id="page-timer-desc" class="text-muted mb-1">{{ $running?->description ?? '' }}</div>
                <div id="page-timer-project" class="small" style="color: var(--brand-green-light);">{{ $running?->project?->name ?? '' }}</div>
                <div id="page-timer-customer" class="small text-muted mb-3">{{ $running?->customer?->name ?? '' }}</div>

                @if($running)
                    <button class="btn btn-danger btn-lg px-4" onclick="pageStopTimer()" type="button">
                        <i class="fas fa-stop me-2"></i>Stop
                    </button>
                @else
                    <button class="btn btn-brand btn-lg px-4" onclick="pageShowStartForm()" type="button">
                        <i class="fas fa-play me-2"></i>Start
                    </button>
                @endif

                <div id="page-start-form" class="mt-3 {{ $running ? 'd-none' : 'd-none' }}">
                    <select id="page-project-select" class="form-select mb-2" onchange="pageProjectChanged(this)">
                        <option value="">No project</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" data-customer-id="{{ $project->customer_id ?? '' }}">{{ $project->name }}@if($project->customer) ({{ $project->customer->name }})@endif</option>
                        @endforeach
                    </select>
                    <select id="page-customer-select" class="form-select mb-2">
                        <option value="">No customer</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                        @endforeach
                    </select>
                    <input type="text" id="page-desc-input" class="form-control mb-2" placeholder="What are you working on?">
                    <div class="d-grid gap-1">
                        <button class="btn btn-brand" onclick="pageConfirmStart()" type="button">
                            <i class="fas fa-play me-1"></i>Start Timer
                        </button>
                        <a href="{{ route('customers.create') }}" class="btn btn-sm btn-brand-outline" target="_blank">
                            <i class="fas fa-plus me-1"></i>New Customer
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Manual Entry Card --}}
        <div class="tt-card mt-3">
            <div class="card-header">
                <i class="fas fa-pen me-2" style="color: var(--brand-green);"></i>
                Log Time Manually
            </div>
            <div class="card-body">
                <select id="page-manual-project" class="form-select mb-2" onchange="pageManualProjectChanged(this)">
                    <option value="">No project</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" data-customer-id="{{ $project->customer_id ?? '' }}">{{ $project->name }}@if($project->customer) ({{ $project->customer->name }})@endif</option>
                    @endforeach
                </select>
                <select id="page-manual-customer" class="form-select mb-2">
                    <option value="">No customer</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                    @endforeach
                </select>
                <input type="text" id="page-manual-desc" class="form-control mb-2" placeholder="Description">
                <div class="row g-2 mb-2">
                    <div class="col-6">
                        <input type="date" id="page-manual-date" class="form-control" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-6">
                        <input type="text" id="page-manual-duration" class="form-control" placeholder="HH:MM:SS">
                    </div>
                </div>
                <button class="btn btn-brand w-100" onclick="pageConfirmManual()" type="button">
                    <i class="fas fa-save me-1"></i>Log Time
                </button>
                <div class="mt-2">
                    <a href="{{ route('customers.create') }}" class="btn btn-sm btn-brand-outline w-100" target="_blank">
                        <i class="fas fa-plus me-1"></i>New Customer
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Entries List --}}
    <div class="col-md-8 mb-4">
        <div class="tt-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0" style="color: var(--brand-green);">
                    @switch($period)
                        @case('today') Today's @break
                        @case('yesterday') Yesterday's @break
                        @case('this_week') This Week's @break
                        @case('last_week') Last Week's @break
                        @case('past_two_weeks') Past 2 Weeks @break
                        @case('this_month') This Month's @break
                        @case('last_month') Last Month's @break
                        @case('month')
                            @if($prevMonth)
                                <a href="{{ route('time-tracker.index', ['period' => 'month', 'month' => $prevMonth]) }}" class="text-decoration-none me-2" style="color:var(--brand-green);" title="Previous month">&lsaquo;</a>
                            @endif
                            <strong>{{ $archiveLabel }}</strong>
                            @if($nextMonth)
                                <a href="{{ route('time-tracker.index', ['period' => 'month', 'month' => $nextMonth]) }}" class="text-decoration-none ms-2" style="color:var(--brand-green);" title="Next month">&rsaquo;</a>
                            @endif
                        @break
                        @case('this_year') This Year's @break
                        @case('last_year') Last Year's @break
                        @case('custom')
                            @if($from && $to)
                                <span class="small text-muted fw-normal me-1">Custom:</span>
                                <strong>{{ \Carbon\Carbon::parse($from)->format('M d, Y') }}</strong>
                                <span class="text-muted mx-1">&ndash;</span>
                                <strong>{{ \Carbon\Carbon::parse($to)->format('M d, Y') }}</strong>
                            @else
                                Custom Range
                            @endif
                        @break
                        @default Today's
                    @endswitch
                    Entries
                </h5>
                <span class="badge" style="background: var(--brand-green);">{{ $entries->count() }} entries</span>
            </div>
            <div class="card-body p-3">
                @php
                    $groupedEntries = $entries->groupBy(function($entry) {
                        $date = in_user_timezone($entry->start_time)->format('Y-m-d');
                        return $date . '_' . ($entry->description ?? '') . '_' . ($entry->project_id ?? '') . '_' . ($entry->customer_id ?? '');
                    });
                @endphp
                @forelse($groupedEntries as $group)
                    @php
                        $firstEntry = $group->first();
                        $groupSeconds = $group->sum(function($e) { return $e->duration; });
                        $hours = intdiv($groupSeconds, 3600);
                        $minutes = intdiv($groupSeconds % 3600, 60);
                        $secs = $groupSeconds % 60;
                        $formattedGroupDuration = sprintf('%02d:%02d:%02d', $hours, $minutes, $secs);

                        $hasRunning = $group->contains(fn($e) => !$e->end_time);
                        $completedSeconds = $group->filter(fn($e) => $e->end_time)->sum(fn($e) => $e->duration);
                    @endphp
                    <div class="table-responsive mb-4 shadow-sm rounded">
                        <table class="table table-bordered mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th colspan="3" style="font-size: 14px; padding: 10px 12px;">
                                        <i class="far fa-calendar-alt me-1" style="color: var(--brand-green);"></i>
                                        <strong>{{ in_user_timezone($firstEntry->start_time)->format('D, M d') }}</strong>
                                        @if($firstEntry->description)
                                            <span class="mx-2 text-muted">|</span>
                                            <span class="text-dark fw-semibold">{{ $firstEntry->description }}</span>
                                        @endif
                                        @if($firstEntry->project)
                                            <span class="ms-2 d-inline-block rounded-circle" style="width:8px;height:8px;background:{{ $firstEntry->project->color }}"></span>
                                            <small class="text-muted">{{ $firstEntry->project->name }}</small>
                                        @endif
                                    </th>
                                    <th colspan="2" class="text-end fw-bold" style="font-size: 14px; padding: 10px 12px;" 
                                        @if($hasRunning) 
                                            id="running-group-duration" 
                                            data-completed-seconds="{{ $completedSeconds }}"
                                        @endif>
                                        {{ $formattedGroupDuration }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($group as $entry)
                                    <tr>
                                        <td style="width: 35%; vertical-align: middle; padding: 10px 12px;">
                                            @if($entry->description)
                                                <span class="text-dark">{{ $entry->description }}</span>
                                            @else
                                                <span class="text-muted italic small">No description</span>
                                            @endif
                                            @if($entry->project)
                                                <div class="mt-1">
                                                    <span class="d-inline-block rounded-circle me-1" style="width:6px;height:6px;background:{{ $entry->project->color }}"></span>
                                                    <small class="text-muted">{{ $entry->project->name }}</small>
                                                    @if($entry->customer)
                                                        <small class="text-muted">&middot; {{ $entry->customer->name }}</small>
                                                    @endif
                                                </div>
                                            @endif
                                        </td>
                                        <td style="width: 25%; vertical-align: middle; padding: 10px 12px;">
                                            <div class="small text-muted mb-1" style="font-size: 0.75rem;">Time Period</div>
                                            <div>
                                                <span class="editable-time" data-id="{{ $entry->id }}" data-field="start_time" data-value="{{ in_user_timezone($entry->start_time)->format('H:i') }}" onclick="editTime(this)">{{ in_user_timezone($entry->start_time)->format('H:i') }}</span>
                                                -
                                                @if($entry->end_time)
                                                    <span class="editable-time" data-id="{{ $entry->id }}" data-field="end_time" data-value="{{ in_user_timezone($entry->end_time)->format('H:i') }}" onclick="editTime(this)">{{ in_user_timezone($entry->end_time)->format('H:i') }}</span>
                                                @else
                                                    <span class="badge bg-danger animate-pulse">Active</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td style="width: 15%; vertical-align: middle; padding: 10px 12px; font-variant-numeric: tabular-nums;" @if(!$entry->end_time) id="running-entry-duration" @endif>
                                            {{ $entry->formatted_duration }}
                                        </td>
                                        <td style="width: 10%; text-align: center; vertical-align: middle; padding: 10px 12px;">
                                            <div class="small text-muted mb-1" style="font-size: 0.75rem;">Billable</div>
                                            @if($entry->billable)
                                                <span class="text-success"><i class="fas fa-check-circle"></i></span>
                                            @else
                                                <span class="text-muted"><i class="fas fa-times-circle"></i></span>
                                            @endif
                                        </td>
                                        <td style="width: 15%; text-align: right; vertical-align: middle; padding: 10px 12px;">
                                            <div class="small text-muted mb-1" style="font-size: 0.75rem;">Actions</div>
                                            @if($entry->end_time)
                                                <button class="btn btn-sm btn-outline-success me-1"
                                                    onclick="continueEntry(this)"
                                                    type="button"
                                                    data-project-id="{{ $entry->project_id }}"
                                                    data-customer-id="{{ $entry->customer_id }}"
                                                    data-description="{{ $entry->description }}"
                                                    title="Continue timer for this activity">
                                                    <i class="fas fa-play"></i>
                                                </button>
                                            @else
                                                <button class="btn btn-sm btn-danger me-1"
                                                    onclick="pageStopTimer()"
                                                    type="button"
                                                    title="Stop running timer">
                                                    <i class="fas fa-stop"></i>
                                                </button>
                                            @endif
                                            <button class="btn btn-sm btn-outline-danger" onclick="deleteEntry({{ $entry->id }})" type="button">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @empty
                    <div class="text-center text-muted py-4">No time entries recorded</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const pageOriginalTitle = document.title;
let pageTimerInterval = null;

function pageTick() {
    const running = @json($running);
    if (!running) return;
    const start = new Date(running.start_time).getTime();
    const elapsed = Math.floor((Date.now() - start) / 1000);
    const h = String(Math.floor(elapsed / 3600)).padStart(2, '0');
    const m = String(Math.floor((elapsed % 3600) / 60)).padStart(2, '0');
    const s = String(elapsed % 60).padStart(2, '0');
    const time = `${h}:${m}:${s}`;
    document.getElementById('page-timer-display').textContent = time;
    document.title = `▶ ${time} - ${pageOriginalTitle}`;
    
    const rowDur = document.getElementById('running-entry-duration');
    if (rowDur) {
        rowDur.textContent = time;
    }
    
    const groupDur = document.getElementById('running-group-duration');
    if (groupDur) {
        const completedSecs = parseInt(groupDur.dataset.completedSeconds || 0, 10);
        const totalSecs = completedSecs + elapsed;
        const gh = String(Math.floor(totalSecs / 3600)).padStart(2, '0');
        const gm = String(Math.floor((totalSecs % 3600) / 60)).padStart(2, '0');
        const gs = String(totalSecs % 60).padStart(2, '0');
        groupDur.textContent = `${gh}:${gm}:${gs}`;
    }

}

@if($running)
pageTick();
pageTimerInterval = setInterval(pageTick, 1000);
@else
document.title = pageOriginalTitle;
@endif

function continueEntry(btn) {
    fetch('{{ route("time-tracker.start") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({
            project_id: btn.dataset.projectId || null,
            customer_id: btn.dataset.customerId || null,
            description: btn.dataset.description || ''
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.error) { alert(data.error); return; }
        location.reload();
    });
}

function pageProjectChanged(sel) {
    const opt = sel.options[sel.selectedIndex];
    const cs = document.getElementById('page-customer-select');
    if (opt && opt.dataset.customerId) { cs.value = opt.dataset.customerId; }
}

function pageManualProjectChanged(sel) {
    const opt = sel.options[sel.selectedIndex];
    const cs = document.getElementById('page-manual-customer');
    if (opt && opt.dataset.customerId) { cs.value = opt.dataset.customerId; }
}

function pageShowStartForm() {
    const f = document.getElementById('page-start-form');
    f.classList.toggle('d-none');
}

function pageConfirmStart() {
    const projectId = document.getElementById('page-project-select').value;
    const customerId = document.getElementById('page-customer-select').value;
    const description = document.getElementById('page-desc-input').value;
    fetch('{{ route("time-tracker.start") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ project_id: projectId || null, customer_id: customerId || null, description })
    })
    .then(r => r.json())
    .then(data => {
        if (data.error) { alert(data.error); return; }
        location.reload();
    });
}

function pageStopTimer() {
    fetch('{{ route("time-tracker.stop") }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.error) { alert(data.error); return; }
        location.reload();
    });
}

function pageConfirmManual() {
    const projectId = document.getElementById('page-manual-project').value;
    const customerId = document.getElementById('page-manual-customer').value;
    const description = document.getElementById('page-manual-desc').value;
    const date = document.getElementById('page-manual-date').value;
    const duration = document.getElementById('page-manual-duration').value;

    if (!duration.match(/^\d{1,2}:\d{2}:\d{2}$/)) {
        alert('Please enter duration in HH:MM:SS format');
        return;
    }

    fetch('{{ route("time-tracker.store") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ project_id: projectId || null, customer_id: customerId || null, description, date, duration })
    })
    .then(r => r.json())
    .then(data => {
        if (data.error) { alert(data.error); return; }
        location.reload();
    });
}

function deleteEntry(id) {
    if (!confirm('Delete this time entry?')) return;
    fetch(`{{ url('time-tracker') }}/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) location.reload();
    });
}

function editTime(el) {
    if (el.tagName === 'INPUT') return;
    const id = el.dataset.id;
    const field = el.dataset.field;
    const val = el.dataset.value || '';
    const input = document.createElement('input');
    input.type = 'time';
    input.value = val;
    input.className = 'form-control form-control-sm d-inline-block';
    input.style.width = '90px';
    el.replaceWith(input);
    input.focus();
    input.select();

    function save() {
        const newVal = input.value;
        fetch(`{{ url('time-tracker') }}/${id}`, {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ [field]: newVal })
        })
        .then(r => r.json())
        .then(data => {
            if (data.entry) {
                location.reload();
            }
        });
    }

    input.addEventListener('change', save);
    input.addEventListener('blur', function() {
        if (this.value !== val) save();
        else {
            const span = document.createElement('span');
            span.className = 'editable-time';
            span.dataset.id = id;
            span.dataset.field = field;
            span.dataset.value = val;
            span.textContent = val;
            span.onclick = function() { editTime(this); };
            this.replaceWith(span);
        }
    });
}
</script>
@endpush
