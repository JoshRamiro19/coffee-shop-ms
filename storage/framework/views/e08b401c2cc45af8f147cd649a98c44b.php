<?php $__env->startSection('title', 'Employees'); ?>
<?php $__env->startSection('page-title', 'Employees'); ?>
<?php $__env->startSection('page-subtitle', 'Manage your team'); ?>

<?php $__env->startSection('header-actions'); ?>
<a href="<?php echo e(route('admin.employees.create')); ?>" class="btn-primary gap-2">
    <i class="fas fa-user-plus"></i> Add Employee
</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card overflow-hidden">
    <div class="p-5 border-b border-gray-100 flex gap-3 flex-wrap">
        <form method="GET" class="flex gap-3 flex-1 flex-wrap">
            <input type="text" name="search" placeholder="Search by name or email..." value="<?php echo e(request('search')); ?>"
                class="form-input py-2 w-64">
            <select name="role" class="form-input py-2 w-36">
                <option value="">All Roles</option>
                <?php $__currentLoopData = ['barista','cashier','manager','admin']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($r); ?>" <?php echo e(request('role') == $r ? 'selected' : ''); ?>><?php echo e(ucfirst($r)); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <button type="submit" class="btn-primary py-2">Filter</button>
            <?php if(request('search') || request('role')): ?>
            <a href="<?php echo e(route('admin.employees.index')); ?>" class="btn-secondary py-2">Clear</a>
            <?php endif; ?>
        </form>
    </div>

    <table>
        <thead><tr>
            <th>Employee</th><th>Role</th><th>Shift</th><th>Phone</th><th>Hired</th><th>Status</th><th class="text-right">Actions</th>
        </tr></thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr class="<?php echo e($emp->trashed() ? 'opacity-60' : ''); ?>">
                <td>
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-caramel to-mocha flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                            <?php echo e(strtoupper(substr($emp->name, 0, 1))); ?>

                        </div>
                        <div>
                            <p class="font-medium text-gray-800"><?php echo e($emp->name); ?></p>
                            <p class="text-xs text-gray-400"><?php echo e($emp->email); ?></p>
                        </div>
                    </div>
                </td>
                <td>
                    <?php $roleColors = ['admin'=>'badge-red','manager'=>'badge-purple','barista'=>'badge-orange','cashier'=>'badge-blue']; ?>
                    <span class="status-badge <?php echo e($roleColors[$emp->role] ?? 'badge-gray'); ?> capitalize"><?php echo e($emp->role); ?></span>
                </td>
                <td class="capitalize text-gray-600"><?php echo e(str_replace('_', ' ', $emp->shift)); ?></td>
                <td class="text-gray-500"><?php echo e($emp->phone ?? '—'); ?></td>
                <td class="text-gray-400 text-sm"><?php echo e($emp->hired_at?->format('M d, Y') ?? '—'); ?></td>
                <td>
                    <?php if($emp->trashed()): ?>
                        <span class="status-badge badge-red">Deleted</span>
                    <?php elseif($emp->is_active): ?>
                        <span class="status-badge badge-green">Active</span>
                    <?php else: ?>
                        <span class="status-badge badge-gray">Inactive</span>
                    <?php endif; ?>
                </td>
                <td class="text-right">
                    <?php if($emp->trashed()): ?>
                    <form action="<?php echo e(route('admin.employees.restore', $emp->id)); ?>" method="POST" class="inline">
                        <?php echo csrf_field(); ?>
                        <button class="text-green-500 text-sm px-2 py-1 border border-green-200 rounded-lg hover:bg-green-50">
                            <i class="fas fa-undo mr-1"></i> Restore
                        </button>
                    </form>
                    <?php else: ?>
                    <div class="flex gap-2 justify-end">
                        <a href="<?php echo e(route('admin.employees.edit', $emp)); ?>" class="text-blue-500 text-sm px-2 py-1 border border-blue-200 rounded-lg hover:bg-blue-50">
                            <i class="fas fa-edit mr-1"></i> Edit
                        </a>
                        <form action="<?php echo e(route('admin.employees.destroy', $emp)); ?>" method="POST"
                              onsubmit="return confirm('Remove <?php echo e($emp->name); ?>? They will be soft-deleted.')">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button class="btn-danger text-xs px-2 py-1">
                                <i class="fas fa-trash mr-1"></i> Remove
                            </button>
                        </form>
                    </div>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="7" class="text-center text-gray-400 py-10">No employees found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-100"><?php echo e($employees->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\coffee-shop\resources\views/admin/employees/index.blade.php ENDPATH**/ ?>