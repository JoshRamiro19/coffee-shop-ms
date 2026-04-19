<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Employee;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Todo;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ─── Products ───
        $products = [
            // Beverages
            ['name' => 'Espresso', 'category' => 'beverage', 'price' => 90, 'stock' => 100, 'low_stock_threshold' => 20],
            ['name' => 'Americano', 'category' => 'beverage', 'price' => 110, 'stock' => 100, 'low_stock_threshold' => 20],
            ['name' => 'Cappuccino', 'category' => 'beverage', 'price' => 130, 'stock' => 100, 'low_stock_threshold' => 20],
            ['name' => 'Latte', 'category' => 'beverage', 'price' => 140, 'stock' => 100, 'low_stock_threshold' => 20],
            ['name' => 'Caramel Macchiato', 'category' => 'beverage', 'price' => 160, 'stock' => 80, 'low_stock_threshold' => 15],
            ['name' => 'Mocha', 'category' => 'beverage', 'price' => 150, 'stock' => 80, 'low_stock_threshold' => 15],
            ['name' => 'Cold Brew', 'category' => 'beverage', 'price' => 135, 'stock' => 60, 'low_stock_threshold' => 10],
            ['name' => 'Matcha Latte', 'category' => 'beverage', 'price' => 160, 'stock' => 50, 'low_stock_threshold' => 10],
            ['name' => 'Strawberry Smoothie', 'category' => 'beverage', 'price' => 145, 'stock' => 40, 'low_stock_threshold' => 10],
            // Foods
            ['name' => 'Croissant', 'category' => 'food', 'price' => 85, 'stock' => 30, 'low_stock_threshold' => 5],
            ['name' => 'Avocado Toast', 'category' => 'food', 'price' => 175, 'stock' => 20, 'low_stock_threshold' => 5],
            ['name' => 'Blueberry Muffin', 'category' => 'food', 'price' => 80, 'stock' => 25, 'low_stock_threshold' => 5],
            ['name' => 'Club Sandwich', 'category' => 'food', 'price' => 195, 'stock' => 15, 'low_stock_threshold' => 3],
            // Snacks
            ['name' => 'Chocolate Chip Cookie', 'category' => 'snack', 'price' => 55, 'stock' => 50, 'low_stock_threshold' => 10],
            ['name' => 'Banana Bread Slice', 'category' => 'snack', 'price' => 70, 'stock' => 20, 'low_stock_threshold' => 5],
            ['name' => 'Trail Mix', 'category' => 'snack', 'price' => 65, 'stock' => 8, 'low_stock_threshold' => 10], // intentionally low
            // Merchandise
            ['name' => 'BrewHouse Tumbler', 'category' => 'merchandise', 'price' => 650, 'stock' => 15, 'low_stock_threshold' => 3],
            ['name' => 'Coffee Beans 250g', 'category' => 'merchandise', 'price' => 380, 'stock' => 2, 'low_stock_threshold' => 5], // intentionally low
        ];

        foreach ($products as $p) {
            Product::create(array_merge($p, ['is_available' => true]));
        }

        // ─── Employees ───
        $employees = [
            ['name' => 'Juan dela Cruz',    'email' => 'juan@brewhouse.com',    'role' => 'manager',  'shift' => 'full_day',  'salary' => 35000, 'hired_at' => '2022-01-10', 'phone' => '0917-100-0001'],
            ['name' => 'Maria Santos',      'email' => 'maria@brewhouse.com',   'role' => 'barista',  'shift' => 'morning',   'salary' => 22000, 'hired_at' => '2022-06-15', 'phone' => '0917-100-0002'],
            ['name' => 'Jose Reyes',        'email' => 'jose@brewhouse.com',    'role' => 'barista',  'shift' => 'afternoon', 'salary' => 22000, 'hired_at' => '2023-02-01', 'phone' => '0917-100-0003'],
            ['name' => 'Ana Gonzalez',      'email' => 'ana@brewhouse.com',     'role' => 'cashier',  'shift' => 'morning',   'salary' => 20000, 'hired_at' => '2023-04-20', 'phone' => '0917-100-0004'],
            ['name' => 'Pedro Lim',         'email' => 'pedro@brewhouse.com',   'role' => 'cashier',  'shift' => 'afternoon', 'salary' => 20000, 'hired_at' => '2023-08-05', 'phone' => '0917-100-0005'],
            ['name' => 'Sofia Ramos',       'email' => 'sofia@brewhouse.com',   'role' => 'barista',  'shift' => 'evening',   'salary' => 22000, 'hired_at' => '2024-01-15', 'phone' => '0917-100-0006'],
        ];

        foreach ($employees as $e) {
            Employee::create(array_merge($e, ['is_active' => true]));
        }

        // ─── Sample Orders ───
        $customers = ['Alex Torres', 'Bella Cruz', 'Carlo Uy', 'Diana Lim', 'Erik Santos', 'Faye Ramos'];
        $productIds = Product::pluck('id')->toArray();

        // Past completed orders (last 14 days)
        for ($day = 14; $day >= 1; $day--) {
            $date = Carbon::now()->subDays($day);
            $ordersThisDay = rand(8, 20);

            for ($i = 0; $i < $ordersThisDay; $i++) {
                $order = Order::create([
                    'customer_name' => $customers[array_rand($customers)],
                    'status'        => 'completed',
                    'completed_at'  => $date->copy()->addHours(rand(8, 20))->addMinutes(rand(0, 59)),
                    'employee_id'   => rand(1, 6),
                ]);

                $total = 0;
                $itemCount = rand(1, 3);
                $shuffled = array_slice(array_keys(array_flip($productIds)), 0, $itemCount);
                foreach ($shuffled as $pid) {
                    $product = Product::find($pid);
                    $qty = rand(1, 3);
                    $subtotal = $product->price * $qty;
                    $total += $subtotal;
                    OrderItem::create([
                        'order_id' => $order->id, 'product_id' => $pid,
                        'quantity' => $qty, 'unit_price' => $product->price, 'subtotal' => $subtotal,
                    ]);
                }
                $order->update(['total_amount' => $total]);
            }
        }

        // Today's active orders
        $activeOrders = [
            ['customer_name' => 'Rico Manalo', 'status' => 'queue'],
            ['customer_name' => 'Tina Vera', 'status' => 'preparing'],
        ];
        foreach ($activeOrders as $ao) {
            $order = Order::create(array_merge($ao, ['employee_id' => 2]));
            $pid = $productIds[array_rand($productIds)];
            $product = Product::find($pid);
            OrderItem::create([
                'order_id' => $order->id, 'product_id' => $pid,
                'quantity' => 1, 'unit_price' => $product->price, 'subtotal' => $product->price,
            ]);
            $order->update(['total_amount' => $product->price]);
        }

        // ─── Todos ───
        $todos = [
            ['title' => 'Restock coffee beans', 'priority' => 'urgent', 'status' => 'pending', 'assigned_to' => 1, 'due_date' => Carbon::tomorrow()],
            ['title' => 'Deep clean espresso machine', 'priority' => 'high', 'status' => 'in_progress', 'assigned_to' => 2, 'due_date' => Carbon::today()],
            ['title' => 'Update weekly schedule', 'priority' => 'medium', 'status' => 'pending', 'assigned_to' => 1],
            ['title' => 'Order new cup sleeves', 'priority' => 'medium', 'status' => 'completed', 'assigned_to' => 4, 'due_date' => Carbon::yesterday()],
            ['title' => 'Train new barista on latte art', 'priority' => 'high', 'status' => 'pending', 'assigned_to' => 2, 'due_date' => Carbon::now()->addDays(3)],
            ['title' => 'Review supplier invoices', 'priority' => 'low', 'status' => 'pending', 'assigned_to' => 1],
            ['title' => 'Fix broken chair at table 4', 'priority' => 'medium', 'status' => 'in_progress'],
        ];

        foreach ($todos as $t) {
            Todo::create($t);
        }
    }
}
