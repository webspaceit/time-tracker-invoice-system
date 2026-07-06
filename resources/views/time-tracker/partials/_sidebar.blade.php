@auth
<div class="border-top border-secondary pt-3 mt-3" id="time-tracker-sidebar">
    <div class="px-2 mb-2">
        <h6 class="text-white-50 small text-uppercase mb-0">
            <i class="fas fa-stopwatch me-1"></i> Time Tracker
        </h6>
    </div>

    <div class="px-2">
        {{-- Running Timer --}}
        <div id="timer-display" class="text-center py-2 bg-dark rounded mb-2">
            <div id="timer-time" class="display-6 text-white fw-bold" style="font-size:1.6rem;font-variant-numeric:tabular-nums">
                00:00:00
            </div>
            <div id="timer-description" class="text-white-50 small" style="font-size:0.75rem"></div>
            <div id="timer-project" class="text-info small" style="font-size:0.7rem"></div>
            <div id="timer-customer" class="text-white-50 small" style="font-size:0.65rem"></div>
        </div>

        {{-- Controls --}}
        <div class="d-flex gap-1">
            <button id="timer-start-btn" class="btn btn-sm flex-grow-1" style="background:#1D560B;border-color:#1D560B;color:#fff;" onclick="startTimer()">
                <i class="fas fa-play me-1"></i>Start
            </button>
            <button id="timer-stop-btn" class="btn btn-danger btn-sm flex-grow-1 d-none" onclick="stopTimer()">
                <i class="fas fa-stop me-1"></i>Stop
            </button>
            <button class="btn btn-outline-light btn-sm" onclick="showManualEntry()" title="Log Time">
                <i class="fas fa-plus"></i>
            </button>
        </div>

        {{-- Start Form --}}
        <div id="timer-start-form" class="mt-2 d-none">
            <select id="timer-project-select" class="form-select form-select-sm mb-1" onchange="timerProjectChanged(this)">
                <option value="">No project</option>
                @foreach(\App\Models\Project::with('customer')->where('is_active', true)->orderBy('name')->get() as $project)
                    <option value="{{ $project->id }}" data-customer-id="{{ $project->customer_id ?? '' }}">{{ $project->name }}@if($project->customer) ({{ $project->customer->name }})@endif</option>
                @endforeach
            </select>
            <select id="timer-customer-select" class="form-select form-select-sm mb-1">
                <option value="">No customer</option>
                @foreach(\App\Models\Customer::orderBy('name')->get() as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                @endforeach
            </select>
            <input type="text" id="timer-desc-input" class="form-control form-control-sm mb-1" placeholder="What are you working on?">
            <button class="btn btn-sm w-100" style="background:#1D560B;border-color:#1D560B;color:#fff;" onclick="confirmStart()">
                <i class="fas fa-play me-1"></i>Start Timer
            </button>
            <div class="mt-1 d-flex justify-content-between">
                <a href="{{ route('customers.create') }}" class="text-white-50" style="font-size:0.65rem" target="_blank">
                    + New customer
                </a>
                <a href="{{ route('projects.index') }}" class="text-white-50" style="font-size:0.65rem">
                    + Manage projects
                </a>
            </div>
        </div>

        {{-- Manual Entry Form --}}
        <div id="timer-manual-form" class="mt-2 d-none">
            <select id="manual-project-select" class="form-select form-select-sm mb-1" onchange="manualProjectChanged(this)">
                <option value="">No project</option>
                @foreach(\App\Models\Project::with('customer')->where('is_active', true)->orderBy('name')->get() as $project)
                    <option value="{{ $project->id }}" data-customer-id="{{ $project->customer_id ?? '' }}">{{ $project->name }}@if($project->customer) ({{ $project->customer->name }})@endif</option>
                @endforeach
            </select>
            <select id="manual-customer-select" class="form-select form-select-sm mb-1">
                <option value="">No customer</option>
                @foreach(\App\Models\Customer::orderBy('name')->get() as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                @endforeach
            </select>
            <input type="text" id="manual-desc" class="form-control form-control-sm mb-1" placeholder="Description">
            <div class="row g-1 mb-1">
                <div class="col-6">
                    <input type="date" id="manual-date" class="form-control form-control-sm" value="{{ date('Y-m-d') }}">
                </div>
                <div class="col-6">
                    <input type="text" id="manual-duration" class="form-control form-control-sm" placeholder="HH:MM:SS">
                </div>
            </div>
            <button class="btn btn-sm w-100" style="background:#1D560B;border-color:#1D560B;color:#fff;" onclick="confirmManualEntry()">
                <i class="fas fa-save me-1"></i>Log Time
            </button>
        </div>

        {{-- Today's Total --}}
        <div class="mt-2 d-flex justify-content-between align-items-center">
            <span class="text-white-50 small">Today:</span>
            <span id="today-total" class="text-white small fw-bold">00:00:00</span>
        </div>

        {{-- Today's Entries --}}
        <div id="today-entries" class="mt-1" style="max-height:180px;overflow-y:auto">
        </div>

        <div class="mt-1 text-center">
            <a href="{{ route('time-tracker.index') }}" class="text-white-50 small" style="font-size:0.7rem">
                View all entries <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
let timerInterval = null;
let runningEntryId = null;
const sidebarOriginalTitle = document.title;

function refreshTimerState() {
    fetch('{{ route("time-tracker.current") }}')
        .then(r => r.json())
        .then(data => {
            if (data.running) {
                runningEntryId = data.running.id;
                startTimerUI(data.running);
            } else {
                stopTimerUI();
            }
        });
}

function refreshTodayEntries() {
    fetch('{{ route("time-tracker.today") }}')
        .then(r => r.json())
        .then(data => {
            document.getElementById('today-total').textContent = data.todayTotalFormatted;
            const container = document.getElementById('today-entries');
            container.innerHTML = '';
            data.entries.forEach(e => {
                const ended = !!e.end_time;
                const dur = ended ? e.total_seconds : Math.floor((Date.now() - new Date(e.start_time).getTime()) / 1000);
                const durStr = String(Math.floor(dur / 3600)).padStart(2,'0') + ':' +
                               String(Math.floor((dur % 3600) / 60)).padStart(2,'0') + ':' +
                               String(dur % 60).padStart(2,'0');
                const proj = e.project ? (e.project.color ? `<span class="d-inline-block rounded-circle me-1" style="width:6px;height:6px;background:${e.project.color}"></span>` : '') + e.project.name : '';
                const div = document.createElement('div');
                div.className = 'd-flex justify-content-between align-items-center py-1 border-bottom border-secondary';
                div.style.fontSize = '0.7rem';
                div.innerHTML = `
                    <div class="text-truncate me-1" style="max-width:100px">
                        <div class="text-white">${e.description || 'No description'}</div>
                        <div class="text-white-50">${proj}${proj && e.customer ? ' &middot; ' : ''}${e.customer ? e.customer.name : ''}</div>
                    </div>
                    <div class="text-nowrap text-white fw-medium ${ended ? '' : 'running-sidebar-entry'}">${durStr}</div>
                `;
                container.appendChild(div);
            });
        });
}

function startTimerUI(entry) {
    document.getElementById('timer-start-btn').classList.add('d-none');
    document.getElementById('timer-stop-btn').classList.remove('d-none');
    document.getElementById('timer-start-form').classList.add('d-none');

    if (timerInterval) clearInterval(timerInterval);

    const startTime = new Date(entry.start_time).getTime();

    function tick() {
        const elapsed = Math.floor((Date.now() - startTime) / 1000);
        const h = String(Math.floor(elapsed / 3600)).padStart(2, '0');
        const m = String(Math.floor((elapsed % 3600) / 60)).padStart(2, '0');
        const s = String(elapsed % 60).padStart(2, '0');
        const time = `${h}:${m}:${s}`;
        document.getElementById('timer-time').textContent = time;
        document.title = `▶ ${time} - ${sidebarOriginalTitle}`;

        const sidebarRowDur = document.querySelector('.running-sidebar-entry');
        if (sidebarRowDur) {
            sidebarRowDur.textContent = time;
        }
    }

    tick();
    timerInterval = setInterval(tick, 1000);

    document.getElementById('timer-description').textContent = entry.description || '';
    document.getElementById('timer-project').textContent = entry.project ? entry.project.name : '';
    document.getElementById('timer-customer').textContent = entry.customer ? entry.customer.name : '';
}

function stopTimerUI() {
    document.getElementById('timer-start-btn').classList.remove('d-none');
    document.getElementById('timer-stop-btn').classList.add('d-none');
    document.getElementById('timer-time').textContent = '00:00:00';
    document.getElementById('timer-description').textContent = '';
    document.getElementById('timer-project').textContent = '';
    document.getElementById('timer-customer').textContent = '';
    if (timerInterval) { clearInterval(timerInterval); timerInterval = null; }
    runningEntryId = null;
    document.title = sidebarOriginalTitle;
}

function timerProjectChanged(sel) {
    const opt = sel.options[sel.selectedIndex];
    const customerSelect = document.getElementById('timer-customer-select');
    if (opt && opt.dataset.customerId) {
        customerSelect.value = opt.dataset.customerId;
    }
}

function manualProjectChanged(sel) {
    const opt = sel.options[sel.selectedIndex];
    const customerSelect = document.getElementById('manual-customer-select');
    if (opt && opt.dataset.customerId) {
        customerSelect.value = opt.dataset.customerId;
    }
}

function startTimer() {
    document.getElementById('timer-start-form').classList.toggle('d-none');
}

function confirmStart() {
    const projectId = document.getElementById('timer-project-select').value;
    const customerId = document.getElementById('timer-customer-select').value;
    const description = document.getElementById('timer-desc-input').value;
    fetch('{{ route("time-tracker.start") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ project_id: projectId || null, customer_id: customerId || null, description: description })
    })
    .then(r => r.json())
    .then(data => {
        if (data.error) { alert(data.error); return; }
        document.getElementById('timer-project-select').value = '';
        document.getElementById('timer-customer-select').value = '';
        document.getElementById('timer-desc-input').value = '';
        document.getElementById('timer-start-form').classList.add('d-none');
        refreshTimerState();
        refreshTodayEntries();
    });
}

function stopTimer() {
    fetch('{{ route("time-tracker.stop") }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.error) { alert(data.error); return; }
        refreshTimerState();
        refreshTodayEntries();
    });
}

function showManualEntry() {
    document.getElementById('timer-manual-form').classList.toggle('d-none');
}

function confirmManualEntry() {
    const projectId = document.getElementById('manual-project-select').value;
    const customerId = document.getElementById('manual-customer-select').value;
    const description = document.getElementById('manual-desc').value;
    const date = document.getElementById('manual-date').value;
    const duration = document.getElementById('manual-duration').value;

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
        document.getElementById('manual-project-select').value = '';
        document.getElementById('manual-customer-select').value = '';
        document.getElementById('manual-desc').value = '';
        document.getElementById('manual-duration').value = '';
        document.getElementById('timer-manual-form').classList.add('d-none');
        refreshTodayEntries();
    });
}

refreshTimerState();
refreshTodayEntries();
setInterval(refreshTimerState, 30000);
setInterval(refreshTodayEntries, 60000);
</script>
@endpush
@endauth
