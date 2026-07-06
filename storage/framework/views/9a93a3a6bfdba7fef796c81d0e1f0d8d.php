<?php $__env->startSection('content'); ?>
<h4 class="fw-bold mb-4" style="color:var(--brand);"><i class="fas fa-credit-card me-2"></i>Payments</h4>
<div class="card shadow-sm" style="border:1px solid #dee2e6;">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>Invoice</th>
                    <th>Customer</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($payment->payment_date->format('d M Y')); ?></td>
                    <td class="fw-medium"><?php echo e($payment->invoice->invoice_number); ?></td>
                    <td><?php echo e($payment->invoice->customer->name); ?></td>
                    <td><?php echo e(format_money($payment->amount, $payment->invoice->currency)); ?></td>
                    <td><?php echo e(str_replace('_', ' ', ucfirst($payment->payment_method))); ?></td>
                    <td>
                        <a href="<?php echo e(route('invoices.show', $payment->invoice)); ?>" class="btn btn-sm btn-brand-outline">Invoice</a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6" class="text-center py-4">No payments recorded.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($payments->hasPages()): ?>
    <div class="card-footer"><?php echo e($payments->links()); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\wamp64\www\invoice-system-server\resources\views/payments/index.blade.php ENDPATH**/ ?>