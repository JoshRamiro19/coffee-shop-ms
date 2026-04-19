<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Employee;
use App\Models\Todo;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        $thisWeek = Carbon::now()->startOfWeek();

        // Sales overview
        $todaySales    = Order::completed()->whereDate('completed_at', $today)->sum('total_amount');
        $weeklySales   = Order::completed()->where('completed_at', '>=', $thisWeek)->sum('total_amount');
        $monthlySales  = Order::completed()->where('completed_at', '>=', $thisMonth)->sum('total_amount');
        $todayOrders   = Order::completed()->whereDate('completed_at', $today)->count();

        // Queue stats
        $queueCount    = Order::whereIn('status', ['queue', 'preparing'])->count();
        $pendingCount  = Order::where('status', 'pending')->count();

        // Top products this month
        $topProducts = OrderItem::with('product')
            ->whereHas('order', fn($q) => $q->completed()->where('completed_at', '>=', $thisMonth))
            ->select('product_id', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(subtotal) as total_revenue'))
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // Daily sales last 7 days
        $dailySales = Order::completed()
            ->where('completed_at', '>=', Carbon::now()->subDays(6)->startOfDay())
            ->select(DB::raw('DATE(completed_at) as date'), DB::raw('SUM(total_amount) as total'), DB::raw('COUNT(*) as orders'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Low stock products
        $lowStock = Product::whereRaw('stock <= low_stock_threshold')->whereNull('deleted_at')->get();

        // Todo stats
        $urgentTodos = Todo::whereIn('priority', ['urgent', 'high'])->where('status', '!=', 'completed')->count();

        // Active employees
        $activeEmployees = Employee::where('is_active', true)->count();

        // Recent orders
        $recentOrders = Order::with('items')->orderByDesc('created_at')->limit(10)->get();

        return view('admin.dashboard', compact(
            'todaySales', 'weeklySales', 'monthlySales', 'todayOrders',
            'queueCount', 'pendingCount', 'topProducts', 'dailySales',
            'lowStock', 'urgentTodos', 'activeEmployees', 'recentOrders'
        ));
    }

    public function sales(Request $request)
    {
        $range = $request->get('range', '30');
        $startDate = Carbon::now()->subDays((int)$range)->startOfDay();

        $salesData = Order::completed()
            ->where('completed_at', '>=', $startDate)
            ->select(DB::raw('DATE(completed_at) as date'), DB::raw('SUM(total_amount) as total'), DB::raw('COUNT(*) as orders'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $topProducts = OrderItem::with('product')
            ->whereHas('order', fn($q) => $q->completed()->where('completed_at', '>=', $startDate))
            ->select('product_id', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(subtotal) as total_revenue'))
            ->groupBy('product_id')
            ->orderByDesc('total_revenue')
            ->limit(10)
            ->get();

        $categoryBreakdown = OrderItem::with('product')
            ->whereHas('order', fn($q) => $q->completed()->where('completed_at', '>=', $startDate))
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select('products.category', DB::raw('SUM(order_items.subtotal) as total'))
            ->groupBy('products.category')
            ->orderByDesc('total')
            ->get();

        $totalRevenue = $salesData->sum('total');
        $totalOrders  = $salesData->sum('orders');
        $avgOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        return view('admin.sales', compact(
            'salesData', 'topProducts', 'categoryBreakdown',
            'totalRevenue', 'totalOrders', 'avgOrderValue', 'range'
        ));
    }

    public function stock()
    {
        $products = Product::withTrashed()
            ->select('*', DB::raw('(stock <= low_stock_threshold) as is_low'))
            ->orderByRaw('(stock <= low_stock_threshold) DESC')
            ->orderBy('stock')
            ->paginate(20);

        $lowStockCount  = Product::whereRaw('stock <= low_stock_threshold')->whereNull('deleted_at')->count();
        $outOfStock     = Product::where('stock', 0)->whereNull('deleted_at')->count();
        $totalProducts  = Product::whereNull('deleted_at')->count();

        return view('admin.stock', compact('products', 'lowStockCount', 'outOfStock', 'totalProducts'));
    }

    public function updateStock(Request $request, Product $product)
    {
        $request->validate(['stock' => 'required|integer|min:0']);
        $product->update(['stock' => $request->stock]);
        return response()->json(['success' => true, 'stock' => $product->stock]);
    }
}
