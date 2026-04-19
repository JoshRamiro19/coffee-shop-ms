<?php $__env->startSection('title', 'To-Do'); ?>
<?php $__env->startSection('page-title', 'To-Do List'); ?>
<?php $__env->startSection('page-subtitle', 'Task management for the team'); ?>

<?php $__env->startSection('header-actions'); ?>
<button onclick="openModal()" class="btn-primary gap-2">
    <i class="fas fa-plus"></i> Add Task
</button>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>


<div class="grid grid-cols-4 gap-4 mb-7">
    <div class="card p-4 text-center">
        <p class="font-display text-2xl text-gray-800"><?php echo e($stats['total']); ?></p>
        <p class="text-xs text-gray-400 mt-0.5">Total Tasks</p>
    </div>
    <div class="card p-4 text-center border-l-4 border-yellow-400">
        <p class="font-display text-2xl text-yellow-600"><?php echo e($stats['pending']); ?></p>
        <p class="text-xs text-gray-400 mt-0.5">Pending</p>
    </div>
    <div class="card p-4 text-center border-l-4 border-blue-400">
        <p class="font-display text-2xl text-blue-600"><?php echo e($stats['in_progress']); ?></p>
        <p class="text-xs text-gray-400 mt-0.5">In Progress</p>
    </div>
    <div class="card p-4 text-center border-l-4 border-green-400">
        <p class="font-display text-2xl text-green-600"><?php echo e($stats['completed']); ?></p>
        <p class="text-xs text-gray-400 mt-0.5">Completed</p>
    </div>
</div>


<div class="mb-5 flex gap-3 flex-wrap">
    <form method="GET" class="flex gap-3 flex-wrap">
        <select name="status" class="form-input py-2 w-36" onchange="this.form.submit()">
            <option value="">All Status</option>
            <?php $__currentLoopData = ['pending','in_progress','completed']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($s); ?>" <?php echo e(request('status') == $s ? 'selected' : ''); ?>><?php echo e(ucwords(str_replace('_', ' ', $s))); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <select name="priority" class="form-input py-2 w-36" onchange="this.form.submit()">
            <option value="">All Priority</option>
            <?php $__currentLoopData = ['urgent','high','medium','low']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($pr); ?>" <?php echo e(request('priority') == $pr ? 'selected' : ''); ?>><?php echo e(ucfirst($pr)); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <?php if(request('status') || request('priority')): ?>
        <a href="<?php echo e(route('admin.todos.index')); ?>" class="btn-secondary py-2">Clear</a>
        <?php endif; ?>
    </form>
</div>


<div class="card overflow-hidden">
    <table>
        <thead><tr>
            <th>Task</th><th>Priority</th><th>Assigned To</th><th>Due Date</th><th>Status</th><th class="text-right">Actions</th>
        </tr></thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $todos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $todo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr class="<?php echo e($todo->status === 'completed' ? 'opacity-60' : ''); ?>">
                <td>
                    <div>
                        <p class="font-medium text-gray-800 <?php echo e($todo->status === 'completed' ? 'line-through' : ''); ?>"><?php echo e($todo->title); ?></p>
                        <?php if($todo->description): ?>
                        <p class="text-xs text-gray-400 mt-0.5 truncate max-w-sm"><?php echo e($todo->description); ?></p>
                        <?php endif; ?>
                    </div>
                </td>
                <td>
                    <?php $prColors = ['urgent'=>'badge-red','high'=>'badge-orange','medium'=>'badge-yellow','low'=>'badge-green']; ?>
                    <span class="status-badge <?php echo e($prColors[$todo->priority] ?? 'badge-gray'); ?> capitalize"><?php echo e($todo->priority); ?></span>
                </td>
                <td class="text-gray-500 text-sm"><?php echo e($todo->assignee?->name ?? '—'); ?></td>
                <td>
                    <?php if($todo->due_date): ?>
                    <span class="text-sm <?php echo e($todo->isOverdue() ? 'text-red-600 font-semibold' : 'text-gray-500'); ?>">
                        <?php echo e($todo->isOverdue() ? '⚠️ ' : ''); ?><?php echo e($todo->due_date->format('M d, Y')); ?>

                    </span>
                    <?php else: ?>
                    <span class="text-gray-400">—</span>
                    <?php endif; ?>
                </td>
                <td>
                    <select onchange="updateStatus(<?php echo e($todo->id); ?>, this.value)"
                        class="text-xs border rounded-lg px-2 py-1.5 <?php echo e($todo->status === 'completed' ? 'text-green-600 border-green-200' : ($todo->status === 'in_progress' ? 'text-blue-600 border-blue-200' : 'text-gray-500 border-gray-200')); ?>">
                        <option value="pending"     <?php echo e($todo->status === 'pending'     ? 'selected' : ''); ?>>Pending</option>
                        <option value="in_progress" <?php echo e($todo->status === 'in_progress' ? 'selected' : ''); ?>>In Progress</option>
                        <option value="completed"   <?php echo e($todo->status === 'completed'   ? 'selected' : ''); ?>>Completed</option>
                    </select>
                </td>
                <td class="text-right">
                    <div class="flex gap-2 justify-end">
                        <button onclick="openEdit(<?php echo e(json_encode($todo)); ?>, <?php echo e(json_encode($todo->assignee?->name)); ?>)"
                            class="text-blue-500 text-sm px-2 py-1 border border-blue-200 rounded-lg hover:bg-blue-50">
                            <i class="fas fa-edit"></i>
                        </button>
                        <form action="<?php echo e(route('admin.todos.destroy', $todo)); ?>" method="POST"
                              onsubmit="return confirm('Delete this task?')">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button class="btn-danger text-xs px-2 py-1">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="6" class="text-center text-gray-400 py-10">
                <i class="fas fa-check-double text-2xl mb-2 block"></i>
                No tasks found. Add one!
            </td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-100"><?php echo e($todos->links()); ?></div>
</div>


<div class="modal-overlay" id="todoModal">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 animate-in">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h2 class="font-display text-lg text-gray-800" id="modalTitle">Add Task</h2>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">×</button>
        </div>
        <form id="todoForm" method="POST" action="<?php echo e(route('admin.todos.store')); ?>">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <div class="p-6 space-y-4">
                <div>
                    <label class="form-label">Title <span class="text-red-400">*</span></label>
                    <input type="text" name="title" id="todoTitle" class="form-input" required>
                </div>
                <div>
                    <label class="form-label">Description</label>
                    <textarea name="description" id="todoDesc" rows="2" class="form-input resize-none"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Priority</label>
                        <select name="priority" id="todoPriority" class="form-input">
                            <?php $__currentLoopData = ['low','medium','high','urgent']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($p); ?>"><?php echo e(ucfirst($p)); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Status</label>
                        <select name="status" id="todoStatus" class="form-input">
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Assign To</label>
                        <select name="assigned_to" id="todoAssigned" class="form-input">
                            <option value="">— Unassigned —</option>
                            <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($emp->id); ?>"><?php echo e($emp->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Due Date</label>
                        <input type="date" name="due_date" id="todoDue" class="form-input">
                    </div>
                </div>
            </div>
            <div class="flex gap-3 px-6 pb-6">
                <button type="button" onclick="closeModal()" class="btn-secondary flex-1 justify-center">Cancel</button>
                <button type="submit" class="btn-primary flex-1 justify-center">
                    <i class="fas fa-save mr-2"></i> Save Task
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function openModal() {
    document.getElementById('modalTitle').textContent = 'Add Task';
    document.getElementById('todoForm').action = '<?php echo e(route("admin.todos.store")); ?>';
    document.getElementById('formMethod').value = 'POST';
    document.getElementById('todoTitle').value = '';
    document.getElementById('todoDesc').value = '';
    document.getElementById('todoPriority').value = 'medium';
    document.getElementById('todoStatus').value = 'pending';
    document.getElementById('todoAssigned').value = '';
    document.getElementById('todoDue').value = '';
    document.getElementById('todoModal').classList.add('show');
}

function openEdit(todo, assigneeName) {
    document.getElementById('modalTitle').textContent = 'Edit Task';
    document.getElementById('todoForm').action = `/admin/todos/${todo.id}`;
    document.getElementById('formMethod').value = 'PUT';
    document.getElementById('todoTitle').value = todo.title;
    document.getElementById('todoDesc').value = todo.description || '';
    document.getElementById('todoPriority').value = todo.priority;
    document.getElementById('todoStatus').value = todo.status;
    document.getElementById('todoAssigned').value = todo.assigned_to || '';
    document.getElementById('todoDue').value = todo.due_date || '';
    document.getElementById('todoModal').classList.add('show');
}

function closeModal() {
    document.getElementById('todoModal').classList.remove('show');
}

function updateStatus(id, status) {
    fetch(`/admin/todos/${id}/status`, {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
        body: JSON.stringify({ status })
    }).then(() => location.reload());
}

document.getElementById('todoModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\coffee-shop\resources\views/admin/todos/index.blade.php ENDPATH**/ ?>