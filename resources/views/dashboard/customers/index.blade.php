<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Customer - OMILE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/alert-animations.css') }}">
</head>
<body>
    <!-- Mobile Toggle -->
    <button class="sidebar-mobile-toggle" onclick="toggleSidebar()">
        <i class="mdi mdi-menu"></i>
    </button>
    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>
    
    <!-- Dark Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <img src="{{ asset('assets/images/favicon.ico') }}" alt="OMILE Logo" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div class="logo-fallback">O</div>
                <div class="sidebar-logo-text">
                    <h4>OMILE</h4>
                    <small>Admin Panel</small>
                </div>
            </div>
            <button class="sidebar-toggle" onclick="toggleSidebarCollapse()">
                <i class="mdi mdi-chevron-left" id="sidebar-toggle-icon"></i>
            </button>
        </div>
        <nav class="sidebar-nav">
            <a class="nav-link" href="{{ route('admin.dashboard') }}" data-tooltip="Dashboard">
                <i class="mdi mdi-view-dashboard"></i>
                <span class="nav-text">Dashboard</span>
            </a>
            <a class="nav-link" href="{{ route('admin.packages.index') }}" data-tooltip="Manajemen Paket">
                <i class="mdi mdi-package-variant"></i>
                <span class="nav-text">Manajemen Paket</span>
            </a>
            <a class="nav-link" href="{{ route('admin.transactions') }}" data-tooltip="Semua Transaksi">
                <i class="mdi mdi-cash-multiple"></i>
                <span class="nav-text">Semua Transaksi</span>
            </a>
            <a class="nav-link active" href="{{ route('admin.customers.index') }}" data-tooltip="Manajemen Customer">
                <i class="mdi mdi-account-group"></i>
                <span class="nav-text">Customer</span>
            </a>
            <a class="nav-link" href="{{ route('landing') }}" data-tooltip="Landing Page">
                <i class="mdi mdi-home"></i>
                <span class="nav-text">Landing Page</span>
            </a>
            <div class="sidebar-sep"></div>
            <form action="{{ route('logout') }}" method="POST" class="px-3 mt-3">
                @csrf
                <button type="submit" class="nav-link w-100 text-start" style="background: transparent; border: none; cursor: pointer;" id="logout-btn">
                    <i class="mdi mdi-logout"></i>
                    <span class="logout-text">Logout</span>
                </button>
            </form>
        </nav>
    </div>

    <!-- Top Header -->
    <div class="top-header">
        <div class="top-header-left">
            <nav class="top-header-nav">
                <a href="{{ route('admin.dashboard') }}">Dashboards</a>
                <a href="{{ route('admin.customers.index') }}" class="active">Pages</a>
                <a href="{{ route('admin.transactions') }}">Apps</a>
            </nav>
        </div>
        <div class="top-header-right">
            <div class="top-header-icon">
                <i class="mdi mdi-brightness-6"></i>
            </div>
            <div class="top-header-user">
                <div class="top-header-user-avatar">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    <span class="online-dot"></span>
                </div>
                <span class="top-header-user-name">{{ Auth::user()->name }}</span>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content" id="main-content">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h1 class="page-title">Manajemen Customer</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Manajemen Customer</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
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

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-card-icon primary">
                            <i class="mdi mdi-account-group"></i>
                        </div>
                    </div>
                    <h3 class="stat-card-value">{{ number_format($totalCustomers, 0, ',', '.') }}</h3>
                    <p class="stat-card-label">Total Customer</p>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-card-icon success">
                            <i class="mdi mdi-check-circle"></i>
                        </div>
                    </div>
                    <h3 class="stat-card-value">{{ number_format($activeCustomers, 0, ',', '.') }}</h3>
                    <p class="stat-card-label">Customer Aktif</p>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-card-icon info">
                            <i class="mdi mdi-account-check"></i>
                        </div>
                    </div>
                    <h3 class="stat-card-value">{{ number_format($customersWithTransactions, 0, ',', '.') }}</h3>
                    <p class="stat-card-label">Customer Bertransaksi</p>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-card-icon warning">
                            <i class="mdi mdi-cash-multiple"></i>
                        </div>
                    </div>
                    <h3 class="stat-card-value">{{ number_format($totalTransactions, 0, ',', '.') }}</h3>
                    <p class="stat-card-label">Total Transaksi</p>
                </div>
            </div>
        </div>

        <!-- Customers Table -->
        <div class="card">
            <div class="card-header">
                <div>
                    <h5 class="card-title">Daftar Customer</h5>
                    <p class="card-subtitle mb-0">Semua customer yang terdaftar di sistem</p>
                </div>
            </div>
            <div class="card-body" style="padding: 0;">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="customersTable">
                        <thead>
                            <tr>
                                <th>NAME</th>
                                <th>EMAIL</th>
                                <th>REGISTERED</th>
                                <th>TOTAL TRANSACTIONS</th>
                                <th>TOTAL PACKAGES</th>
                                <th>STATUS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customers as $customer)
                                <tr>
                                    <td><strong>{{ $customer->name }}</strong></td>
                                    <td>{{ $customer->email }}</td>
                                    <td>{{ $customer->created_at->format('d M Y') }}</td>
                                    <td><span class="badge badge-soft info">{{ $customer->transactions_count }}</span></td>
                                    <td><span class="badge badge-soft info">{{ $customer->user_packages_count }}</span></td>
                                    <td>
                                        @php
                                            $hasActivePackage = $customer->userPackages()
                                                ->where('is_active', true)
                                                ->where('end_date', '>', now())
                                                ->exists();
                                        @endphp
                                        @if($hasActivePackage)
                                            <span class="badge badge-soft success">Aktif</span>
                                        @else
                                            <span class="badge badge-soft danger">Tidak Aktif</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="mdi mdi-information-outline text-muted" style="font-size: 2rem;"></i>
                                        <p class="text-muted mt-2 mb-0">Tidak ada customer</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    <script src="{{ asset('js/sidebar.js') }}"></script>
    <script src="{{ asset('js/alert-auto-close.js') }}"></script>

    <script>
        // Initialize DataTables
        (function() {
            function initDataTable() {
                if ($.fn.DataTable.isDataTable('#customersTable')) {
                    console.log('DataTables already initialized');
                    return;
                }
                
                try {
                    if ($.fn.DataTable.isDataTable('#customersTable')) {
                        $('#customersTable').DataTable().destroy();
                    }
                    
                    var table = $('#customersTable').DataTable({
                        language: {
                            search: "Cari:",
                            searchPlaceholder: "Cari customer...",
                            lengthMenu: "Tampilkan _MENU_ data per halaman",
                            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                            infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                            infoFiltered: "(difilter dari _MAX_ total data)",
                            paginate: {
                                first: "Pertama",
                                last: "Terakhir",
                                next: "Selanjutnya",
                                previous: "Sebelumnya"
                            },
                            emptyTable: "Tidak ada data customer",
                            zeroRecords: "Tidak ada data yang cocok dengan pencarian"
                        },
                        responsive: true,
                        pageLength: 10,
                        lengthMenu: [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, "Semua"]],
                        order: [[2, 'desc']],
                        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                        drawCallback: function() {
                            console.log('DataTables draw completed');
                        }
                    });
                    
                    console.log('DataTables customersTable initialized successfully');
                } catch (error) {
                    console.error('Error initializing DataTables:', error);
                }
            }
            
            if (document.readyState === 'complete') {
                setTimeout(initDataTable, 200);
            } else {
                window.addEventListener('load', function() {
                    setTimeout(initDataTable, 200);
                });
            }
            
            $(document).ready(function() {
                setTimeout(initDataTable, 300);
            });
        })();
    </script>
</body>
</html>
