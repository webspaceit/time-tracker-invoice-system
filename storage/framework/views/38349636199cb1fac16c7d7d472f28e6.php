<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold" style="color:var(--brand);"><i class="fas fa-users me-2"></i>Customers</h4>
    <a href="<?php echo e(route('customers.create')); ?>" class="btn btn-brand"><i class="fas fa-plus me-1"></i> New Customer</a>
</div>
<div class="card shadow-sm" style="border:1px solid #dee2e6;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Company</th>
                        <th>Invoices</th>
                        <th>Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="fw-medium"><?php echo e($customer->name); ?></td>
                        <td><?php echo e($customer->email); ?></td>
                        <td><?php echo e($customer->company ?? '—'); ?></td>
                        <td><?php echo e($customer->invoices_count); ?></td>
                        <td><?php echo e(format_money($customer->invoices_sum_total_amount ?? 0)); ?></td>
                        <td>
                            <a href="<?php echo e(route('customers.show', $customer)); ?>" class="btn btn-sm btn-brand-outline">View</a>
                            <a href="<?php echo e(route('customers.edit', $customer)); ?>" class="btn btn-sm btn-brand-outline">Edit</a>
                            <?php if($customer->invoices_count === 0): ?>
                            <form method="POST" action="<?php echo e(route('customers.destroy', $customer)); ?>" class="d-inline"
                                  onsubmit="return confirm('Delete this customer permanently?')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">Delete</button>
                            </form>
                            <?php else: ?>
                            <button type="button" class="btn btn-sm btn-outline-secondary" disabled
                                    title="Cannot delete — customer has invoices">Delete</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="6" class="text-center py-4">No customers yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if($customers->hasPages()): ?>
    <div class="card-footer"><?php echo e($customers->links()); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\wamp64\www\invoice-system-server\resources\views/customers/index.blade.php ENDPATH**/ ?>