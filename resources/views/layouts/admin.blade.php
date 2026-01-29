<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sports Shop Admin - @yield('title')</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-bg: #f8fbff;
            --sidebar-bg: #ffffff;
            --accent-color: #3b82f6;
            --sidebar-width: 260px;
            --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--primary-bg);
            color: #1e293b;
            overflow-x: hidden;
        }

        /* Sidebar Styling */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: var(--sidebar-bg);
            border-right: 1px solid rgba(0,0,0,0.05);
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .sidebar-header {
            padding: 30px 25px;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--accent-color);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-links {
            padding: 0 15px;
        }

        .nav-item {
            list-style: none;
            margin-bottom: 5px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            color: #64748b;
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.2s ease;
            font-weight: 500;
        }

        .nav-link i {
            width: 24px;
            font-size: 1.1rem;
            margin-right: 12px;
        }

        .nav-link:hover, .nav-link.active {
            background-color: #eff6ff;
            color: var(--accent-color);
        }

        /* Main Content Styling */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 30px;
            min-height: 100vh;
        }

        .top-navbar {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 15px 25px;
            margin-bottom: 30px;
            box-shadow: var(--card-shadow);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Card Styling */
        .stat-card {
            background: #ffffff;
            border: none;
            border-radius: 20px;
            padding: 25px;
            box-shadow: var(--card-shadow);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 15px;
        }

        .bg-primary-soft { background: #eff6ff; color: #3b82f6; }
        .bg-success-soft { background: #f0fdf4; color: #22c55e; }
        .bg-warning-soft { background: #fffbeb; color: #f59e0b; }
        .bg-danger-soft { background: #fef2f2; color: #ef4444; }

        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            border: 1px solid #e2e8f0;
        }

        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            border-color: var(--accent-color);
        }

        .btn-primary {
            background-color: var(--accent-color);
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 500;
        }

        .table-glass {
            background: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
        }

        .table thead th {
            background: #f8fafc;
            padding: 15px 20px;
            font-weight: 600;
            color: #64748b;
            border: none;
        }

        .table tbody td {
            padding: 15px 20px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <i class="fas fa-running"></i>
            <span>SportsShop</span>
        </div>
        <ul class="nav-links">
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                    <i class="fas fa-box"></i> Products
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('sales.index') }}" class="nav-link {{ request()->routeIs('sales.*') ? 'active' : '' }}">
                    <i class="fas fa-shopping-cart"></i> Sales
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                    <i class="fas fa-tags"></i> Categories
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('brands.index') }}" class="nav-link {{ request()->routeIs('brands.*') ? 'active' : '' }}">
                    <i class="fas fa-building"></i> Brands
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('stocks.index') }}" class="nav-link {{ request()->routeIs('stocks.*') ? 'active' : '' }}">
                    <i class="fas fa-warehouse"></i> Stock Management
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('purchases.index') }}" class="nav-link {{ request()->routeIs('purchases.*') ? 'active' : '' }}">
                    <i class="fas fa-truck-loading"></i> Purchases
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('suppliers.index') }}" class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                    <i class="fas fa-truck"></i> Suppliers
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('expenses.index') }}" class="nav-link {{ request()->routeIs('expenses.*') ? 'active' : '' }}">
                    <i class="fas fa-receipt"></i> Expenses
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('customers.index') }}" class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i> Customers
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                    <i class="fas fa-file-invoice-dollar"></i> Business Reports
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.profile') }}" class="nav-link {{ request()->routeIs('admin.profile') ? 'active' : '' }}">
                    <i class="fas fa-user-cog"></i> Profile Settings
                </a>
            </li>
            <li class="nav-item mt-4">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="nav-link border-0 bg-transparent w-100 text-start">
                        <i class="fas fa-sign-out-alt text-danger"></i> Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <nav class="top-navbar">
            <div class="search-bar">
                <input type="text" class="form-control px-4" style="width: 300px; border-radius: 30px;" placeholder="Search anything...">
            </div>
            <div class="user-profile d-flex align-items-center">
                @php $lowStockCount = \App\Models\Stock::where('quantity', '<', 5)->count(); @endphp
                <div class="dropdown me-4">
                    <div class="position-relative cursor-pointer" data-bs-toggle="dropdown">
                        <i class="fas fa-bell fs-5 text-muted"></i>
                        @if($lowStockCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                            {{ $lowStockCount }}
                        </span>
                        @endif
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg p-3 rounded-4 mt-2" style="width: 300px;">
                        <h6 class="fw-bold mb-3">Inventory Alerts</h6>
                        @forelse(\App\Models\Stock::with('product')->where('quantity', '<', 5)->take(5)->get() as $alert)
                        <li class="mb-2">
                            <a class="dropdown-item rounded-3 py-2 px-3 bg-light" href="{{ route('stocks.index') }}">
                                <div class="fw-bold small">{{ $alert->product->title }}</div>
                                <div class="text-danger small">Critical: Only {{ $alert->quantity }} left!</div>
                            </a>
                        </li>
                        @empty
                        <li class="text-center text-muted py-3">No critical alerts</li>
                        @endforelse
                        <li><hr class="dropdown-divider opacity-10"></li>
                        <li><a class="dropdown-item text-center small text-primary fw-bold" href="{{ route('stocks.index') }}">View All Stock</a></li>
                    </ul>
                </div>

                <div class="me-3 text-end d-none d-md-block">
                    <div class="fw-bold">{{ auth()->user()->name }}</div>
                    <div class="text-muted small">Super Admin</div>
                </div>
                <div class="dropdown">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=eff6ff&color=3b82f6" class="rounded-circle cursor-pointer" width="45" height="45" data-bs-toggle="dropdown">
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg p-2 rounded-4 mt-2">
                        <li><a class="dropdown-item rounded-3" href="{{ route('admin.profile') }}"><i class="fas fa-user-circle me-2 opacity-50"></i> Profile</a></li>
                        <li><hr class="dropdown-divider opacity-10"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item rounded-3 text-danger"><i class="fas fa-sign-out-alt me-2 opacity-50"></i> Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        @yield('content')
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
