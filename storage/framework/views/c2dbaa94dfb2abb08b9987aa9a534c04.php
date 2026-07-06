<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold" style="color:var(--brand);"><i class="fas fa-file-invoice me-2"></i>Invoices</h4>
    <a href="<?php echo e(route('invoices.create')); ?>" class="btn btn-brand"><i class="fas fa-plus me-1"></i> New Invoice</a>
</div>
<div class="card shadow-sm" style="border:1px solid #dee2e6;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Invoice #</th>
                        <th>Customer</th>
                        <th>Issue Date</th>
                        <th>Due Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="fw-medium"><?php echo e($invoice->invoice_number); ?></td>
                        <td><?php echo e($invoice->customer->name); ?></td>
                        <td><?php echo e($invoice->issue_date->format('d M Y')); ?></td>
                        <td><?php echo e($invoice->due_date->format('d M Y')); ?></td>
                        <td><?php echo e(format_money($invoice->total_amount, $invoice->currency)); ?></td>
                        <td><span class="badge bg-<?php echo e($invoice->status == 'paid' ? 'success' : ($invoice->status == 'overdue' ? 'danger' : ($invoice->status == 'sent' ? 'warning text-dark' : 'secondary'))); ?>"><?php echo e(ucfirst($invoice->status)); ?></span></td>
                        <td>
                            <a href="<?php echo e(route('invoices.pdf', $invoice)); ?>" class="btn btn-sm btn-danger" title="Download PDF"><i class="fas fa-file-pdf"></i></a>
                            <a href="<?php echo e(route('invoices.show', $invoice)); ?>" class="btn btn-sm btn-brand-outline">View</a>
                            <?php if($invoice->status !== 'paid'): ?>
                            <a href="<?php echo e(route('invoices.edit', $invoice)); ?>" class="btn btn-sm btn-brand-outline">Edit</a>
                            <form method="POST" action="<?php echo e(route('invoices.destroy', $invoice)); ?>" class="d-inline"
                                  onsubmit="return confirm('Delete this invoice permanently?')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">Delete</button>
                            </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="7" class="text-center py-4">No invoices yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if($invoices->hasPages()): ?>
    <div class="card-footer"><?php echo e($invoices->links()); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\wamp64\www\invoice-system-server\resources\views/invoices/index.blade.php ENDPATH**/ ?>