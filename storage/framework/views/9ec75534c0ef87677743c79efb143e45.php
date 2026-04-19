<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('page-title', 'Dashboard'); ?>
<?php $__env->startSection('page-subtitle', 'Welcome back! Here\'s what\'s brewing today.'); ?>

<?php $__env->startSection('content'); ?>


<div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    <div class="card p-5">
        <div class="flex items-center justify-between mb-3">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Today's Sales</p>
            <div class="w-9 h-9 bg-green-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-peso-sign text-green-600 text-sm"></i>
            </div>
        </div>
        <p class="font-display text-2xl text-gray-800">₱<?php echo e(number_format($todaySales, 2)); ?></p>
        <p class="text-xs text-gray-400 mt-1"><?php echo e($todayOrders); ?> orders today</p>
    </div>

    <div class="card p-5">
        <div class="flex items-center justify-between mb-3">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Weekly Sales</p>
            <div class="w-9 h-9 bg-blue-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-chart-bar text-blue-600 text-sm"></i>
            </div>
        </div>
        <p class="font-display text-2xl text-gray-800">₱<?php echo e(number_format($weeklySales, 2)); ?></p>
        <p class="text-xs text-gray-400 mt-1">This week</p>
    </div>

    <div class="card p-5">
        <div class="flex items-center justify-between mb-3">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Monthly Sales</p>
            <div class="w-9 h-9 bg-purple-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-calendar text-purple-600 text-sm"></i>
            </div>
        </div>
        <p class="font-display text-2xl text-gray-800">₱<?php echo e(number_format($monthlySales, 2)); ?></p>
        <p class="text-xs text-gray-400 mt-1">This month</p>
    </div>

    <div class="card p-5">
        <div class="flex items-center justify-between mb-3">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Live Queue</p>
            <div class="w-9 h-9 <?php echo e($queueCount > 0 ? 'bg-orange-100' : 'bg-gray-100'); ?> rounded-xl flex items-center justify-center">
                <i class="fas fa-list-ol <?php echo e($queueCount > 0 ? 'text-orange-600' : 'text-gray-400'); ?> text-sm"></i>
            </div>
        </div>
        <p class="font-display text-2xl text-gray-800"><?php echo e($queueCount); ?></p>
        <p class="text-xs text-gray-400 mt-1"><?php echo e($pendingCount); ?> pending</p>
    </div>
</div>


<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    
    <div class="card p-6 lg:col-span-2">
        <div class="flex items-center justify-between mb-5">
            <h2 class="font-display text-lg text-gray-800">Sales Last 7 Days</h2>
            <a href="<?php echo e(route('admin.sales')); ?>" class="text-xs text-caramel hover:underline font-medium">View full report →</a>
        </div>
        <canvas id="salesChart" height="90"></canvas>
    </div>

    
    <div class="card p-6">
        <h2 class="font-display text-lg text-gray-800 mb-4">Top Products</h2>
        <?php $__empty_1 = true; $__currentLoopData = $topProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="flex items-center gap-3 mb-3">
            <span class="w-6 h-6 bg-cream rounded-full text-xs font-bold text-caramel flex items-center justify-center flex-shrink-0"><?php echo e($i+1); ?></span>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-700 truncate"><?php echo e($item->product->name ?? 'Unknown'); ?></p>
                <div class="h-1.5 bg-gray-100 rounded-full mt-1">
                    <div class="h-1.5 bg-caramel rounded-full" style="width:<?php echo e($topProducts[0]->total_qty > 0 ? round($item->total_qty / $topProducts[0]->total_qty * 100) : 0); ?>%"></div>
                </div>
            </div>
            <span class="text-xs font-semibold text-gray-500 flex-shrink-0"><?php echo e($item->total_qty); ?>x</span>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <p class="text-sm text-gray-400">No sales data yet.</p>
        <?php endif; ?>
    </div>
</div>


<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <div class="card lg:col-span-2 overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h2 class="font-display text-lg text-gray-800">Recent Orders</h2>
            <a href="<?php echo e(route('orders.index')); ?>" class="text-xs text-caramel hover:underline font-medium">View all →</a>
        </div>
        <table>
            <thead><tr>
                <th>Customer</th><th>Items</th><th>Total</th><th>Status</th><th>Time</th>
            </tr></thead>
            <tbody>
                <?php $__currentLoopData = $recentOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td class="font-medium"><?php echo e($order->customer_name); ?></td>
                    <td class="text-gray-500"><?php echo e($order->items->sum('quantity')); ?>x items</td>
                    <td class="font-semibold">₱<?php echo e(number_format($order->total_amount, 2)); ?></td>
                    <td><span class="status-badge status-<?php echo e($order->status); ?>"><?php echo e($order->status_label); ?></span></td>
                    <td class="text-gray-400 text-xs"><?php echo e($order->created_at->diffForHumans()); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>

    
    <div class="space-y-4">
        
        <?php if($lowStock->count()): ?>
        <div class="card p-5">
            <div class="flex items-center gap-2 mb-3">
                <i class="fas fa-triangle-exclamation text-red-500"></i>
                <h3 class="font-semibold text-gray-700">Low Stock Alert</h3>
                <span class="ml-auto text-xs bg-red-100 text-red-600 font-bold px-2 py-0.5 rounded-full"><?php echo e($lowStock->count()); ?></span>
            </div>
            <?php $__currentLoopData = $lowStock->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="flex justify-between items-center text-sm py-1.5 border-b border-gray-50 last:border-0">
                <span class="text-gray-700"><?php echo e($p->name); ?></span>
                <span class="font-bold <?php echo e($p->stock == 0 ? 'text-red-600' : 'text-orange-500'); ?>"><?php echo e($p->stock); ?></span>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('admin.stock')); ?>" class="text-xs text-caramel hover:underline block mt-2">Manage stock →</a>
        </div>
        <?php endif; ?>

        
        <div class="card p-5">
            <h3 class="font-semibold text-gray-700 mb-4">Quick Stats</h3>
            <div class="space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500"><i class="fas fa-users mr-1 text-blue-400"></i> Active Staff</span>
                    <span class="font-semibold"><?php echo e($activeEmployees); ?></span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500"><i class="fas fa-exclamation-circle mr-1 text-orange-400"></i> Urgent Tasks</span>
                    <span class="font-semibold <?php echo e($urgentTodos > 0 ? 'text-orange-600' : ''); ?>"><?php echo e($urgentTodos); ?></span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500"><i class="fas fa-boxes mr-1 text-red-400"></i> Low Stock Items</span>
                    <span class="font-semibold <?php echo e($lowStock->count() > 0 ? 'text-red-600' : ''); ?>"><?php echo e($lowStock->count()); ?></span>
                </div>
            </div>
            <div class="flex gap-2 mt-4">
                <a href="<?php echo e(route('admin.todos.index')); ?>" class="text-xs text-center flex-1 border border-gray-200 text-gray-600 hover:border-caramel hover:text-caramel rounded-lg py-2 font-medium transition-colors">
                    <i class="fas fa-tasks mr-1"></i> Tasks
                </a>
                <a href="<?php echo e(route('admin.employees.index')); ?>" class="text-xs text-center flex-1 border border-gray-200 text-gray-600 hover:border-caramel hover:text-caramel rounded-lg py-2 font-medium transition-colors">
                    <i class="fas fa-users mr-1"></i> Staff
                </a>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
const labels = <?php echo json_encode($dailySales->pluck('date'), 15, 512) ?>;
const totals = <?php echo json_encode($dailySales->pluck('total'), 15, 512) ?>;
const ctx = document.getElementById('salesChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: labels.map(d => new Date(d).toLocaleDateString('en-US', { month: 'short', day: 'numeric' })),
        datasets: [{
            label: 'Sales (₱)',
            data: totals,
            backgroundColor: 'rgba(200,131,58,0.2)',
            borderColor: '#c8833a',
            borderWidth: 2,
            borderRadius: 8,
        }]
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
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\coffee-shop\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>