

<?php $__env->startSection('content'); ?>
    <!-- Settings -->
    <div class="card mb-4">
        <div class="card-header brand-header">
            <h5 class="mb-0"><i class="fas fa-cog me-2"></i>Settings</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="<?php echo e(route('settings.profile')); ?>" class="row g-3">
                <?php echo csrf_field(); ?>
                <div class="col-md-4">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" name="name" id="name" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(Auth::user()->name); ?>">
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-4">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(Auth::user()->email); ?>">
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-4">
                    <label for="password" class="form-label">New Password <small class="text-muted">(leave blank to keep current)</small></label>
                    <input type="password" name="password" id="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-md-4">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control <?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-brand">Update Profile</button>
                </div>
            </form>
            <hr>
            <form method="POST" action="<?php echo e(route('settings.timezone')); ?>" class="row g-3 align-items-center">
                <?php echo csrf_field(); ?>
                <div class="col-auto">
                    <label for="timezone" class="col-form-label"><i class="fas fa-globe me-1"></i> Timezone</label>
                </div>
                <div class="col-auto">
                    <select name="timezone" id="timezone" class="form-select" onchange="this.form.submit()">
                        <?php $__currentLoopData = DateTimeZone::listIdentifiers(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tz): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($tz); ?>" <?php echo e(Auth::user()->timezone === $tz ? 'selected' : ''); ?>><?php echo e($tz); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-auto">
                    <small class="text-muted">Current time: <?php echo e(in_user_timezone(now())->format('Y-m-d H:i:s T')); ?></small>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card text-white stats-card" style="background: linear-gradient(135deg, #1D560B 0%, #2a7e10 100%);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5 class="card-title" style="font-size:0.85rem;text-transform:uppercase;letter-spacing:0.5px;opacity:0.9;">Total Revenue</h5>
                            <h2 class="mb-0 fw-bold"><?php echo e(format_money($totalRevenue)); ?></h2>
                        </div>
                        <i class="fas fa-dollar-sign fa-2x" style="opacity:0.3;"></i>
                    </div>
                    <p class="mb-0 mt-2 small" style="opacity:0.8;">All time</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card text-white stats-card" style="background: linear-gradient(135deg, #1D560B 0%, #3a9e1a 100%);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5 class="card-title" style="font-size:0.85rem;text-transform:uppercase;letter-spacing:0.5px;opacity:0.9;">Pending Amount</h5>
                            <h2 class="mb-0 fw-bold"><?php echo e(format_money($pendingAmount)); ?></h2>
                        </div>
                        <i class="fas fa-clock fa-2x" style="opacity:0.3;"></i>
                    </div>
                    <p class="mb-0 mt-2 small" style="opacity:0.8;">Unpaid invoices</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card text-white stats-card" style="background: linear-gradient(135deg, #2a7e10 0%, #1D560B 100%);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5 class="card-title" style="font-size:0.85rem;text-transform:uppercase;letter-spacing:0.5px;opacity:0.9;">Total Invoices</h5>
                            <h2 class="mb-0 fw-bold"><?php echo e($totalInvoices); ?></h2>
                        </div>
                        <i class="fas fa-file-invoice fa-2x" style="opacity:0.3;"></i>
                    </div>
                    <p class="mb-0 mt-2 small" style="opacity:0.8;">All invoices</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card text-white stats-card" style="background: linear-gradient(135deg, #3a9e1a 0%, #1D560B 100%);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5 class="card-title" style="font-size:0.85rem;text-transform:uppercase;letter-spacing:0.5px;opacity:0.9;">Customers</h5>
                            <h2 class="mb-0 fw-bold"><?php echo e($totalCustomers); ?></h2>
                        </div>
                        <i class="fas fa-users fa-2x" style="opacity:0.3;"></i>
                    </div>
                    <p class="mb-0 mt-2 small" style="opacity:0.8;">Active customers</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header brand-header">
                    <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Monthly Revenue <?php echo e(date('Y')); ?></h5>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" height="300"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header brand-header">
                    <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Invoice Status Distribution</h5>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Invoices & Overdue -->
    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header brand-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-file-invoice me-2"></i>Recent Invoices</h5>
                    <a href="<?php echo e(route('invoices.index')); ?>" class="btn btn-sm btn-light" style="color:var(--brand);">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Invoice #</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $recentInvoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($invoice->invoice_number); ?></td>
                                    <td><?php echo e($invoice->customer->name); ?></td>
                                    <td><?php echo e($invoice->issue_date->format('d M Y')); ?></td>
                                    <td><?php echo e(format_money($invoice->total_amount, $invoice->currency)); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo e($invoice->status == 'paid' ? 'success' : ($invoice->status == 'overdue' ? 'danger' : 'warning')); ?>">
                                            <?php echo e(ucfirst($invoice->status)); ?>

                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr><td colspan="5" class="text-center py-3">No invoices yet.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header brand-header">
                    <h5 class="mb-0"><i class="fas fa-trophy me-2"></i>Top Customers</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Invoices</th>
                                    <th>Total Spent</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $topCustomers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($customer->name); ?></td>
                                    <td><?php echo e($customer->invoices_count); ?></td>
                                    <td><?php echo e(format_money($customer->invoices_sum_total_amount ?? 0)); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if($overdueInvoices->count() > 0): ?>
    <div class="row mt-3">
        <div class="col-12">
            <div class="card" style="border-color:var(--brand);">
                <div class="card-header bg-brand text-white">
                    <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Overdue Invoices</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Invoice #</th>
                                    <th>Customer</th>
                                    <th>Due Date</th>
                                    <th>Amount</th>
                                    <th>Days Overdue</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $overdueInvoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($invoice->invoice_number); ?></td>
                                    <td><?php echo e($invoice->customer->name); ?></td>
                                    <td><?php echo e($invoice->due_date->format('d M Y')); ?></td>
                                    <td><?php echo e(format_money($invoice->total_amount, $invoice->currency)); ?></td>
                                    <td class="text-danger"><?php echo e(now()->diffInDays($invoice->due_date)); ?> days</td>
                                    <td>
                                        <a href="<?php echo e(route('invoices.show', $invoice)); ?>" class="btn btn-sm btn-info">View</a>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    // Revenue Chart
    const revenueEl = document.getElementById('revenueChart');
    if (revenueEl) {
    const revenueCtx = revenueEl.getContext('2d');
    const revenueData = <?php echo json_encode($monthlyRevenue, 15, 512) ?>;
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const revenueValues = Array(12).fill(0);
    revenueData.forEach(item => { revenueValues[item.month - 1] = item.total; });
    new Chart(revenueCtx, {
        type: 'line',
        data: { labels: months, datasets: [{ label: 'Revenue ($)', data: revenueValues, borderColor: 'rgb(75, 192, 192)', backgroundColor: 'rgba(75, 192, 192, 0.2)', tension: 0.1 }] },
        options: { responsive: true, maintainAspectRatio: true }
    });
    }
    const statusEl = document.getElementById('statusChart');
    if (statusEl) {
    const statusCtx = statusEl.getContext('2d');
    const statusData = <?php echo json_encode($statusDistribution, 15, 512) ?>;
    const statusLabels = statusData.map(s => s.status);
    const statusCounts = statusData.map(s => s.count);
    
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: statusLabels,
            datasets: [{
                data: statusCounts,
                backgroundColor: [
                    'rgb(255, 193, 7)',
                    'rgb(23, 162, 184)',
                    'rgb(40, 167, 69)',
                    'rgb(220, 53, 69)',
                    'rgb(108, 117, 125)'
                ]
            }]
        },
        options: { responsive: true, maintainAspectRatio: true }
    });
    }
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\wamp64\www\invoice-system-server\resources\views/dashboard.blade.php ENDPATH**/ ?>