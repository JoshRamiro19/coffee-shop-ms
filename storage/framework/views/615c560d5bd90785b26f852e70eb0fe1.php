<?php $__env->startSection('title', 'Orders — BrewHouse'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex gap-6">

    
    <div class="flex-1 min-w-0">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h1 class="font-display text-2xl text-espresso">Orders</h1>
                <p class="text-sm text-gray-500 mt-0.5">Today's activity & history</p>
            </div>
            <button onclick="openNewOrderModal()" class="btn-primary flex items-center gap-2">
                <i class="fas fa-plus"></i> New Order
            </button>
        </div>

        
        <?php if($active->count()): ?>
        <div class="mb-6">
            <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Active Orders</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                <?php $__currentLoopData = $active; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="card p-4 border-l-4 <?php echo e($order->status === 'preparing' ? 'border-orange-400' : ($order->status === 'queue' ? 'border-blue-400' : 'border-yellow-400')); ?>">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <p class="font-semibold text-espresso"><?php echo e($order->customer_name); ?></p>
                            <p class="text-xs text-gray-400"><?php echo e($order->order_number); ?></p>
                        </div>
                        <span class="status-badge status-<?php echo e($order->status); ?>">
                            <?php echo e($order->status_label); ?>

                        </span>
                    </div>
                    <div class="text-sm text-gray-600 mb-3">
                        <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex justify-between">
                            <span><?php echo e($item->quantity); ?>x <?php echo e($item->product->name); ?></span>
                            <span class="text-gray-400">₱<?php echo e(number_format($item->subtotal, 2)); ?></span>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <div class="flex items-center justify-between border-t pt-2">
                        <span class="font-bold text-espresso">₱<?php echo e(number_format($order->total_amount, 2)); ?></span>
                        <div class="flex gap-2">
                            <select onchange="updateStatus(<?php echo e($order->id); ?>, this.value)" class="text-xs border rounded-lg px-2 py-1 text-gray-600">
                                <option value="pending"   <?php echo e($order->status === 'pending'   ? 'selected' : ''); ?>>Pending</option>
                                <option value="queue"     <?php echo e($order->status === 'queue'     ? 'selected' : ''); ?>>Queue</option>
                                <option value="preparing" <?php echo e($order->status === 'preparing' ? 'selected' : ''); ?>>Preparing</option>
                            </select>
                            <button onclick="cancelOrder(<?php echo e($order->id); ?>)" class="text-red-400 hover:text-red-600 text-xs px-2 py-1 border border-red-200 rounded-lg">Cancel</button>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <?php endif; ?>

        
        <div>
            <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Completed Orders</h2>
            <?php if($completed->count()): ?>
            <div class="card overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-foam border-b border-gray-100">
                            <th class="text-left px-4 py-3 text-gray-500 font-medium">Order #</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-medium">Customer</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-medium">Items</th>
                            <th class="text-right px-4 py-3 text-gray-500 font-medium">Total</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-medium">Completed</th>
                            <th class="text-left px-4 py-3 text-gray-500 font-medium">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $completed; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="border-b border-gray-50 hover:bg-foam transition-colors">
                            <td class="px-4 py-3 font-mono text-xs text-gray-400"><?php echo e($order->order_number); ?></td>
                            <td class="px-4 py-3 font-medium text-espresso"><?php echo e($order->customer_name); ?></td>
                            <td class="px-4 py-3 text-gray-500">
                                <?php echo e($order->items->sum('quantity')); ?> item(s):
                                <?php echo e($order->items->map(fn($i) => $i->product->name)->implode(', ')); ?>

                            </td>
                            <td class="px-4 py-3 text-right font-semibold text-espresso">₱<?php echo e(number_format($order->total_amount, 2)); ?></td>
                            <td class="px-4 py-3 text-gray-400"><?php echo e($order->completed_at?->format('M d, g:ia')); ?></td>
                            <td class="px-4 py-3"><span class="status-badge status-completed">Completed</span></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
                <div class="px-4 py-3 border-t border-gray-100">
                    <?php echo e($completed->links()); ?>

                </div>
            </div>
            <?php else: ?>
            <div class="card p-10 text-center text-gray-400">
                <i class="fas fa-coffee text-3xl mb-3 block"></i>
                No completed orders yet today.
            </div>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="w-72 shrink-0">
        <div class="card p-4 sticky top-24">
            <h2 class="font-display text-lg text-espresso mb-4">Menu</h2>
            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="mb-4">
                <p class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-2"><?php echo e(ucfirst($category)); ?></p>
                <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                    <div>
                        <p class="text-sm font-medium text-espresso"><?php echo e($product->name); ?></p>
                        <p class="text-xs text-gray-400">Stock: <?php echo e($product->stock); ?></p>
                    </div>
                    <span class="text-sm font-semibold text-caramel">₱<?php echo e(number_format($product->price, 2)); ?></span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <button onclick="openNewOrderModal()" class="btn-primary w-full mt-2 flex items-center justify-center gap-2">
                <i class="fas fa-plus"></i> New Order
            </button>
        </div>
    </div>
</div>


<div class="modal-overlay" id="orderModal">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-4 animate-in max-h-[90vh] flex flex-col">
        
        <div id="step1" class="p-6">
            <div class="flex items-center justify-between mb-5">
                <h2 class="font-display text-xl text-espresso">New Order</h2>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 text-xl">×</button>
            </div>
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Customer Name</label>
                <input type="text" id="customerName" placeholder="e.g. Maria Santos"
                    class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-lg focus:outline-none focus:border-caramel transition-colors"
                    onkeydown="if(event.key==='Enter') goToStep2()">
            </div>
            <div class="flex justify-end gap-3">
                <button onclick="closeModal()" class="btn-secondary">Cancel</button>
                <button onclick="goToStep2()" class="btn-primary">Next <i class="fas fa-arrow-right ml-1"></i></button>
            </div>
        </div>

        
        <div id="step2" class="hidden flex flex-col h-full overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="font-display text-xl text-espresso">Select Items</h2>
                        <p class="text-sm text-gray-500" id="customerLabel"></p>
                    </div>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 text-xl">×</button>
                </div>
            </div>

            <div class="flex gap-4 p-6 overflow-hidden" style="min-height:0;flex:1">
                
                <div class="flex-1 overflow-y-auto pr-2">
                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="mb-4">
                        <p class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-2 sticky top-0 bg-white py-1"><?php echo e(ucfirst($category)); ?></p>
                        <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-center justify-between p-3 rounded-xl hover:bg-foam cursor-pointer border border-transparent hover:border-caramel/20 transition-all mb-1"
                             onclick="addItem(<?php echo e($product->id); ?>, '<?php echo e(addslashes($product->name)); ?>', <?php echo e($product->price); ?>)">
                            <div>
                                <p class="font-medium text-espresso text-sm"><?php echo e($product->name); ?></p>
                                <p class="text-xs text-gray-400">Stock: <?php echo e($product->stock); ?></p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-caramel font-semibold text-sm">₱<?php echo e(number_format($product->price, 2)); ?></span>
                                <div class="w-7 h-7 bg-caramel rounded-full flex items-center justify-center text-white text-xs">+</div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                
                <div class="w-56 shrink-0 flex flex-col">
                    <p class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Order Summary</p>
                    <div id="cartItems" class="flex-1 overflow-y-auto space-y-2 min-h-0">
                        <p id="emptyCart" class="text-sm text-gray-400 text-center py-4">No items yet</p>
                    </div>
                    <div class="border-t border-gray-100 pt-3 mt-3">
                        <div class="flex justify-between font-bold text-espresso mb-1">
                            <span>Total</span>
                            <span id="cartTotal">₱0.00</span>
                        </div>
                        <textarea id="orderNotes" placeholder="Special instructions..." rows="2"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-xs mt-2 focus:outline-none focus:border-caramel resize-none"></textarea>
                    </div>
                </div>
            </div>

            <div class="p-6 border-t border-gray-100 flex gap-3">
                <button onclick="backToStep1()" class="btn-secondary"><i class="fas fa-arrow-left mr-1"></i> Back</button>
                <button onclick="submitOrder()" class="btn-primary flex-1">
                    <i class="fas fa-check mr-2"></i> Confirm Order
                </button>
            </div>
        </div>
    </div>
</div>


<div class="modal-overlay" id="cancelModal">
    <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-sm mx-4 animate-in">
        <h3 class="font-display text-lg text-espresso mb-2">Cancel Order?</h3>
        <p class="text-gray-500 text-sm mb-5">This will cancel the order and restore stock. This action cannot be undone.</p>
        <div class="flex gap-3">
            <button onclick="closeCancelModal()" class="btn-secondary flex-1">Keep Order</button>
            <button onclick="confirmCancel()" class="flex-1 bg-red-500 text-white rounded-xl py-2.5 font-semibold hover:bg-red-600 transition-colors">Yes, Cancel</button>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
let cart = {};
let cancelOrderId = null;

// ─── Modal ───
function openNewOrderModal() {
    document.getElementById('orderModal').classList.add('show');
    document.getElementById('customerName').focus();
}
function closeModal() {
    document.getElementById('orderModal').classList.remove('show');
    resetModal();
}
function resetModal() {
    document.getElementById('customerName').value = '';
    document.getElementById('step1').classList.remove('hidden');
    document.getElementById('step2').classList.add('hidden');
    cart = {};
    renderCart();
}
function goToStep2() {
    const name = document.getElementById('customerName').value.trim();
    if (!name) { document.getElementById('customerName').focus(); return; }
    document.getElementById('customerLabel').textContent = 'For: ' + name;
    document.getElementById('step1').classList.add('hidden');
    document.getElementById('step2').classList.remove('hidden');
}
function backToStep1() {
    document.getElementById('step2').classList.add('hidden');
    document.getElementById('step1').classList.remove('hidden');
}

// ─── Cart ───
function addItem(id, name, price) {
    if (cart[id]) {
        cart[id].qty++;
    } else {
        cart[id] = { name, price, qty: 1 };
    }
    renderCart();
}
function removeItem(id) {
    delete cart[id];
    renderCart();
}
function changeQty(id, delta) {
    cart[id].qty = Math.max(1, cart[id].qty + delta);
    renderCart();
}
function renderCart() {
    const container = document.getElementById('cartItems');
    const empty = document.getElementById('emptyCart');
    const keys = Object.keys(cart);
    if (keys.length === 0) {
        container.innerHTML = '<p id="emptyCart" class="text-sm text-gray-400 text-center py-4">No items yet</p>';
        document.getElementById('cartTotal').textContent = '₱0.00';
        return;
    }
    let total = 0;
    container.innerHTML = keys.map(id => {
        const item = cart[id];
        total += item.price * item.qty;
        return `<div class="bg-foam rounded-lg p-2 text-xs">
            <div class="flex justify-between items-start mb-1">
                <span class="font-medium text-espresso leading-tight">${item.name}</span>
                <button onclick="removeItem(${id})" class="text-red-400 hover:text-red-600 ml-1">×</button>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-1">
                    <button onclick="changeQty(${id}, -1)" class="w-5 h-5 bg-white border rounded text-xs font-bold">-</button>
                    <span class="w-6 text-center font-semibold">${item.qty}</span>
                    <button onclick="changeQty(${id}, 1)" class="w-5 h-5 bg-white border rounded text-xs font-bold">+</button>
                </div>
                <span class="font-semibold text-caramel">₱${(item.price * item.qty).toFixed(2)}</span>
            </div>
        </div>`;
    }).join('');
    document.getElementById('cartTotal').textContent = '₱' + total.toFixed(2);
}

// ─── Submit Order ───
function submitOrder() {
    const customerName = document.getElementById('customerName').value.trim();
    const keys = Object.keys(cart);
    if (!customerName || keys.length === 0) return;

    const items = keys.map(id => ({ product_id: parseInt(id), quantity: cart[id].qty }));
    const notes = document.getElementById('orderNotes').value;

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '<?php echo e(route("orders.store")); ?>';
    form.innerHTML = `<input name="_token" value="<?php echo e(csrf_token()); ?>">
        <input name="customer_name" value="${customerName}">
        <input name="notes" value="${notes}">
        ${items.map((item,i) => `<input name="items[${i}][product_id]" value="${item.product_id}">
        <input name="items[${i}][quantity]" value="${item.quantity}">`).join('')}`;
    document.body.appendChild(form);
    form.submit();
}

// ─── Status Update ───
function updateStatus(orderId, status) {
    fetch(`/orders/${orderId}/status`, {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
        body: JSON.stringify({ status })
    }).then(() => location.reload());
}

// ─── Cancel ───
function cancelOrder(id) {
    cancelOrderId = id;
    document.getElementById('cancelModal').classList.add('show');
}
function closeCancelModal() {
    document.getElementById('cancelModal').classList.remove('show');
    cancelOrderId = null;
}
function confirmCancel() {
    if (!cancelOrderId) return;
    fetch(`/orders/${cancelOrderId}/cancel`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }
    }).then(r => r.json()).then(d => {
        if (d.success) location.reload();
        else alert(d.message);
    });
}

// Close modal on backdrop click
document.getElementById('orderModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
document.getElementById('cancelModal').addEventListener('click', function(e) {
    if (e.target === this) closeCancelModal();
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\coffee-shop\resources\views/orders/index.blade.php ENDPATH**/ ?>