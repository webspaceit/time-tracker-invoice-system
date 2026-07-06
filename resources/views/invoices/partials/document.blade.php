@php
    $company = config('invoice.company');
    $bank = config('invoice.bank');
    $terms = config('invoice.terms');
    $currencyLabel = 'TOTAL IN ' . strtoupper($invoice->currency ?? 'USD');
    $isForm = $editable ?? false;
    $signaturePath = public_path(config('invoice.signature', 'images/signature.jpg'));
    $signatureExists = file_exists($signaturePath);
    if ($signatureExists) {
        $signatureSrc = ($forPdf ?? false)
            ? 'data:image/jpg;base64,'.base64_encode(file_get_contents($signaturePath))
            : asset(config('invoice.signature', 'images/signature.jpg'));
    }
@endphp

<div class="invoice-doc{{ ($forPdf ?? false) ? ' invoice-doc-pdf' : '' }}" id="invoice-doc-root">
    <table class="header-grid">
        <tr>
            <td width="50%">
                <div class="doc-title">INVOICE</div>
                <div class="issuer-name">{{ $company['name'] }}</div>
                @if(!empty($company['address']))
                    <div class="company-address">{{ $company['address'] }}</div>
                @endif
            </td>
            <td width="50%">
                <table class="meta-table">
                    <tr>
                        <td>
                            <div class="bill-to-label">BILL TO</div>
                            @if($isForm)
                                <select name="customer_id" class="form-control-inline" required>
                                    <option value="">Select customer</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" @selected(old('customer_id', $invoice->customer_id ?? ($customer->id === 1 ? 1 : null)) == $customer->id)>
                                            {{ $customer->name }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <div class="bill-to-name">{{ $invoice->customer->name }}</div>
                                @if($invoice->customer->company)
                                    <div>{{ $invoice->customer->company }}</div>
                                @endif
                                @if($invoice->customer->email)
                                    <div class="customer-email">{{ $invoice->customer->email }}</div>
                                @endif
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="inv">
                            <strong>INVOICE #</strong>
                            {{ $invoice->invoice_number ?? '—' }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>INVOICE DATE:</strong>
                            @if($isForm)
                                <input type="date" name="issue_date" class="form-control-inline d-inline-block" style="width: auto;"
                                       value="{{ old('issue_date', isset($invoice->issue_date) ? $invoice->issue_date->format('Y-m-d') : date('Y-m-d')) }}" required>
                            @else
                                {{ strtoupper($invoice->issue_date->format('M d, Y')) }}
                            @endif
                        </td>
                    </tr>
                    @if($isForm)
                    <tr>
                        <td style="padding-top: 6px;">
                            <strong>DUE DATE:</strong>
                            <input type="date" name="due_date" class="form-control-inline d-inline-block" style="width: auto;"
                                   value="{{ old('due_date', isset($invoice->due_date) ? $invoice->due_date->format('Y-m-d') : date('Y-m-d', strtotime('+7 days'))) }}" required>
                        </td>
                    </tr>
                    @endif
                </table>
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr style="background:#ccc;">
                <th>
                    @if($isForm)
                        <div class="typo-wrapper d-inline-block position-relative">
                            DESCRIPTION
                            <button type="button" class="btn btn-sm btn-outline-secondary typo-trigger ms-1" title="Typography" style="padding:0 4px;font-size:11px;line-height:1.5;"><b>T</b></button>
                            @include('invoices.partials._typography-popup', ['field' => 'items', 'typography' => old('typography', $invoice->typography ?? [])])
                        </div>
                    @else
                        DESCRIPTION
                    @endif
                </th>
                <th class="amount">AMOUNT<br><span class="duration-hint" style="text-align:center;">(In USD)</span></th>
                <th class="duration">DURATION<br><span class="duration-hint">(H:M:S)</span></th>
                @if($isForm)<th style="width: 50px;"></th>@endif
            </tr>
        </thead>
        <tbody id="items-container">
            @if($isForm)
                @php $formItems = old('items', $invoice->items ?? [['description' => 'Total Hours', 'amount' => '', 'duration' => '']]); @endphp
                @foreach($formItems as $index => $item)
                <tr class="item-row item-input-row">
                    <td>
                        <input type="text" name="items[{{ $index }}][description]" class="form-control-inline item-description"
                               value="{{ is_object($item) ? $item->description : ($item['description'] ?? '') }}" required>
                    </td>
                    <td class="amount">
                        <input type="number" step="0.01" name="items[{{ $index }}][amount]" class="form-control-inline item-amount text-end"
                               value="{{ is_object($item) ? $item->total : ($item['amount'] ?? '') }}" placeholder="—">
                    </td>
                    <td class="duration">
                        <input type="text" name="items[{{ $index }}][duration]" class="form-control-inline item-duration text-center"
                               value="{{ is_object($item) ? $item->duration : ($item['duration'] ?? '') }}" placeholder="00:00:00">
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-outline-danger btn-remove-item remove-item">×</button>
                    </td>
                </tr>
                @endforeach
            @else
                @php $itemsStyle = typography_styles($invoice->typography['items'] ?? null); @endphp
                @foreach($invoice->items as $item)
                <tr>
                    <td style="{{ $itemsStyle }}">{{ $item->description }}</td>
                    <td class="amount">
                        @if($item->total > 0)
                            {{ format_money($item->total, $invoice->currency) }}
                        @else
                            —
                        @endif
                    </td>
                    <td class="duration">{{ $item->duration ?? '—' }}</td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td>{{ $currencyLabel }}</td>
                    <td class="amount">{{ format_money($invoice->total_amount, $invoice->currency) }}</td>
                    <td class="duration">
                        {{ $invoice->display_total_duration !== '00:00:00' ? $invoice->display_total_duration : '' }}
                    </td>
                </tr>
            @endif
        </tbody>
        @if($isForm)
        <tfoot>
            <tr class="total-row">
                <td>{{ $currencyLabel }}</td>
                <td class="amount"><span id="display-total">{{ currency_symbol(old('currency', $invoice->currency ?? 'USD')) }}0.00</span></td>
                <td class="duration">
                    <input type="text" name="total_duration" id="total_duration" class="form-control-inline text-center bg-light"
                           value="{{ old('total_duration', $invoice->total_duration ?? '') }}" placeholder="00:00:00"
                           readonly tabindex="-1" title="Auto-calculated from line item durations">
                </td>
                <td></td>
            </tr>
        </tfoot>
        @endif
    </table>

    @if($isForm)
        <button type="button" class="btn btn-sm btn-secondary mb-3" id="add-item"><i class="fas fa-plus"></i> Add line</button>
        <div class="row mb-3" style="max-width: 600px;">
            <div class="col-4">
                <label class="label">Hourly Rate ($)</label>
                <input type="number" step="0.01" name="hourly_rate" id="hourly_rate" class="form-control-inline"
                       value="{{ old('hourly_rate', $invoice->hourly_rate ?? 20) }}"
                       placeholder="20.00">
            </div>
            <div class="col-4">
                <label class="label">Tax Rate (%)</label>
                <input type="number" step="0.01" name="tax_rate" class="form-control-inline" value="{{ old('tax_rate', $invoice->tax_rate ?? 0) }}">
            </div>
            <div class="col-4">
                <label class="label">Currency</label>
                <select name="currency" class="form-control-inline">
                    @foreach(\App\Helpers\Currency::OPTIONS as $code)
                    <option value="{{ $code }}" @selected(old('currency', $invoice->currency ?? 'USD') === $code)>{{ \App\Helpers\Currency::label($code) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    @endif

    @if($isForm || !empty($invoice->project_title) || !empty($invoice->work_details))
    <div class="work-details">
        @if($isForm)
            <div class="work-details-title">Project / work details</div>
            <div class="typo-wrapper position-relative mb-2">
                <button type="button" class="btn btn-sm btn-outline-secondary typo-trigger float-end" title="Typography" style="padding:0 6px;font-size:13px;line-height:1.6;"><b>T</b></button>
                <input type="text" name="project_title" class="form-control-inline"
                       value="{{ old('project_title', (isset($invoice) && $invoice->exists) ? $invoice->project_title : 'WILDERNESS-EXPLORERS.COM WEBSITE UPDATE PARTICULARS') }}"
                       placeholder="WILDERNESS-EXPLORERS.COM WEBSITE UPDATE PARTICULARS" style="width:calc(100% - 32px);">
                @include('invoices.partials._typography-popup', ['field' => 'project_title', 'typography' => old('typography', $invoice->typography ?? [])])
            </div>
            <div class="typo-wrapper position-relative mb-2">
                <button type="button" class="btn btn-sm btn-outline-secondary typo-trigger float-end" title="Typography" style="padding:0 6px;font-size:13px;line-height:1.6;"><b>T</b></button>
                <textarea name="work_details" class="form-control-inline" rows="6"
                          placeholder="logo fixing&#10;tour page load fixing&#10;..." style="width:calc(100% - 32px);">{{ old('work_details', $invoice->work_details ?? '') }}</textarea>
                @include('invoices.partials._typography-popup', ['field' => 'work_details', 'typography' => old('typography', $invoice->typography ?? [])])
            </div>
        @else
            @if($invoice->work_details)
                @php
                    $workStyle = typography_styles($invoice->typography['work_details'] ?? null);
                    $titleStyle = typography_styles($invoice->typography['project_title'] ?? null);
                    $workLines = array_values(array_filter(array_map('trim', explode("\n", trim($invoice->work_details)))));
                    $half = (int) ceil(count($workLines) / 2);
                    $col1 = array_slice($workLines, 0, $half);
                    $col2 = array_slice($workLines, $half);
                @endphp
                <table class="work-details-table">
                    <thead>
                        <tr>
                            <th class="wd-title" colspan="5" style="{{ $titleStyle }}">{{ $invoice->project_title }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($col1 as $i => $line)
                        <tr class="{{ $i % 2 === 0 ? 'wd-even' : 'wd-odd' }}">
                            <td style="width:5%" class="wd-num">{{ $i + 1 }}</td>
                            <td style="width:45%">{{ $line }}</td>
                            <td class="wd-sep"></td>
                            <td style="width:5%" class="wd-num">{{ isset($col2[$i]) ? $i + $half + 1 : '' }}</td>
                            <td style="width:45%">{{ $col2[$i] ?? '' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        @endif
    </div>
    @endif

    @php
        $bankInfoStyle = typography_styles($invoice->typography['bank_info'] ?? null);
    @endphp
    <div class="bank-block">
        @if($isForm)
            <div class="typo-wrapper position-relative mb-2">
                <span class="bank-block-title d-inline-block">Bank Information</span>
                <button type="button" class="btn btn-sm btn-outline-secondary typo-trigger float-end" title="Typography" style="padding:0 6px;font-size:13px;line-height:1.6;"><b>T</b></button>
                @include('invoices.partials._typography-popup', ['field' => 'bank_info', 'typography' => old('typography', $invoice->typography ?? [])])
            </div>
        @else
            <div class="bank-block-title" style="{{ $bankInfoStyle }}">Bank Information</div>
        @endif
        <table class="bank-table" cellpadding="0" cellspacing="0" style="{{ $bankInfoStyle }}">
            <tr>
                <td class="bank-label" style="{{ $bankInfoStyle }}">Account Number</td>
                <td class="bank-value" style="{{ $bankInfoStyle }}">{{ $bank['account_number'] }}</td>
            </tr>
            <tr>
                <td class="bank-label" style="{{ $bankInfoStyle }}">Account Name</td>
                <td class="bank-value" style="{{ $bankInfoStyle }}">{{ $bank['account_name'] }}</td>
            </tr>
            <tr>
                <td class="bank-label" style="{{ $bankInfoStyle }}">Branch</td>
                <td class="bank-value" style="{{ $bankInfoStyle }}">{{ $bank['branch'] }}</td>
            </tr>
            <tr>
                <td class="bank-label" style="{{ $bankInfoStyle }}">Bank Name</td>
                <td class="bank-value" style="{{ $bankInfoStyle }}">{{ $bank['bank_name'] }}</td>
            </tr>
            <tr>
                <td class="bank-label" style="{{ $bankInfoStyle }}">SWIFT Code</td>
                <td class="bank-value" style="{{ $bankInfoStyle }}">{{ $bank['swift_code'] }}</td>
            </tr>
            <tr>
                <td class="bank-label" style="{{ $bankInfoStyle }}">Home Address</td>
                <td class="bank-value" style="{{ $bankInfoStyle }}">{{ $company['address'] }}</td>
            </tr>
        </table>
    </div>

    {{-- Signature + Thank You + Terms moved below Bank Information --}}
    @if($signatureExists)
    <div class="signature-block">
        <img src="{{ $signatureSrc }}" alt="Signature" class="invoice-signature">
    </div>
    @endif
    <hr class="invoice-divider">
    <div class="thank-you">Thank You</div>
    @php
        $termsStyle = typography_styles($invoice->typography['terms'] ?? null);
        $termLines = $invoice->terms ? array_filter(array_map('trim', explode("\n", $invoice->terms))) : $terms;
    @endphp
    <div class="terms-section" style="{{ $termsStyle }}">
        <div class="terms-title" style="{{ $termsStyle }}">Terms &amp; Conditions</div>
        @if($isForm)
            <div class="typo-wrapper position-relative mb-2">
                <button type="button" class="btn btn-sm btn-outline-secondary typo-trigger float-end" title="Typography" style="padding:0 6px;font-size:13px;line-height:1.6;"><b>T</b></button>
                <textarea name="terms" class="form-control-inline" rows="4"
                          placeholder="Enter terms and conditions..." style="width:calc(100% - 32px);">{{ old('terms', (isset($invoice) && $invoice->exists) ? $invoice->terms : implode("\n", $terms)) }}</textarea>
                @include('invoices.partials._typography-popup', ['field' => 'terms', 'typography' => old('typography', $invoice->typography ?? [])])
            </div>
        @else
            <ul class="terms-list" style="{{ $termsStyle }}">
                @foreach($termLines as $term)
                    <li style="{{ $termsStyle }}">{{ $term }}</li>
                @endforeach
            </ul>
        @endif
    </div>

</div>
