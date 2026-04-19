<?php $__env->startSection('title', 'Queue — BrewHouse'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="font-display text-2xl text-espresso">Order Queue</h1>
        <p class="text-sm text-gray-500 mt-0.5"><?php echo e($orders->count()); ?> active order(s)</p>
    </div>
    <div class="flex items-center gap-3">
        <span id="clock" class="text-lg font-semibold text-espresso font-mono"></span>
        <a href="<?php echo e(route('orders.index')); ?>" class="btn-secondary text-sm">
            <i class="fas fa-plus mr-1"></i> New Order
        </a>
    </div>
</div>

<?php if($orders->count()): ?>
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5" id="queueGrid">
    <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="card p-5 relative overflow-hidden group" id="order-card-<?php echo e($order->id); ?>"
         data-order-id="<?php echo e($order->id); ?>" data-status="<?php echo e($order->status); ?>">

        
        <div class="absolute top-0 left-0 right-0 h-1 <?php echo e($order->status === 'preparing' ? 'bg-orange-400' : 'bg-blue-400'); ?>"></div>

        
        <div class="flex justify-between items-start mb-3 mt-1">
            <div>
                <h3 class="font-display text-lg text-espresso"><?php echo e($order->customer_name); ?></h3>
                <p class="text-xs text-gray-400 font-mono"><?php echo e($order->order_number); ?></p>
            </div>
            <span class="status-badge status-<?php echo e($order->status); ?>"><?php echo e($order->status_label); ?></span>
        </div>

        
        <div class="text-xs text-gray-400 mb-3 flex items-center gap-1">
            <i class="fas fa-clock"></i>
            <span class="elapsed-time" data-created="<?php echo e($order->created_at->toIso8601String()); ?>"></span>
        </div>

        
        <div class="bg-foam rounded-xl p-3 mb-4">
            <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="flex justify-between items-center py-1 text-sm">
                <div class="flex items-center gap-2">
                    <span class="w-6 h-6 bg-caramel text-white text-xs font-bold rounded-full flex items-center justify-center"><?php echo e($item->quantity); ?></span>
                    <span class="text-espresso font-medium"><?php echo e($item->product->name); ?></span>
                </div>
                <span class="text-gray-400">₱<?php echo e(number_format($item->subtotal, 2)); ?></span>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php if($order->notes): ?>
            <div class="border-t border-dashed border-gray-200 mt-2 pt-2 text-xs text-gray-400 italic">
                <i class="fas fa-sticky-note mr-1"></i><?php echo e($order->notes); ?>

            </div>
            <?php endif; ?>
        </div>

        
        <div class="flex justify-between items-center mb-4">
            <span class="text-xs text-gray-400">Total Amount</span>
            <span class="font-bold text-lg text-espresso">₱<?php echo e(number_format($order->total_amount, 2)); ?></span>
        </div>

        
        <?php if($order->status === 'queue'): ?>
        <button onclick="setStatus(<?php echo e($order->id); ?>, 'preparing')"
            class="w-full border-2 border-orange-300 text-orange-600 rounded-xl py-2 text-sm font-semibold hover:bg-orange-50 transition-colors mb-2">
            <i class="fas fa-fire mr-1"></i> Start Preparing
        </button>
        <?php endif; ?>

        
        <button
            class="complete-btn w-full bg-green-500 text-white rounded-xl py-3 text-sm font-bold hover:bg-green-600 active:scale-95 transition-all"
            data-order-id="<?php echo e($order->id); ?>"
            data-click-count="0"
            onclick="handleComplete(this)">
            <span class="btn-label"><i class="fas fa-check mr-1"></i> Complete Order</span>
        </button>

        
        <div class="confirm-overlay hidden absolute inset-0 bg-green-500/95 rounded-2xl flex flex-col items-center justify-center text-white p-5">
            <i class="fas fa-check-circle text-3xl mb-2"></i>
            <p class="font-bold text-lg mb-1">Confirm Complete?</p>
            <p class="text-sm opacity-80 mb-4 text-center">Tap again to mark this order as done</p>
            <div class="flex gap-3 w-full">
                <button onclick="cancelComplete(this)" class="flex-1 bg-white/20 text-white rounded-lg py-2 text-sm font-semibold hover:bg-white/30">
                    Cancel
                </button>
                <button onclick="confirmComplete(<?php echo e($order->id); ?>, this)" class="flex-1 bg-white text-green-600 rounded-lg py-2 text-sm font-bold hover:bg-green-50">
                    <i class="fas fa-check mr-1"></i> Done!
                </button>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php else: ?>
<div class="card p-16 text-center">
    <div class="w-20 h-20 bg-foam rounded-full flex items-center justify-center mx-auto mb-4">
        <i class="fas fa-mug-hot text-3xl text-caramel"></i>
    </div>
    <h3 class="font-display text-xl text-espresso mb-2">Queue is empty!</h3>
    <p class="text-gray-400 mb-5">No active orders at the moment.</p>
    <a href="<?php echo e(route('orders.index')); ?>" class="btn-primary inline-flex items-center gap-2">
        <i class="fas fa-plus"></i> Create New Order
    </a>
</div>
<?php endif; ?>


<div class="fixed bottom-5 right-5 bg-espresso/90 text-white text-xs px-4 py-2 rounded-full flex items-center gap-2">
    <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
    Auto-refreshes every 30s
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// ─── Clock ───
function updateClock() {
    const now = new Date();
    document.getElementById('clock').textContent = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
}
setInterval(updateClock, 1000);
updateClock();

// ─── Elapsed time ───
function updateElapsedTimes() {
    document.querySelectorAll('.elapsed-time').forEach(el => {
        const created = new Date(el.dataset.created);
        const diff = Math.floor((Date.now() - created) / 1000);
        const m = Math.floor(diff / 60), s = diff % 60;
        el.textContent = `${m}m ${s}s ago`;
        el.parentElement.classList.toggle('text-red-500', m >= 15);
        el.parentElement.classList.toggle('text-gray-400', m < 15);
    });
}
setInterval(updateElapsedTimes, 1000);
updateElapsedTimes();

// ─── Auto refresh ───
setTimeout(() => location.reload(), 30000);

// ─── Double-click complete ───
function handleComplete(btn) {
    const card = btn.closest('.card');
    const overlay = card.querySelector('.confirm-overlay');
    overlay.classList.remove('hidden');
    overlay.classList.add('flex');
}
function cancelComplete(btn) {
    const overlay = btn.closest('.confirm-overlay');
    overlay.classList.add('hidden');
    overlay.classList.remove('flex');
}
function confirmComplete(orderId, btn) {
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Processing...';

    fetch(`/orders/${orderId}/complete`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            const card = document.getElementById(`order-card-${orderId}`);
            card.style.transform = 'scale(0.95)';
            card.style.opacity = '0';
            card.style.transition = 'all 0.4s ease';
            setTimeout(() => {
                card.remove();
                const remaining = document.querySelectorAll('[id^="order-card-"]').length;
                if (remaining === 0) location.reload();
            }, 400);
        }
    });
}

// ─── Set status ───
function setStatus(orderId, status) {
    fetch(`/orders/${orderId}/status`, {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
        body: JSON.stringify({ status })
    }).then(() => location.reload());
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\coffee-shop\resources\views/orders/queue.blade.php ENDPATH**/ ?>