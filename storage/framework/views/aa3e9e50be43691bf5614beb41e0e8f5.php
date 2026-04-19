<?php $__env->startSection('title', 'Stock'); ?>
<?php $__env->startSection('page-title', 'Stock Monitoring'); ?>
<?php $__env->startSection('page-subtitle', 'Manage product inventory levels'); ?>

<?php $__env->startSection('header-actions'); ?>
<a href="<?php echo e(route('admin.products.create')); ?>" class="btn-primary gap-2">
    <i class="fas fa-plus"></i> Add Product
</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="grid grid-cols-3 gap-5 mb-8">
    <div class="card p-5 border-l-4 border-green-400">
        <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Total Products</p>
        <p class="font-display text-2xl"><?php echo e($totalProducts); ?></p>
    </div>
    <div class="card p-5 border-l-4 border-orange-400">
        <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Low Stock</p>
        <p class="font-display text-2xl text-orange-500"><?php echo e($lowStockCount); ?></p>
    </div>
    <div class="card p-5 border-l-4 border-red-400">
        <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Out of Stock</p>
        <p class="font-display text-2xl text-red-500"><?php echo e($outOfStock); ?></p>
    </div>
</div>

<div class="card overflow-hidden">
    <table>
        <thead><tr>
            <th>Product</th><th>Category</th><th>Price</th>
            <th>Stock</th><th>Threshold</th><th>Status</th><th>Actions</th>
        </tr></thead>
        <tbody>
            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr class="<?php echo e($p->trashed() ? 'opacity-50' : ''); ?>">
                <td>
                    <p class="font-medium"><?php echo e($p->name); ?></p>
                    <?php if($p->trashed()): ?> <span class="text-xs text-red-400">Deleted</span> <?php endif; ?>
                </td>
                <td><span class="status-badge badge-gray capitalize"><?php echo e($p->category); ?></span></td>
                <td class="font-semibold">₱<?php echo e(number_format($p->price, 2)); ?></td>
                <td>
                    <div class="flex items-center gap-2">
                        <input type="number" value="<?php echo e($p->stock); ?>" min="0"
                            class="w-20 border border-gray-200 rounded-lg px-2 py-1 text-sm text-center focus:outline-none focus:border-caramel"
                            data-product-id="<?php echo e($p->id); ?>"
                            onchange="updateStock(<?php echo e($p->id); ?>, this.value, this)"
                            <?php echo e($p->trashed() ? 'disabled' : ''); ?>>
                    </div>
                </td>
                <td class="text-gray-400"><?php echo e($p->low_stock_threshold); ?></td>
                <td>
                    <?php if($p->trashed()): ?>
                        <span class="status-badge badge-gray">Deleted</span>
                    <?php elseif($p->stock == 0): ?>
                        <span class="status-badge badge-red">Out of Stock</span>
                    <?php elseif($p->stock <= $p->low_stock_threshold): ?>
                        <span class="status-badge badge-orange">Low Stock</span>
                    <?php else: ?>
                        <span class="status-badge badge-green">In Stock</span>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="<?php echo e(route('admin.products.edit', $p->id)); ?>" class="text-blue-500 hover:text-blue-700 text-sm mr-2">
                        <i class="fas fa-edit"></i>
                    </a>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-100"><?php echo e($products->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function updateStock(id, value, input) {
    fetch(`/admin/stock/${id}`, {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
        body: JSON.stringify({ stock: parseInt(value) })
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            input.style.background = '#dcfce7';
            setTimeout(() => input.style.background = '', 1500);
        }
    });
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\coffee-shop\resources\views/admin/stock.blade.php ENDPATH**/ ?>