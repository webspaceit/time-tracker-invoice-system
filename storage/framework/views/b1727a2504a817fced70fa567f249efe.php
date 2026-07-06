<script>
function parseDurationToSeconds(str) {
    if (!str || !String(str).trim()) return 0;
    const parts = String(str).trim().split(':').map(p => parseInt(p, 10));
    if (parts.length === 3 && parts.every(p => !isNaN(p))) {
        return (parts[0] * 3600) + (parts[1] * 60) + parts[2];
    }
    if (parts.length === 2 && parts.every(p => !isNaN(p))) {
        return (parts[0] * 3600) + (parts[1] * 60);
    }
    return 0;
}

function formatSecondsToDuration(total) {
    if (total <= 0) return '00:00:00';
    const h = Math.floor(total / 3600);
    const m = Math.floor((total % 3600) / 60);
    const s = total % 60;
    return [h, m, s].map(v => String(v).padStart(2, '0')).join(':');
}

function getHourlyRate() {
    return parseFloat($('#hourly_rate').val()) || 0;
}

// When duration changes on a row, auto-calculate the amount from duration × hourly rate
function recalcRowAmount($row) {
    const hourlyRate = getHourlyRate();
    if (hourlyRate <= 0) return;
    const duration = $row.find('.item-duration').val();
    const seconds = parseDurationToSeconds(duration);
    if (seconds > 0) {
        const amount = (seconds / 3600) * hourlyRate;
        $row.find('.item-amount').val(amount.toFixed(2));
    }
}

function calculateTotalDuration() {
    let total = 0;
    $('.item-duration').each(function() {
        total += parseDurationToSeconds($(this).val());
    });
    $('#total_duration').val(formatSecondsToDuration(total));
}

function calculateTotals() {
    let subtotal = 0;
    $('.item-amount').each(function() {
        subtotal += parseFloat($(this).val()) || 0;
    });
    const taxRate = parseFloat($('input[name="tax_rate"]').val()) || 0;
    const total = subtotal + (subtotal * taxRate / 100);
    const symbols = <?php echo json_encode(\App\Helpers\Currency::SYMBOLS, 15, 512) ?>;
    const code = $('select[name="currency"]').val() || 'USD';
    $('#display-total').text((symbols[code] || code) + total.toFixed(2));
    calculateTotalDuration();
}

// Duration typed → recalc that row's amount, then recalc totals
$(document).on('keyup change', '.item-duration', function() {
    recalcRowAmount($(this).closest('tr'));
    calculateTotals();
});

// Hourly rate changed → recalc all rows that have a duration
$(document).on('keyup change', '#hourly_rate', function() {
    $('.item-row').each(function() {
        recalcRowAmount($(this));
    });
    calculateTotals();
});

$(document).on('keyup change', '.item-amount, input[name="tax_rate"], select[name="currency"]', calculateTotals);

$(document).on('click', '.remove-item', function() {
    if ($('.item-row').length > 1) {
        $(this).closest('.item-row').remove();
        calculateTotals();
    }
});

calculateTotals();
</script>

<?php /**PATH E:\wamp64\www\invoice-system-server\resources\views/invoices/partials/invoice-form-scripts.blade.php ENDPATH**/ ?>