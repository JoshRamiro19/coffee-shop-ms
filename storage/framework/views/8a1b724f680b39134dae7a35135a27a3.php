<?php $__env->startSection('title', 'Sales'); ?>
<?php $__env->startSection('page-title', 'Sales Monitoring'); ?>
<?php $__env->startSection('page-subtitle', 'Revenue analytics and order performance'); ?>

<?php $__env->startSection('header-actions'); ?>
<form method="GET" class="flex items-center gap-2">
    <label class="text-sm text-gray-500">Range:</label>
    <select name="range" onchange="this.form.submit()" class="form-input py-2 w-32">
        <option value="7"  <?php echo e($range == 7  ? 'selected' : ''); ?>>Last 7 days</option>
        <option value="14" <?php echo e($range == 14 ? 'selected' : ''); ?>>Last 14 days</option>
        <option value="30" <?php echo e($range == 30 ? 'selected' : ''); ?>>Last 30 days</option>
        <option value="90" <?php echo e($range == 90 ? 'selected' : ''); ?>>Last 90 days</option>
    </select>
</form>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>


<div class="grid grid-cols-3 gap-5 mb-8">
    <div class="card p-5 border-l-4 border-green-400">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Total Revenue</p>
        <p class="font-display text-3xl text-gray-800">₱<?php echo e(number_format($totalRevenue, 2)); ?></p>
        <p class="text-xs text-gray-400 mt-1">Last <?php echo e($range); ?> days</p>
    </div>
    <div class="card p-5 border-l-4 border-blue-400">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Total Orders</p>
        <p class="font-display text-3xl text-gray-800"><?php echo e(number_format($totalOrders)); ?></p>
        <p class="text-xs text-gray-400 mt-1">Completed orders</p>
    </div>
    <div class="card p-5 border-l-4 border-purple-400">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Avg Order Value</p>
        <p class="font-display text-3xl text-gray-800">₱<?php echo e(number_format($avgOrderValue, 2)); ?></p>
        <p class="text-xs text-gray-400 mt-1">Per completed order</p>
    </div>
</div>


<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <div class="card p-6 lg:col-span-2">
        <h2 class="font-display text-lg text-gray-800 mb-5">Daily Revenue</h2>
        <canvas id="revenueChart" height="90"></canvas>
    </div>
    <div class="card p-6">
        <h2 class="font-display text-lg text-gray-800 mb-5">By Category</h2>
        <canvas id="categoryChart"></canvas>
        <div class="mt-4 space-y-2">
            <?php $colors = ['#c8833a','#6b3f2a','#d4a574','#2c1810','#fdf6ec']; ?>
            <?php $__currentLoopData = $categoryBreakdown; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="flex justify-between text-sm items-center">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full" style="background:<?php echo e($colors[$i % count($colors)]); ?>"></span>
                    <span class="text-gray-600 capitalize"><?php echo e($cat->category); ?></span>
                </div>
                <span class="font-semibold">₱<?php echo e(number_format($cat->total, 2)); ?></span>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>


<div class="card overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h2 class="font-display text-lg text-gray-800">Top Products by Revenue</h2>
    </div>
    <table>
        <thead><tr>
            <th>#</th><th>Product</th><th>Category</th><th>Qty Sold</th><th>Revenue</th>
        </tr></thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $topProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td class="text-gray-400 font-bold"><?php echo e($i + 1); ?></td>
                <td class="font-medium"><?php echo e($item->product->name ?? '—'); ?></td>
                <td><span class="badge-gray status-badge capitalize"><?php echo e($item->product->category ?? '—'); ?></span></td>
                <td class="font-semibold"><?php echo e(number_format($item->total_qty)); ?></td>
                <td class="font-bold text-green-600">₱<?php echo e(number_format($item->total_revenue, 2)); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="5" class="text-center text-gray-400 py-8">No sales data for this period.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
const labels  = <?php echo json_encode($salesData->pluck('date'), 15, 512) ?>;
const totals  = <?php echo json_encode($salesData->pluck('total'), 15, 512) ?>.map(v => parseFloat(v));
const orders  = <?php echo json_encode($salesData->pluck('orders'), 15, 512) ?>.map(v => parseInt(v));

new Chart(document.getElementById('revenueChart'), {
    type: 'line',
    data: {
        labels: labels.map(d => new Date(d).toLocaleDateString('en-US', { month: 'short', day: 'numeric' })),
        datasets: [
            {
                label: 'Revenue (₱)',
                data: totals,
                borderColor: '#c8833a',
                backgroundColor: 'rgba(200,131,58,0.1)',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#c8833a',
            }
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: '#f3f4f6' }, ticks: { callback: v => '₱' + v.toLocaleString() } },
            x: { grid: { display: false } }
        }
    }
});

const catData = <?php echo json_encode($categoryBreakdown, 15, 512) ?>;
new Chart(document.getElementById('categoryChart'), {
    type: 'doughnut',
    data: {
        labels: catData.map(c => c.category),
        datasets: [{
            data: catData.map(c => c.total),
            backgroundColor: ['#c8833a', '#6b3f2a', '#d4a574', '#2c1810', '#fdf6ec'],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } }
    }
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\coffee-shop\resources\views/admin/sales.blade.php ENDPATH**/ ?>