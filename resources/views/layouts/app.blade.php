<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Library Management System')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --peach-light: #AB886D;
            --peach-medium: #AB886D;
            --rose-light: #493628;
            --rose-medium: #493628;
        }
        
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--rose-medium) 0%, var(--rose-light) 100%);
            box-shadow: 2px 0 15px rgba(192, 132, 151, 0.2);
        }
        
        .sidebar .nav-link {
            color: rgba(255,255,255,0.95);
            padding: 15px 20px;
            border-radius: 12px;
            margin: 5px 15px;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: rgba(255,255,255,0.25);
            color: white;
            transform: translateX(8px);
            box-shadow: 0 5px 15px rgba(255,255,255,0.1);
        }
        
        .main-content {
            background: linear-gradient(135deg, #fff5f3 0%, #fef7f7 100%);
            min-height: 100vh;
        }
        
        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(192, 132, 151, 0.1);
            transition: all 0.3s ease;
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(10px);
        }
        
        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(192, 132, 151, 0.15);
        }
        
        .stat-card {
            background: linear-gradient(135deg, var(--peach-medium) 0%, var(--rose-light) 100%);
            color: white;
            border-radius: 25px;
            border: 2px solid rgba(255,255,255,0.2);
        }
        
        .stat-card.books {
            background: linear-gradient(135deg, var(--peach-light) 0%, var(--peach-medium) 100%);
        }
        
        .stat-card.members {
            background: linear-gradient(135deg, var(--peach-medium) 0%, var(--rose-light) 100%);
        }
        
        .stat-card.borrowed {
            background: linear-gradient(135deg, var(--rose-light) 0%, var(--rose-medium) 100%);
        }
        
        .stat-card.overdue {
            background: linear-gradient(135deg, var(--rose-medium) 0%, #a67c8a 100%);
        }
        
        .btn-gradient {
            background: linear-gradient(135deg, var(--rose-light) 0%, var(--rose-medium) 100%);
            border: none;
            color: white;
            padding: 12px 35px;
            border-radius: 30px;
            transition: all 0.3s ease;
            font-weight: 500;
            box-shadow: 0 4px 15px rgba(241, 149, 155, 0.3);
        }
        
        .btn-gradient:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(241, 149, 155, 0.4);
            color: white;
        }
        
        .btn-peach {
            background: linear-gradient(135deg, var(--peach-light) 0%, var(--peach-medium) 100%);
            border: none;
            color: white;
            padding: 10px 30px;
            border-radius: 25px;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .btn-peach:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(253, 188, 180, 0.4);
            color: white;
        }
        
        .page-title {
            background: linear-gradient(135deg, var(--rose-light) 0%, var(--rose-medium) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: bold;
        }
        
        .badge-custom {
            background: linear-gradient(135deg, var(--peach-medium) 0%, var(--rose-light) 100%);
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 500;
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(253, 188, 180, 0.1);
        }
        
        .alert-success {
            background: linear-gradient(135deg, rgba(253, 188, 180, 0.2) 0%, rgba(248, 173, 157, 0.2) 100%);
            border: 1px solid var(--peach-medium);
            color: var(--rose-medium);
        }
        
        .alert-danger {
            background: linear-gradient(135deg, rgba(192, 132, 151, 0.2) 0%, rgba(166, 124, 138, 0.2) 100%);
            border: 1px solid var(--rose-medium);
            color: #8b5a6b;
        }
        
        .form-control:focus {
            border-color: var(--peach-medium);
            box-shadow: 0 0 0 0.2rem rgba(248, 173, 157, 0.25);
        }
        
        .form-select:focus {
            border-color: var(--peach-medium);
            box-shadow: 0 0 0 0.2rem rgba(248, 173, 157, 0.25);
        }
        
        .detail-card {
            background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(253, 188, 180, 0.1) 100%);
            border: 1px solid rgba(248, 173, 157, 0.3);
        }
        
        .info-badge {
            background: linear-gradient(135deg, var(--peach-light) 0%, var(--peach-medium) 100%);
            color: white;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 0.85rem;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        <h4 class="text-white fw-bold"><i class="fas fa-book me-2"></i>Library TPS</h4>
                        <small class="text-white opacity-75">Transaction Processing System</small>
                    </div>
                    
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('books.*') ? 'active' : '' }}" href="{{ route('books.index') }}">
                                <i class="fas fa-book me-2"></i> Books
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('members.*') ? 'active' : '' }}" href="{{ route('members.index') }}">
                                <i class="fas fa-users me-2"></i> Members
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('transactions.*') ? 'active' : '' }}" href="{{ route('transactions.index') }}">
                                <i class="fas fa-exchange-alt me-2"></i> Transactions
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('transactions.borrowed') }}">
                                <i class="fas fa-hand-holding me-2"></i> Borrowed Books
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('transactions.overdue') }}">
                                <i class="fas fa-exclamation-triangle me-2"></i> Overdue Books
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <div class="py-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>