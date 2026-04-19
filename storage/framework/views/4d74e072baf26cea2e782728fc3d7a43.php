<?php $__env->startSection('title', 'Products'); ?>
<?php $__env->startSection('page-title', 'Products'); ?>
<?php $__env->startSection('page-subtitle', 'Manage your menu items'); ?>

<?php $__env->startSection('header-actions'); ?>
<a href="<?php echo e(route('admin.products.create')); ?>" class="btn-primary gap-2">
    <i class="fas fa-plus"></i> Add Product
</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card overflow-hidden">
    
    <div class="p-5 border-b border-gray-100 flex gap-3 flex-wrap items-center">
        <form method="GET" class="flex gap-3 flex-1 flex-wrap">
            <input type="text" name="search" placeholder="Search products..." value="<?php echo e(request('search')); ?>"
                class="form-input py-2 w-56">
            <select name="category" class="form-input py-2 w-36">
                <option value="">All Categories</option>
                <?php $__currentLoopData = ['beverage','food','snack','merchandise']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($cat); ?>" <?php echo e(request('category') == $cat ? 'selected' : ''); ?>><?php echo e(ucfirst($cat)); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <button type="submit" class="btn-primary py-2">Filter</button>
            <?php if(request('search') || request('category')): ?>
            <a href="<?php echo e(route('admin.products.index')); ?>" class="btn-secondary py-2">Clear</a>
            <?php endif; ?>
        </form>
    </div>

    <table>
        <thead><tr>
            <th>Product</th><th>Category</th><th>Price</th><th>Stock</th><th>Available</th><th>Status</th><th class="text-right">Actions</th>
        </tr></thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr class="<?php echo e($p->trashed() ? 'opacity-60 bg-red-50/30' : ''); ?>">
                <td>
                    <div>
                        <p class="font-medium text-gray-800"><?php echo e($p->name); ?></p>
                        <?php if($p->description): ?><p class="text-xs text-gray-400 mt-0.5 truncate max-w-xs"><?php echo e($p->description); ?></p><?php endif; ?>
                    </div>
                </td>
                <td><span class="status-badge badge-gray capitalize"><?php echo e($p->category); ?></span></td>
                <td class="font-semibold text-green-600">₱<?php echo e(number_format($p->price, 2)); ?></td>
                <td>
                    <span class="<?php echo e($p->stock == 0 ? 'text-red-600 font-bold' : ($p->stock <= $p->low_stock_threshold ? 'text-orange-500 font-semibold' : 'text-gray-700')); ?>">
                        <?php echo e($p->stock); ?>

                    </span>
                </td>
                <td>
                    <?php if($p->is_available): ?>
                        <span class="status-badge badge-green">Yes</span>
                    <?php else: ?>
                        <span class="status-badge badge-red">No</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if($p->trashed()): ?>
                        <span class="status-badge badge-red">Deleted</span>
                    <?php elseif($p->stock == 0): ?>
                        <span class="status-badge badge-red">Out of Stock</span>
                    <?php elseif($p->stock <= $p->low_stock_threshold): ?>
                        <span class="status-badge badge-orange">Low Stock</span>
                    <?php else: ?>
                        <span class="status-badge badge-green">Active</span>
                    <?php endif; ?>
                </td>
                <td class="text-right">
                    <?php if($p->trashed()): ?>
                    <form action="<?php echo e(route('admin.products.restore', $p->id)); ?>" method="POST" class="inline">
                        <?php echo csrf_field(); ?>
                        <button class="text-green-500 hover:text-green-700 text-sm px-2 py-1 border border-green-200 rounded-lg">
                            <i class="fas fa-undo mr-1"></i> Restore
                        </button>
                    </form>
                    <?php else: ?>
                    <div class="flex items-center justify-end gap-2">
                        <a href="<?php echo e(route('admin.products.edit', $p)); ?>" class="text-blue-500 hover:text-blue-700 text-sm px-2 py-1 border border-blue-200 rounded-lg">
                            <i class="fas fa-edit mr-1"></i> Edit
                        </a>
                        <form action="<?php echo e(route('admin.products.destroy', $p)); ?>" method="POST" onsubmit="return confirm('Remove this product? It will be soft-deleted.')">
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
            <tr><td colspan="7" class="text-center text-gray-400 py-10">No products found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-100"><?php echo e($products->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\coffee-shop\resources\views/admin/products/index.blade.php ENDPATH**/ ?>