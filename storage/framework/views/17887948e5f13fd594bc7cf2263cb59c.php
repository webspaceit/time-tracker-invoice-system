<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h4 class="mb-0 fw-bold" style="color:var(--brand);"><i class="fas fa-file-invoice me-2"></i>Invoice <?php echo e($invoice->invoice_number); ?></h4>
    <div class="d-flex flex-wrap gap-2">
        <a href="<?php echo e(route('invoices.pdf', $invoice)); ?>" class="btn btn-danger">
            <i class="fas fa-file-pdf me-1"></i> Download PDF
        </a>
        <?php if($invoice->status !== 'paid'): ?>
            <a href="<?php echo e(route('invoices.edit', $invoice)); ?>" class="btn btn-brand">Edit</a>
            <form method="POST" action="<?php echo e(route('invoices.destroy', $invoice)); ?>" class="d-inline"
                  onsubmit="return confirm('Delete this invoice permanently?')">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <button type="submit" class="btn btn-outline-danger">Delete</button>
            </form>
            <?php if($invoice->balance_due > 0): ?>
                <a href="<?php echo e(route('payments.create', $invoice)); ?>" class="btn btn-brand">Record Payment</a>
            <?php endif; ?>
        <?php endif; ?>
        <a href="<?php echo e(route('invoices.index')); ?>" class="btn btn-outline-secondary">Back</a>
    </div>
</div>

<?php if($invoice->status !== 'paid'): ?>
<div class="mb-3">
    <span class="badge bg-<?php echo e($invoice->status == 'paid' ? 'success' : ($invoice->status == 'overdue' ? 'danger' : ($invoice->status == 'sent' ? 'warning text-dark' : 'secondary'))); ?> me-1"><?php echo e(ucfirst($invoice->status)); ?></span>
    <?php $__currentLoopData = ['draft', 'sent', 'paid', 'cancelled']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if($status !== $invoice->status): ?>
        <form method="POST" action="<?php echo e(route('invoices.update-status', [$invoice, $status])); ?>" class="d-inline">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PATCH'); ?>
            <button type="submit" class="btn btn-sm btn-brand-outline"><?php echo e(ucfirst($status)); ?></button>
        </form>
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php endif; ?>

<div class="card shadow-sm mb-4" style="border:1px solid #dee2e6;">
    <div class="card-body p-4">
        <?php echo $__env->make('invoices.partials.document-styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('invoices.partials.document', ['editable' => false], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
</div>

<?php if($invoice->payments->count()): ?>
<div class="card shadow-sm" style="border:1px solid #dee2e6;">
    <div class="card-header brand-header"><h5 class="mb-0"><i class="fas fa-credit-card me-2"></i>Payments</h5></div>
    <table class="table mb-0">
        <thead class="table-light">
            <tr><th>Date</th><th>Amount</th><th>Method</th><th></th></tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $invoice->payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($payment->payment_date->format('d M Y')); ?></td>
                <td><?php echo e(format_money($payment->amount, $invoice->currency)); ?></td>
                <td><?php echo e(str_replace('_', ' ', ucfirst($payment->payment_method))); ?></td>
                <td>
                    <form method="POST" action="<?php echo e(route('payments.destroy', $payment)); ?>" class="d-inline" onsubmit="return confirm('Delete this payment?')">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\wamp64\www\invoice-system-server\resources\views/invoices/show.blade.php ENDPATH**/ ?>