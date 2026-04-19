@extends('layouts.admin')
@section('title', 'Stock')
@section('page-title', 'Stock Monitoring')
@section('page-subtitle', 'Manage product inventory levels')

@section('header-actions')
<a href="{{ route('admin.products.create') }}" class="btn-primary gap-2">
    <i class="fas fa-plus"></i> Add Product
</a>
@endsection

@section('content')

<div class="grid grid-cols-3 gap-5 mb-8">
    <div class="card p-5 border-l-4 border-green-400">
        <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Total Products</p>
        <p class="font-display text-2xl">{{ $totalProducts }}</p>
    </div>
    <div class="card p-5 border-l-4 border-orange-400">
        <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Low Stock</p>
        <p class="font-display text-2xl text-orange-500">{{ $lowStockCount }}</p>
    </div>
    <div class="card p-5 border-l-4 border-red-400">
        <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Out of Stock</p>
        <p class="font-display text-2xl text-red-500">{{ $outOfStock }}</p>
    </div>
</div>

<div class="card overflow-hidden">
    <table>
        <thead><tr>
            <th>Product</th><th>Category</th><th>Price</th>
            <th>Stock</th><th>Threshold</th><th>Status</th><th>Actions</th>
        </tr></thead>
        <tbody>
            @foreach($products as $p)
            <tr class="{{ $p->trashed() ? 'opacity-50' : '' }}">
                <td>
                    <p class="font-medium">{{ $p->name }}</p>
                    @if($p->trashed()) <span class="text-xs text-red-400">Deleted</span> @endif
                </td>
                <td><span class="status-badge badge-gray capitalize">{{ $p->category }}</span></td>
                <td class="font-semibold">₱{{ number_format($p->price, 2) }}</td>
                <td>
                    <div class="flex items-center gap-2">
                        <input type="number" value="{{ $p->stock }}" min="0"
                            class="w-20 border border-gray-200 rounded-lg px-2 py-1 text-sm text-center focus:outline-none focus:border-caramel"
                            data-product-id="{{ $p->id }}"
                            onchange="updateStock({{ $p->id }}, this.value, this)"
                            {{ $p->trashed() ? 'disabled' : '' }}>
                    </div>
                </td>
                <td class="text-gray-400">{{ $p->low_stock_threshold }}</td>
                <td>
                    @if($p->trashed())
                        <span class="status-badge badge-gray">Deleted</span>
                    @elseif($p->stock == 0)
                        <span class="status-badge badge-red">Out of Stock</span>
                    @elseif($p->stock <= $p->low_stock_threshold)
                        <span class="status-badge badge-orange">Low Stock</span>
                    @else
                        <span class="status-badge badge-green">In Stock</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.products.edit', $p->id) }}" class="text-blue-500 hover:text-blue-700 text-sm mr-2">
                        <i class="fas fa-edit"></i>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-100">{{ $products->links() }}</div>
</div>
@endsection

@push('scripts')
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
@endpush
