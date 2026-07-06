<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold" style="color:var(--brand);"><i class="fas fa-folder me-2"></i>Projects</h4>
    <button class="btn btn-brand" onclick="showCreateModal()">
        <i class="fas fa-plus me-1"></i> New Project
    </button>
</div>

<div class="card shadow-sm" style="border:1px solid #dee2e6;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th></th>
                        <th>Name</th>
                        <th>Customer</th>
                        <th>Hourly Rate</th>
                        <th>Time Entries</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td style="width:30px">
                                <span class="d-inline-block rounded-circle" style="width:14px;height:14px;background:<?php echo e($project->color); ?>"></span>
                            </td>
                            <td class="fw-medium"><?php echo e($project->name); ?></td>
                            <td><?php echo e($project->customer?->name ?: '-'); ?></td>
                            <td><?php echo e($project->hourly_rate ? '$' . number_format($project->hourly_rate, 2) : '-'); ?></td>
                            <td><?php echo e($project->time_entries_count); ?></td>
                            <td>
                                <?php if($project->is_active): ?>
                                    <span class="badge bg-brand">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Archived</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-brand-outline" onclick="showEditModal(<?php echo e($project->id); ?>)">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteProject(<?php echo e($project->id); ?>)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">No projects yet</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>

<div class="modal fade" id="projectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="projectForm">
                <?php echo csrf_field(); ?>
                <input type="hidden" id="project-id" name="project_id">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">New Project</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Project Name</label>
                        <input type="text" id="project-name" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Customer</label>
                        <select id="project-customer" name="customer_id" class="form-select">
                            <option value="">No customer</option>
                            <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($customer->id); ?>"><?php echo e($customer->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea id="project-description" name="description" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label">Color</label>
                            <input type="color" id="project-color" name="color" class="form-control form-control-color" value="#3498db">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Hourly Rate ($)</label>
                            <input type="number" id="project-rate" name="hourly_rate" class="form-control" step="0.01" min="0" placeholder="20.00">
                        </div>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" id="project-active" name="is_active" class="form-check-input" value="1" checked>
                        <label class="form-check-label" for="project-active">Active</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-brand">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let modal = null;
document.addEventListener('DOMContentLoaded', function () {
    modal = new bootstrap.Modal(document.getElementById('projectModal'));
});

function showCreateModal() {
    document.getElementById('modalTitle').textContent = 'New Project';
    document.getElementById('project-id').value = '';
    document.getElementById('projectForm').reset();
    document.getElementById('project-color').value = '#3498db';
    document.getElementById('project-active').checked = true;
    modal.show();
}

function showEditModal(id) {
    fetch(`<?php echo e(url('projects')); ?>/${id}`)
        .then(r => r.json())
        .then(data => {
            const p = data.project;
            document.getElementById('modalTitle').textContent = 'Edit Project';
            document.getElementById('project-id').value = p.id;
            document.getElementById('project-name').value = p.name;
            document.getElementById('project-customer').value = p.customer_id || '';
            document.getElementById('project-description').value = p.description || '';
            document.getElementById('project-color').value = p.color;
            document.getElementById('project-rate').value = p.hourly_rate || '';
            document.getElementById('project-active').checked = p.is_active;
            modal.show();
        });
}

document.getElementById('projectForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const id = document.getElementById('project-id').value;
    const formData = {
        name: document.getElementById('project-name').value,
        customer_id: document.getElementById('project-customer').value || null,
        description: document.getElementById('project-description').value,
        color: document.getElementById('project-color').value,
        hourly_rate: document.getElementById('project-rate').value || null,
        is_active: document.getElementById('project-active').checked,
    };

    const url = id ? `<?php echo e(url('projects')); ?>/${id}` : `<?php echo e(route('projects.store')); ?>`;
    const method = id ? 'PUT' : 'POST';

    fetch(url, {
        method: method,
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>' },
        body: JSON.stringify(formData)
    })
    .then(r => r.json())
    .then(data => {
        if (data.error) { alert(data.error); return; }
        modal.hide();
        location.reload();
    });
});

function deleteProject(id) {
    if (!confirm('Delete this project? Time entries will not be deleted.')) return;
    fetch(`<?php echo e(url('projects')); ?>/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) location.reload();
    });
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\wamp64\www\invoice-system-server\resources\views/projects/index.blade.php ENDPATH**/ ?>