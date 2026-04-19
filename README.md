# ☕ BrewHouse — Coffee Shop Management System

A full-featured **Laravel 11** Coffee Shop POS & Management System with order management, queue display, sales analytics, stock monitoring, employee management, and task tracking.

---

## ✨ Features

### 🧾 POS / Order Interface (`/`)
- **Priority display** of active/in-queue orders at the top
- Full **order history** table with status badges
- **New Order button** → pop-up modal asks for customer name first
- Step 2: browse categorized menu, add items to cart with quantity controls
- **Confirm / Cancel** in modal before placing order
- Order goes directly to **Queue** on confirmation

### 🔄 Queue Interface (`/queue`)
- Live queue of all orders with `queue` or `preparing` status
- Shows customer name, order number, items, total, and **elapsed time** (turns red after 15 min)
- **Start Preparing** button for queue → preparing
- **Complete Order** button with **double-click confirmation overlay** to prevent accidents
- Completed orders animate out; page auto-refreshes every 30s

### 📊 Admin Dashboard (`/admin`)
- KPI cards: today's sales, weekly, monthly, live queue count
- **Daily sales bar chart** (Chart.js)
- **Top 5 products** by quantity this month
- Low stock alerts panel
- Recent orders list

### 📈 Sales Monitoring (`/admin/sales`)
- Filterable by 7 / 14 / 30 / 90 day range
- **Line chart** of daily revenue
- **Doughnut chart** of revenue by category
- Top products table with qty sold & revenue

### 📦 Stock Monitoring (`/admin/stock`)
- All products with inline editable stock quantities
- Color-coded: green (ok), orange (low), red (out of stock)
- Live AJAX stock update (no page reload)

### 🫖 Product Management (`/admin/products`)
- Full CRUD: add, edit, update, soft delete, **restore**
- Fields: name, category, price, stock, low-stock threshold, description, availability toggle
- Search & filter by name/category

### 👥 Employee Management (`/admin/employees`)
- Full CRUD with soft delete & restore
- Fields: name, email, phone, role (barista/cashier/manager/admin), shift, salary, hire date, active status
- Role color-coded badges
- Search & filter by name/role

### ✅ To-Do List (`/admin/todos`)
- Task management with priority (urgent/high/medium/low) and status (pending/in_progress/completed)
- Assign to employees, set due dates
- Overdue detection with visual warning
- Inline status dropdown (AJAX update)
- Add/Edit tasks via modal (no page reload)
- Stats overview: total, pending, in progress, completed

---

## 🛠 Tech Stack

| Layer     | Technology                          |
|-----------|-------------------------------------|
| Backend   | Laravel 11, PHP 8.2+                |
| Database  | SQLite (dev) / MySQL (prod)         |
| Frontend  | Blade templates + Tailwind CSS CDN  |
| Charts    | Chart.js 4                          |
| Icons     | Font Awesome 6                      |
| Fonts     | Playfair Display + DM Sans          |

---

## 🚀 Installation

### Prerequisites
- PHP 8.2+
- Composer
- Node.js (optional, for asset building)

### 1. Clone / extract the project

```bash
git clone <repo-url> brewhouse
cd brewhouse
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Configure environment

```bash
cp .env.example .env
php artisan key:generate
```

**For SQLite (default, easiest):**
```bash
touch database/database.sqlite
```

**For MySQL (production):**
Edit `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=brewhouse
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 4. Run migrations + seed

```bash
php artisan migrate --seed
```

This seeds:
- 18 sample products (beverages, food, snacks, merchandise)
- 6 employees (manager, baristas, cashiers)
- 14 days of completed order history
- 2 active queue orders
- 7 sample tasks

### 5. Start the development server

```bash
php artisan serve
```

Open **http://localhost:8000** in your browser.

---

## 📂 Project Structure

```
app/
├── Http/Controllers/
│   ├── OrderController.php          # POS + queue logic
│   └── Admin/
│       ├── DashboardController.php  # Dashboard, sales, stock
│       ├── ProductController.php
│       ├── EmployeeController.php
│       └── TodoController.php
├── Models/
│   ├── Order.php
│   ├── OrderItem.php
│   ├── Product.php
│   ├── Employee.php
│   └── Todo.php

database/
├── migrations/
│   ├── ..._create_products_table.php
│   ├── ..._create_orders_table.php
│   └── ..._create_employees_todos_table.php
└── seeders/
    └── DatabaseSeeder.php

resources/views/
├── layouts/
│   ├── app.blade.php       # POS layout (top nav)
│   └── admin.blade.php     # Admin layout (sidebar)
├── orders/
│   ├── index.blade.php     # Main POS interface
│   └── queue.blade.php     # Queue display
└── admin/
    ├── dashboard.blade.php
    ├── sales.blade.php
    ├── stock.blade.php
    ├── products/           # index, create, edit, _form
    ├── employees/          # index, create, edit, _form
    └── todos/              # index

routes/
└── web.php
```

---

## 🌐 Navigation

| URL                        | Page                         |
|----------------------------|------------------------------|
| `/`                        | POS Order Interface          |
| `/queue`                   | Kitchen Queue Display        |
| `/admin`                   | Admin Dashboard              |
| `/admin/sales`             | Sales Analytics              |
| `/admin/stock`             | Stock Monitoring             |
| `/admin/products`          | Product Management           |
| `/admin/employees`         | Employee Management          |
| `/admin/todos`             | To-Do List                   |

---

## 💡 Notes

- **Currency**: Philippine Peso (₱). To change, search and replace `₱` in views.
- **Timezone**: Set to `Asia/Manila` in `.env` (`APP_TIMEZONE`).
- **Soft Deletes**: Products and Employees use soft deletes. Deleted records show a "Restore" button.
- **Stock Decrement**: Placing an order automatically decrements product stock. Cancelling restores it.
- **Auto-refresh**: Queue page reloads every 30 seconds automatically.

---

## 🎨 Design System

- **Primary color**: Caramel `#c8833a`
- **Dark accent**: Espresso `#2c1810`
- **Background**: Cream `#fdf6ec`
- **Display font**: Playfair Display
- **Body font**: DM Sans
