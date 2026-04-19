<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Admin'); ?> — BrewHouse</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root { --espresso:#2c1810; --caramel:#c8833a; --mocha:#6b3f2a; --latte:#d4a574; --cream:#fdf6ec; --foam:#fef9f3; }
        body { font-family:'DM Sans',sans-serif; background:#f8f4f0; }
        .font-display { font-family:'Playfair Display',serif; }
        .sidebar { background:var(--espresso); width:240px; min-height:100vh; position:fixed; top:0; left:0; padding:24px 12px; display:flex; flex-direction:column; z-index:30; }
        .main-content { margin-left:240px; min-height:100vh; }
        .card { background:white; border-radius:16px; box-shadow:0 2px 12px rgba(44,24,16,0.08); }
        .sidebar-link { display:flex; align-items:center; gap:10px; padding:10px 12px; border-radius:10px; color:#d4a574; font-size:14px; font-weight:500; transition:all 0.2s; text-decoration:none; }
        .sidebar-link:hover,.sidebar-link.active { background:rgba(200,131,58,0.18); color:#c8833a; }
        .sidebar-link i { width:18px; text-align:center; }
        .btn-primary { background:var(--caramel); color:white; padding:0.55rem 1.3rem; border-radius:10px; font-weight:600; transition:all 0.2s; border:none; cursor:pointer; font-size:14px; text-decoration:none; display:inline-flex; align-items:center; }
        .btn-primary:hover { background:var(--mocha); }
        .btn-secondary { background:transparent; color:#444; padding:0.55rem 1.3rem; border-radius:10px; font-weight:500; transition:all 0.2s; border:1.5px solid #ddd; cursor:pointer; font-size:14px; text-decoration:none; display:inline-flex; align-items:center; }
        .btn-secondary:hover { border-color:var(--caramel); color:var(--caramel); }
        .btn-danger { background:#ef4444; color:white; padding:0.5rem 1rem; border-radius:8px; font-weight:600; border:none; cursor:pointer; font-size:13px; transition:all 0.2s; }
        .btn-danger:hover { background:#dc2626; }
        .form-input { width:100%; border:1.5px solid #e5e7eb; border-radius:10px; padding:0.6rem 0.9rem; font-size:14px; transition:border-color 0.2s; outline:none; }
        .form-input:focus { border-color:var(--caramel); }
        .form-label { display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:5px; }
        .status-badge { display:inline-flex; align-items:center; padding:2px 10px; border-radius:20px; font-size:11px; font-weight:700; }
        .badge-green { background:#dcfce7; color:#16a34a; }
        .badge-red { background:#fee2e2; color:#dc2626; }
        .badge-yellow { background:#fef9c3; color:#b45309; }
        .badge-blue { background:#dbeafe; color:#2563eb; }
        .badge-purple { background:#f3e8ff; color:#7c3aed; }
        .badge-orange { background:#fed7aa; color:#ea580c; }
        .badge-gray { background:#f3f4f6; color:#6b7280; }
        .modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:50; align-items:center; justify-content:center; }
        .modal-overlay.show { display:flex; }
        @keyframes fadeIn { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }
        .animate-in { animation:fadeIn 0.25s ease; }
        table { width:100%; border-collapse:collapse; }
        th { text-align:left; padding:10px 14px; font-size:12px; font-weight:600; color:#9ca3af; text-transform:uppercase; letter-spacing:0.05em; background:#fef9f3; }
        td { padding:12px 14px; font-size:14px; border-bottom:1px solid #f3f4f6; }
        tr:hover td { background:#fef9f3; }
        tr:last-child td { border-bottom:none; }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>


<aside class="sidebar">
    <a href="<?php echo e(route('orders.index')); ?>" class="flex items-center gap-3 mb-8 px-2">
        <div class="w-9 h-9 bg-caramel rounded-full flex items-center justify-center flex-shrink-0">
            <i class="fas fa-coffee text-white text-sm"></i>
        </div>
        <span class="font-display text-xl text-white">BrewHouse</span>
    </a>

    <p class="text-xs font-bold text-gray-600 uppercase tracking-wider px-3 mb-2">Operations</p>
    <a href="<?php echo e(route('orders.index')); ?>" class="sidebar-link <?php echo e(request()->routeIs('orders.index') ? 'active' : ''); ?>">
        <i class="fas fa-receipt"></i> Orders
    </a>
    <a href="<?php echo e(route('orders.queue')); ?>" class="sidebar-link <?php echo e(request()->routeIs('orders.queue') ? 'active' : ''); ?>">
        <i class="fas fa-list-ol"></i> Queue
        <?php $qc = \App\Models\Order::whereIn('status',['queue','preparing'])->count(); ?>
        <?php if($qc > 0): ?>
        <span class="ml-auto bg-caramel text-white text-xs font-bold px-2 py-0.5 rounded-full"><?php echo e($qc); ?></span>
        <?php endif; ?>
    </a>

    <p class="text-xs font-bold text-gray-600 uppercase tracking-wider px-3 mb-2 mt-5">Admin</p>
    <a href="<?php echo e(route('admin.dashboard')); ?>" class="sidebar-link <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>">
        <i class="fas fa-chart-pie"></i> Dashboard
    </a>
    <a href="<?php echo e(route('admin.sales')); ?>" class="sidebar-link <?php echo e(request()->routeIs('admin.sales') ? 'active' : ''); ?>">
        <i class="fas fa-chart-line"></i> Sales
    </a>
    <a href="<?php echo e(route('admin.stock')); ?>" class="sidebar-link <?php echo e(request()->routeIs('admin.stock') ? 'active' : ''); ?>">
        <?php $ls = \App\Models\Product::whereRaw('stock <= low_stock_threshold')->whereNull('deleted_at')->count(); ?>
        <i class="fas fa-boxes-stacked"></i> Stock
        <?php if($ls > 0): ?>
        <span class="ml-auto bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full"><?php echo e($ls); ?></span>
        <?php endif; ?>
    </a>
    <a href="<?php echo e(route('admin.products.index')); ?>" class="sidebar-link <?php echo e(request()->routeIs('admin.products.*') ? 'active' : ''); ?>">
        <i class="fas fa-mug-hot"></i> Products
    </a>
    <a href="<?php echo e(route('admin.employees.index')); ?>" class="sidebar-link <?php echo e(request()->routeIs('admin.employees.*') ? 'active' : ''); ?>">
        <i class="fas fa-users"></i> Employees
    </a>
    <a href="<?php echo e(route('admin.todos.index')); ?>" class="sidebar-link <?php echo e(request()->routeIs('admin.todos.*') ? 'active' : ''); ?>">
        <?php $tc = \App\Models\Todo::whereIn('priority',['urgent','high'])->where('status','!=','completed')->count(); ?>
        <i class="fas fa-check-square"></i> To-Do
        <?php if($tc > 0): ?>
        <span class="ml-auto bg-orange-400 text-white text-xs font-bold px-2 py-0.5 rounded-full"><?php echo e($tc); ?></span>
        <?php endif; ?>
    </a>
</aside>


<div class="main-content">
    
    <div class="bg-white border-b border-gray-100 px-8 py-4 flex items-center justify-between sticky top-0 z-20">
        <div>
            <h1 class="font-display text-xl text-gray-800"><?php echo $__env->yieldContent('page-title', 'Admin'); ?></h1>
            <?php if (! empty(trim($__env->yieldContent('page-subtitle')))): ?>
            <p class="text-sm text-gray-400"><?php echo $__env->yieldContent('page-subtitle'); ?></p>
            <?php endif; ?>
        </div>
        <div class="flex items-center gap-3">
            <?php echo $__env->yieldContent('header-actions'); ?>
        </div>
    </div>

    
    <?php if(session('success')): ?>
    <div class="mx-8 mt-4 bg-green-50 border border-green-200 text-green-800 px-5 py-3 rounded-xl flex items-center gap-2 animate-in" id="flash-msg">
        <i class="fas fa-check-circle text-green-500"></i> <?php echo e(session('success')); ?>

        <button onclick="this.parentElement.remove()" class="ml-auto text-green-400 hover:text-green-600">×</button>
    </div>
    <script>setTimeout(() => document.getElementById('flash-msg')?.remove(), 4000);</script>
    <?php endif; ?>

    <?php if($errors->any()): ?>
    <div class="mx-8 mt-4 bg-red-50 border border-red-200 text-red-800 px-5 py-3 rounded-xl animate-in">
        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $err): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="flex items-center gap-2"><i class="fas fa-exclamation-circle text-red-400"></i> <?php echo e($err); ?></div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php endif; ?>

    <div class="p-8">
        <?php echo $__env->yieldContent('content'); ?>
    </div>
</div>

<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\coffee-shop\resources\views/layouts/admin.blade.php ENDPATH**/ ?>