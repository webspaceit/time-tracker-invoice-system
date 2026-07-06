<script>
const currencySymbols = @json(\App\Helpers\Currency::SYMBOLS);
function updateCurrencySymbol() {
    const code = document.querySelector('select[name="currency"]')?.value || 'USD';
    const symbol = currencySymbols[code] || code;
    document.querySelectorAll('.currency-symbol').forEach(el => el.textContent = symbol);
}
document.querySelector('select[name="currency"]')?.addEventListener('change', updateCurrencySymbol);
updateCurrencySymbol();
</script>
