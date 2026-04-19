<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::withTrashed()
            ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->when($request->category, fn($q) => $q->where('category', $request->category))
            ->orderBy('category')->orderBy('name')
            ->paginate(15);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'                => 'required|string|max:100',
            'category'            => 'required|in:beverage,food,snack,merchandise',
            'price'               => 'required|numeric|min:0',
            'stock'               => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:0',
            'description'         => 'nullable|string',
            'is_available'        => 'boolean',
        ]);

        $data['is_available'] = $request->boolean('is_available', true);
        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully!');
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name'                => 'required|string|max:100',
            'category'            => 'required|in:beverage,food,snack,merchandise',
            'price'               => 'required|numeric|min:0',
            'stock'               => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:0',
            'description'         => 'nullable|string',
            'is_available'        => 'boolean',
        ]);

        $data['is_available'] = $request->boolean('is_available', true);
        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        $product->delete(); // Soft delete
        return redirect()->route('admin.products.index')->with('success', 'Product removed.');
    }

    public function restore($id)
    {
        Product::withTrashed()->findOrFail($id)->restore();
        return redirect()->route('admin.products.index')->with('success', 'Product restored.');
    }
}
