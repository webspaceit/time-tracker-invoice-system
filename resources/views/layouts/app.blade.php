<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Invoice Management System</title>
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('images/favicon-96x96.png') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --brand: #1D560B;
            --brand-dark: #143d08;
            --brand-light: #2a7e10;
            --brand-muted: #e8f0e5;
        }
        .btn-brand {
            background-color: var(--brand);
            border-color: var(--brand);
            color: #fff;
        }
        .btn-brand:hover, .btn-brand:focus, .btn-brand:active {
            background-color: var(--brand-dark) !important;
            border-color: var(--brand-dark) !important;
            color: #fff !important;
        }
        .btn-brand-outline {
            color: var(--brand);
            border-color: var(--brand);
            background: transparent;
        }
        .btn-brand-outline:hover {
            background-color: var(--brand);
            color: #fff;
        }
        .bg-brand { background-color: var(--brand); }
        .text-brand { color: var(--brand); }
        .border-brand { border-color: var(--brand); }
        .sidebar { min-height: 100vh; background: var(--brand-dark); }
        .sidebar-inner { min-height: 100vh; display: flex; flex-direction: column; overflow-y: auto; }
        .sidebar-nav { flex-shrink: 0; }
        .sidebar-tracker { flex-shrink: 0; }
        .sidebar-footer { flex-shrink: 0; margin-top: auto; }
        .sidebar .nav-link { color: #d4e8ce; }
        .sidebar .nav-link:hover { background: rgba(255,255,255,0.1); color: #fff; }
        .sidebar .nav-link.active { background: var(--brand); color: #fff; }
        .sidebar .btn-logout { color: #d4e8ce; }
        .sidebar .btn-logout:hover { background: #a93226; color: #fff; }
        .main-content { background: #f5f7f4; min-height: 100vh; }
        .card-header.brand-header {
            background: linear-gradient(135deg, var(--brand) 0%, var(--brand-dark) 100%);
            color: #fff;
            font-weight: 600;
            border-bottom: none;
        }
        .card-header.brand-header h5,
        .card-header.brand-header .h5 {
            color: #fff;
        }
        .stats-card { transition: transform 0.3s, box-shadow 0.3s; }
        .stats-card:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(29,86,11,0.15); }
        .editable-time { cursor: pointer; border-bottom: 1px dashed #aaa; }
        .editable-time:hover { background: #e9ecef; }
        a { color: var(--brand); }
        a:hover { color: var(--brand-light); }
        .pagination .page-item.active .page-link {
            background-color: var(--brand);
            border-color: var(--brand);
        }
        .pagination .page-link { color: var(--brand); }
        .pagination .page-link:focus { box-shadow: 0 0 0 0.2rem rgba(29,86,11,0.25); }
        .form-control:focus, .form-select:focus {
            border-color: var(--brand-light);
            box-shadow: 0 0 0 0.2rem rgba(29,86,11,0.15);
        }
        .badge.bg-brand { background-color: var(--brand); }
    </style>
    @stack('styles')
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 col-lg-2 px-0 sidebar">
                <div class="py-4 px-3 sidebar-inner d-flex flex-column">
                    <h5 class="text-white mb-4 px-2" style="letter-spacing:0.5px;">
                        <i class="fas fa-file-invoice me-2" style="color:#6ab04c;"></i>Invoice System
                    </h5>
                    <nav class="nav flex-column sidebar-nav">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                        </a>
                        <a class="nav-link {{ request()->routeIs('invoices.*') ? 'active' : '' }}" href="{{ route('invoices.index') }}">
                            <i class="fas fa-file-invoice me-2"></i> Invoices
                        </a>
                        <a class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}" href="{{ route('customers.index') }}">
                            <i class="fas fa-users me-2"></i> Customers
                        </a>
                        <a class="nav-link {{ request()->routeIs('payments.*') ? 'active' : '' }}" href="{{ route('payments.index') }}">
                            <i class="fas fa-credit-card me-2"></i> Payments
                        </a>
                        <a class="nav-link {{ request()->routeIs('projects.*') ? 'active' : '' }}" href="{{ route('projects.index') }}">
                            <i class="fas fa-folder me-2"></i> Projects
                        </a>
                        <a class="nav-link {{ request()->routeIs('time-tracker.*') ? 'active' : '' }}" href="{{ route('time-tracker.index') }}">
                            <i class="fas fa-stopwatch me-2"></i> Time Tracker
                        </a>
                    </nav>
                    @auth
                    <div class="sidebar-tracker">
                        @include('time-tracker.partials._sidebar')
                    </div>
                    <div class="border-top pt-3 mt-3 sidebar-footer" style="border-color:rgba(255,255,255,0.15) !important;">
                        <div class="text-white-50 small px-2 mb-2">
                            <i class="fas fa-user me-1"></i> {{ Auth::user()->name }}
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="nav-link btn-logout w-100 text-start border-0 bg-transparent">
                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                            </button>
                        </form>
                    </div>
                    @endauth
                </div>
            </div>
            <div class="col-md-9 col-lg-10 main-content">
                <div class="px-4 pb-4 pt-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-left:4px solid var(--brand);">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @stack('scripts')
</body>
</html>