<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'BrewHouse') — Coffee Shop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --cream: #fdf6ec;
            --espresso: #2c1810;
            --caramel: #c8833a;
            --mocha: #6b3f2a;
            --latte: #d4a574;
            --foam: #fef9f3;
        }
        body { font-family: 'DM Sans', sans-serif; background: var(--cream); }
        .font-display { font-family: 'Playfair Display', serif; }
        .bg-espresso { background-color: var(--espresso); }
        .bg-caramel { background-color: var(--caramel); }
        .bg-mocha { background-color: var(--mocha); }
        .bg-cream { background-color: var(--cream); }
        .bg-foam { background-color: var(--foam); }
        .text-espresso { color: var(--espresso); }
        .text-caramel { color: var(--caramel); }
        .text-mocha { color: var(--mocha); }
        .border-caramel { border-color: var(--caramel); }
        .hover\:bg-mocha:hover { background-color: var(--mocha); }

        .nav-link { transition: all 0.2s; }
        .nav-link:hover { color: var(--caramel); }
        .nav-link.active { color: var(--caramel); border-bottom: 2px solid var(--caramel); }

        .card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(44,24,16,0.08);
            transition: box-shadow 0.2s;
        }
        .card:hover { box-shadow: 0 4px 20px rgba(44,24,16,0.14); }

        .btn-primary {
            background: var(--caramel);
            color: white;
            padding: 0.6rem 1.4rem;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
        }
        .btn-primary:hover { background: var(--mocha); transform: translateY(-1px); }

        .btn-secondary {
            background: transparent;
            color: var(--espresso);
            padding: 0.6rem 1.4rem;
            border-radius: 10px;
            font-weight: 500;
            transition: all 0.2s;
            border: 1.5px solid #ddd;
            cursor: pointer;
        }
        .btn-secondary:hover { border-color: var(--caramel); color: var(--caramel); }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .status-completed { background: #dcfce7; color: #16a34a; }
        .status-queue     { background: #dbeafe; color: #2563eb; }
        .status-preparing { background: #fed7aa; color: #ea580c; }
        .status-pending   { background: #fef9c3; color: #ca8a04; }
        .status-cancelled { background: #fee2e2; color: #dc2626; }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            border-radius: 10px;
            color: #d4a574;
            font-weight: 500;
            transition: all 0.2s;
            text-decoration: none;
        }
        .sidebar-link:hover, .sidebar-link.active {
            background: rgba(200,131,58,0.15);
            color: var(--caramel);
        }

        /* Modal */
        .modal-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 50;
            align-items: center;
            justify-content: center;
        }
        .modal-overlay.show { display: flex; }

        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .animate-in { animation: fadeIn 0.3s ease; }
    </style>
    @stack('styles')
</head>
<body>

{{-- Top Navigation --}}
<nav class="bg-espresso shadow-lg sticky top-0 z-40">
    <div class="max-w-screen-2xl mx-auto px-6 flex items-center justify-between h-16">
        <a href="{{ route('orders.index') }}" class="flex items-center gap-3">
            <div class="w-9 h-9 bg-caramel rounded-full flex items-center justify-center">
                <i class="fas fa-coffee text-white text-sm"></i>
            </div>
            <span class="font-display text-xl text-white">BrewHouse</span>
        </a>

        <div class="flex items-center gap-1">
            <a href="{{ route('orders.index') }}" class="nav-link px-4 py-2 text-sm text-gray-300 {{ request()->routeIs('orders.index') ? 'active text-caramel' : '' }}">
                <i class="fas fa-receipt mr-1"></i> Orders
            </a>
            <a href="{{ route('orders.queue') }}" class="nav-link px-4 py-2 text-sm text-gray-300 {{ request()->routeIs('orders.queue') ? 'active text-caramel' : '' }}">
                <i class="fas fa-list-ol mr-1"></i> Queue
                @php $queueCount = \App\Models\Order::whereIn('status',['queue','preparing'])->count(); @endphp
                @if($queueCount > 0)
                    <span class="ml-1 bg-caramel text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $queueCount }}</span>
                @endif
            </a>
            <a href="{{ route('admin.dashboard') }}" class="nav-link px-4 py-2 text-sm text-gray-300 {{ request()->routeIs('admin.*') ? 'active text-caramel' : '' }}">
                <i class="fas fa-chart-bar mr-1"></i> Admin
            </a>
        </div>
    </div>
</nav>

{{-- Flash Messages --}}
@if(session('success'))
<div class="fixed top-20 right-5 z-50 bg-green-50 border border-green-200 text-green-800 px-5 py-3 rounded-xl shadow-lg animate-in flex items-center gap-2" id="flash-msg">
    <i class="fas fa-check-circle text-green-500"></i>
    {{ session('success') }}
    <button onclick="this.parentElement.remove()" class="ml-3 text-green-400 hover:text-green-600">×</button>
</div>
<script>setTimeout(() => document.getElementById('flash-msg')?.remove(), 4000);</script>
@endif

@if($errors->any())
<div class="fixed top-20 right-5 z-50 bg-red-50 border border-red-200 text-red-800 px-5 py-3 rounded-xl shadow-lg animate-in">
    @foreach($errors->all() as $error)
        <div class="flex items-center gap-2"><i class="fas fa-exclamation-circle text-red-500"></i> {{ $error }}</div>
    @endforeach
</div>
@endif

<main class="@yield('main-class', 'max-w-screen-2xl mx-auto px-6 py-8')">
    @yield('content')
</main>

@stack('scripts')
<script>
// Global CSRF helper
function csrfPost(url, data = {}) {
    return fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
        body: JSON.stringify(data)
    }).then(r => r.json());
}
</script>
</body>
</html>
