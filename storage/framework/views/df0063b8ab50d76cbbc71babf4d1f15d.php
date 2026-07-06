<?php
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
?>

<div class="invoice-doc<?php echo e(($forPdf ?? false) ? ' invoice-doc-pdf' : ''); ?>" id="invoice-doc-root">
    <table class="header-grid">
        <tr>
            <td width="50%">
                <div class="doc-title">INVOICE</div>
                <div class="issuer-name"><?php echo e($company['name']); ?></div>
                <?php if(!empty($company['address'])): ?>
                    <div class="company-address"><?php echo e($company['address']); ?></div>
                <?php endif; ?>
            </td>
            <td width="50%">
                <table class="meta-table">
                    <tr>
                        <td>
                            <div class="bill-to-label">BILL TO</div>
                            <?php if($isForm): ?>
                                <select name="customer_id" class="form-control-inline" required>
                                    <option value="">Select customer</option>
                                    <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($customer->id); ?>" <?php if(old('customer_id', $invoice->customer_id ?? ($customer->id === 1 ? 1 : null)) == $customer->id): echo 'selected'; endif; ?>>
                                            <?php echo e($customer->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            <?php else: ?>
                                <div class="bill-to-name"><?php echo e($invoice->customer->name); ?></div>
                                <?php if($invoice->customer->company): ?>
                                    <div><?php echo e($invoice->customer->company); ?></div>
                                <?php endif; ?>
                                <?php if($invoice->customer->email): ?>
                                    <div class="customer-email"><?php echo e($invoice->customer->email); ?></div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="inv">
                            <strong>INVOICE #</strong>
                            <?php echo e($invoice->invoice_number ?? '—'); ?>

                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>INVOICE DATE:</strong>
                            <?php if($isForm): ?>
                                <input type="date" name="issue_date" class="form-control-inline d-inline-block" style="width: auto;"
                                       value="<?php echo e(old('issue_date', isset($invoice->issue_date) ? $invoice->issue_date->format('Y-m-d') : date('Y-m-d'))); ?>" required>
                            <?php else: ?>
                                <?php echo e(strtoupper($invoice->issue_date->format('M d, Y'))); ?>

                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php if($isForm): ?>
                    <tr>
                        <td style="padding-top: 6px;">
                            <strong>DUE DATE:</strong>
                            <input type="date" name="due_date" class="form-control-inline d-inline-block" style="width: auto;"
                                   value="<?php echo e(old('due_date', isset($invoice->due_date) ? $invoice->due_date->format('Y-m-d') : date('Y-m-d', strtotime('+7 days')))); ?>" required>
                        </td>
                    </tr>
                    <?php endif; ?>
                </table>
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr style="background:#ccc;">
                <th>
                    <?php if($isForm): ?>
                        <div class="typo-wrapper d-inline-block position-relative">
                            DESCRIPTION
                            <button type="button" class="btn btn-sm btn-outline-secondary typo-trigger ms-1" title="Typography" style="padding:0 4px;font-size:11px;line-height:1.5;"><b>T</b></button>
                            <?php echo $__env->make('invoices.partials._typography-popup', ['field' => 'items', 'typography' => old('typography', $invoice->typography ?? [])], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        </div>
                    <?php else: ?>
                        DESCRIPTION
                    <?php endif; ?>
                </th>
                <th class="amount">AMOUNT<br><span class="duration-hint" style="text-align:center;">(In USD)</span></th>
                <th class="duration">DURATION<br><span class="duration-hint">(H:M:S)</span></th>
                <?php if($isForm): ?><th style="width: 50px;"></th><?php endif; ?>
            </tr>
        </thead>
        <tbody id="items-container">
            <?php if($isForm): ?>
                <?php $formItems = old('items', $invoice->items ?? [['description' => 'Total Hours', 'amount' => '', 'duration' => '']]); ?>
                <?php $__currentLoopData = $formItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="item-row item-input-row">
                    <td>
                        <input type="text" name="items[<?php echo e($index); ?>][description]" class="form-control-inline item-description"
                               value="<?php echo e(is_object($item) ? $item->description : ($item['description'] ?? '')); ?>" required>
                    </td>
                    <td class="amount">
                        <input type="number" step="0.01" name="items[<?php echo e($index); ?>][amount]" class="form-control-inline item-amount text-end"
                               value="<?php echo e(is_object($item) ? $item->total : ($item['amount'] ?? '')); ?>" placeholder="—">
                    </td>
                    <td class="duration">
                        <input type="text" name="items[<?php echo e($index); ?>][duration]" class="form-control-inline item-duration text-center"
                               value="<?php echo e(is_object($item) ? $item->duration : ($item['duration'] ?? '')); ?>" placeholder="00:00:00">
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-outline-danger btn-remove-item remove-item">×</button>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                <?php $itemsStyle = typography_styles($invoice->typography['items'] ?? null); ?>
                <?php $__currentLoopData = $invoice->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td style="<?php echo e($itemsStyle); ?>"><?php echo e($item->description); ?></td>
                    <td class="amount">
                        <?php if($item->total > 0): ?>
                            <?php echo e(format_money($item->total, $invoice->currency)); ?>

                        <?php else: ?>
                            —
                        <?php endif; ?>
                    </td>
                    <td class="duration"><?php echo e($item->duration ?? '—'); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <tr class="total-row">
                    <td><?php echo e($currencyLabel); ?></td>
                    <td class="amount"><?php echo e(format_money($invoice->total_amount, $invoice->currency)); ?></td>
                    <td class="duration">
                        <?php echo e($invoice->display_total_duration !== '00:00:00' ? $invoice->display_total_duration : ''); ?>

                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
        <?php if($isForm): ?>
        <tfoot>
            <tr class="total-row">
                <td><?php echo e($currencyLabel); ?></td>
                <td class="amount"><span id="display-total"><?php echo e(currency_symbol(old('currency', $invoice->currency ?? 'USD'))); ?>0.00</span></td>
                <td class="duration">
                    <input type="text" name="total_duration" id="total_duration" class="form-control-inline text-center bg-light"
                           value="<?php echo e(old('total_duration', $invoice->total_duration ?? '')); ?>" placeholder="00:00:00"
                           readonly tabindex="-1" title="Auto-calculated from line item durations">
                </td>
                <td></td>
            </tr>
        </tfoot>
        <?php endif; ?>
    </table>

    <?php if($isForm): ?>
        <button type="button" class="btn btn-sm btn-secondary mb-3" id="add-item"><i class="fas fa-plus"></i> Add line</button>
        <div class="row mb-3" style="max-width: 600px;">
            <div class="col-4">
                <label class="label">Hourly Rate ($)</label>
                <input type="number" step="0.01" name="hourly_rate" id="hourly_rate" class="form-control-inline"
                       value="<?php echo e(old('hourly_rate', $invoice->hourly_rate ?? 20)); ?>"
                       placeholder="20.00">
            </div>
            <div class="col-4">
                <label class="label">Tax Rate (%)</label>
                <input type="number" step="0.01" name="tax_rate" class="form-control-inline" value="<?php echo e(old('tax_rate', $invoice->tax_rate ?? 0)); ?>">
            </div>
            <div class="col-4">
                <label class="label">Currency</label>
                <select name="currency" class="form-control-inline">
                    <?php $__currentLoopData = \App\Helpers\Currency::OPTIONS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($code); ?>" <?php if(old('currency', $invoice->currency ?? 'USD') === $code): echo 'selected'; endif; ?>><?php echo e(\App\Helpers\Currency::label($code)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>
    <?php endif; ?>

    <?php if($isForm || !empty($invoice->project_title) || !empty($invoice->work_details)): ?>
    <div class="work-details">
        <?php if($isForm): ?>
            <div class="work-details-title">Project / work details</div>
            <div class="typo-wrapper position-relative mb-2">
                <button type="button" class="btn btn-sm btn-outline-secondary typo-trigger float-end" title="Typography" style="padding:0 6px;font-size:13px;line-height:1.6;"><b>T</b></button>
                <input type="text" name="project_title" class="form-control-inline"
                       value="<?php echo e(old('project_title', (isset($invoice) && $invoice->exists) ? $invoice->project_title : 'WILDERNESS-EXPLORERS.COM WEBSITE UPDATE PARTICULARS')); ?>"
                       placeholder="WILDERNESS-EXPLORERS.COM WEBSITE UPDATE PARTICULARS" style="width:calc(100% - 32px);">
                <?php echo $__env->make('invoices.partials._typography-popup', ['field' => 'project_title', 'typography' => old('typography', $invoice->typography ?? [])], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
            <div class="typo-wrapper position-relative mb-2">
                <button type="button" class="btn btn-sm btn-outline-secondary typo-trigger float-end" title="Typography" style="padding:0 6px;font-size:13px;line-height:1.6;"><b>T</b></button>
                <textarea name="work_details" class="form-control-inline" rows="6"
                          placeholder="logo fixing&#10;tour page load fixing&#10;..." style="width:calc(100% - 32px);"><?php echo e(old('work_details', $invoice->work_details ?? '')); ?></textarea>
                <?php echo $__env->make('invoices.partials._typography-popup', ['field' => 'work_details', 'typography' => old('typography', $invoice->typography ?? [])], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        <?php else: ?>
            <?php if($invoice->work_details): ?>
                <?php
                    $workStyle = typography_styles($invoice->typography['work_details'] ?? null);
                    $titleStyle = typography_styles($invoice->typography['project_title'] ?? null);
                    $workLines = array_values(array_filter(array_map('trim', explode("\n", trim($invoice->work_details)))));
                    $half = (int) ceil(count($workLines) / 2);
                    $col1 = array_slice($workLines, 0, $half);
                    $col2 = array_slice($workLines, $half);
                ?>
                <table class="work-details-table">
                    <thead>
                        <tr>
                            <th class="wd-title" colspan="5" style="<?php echo e($titleStyle); ?>"><?php echo e($invoice->project_title); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $col1; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="<?php echo e($i % 2 === 0 ? 'wd-even' : 'wd-odd'); ?>">
                            <td style="width:5%" class="wd-num"><?php echo e($i + 1); ?></td>
                            <td style="width:45%"><?php echo e($line); ?></td>
                            <td class="wd-sep"></td>
                            <td style="width:5%" class="wd-num"><?php echo e(isset($col2[$i]) ? $i + $half + 1 : ''); ?></td>
                            <td style="width:45%"><?php echo e($col2[$i] ?? ''); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php
        $bankInfoStyle = typography_styles($invoice->typography['bank_info'] ?? null);
    ?>
    <div class="bank-block">
        <?php if($isForm): ?>
            <div class="typo-wrapper position-relative mb-2">
                <span class="bank-block-title d-inline-block">Bank Information</span>
                <button type="button" class="btn btn-sm btn-outline-secondary typo-trigger float-end" title="Typography" style="padding:0 6px;font-size:13px;line-height:1.6;"><b>T</b></button>
                <?php echo $__env->make('invoices.partials._typography-popup', ['field' => 'bank_info', 'typography' => old('typography', $invoice->typography ?? [])], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        <?php else: ?>
            <div class="bank-block-title" style="<?php echo e($bankInfoStyle); ?>">Bank Information</div>
        <?php endif; ?>
        <table class="bank-table" cellpadding="0" cellspacing="0" style="<?php echo e($bankInfoStyle); ?>">
            <tr>
                <td class="bank-label" style="<?php echo e($bankInfoStyle); ?>">Account Number</td>
                <td class="bank-value" style="<?php echo e($bankInfoStyle); ?>"><?php echo e($bank['account_number']); ?></td>
            </tr>
            <tr>
                <td class="bank-label" style="<?php echo e($bankInfoStyle); ?>">Account Name</td>
                <td class="bank-value" style="<?php echo e($bankInfoStyle); ?>"><?php echo e($bank['account_name']); ?></td>
            </tr>
            <tr>
                <td class="bank-label" style="<?php echo e($bankInfoStyle); ?>">Branch</td>
                <td class="bank-value" style="<?php echo e($bankInfoStyle); ?>"><?php echo e($bank['branch']); ?></td>
            </tr>
            <tr>
                <td class="bank-label" style="<?php echo e($bankInfoStyle); ?>">Bank Name</td>
                <td class="bank-value" style="<?php echo e($bankInfoStyle); ?>"><?php echo e($bank['bank_name']); ?></td>
            </tr>
            <tr>
                <td class="bank-label" style="<?php echo e($bankInfoStyle); ?>">SWIFT Code</td>
                <td class="bank-value" style="<?php echo e($bankInfoStyle); ?>"><?php echo e($bank['swift_code']); ?></td>
            </tr>
            <tr>
                <td class="bank-label" style="<?php echo e($bankInfoStyle); ?>">Home Address</td>
                <td class="bank-value" style="<?php echo e($bankInfoStyle); ?>"><?php echo e($company['address']); ?></td>
            </tr>
        </table>
    </div>

    
    <?php if($signatureExists): ?>
    <div class="signature-block">
        <img src="<?php echo e($signatureSrc); ?>" alt="Signature" class="invoice-signature">
    </div>
    <?php endif; ?>
    <hr class="invoice-divider">
    <div class="thank-you">Thank You</div>
    <?php
        $termsStyle = typography_styles($invoice->typography['terms'] ?? null);
        $termLines = $invoice->terms ? array_filter(array_map('trim', explode("\n", $invoice->terms))) : $terms;
    ?>
    <div class="terms-section" style="<?php echo e($termsStyle); ?>">
        <div class="terms-title" style="<?php echo e($termsStyle); ?>">Terms &amp; Conditions</div>
        <?php if($isForm): ?>
            <div class="typo-wrapper position-relative mb-2">
                <button type="button" class="btn btn-sm btn-outline-secondary typo-trigger float-end" title="Typography" style="padding:0 6px;font-size:13px;line-height:1.6;"><b>T</b></button>
                <textarea name="terms" class="form-control-inline" rows="4"
                          placeholder="Enter terms and conditions..." style="width:calc(100% - 32px);"><?php echo e(old('terms', (isset($invoice) && $invoice->exists) ? $invoice->terms : implode("\n", $terms))); ?></textarea>
                <?php echo $__env->make('invoices.partials._typography-popup', ['field' => 'terms', 'typography' => old('typography', $invoice->typography ?? [])], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        <?php else: ?>
            <ul class="terms-list" style="<?php echo e($termsStyle); ?>">
                <?php $__currentLoopData = $termLines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $term): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li style="<?php echo e($termsStyle); ?>"><?php echo e($term); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        <?php endif; ?>
    </div>

</div>
<?php /**PATH E:\wamp64\www\invoice-system-server\resources\views/invoices/partials/document.blade.php ENDPATH**/ ?>