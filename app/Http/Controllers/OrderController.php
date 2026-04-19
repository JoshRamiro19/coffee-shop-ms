<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $completed = Order::with('items.product')
            ->where('status', 'completed')
            ->orderByDesc('completed_at')
            ->paginate(10, ['*'], 'completed_page');

        $active = Order::with('items.product')
            ->whereIn('status', ['pending', 'queue', 'preparing'])
            ->orderByDesc('created_at')
            ->get();

        $products = Product::available()->orderBy('category')->orderBy('name')->get()->groupBy('category');

        return view('orders.index', compact('completed', 'active', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name'     => 'required|string|max:100',
            'items'             => 'required|array|min:1',
            'items.*.product_id'=> 'required|exists:products,id',
            'items.*.quantity'  => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request) {
            $order = Order::create([
                'customer_name' => $request->customer_name,
                'status'        => 'queue',
                'notes'         => $request->notes,
            ]);

            $total = 0;
            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                $subtotal = $product->price * $item['quantity'];
                $total += $subtotal;

                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $product->id,
                    'quantity'   => $item['quantity'],
                    'unit_price' => $product->price,
                    'subtotal'   => $subtotal,
                ]);

                // Decrement stock
                $product->decrement('stock', $item['quantity']);
            }

            $order->update(['total_amount' => $total]);
        });

        return redirect()->route('orders.index')->with('success', 'Order placed successfully!');
    }

    public function queue()
    {
        $orders = Order::with('items.product')
            ->whereIn('status', ['queue', 'preparing'])
            ->orderByDesc('created_at')
            ->get();

        return view('orders.queue', compact('orders'));
    }

    public function complete(Order $order)
    {
        $order->update([
            'status'       => 'completed',
            'completed_at' => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Order marked as completed!']);
    }

    public function cancel(Order $order)
    {
        if ($order->status === 'completed') {
            return response()->json(['success' => false, 'message' => 'Cannot cancel a completed order.']);
        }

        // Restore stock
        foreach ($order->items as $item) {
            $item->product->increment('stock', $item->quantity);
        }

        $order->update(['status' => 'cancelled']);

        return response()->json(['success' => true, 'message' => 'Order cancelled.']);
    }

    public function updateStatus(Order $order, Request $request)
    {
        $request->validate(['status' => 'required|in:pending,queue,preparing,completed,cancelled']);
        $order->update(['status' => $request->status]);
        return response()->json(['success' => true]);
    }
}
