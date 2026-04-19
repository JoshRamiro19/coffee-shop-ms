@extends('layouts.admin')
@section('title', 'Sales')
@section('page-title', 'Sales Monitoring')
@section('page-subtitle', 'Revenue analytics and order performance')

@section('header-actions')
<form method="GET" class="flex items-center gap-2">
    <label class="text-sm text-gray-500">Range:</label>
    <select name="range" onchange="this.form.submit()" class="form-input py-2 w-32">
        <option value="7"  {{ $range == 7  ? 'selected' : '' }}>Last 7 days</option>
        <option value="14" {{ $range == 14 ? 'selected' : '' }}>Last 14 days</option>
        <option value="30" {{ $range == 30 ? 'selected' : '' }}>Last 30 days</option>
        <option value="90" {{ $range == 90 ? 'selected' : '' }}>Last 90 days</option>
    </select>
</form>
@endsection

@section('content')

{{-- Summary KPIs --}}
<div class="grid grid-cols-3 gap-5 mb-8">
    <div class="card p-5 border-l-4 border-green-400">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Total Revenue</p>
        <p class="font-display text-3xl text-gray-800">₱{{ number_format($totalRevenue, 2) }}</p>
        <p class="text-xs text-gray-400 mt-1">Last {{ $range }} days</p>
    </div>
    <div class="card p-5 border-l-4 border-blue-400">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Total Orders</p>
        <p class="font-display text-3xl text-gray-800">{{ number_format($totalOrders) }}</p>
        <p class="text-xs text-gray-400 mt-1">Completed orders</p>
    </div>
    <div class="card p-5 border-l-4 border-purple-400">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Avg Order Value</p>
        <p class="font-display text-3xl text-gray-800">₱{{ number_format($avgOrderValue, 2) }}</p>
        <p class="text-xs text-gray-400 mt-1">Per completed order</p>
    </div>
</div>

{{-- Charts Row --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <div class="card p-6 lg:col-span-2">
        <h2 class="font-display text-lg text-gray-800 mb-5">Daily Revenue</h2>
        <canvas id="revenueChart" height="90"></canvas>
    </div>
    <div class="card p-6">
        <h2 class="font-display text-lg text-gray-800 mb-5">By Category</h2>
        <canvas id="categoryChart"></canvas>
        <div class="mt-4 space-y-2">
            @php $colors = ['#c8833a','#6b3f2a','#d4a574','#2c1810','#fdf6ec']; @endphp
            @foreach($categoryBreakdown as $i => $cat)
            <div class="flex justify-between text-sm items-center">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full" style="background:{{ $colors[$i % count($colors)] }}"></span>
                    <span class="text-gray-600 capitalize">{{ $cat->category }}</span>
                </div>
                <span class="font-semibold">₱{{ number_format($cat->total, 2) }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Top Products Table --}}
<div class="card overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h2 class="font-display text-lg text-gray-800">Top Products by Revenue</h2>
    </div>
    <table>
        <thead><tr>
            <th>#</th><th>Product</th><th>Category</th><th>Qty Sold</th><th>Revenue</th>
        </tr></thead>
        <tbody>
            @forelse($topProducts as $i => $item)
            <tr>
                <td class="text-gray-400 font-bold">{{ $i + 1 }}</td>
                <td class="font-medium">{{ $item->product->name ?? '—' }}</td>
                <td><span class="badge-gray status-badge capitalize">{{ $item->product->category ?? '—' }}</span></td>
                <td class="font-semibold">{{ number_format($item->total_qty) }}</td>
                <td class="font-bold text-green-600">₱{{ number_format($item->total_revenue, 2) }}</td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center text-gray-400 py-8">No sales data for this period.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<script>
const labels  = @json($salesData->pluck('date'));
const totals  = @json($salesData->pluck('total')).map(v => parseFloat(v));
const orders  = @json($salesData->pluck('orders')).map(v => parseInt(v));

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

const catData = @json($categoryBreakdown);
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
@endpush
