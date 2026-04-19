@extends('layouts.admin')
@section('title', 'Products')
@section('page-title', 'Products')
@section('page-subtitle', 'Manage your menu items')

@section('header-actions')
<a href="{{ route('admin.products.create') }}" class="btn-primary gap-2">
    <i class="fas fa-plus"></i> Add Product
</a>
@endsection

@section('content')
<div class="card overflow-hidden">
    {{-- Filters --}}
    <div class="p-5 border-b border-gray-100 flex gap-3 flex-wrap items-center">
        <form method="GET" class="flex gap-3 flex-1 flex-wrap">
            <input type="text" name="search" placeholder="Search products..." value="{{ request('search') }}"
                class="form-input py-2 w-56">
            <select name="category" class="form-input py-2 w-36">
                <option value="">All Categories</option>
                @foreach(['beverage','food','snack','merchandise'] as $cat)
                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-primary py-2">Filter</button>
            @if(request('search') || request('category'))
            <a href="{{ route('admin.products.index') }}" class="btn-secondary py-2">Clear</a>
            @endif
        </form>
    </div>

    <table>
        <thead><tr>
            <th>Product</th><th>Category</th><th>Price</th><th>Stock</th><th>Available</th><th>Status</th><th class="text-right">Actions</th>
        </tr></thead>
        <tbody>
            @forelse($products as $p)
            <tr class="{{ $p->trashed() ? 'opacity-60 bg-red-50/30' : '' }}">
                <td>
                    <div>
                        <p class="font-medium text-gray-800">{{ $p->name }}</p>
                        @if($p->description)<p class="text-xs text-gray-400 mt-0.5 truncate max-w-xs">{{ $p->description }}</p>@endif
                    </div>
                </td>
                <td><span class="status-badge badge-gray capitalize">{{ $p->category }}</span></td>
                <td class="font-semibold text-green-600">₱{{ number_format($p->price, 2) }}</td>
                <td>
                    <span class="{{ $p->stock == 0 ? 'text-red-600 font-bold' : ($p->stock <= $p->low_stock_threshold ? 'text-orange-500 font-semibold' : 'text-gray-700') }}">
                        {{ $p->stock }}
                    </span>
                </td>
                <td>
                    @if($p->is_available)
                        <span class="status-badge badge-green">Yes</span>
                    @else
                        <span class="status-badge badge-red">No</span>
                    @endif
                </td>
                <td>
                    @if($p->trashed())
                        <span class="status-badge badge-red">Deleted</span>
                    @elseif($p->stock == 0)
                        <span class="status-badge badge-red">Out of Stock</span>
                    @elseif($p->stock <= $p->low_stock_threshold)
                        <span class="status-badge badge-orange">Low Stock</span>
                    @else
                        <span class="status-badge badge-green">Active</span>
                    @endif
                </td>
                <td class="text-right">
                    @if($p->trashed())
                    <form action="{{ route('admin.products.restore', $p->id) }}" method="POST" class="inline">
                        @csrf
                        <button class="text-green-500 hover:text-green-700 text-sm px-2 py-1 border border-green-200 rounded-lg">
                            <i class="fas fa-undo mr-1"></i> Restore
                        </button>
                    </form>
                    @else
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.products.edit', $p) }}" class="text-blue-500 hover:text-blue-700 text-sm px-2 py-1 border border-blue-200 rounded-lg">
                            <i class="fas fa-edit mr-1"></i> Edit
                        </a>
                        <form action="{{ route('admin.products.destroy', $p) }}" method="POST" onsubmit="return confirm('Remove this product? It will be soft-deleted.')">
                            @csrf @method('DELETE')
                            <button class="btn-danger text-xs px-2 py-1">
                                <i class="fas fa-trash mr-1"></i> Remove
                            </button>
                        </form>
                    </div>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center text-gray-400 py-10">No products found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-100">{{ $products->links() }}</div>
</div>
@endsection
