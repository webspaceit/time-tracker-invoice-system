@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold" style="color:var(--brand);"><i class="fas fa-plus-circle me-2"></i>Create New Invoice</h4>
    <a href="{{ route('invoices.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>Back</a>
</div>

@if($errors->any())
    <div class="alert alert-danger" style="border-left:4px solid var(--brand);">
        @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<div class="card shadow-sm mb-3" style="border:1px solid #dee2e6;">
    <div class="card-body p-3">
        <h6 class="mb-2" style="color:var(--brand);"><i class="fas fa-clock me-1"></i>Import from Time Tracker</h6>
        <div class="row g-2 align-items-center">
            <div class="col-auto">
                <input type="date" id="tt-from" class="form-control form-control-sm" value="{{ date('Y-m-01') }}">
            </div>
            <div class="col-auto">
                <span class="mx-1 text-muted">to</span>
            </div>
            <div class="col-auto">
                <input type="date" id="tt-to" class="form-control form-control-sm" value="{{ date('Y-m-d') }}">
            </div>
            <div class="col-auto">
                <button type="button" class="btn btn-sm btn-brand-outline" id="tt-import-btn">
                    <i class="fas fa-download me-1"></i>Import
                </button>
            </div>
            <div class="col-auto" id="tt-result" style="display:none;">
                <small class="text-muted">
                    Duration: <strong class="text-brand" id="tt-duration"></strong> —
                    Earn: <strong class="text-brand" id="tt-earn"></strong>
                    (<span id="tt-count"></span> entries)
                </small>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm mb-4" style="border:1px solid #dee2e6;">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('invoices.store') }}" id="invoiceForm">
            @csrf
            @php $invoice = new \App\Models\Invoice(['currency' => old('currency', 'USD')]); @endphp
            @include('invoices.partials.document-styles')
            @include('invoices.partials.document', ['editable' => true, 'invoice' => $invoice])

            <div class="mt-4 pt-3 border-top text-end">
                <button type="submit" class="btn btn-brand btn-lg px-5"><i class="fas fa-save me-2"></i>Save Invoice</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
let itemCount = {{ max(1, count(old('items', [['description' => 'Total Hours']]))) }};

$('#add-item').on('click', function() {
    $('#items-container').append(`
        <tr class="item-row item-input-row">
            <td><input type="text" name="items[${itemCount}][description]" class="form-control-inline item-description" required></td>
            <td class="amount"><input type="number" step="0.01" name="items[${itemCount}][amount]" class="form-control-inline item-amount text-end" placeholder="—"></td>
            <td class="duration"><input type="text" name="items[${itemCount}][duration]" class="form-control-inline item-duration text-center" placeholder="00:00:00"></td>
            <td><button type="button" class="btn btn-sm btn-outline-danger btn-remove-item remove-item">×</button></td>
        </tr>
    `);
    itemCount++;
});

$(document).on('click', '.typo-trigger', function(e) {
    e.preventDefault();
    var $btn = $(this);
    var $popup = $btn.closest('.typo-wrapper').find('.typography-popup');
    $('.typography-popup').not($popup).hide();
    $popup.toggle();
});
$(document).on('click', '.typography-backdrop', function() {
    $(this).closest('.typography-popup').hide();
});
$(document).on('input change', '.typo-input', function() {
    var $this = $(this);
    var name = $this.attr('name');
    if ($this.attr('type') === 'range') {
        var $textInput = $this.closest('.typography-panel').find('input[type="text"][name="' + name + '"]');
        if ($textInput.length) $textInput.val($this.val());
    }
});

$('#tt-import-btn').on('click', function() {
    const from = $('#tt-from').val();
    const to = $('#tt-to').val();
    if (!from || !to) { alert('Please select both dates.'); return; }

    $('#tt-import-btn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Loading...');

    fetch(`{{ route('time-tracker.summary') }}?from=${from}&to=${to}`)
        .then(r => r.json())
        .then(data => {
            if (data.error) { alert(data.error); return; }

            $('#tt-duration').text(data.totalDurationFormatted);
            $('#tt-earn').text('$' + data.totalEarn.toFixed(2));
            $('#tt-count').text(data.entryCount);
            $('#tt-result').show();

            if (data.entryCount === 0) return;

            const $firstRow = $('.item-row').first();
            const months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
            const now = new Date();
            const monthYear = months[now.getMonth()] + ' ' + now.getFullYear();
            $firstRow.find('.item-description').val('Website maintenance For the Month of ' + monthYear);
            $firstRow.find('.item-duration').val(data.totalDurationFormatted).trigger('change');
            $firstRow.find('.item-amount').val(data.totalEarn.toFixed(2));

            let detailLines = data.entries.map(e => e.label + ' (' + e.duration + ')');
            $('textarea[name="work_details"]').val(detailLines.join('\n'));

            calculateTotals();
        })
        .catch(err => { alert('Failed to fetch time tracker data.'); })
        .finally(() => {
            $('#tt-import-btn').prop('disabled', false).html('<i class="fas fa-download me-1"></i>Import');
        });
});
</script>
@include('invoices.partials.invoice-form-scripts')
@endpush
