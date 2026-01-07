<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - OMILE</title>
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
            <a class="nav-link active" href="{{ route('admin.dashboard') }}" data-tooltip="Dashboard">
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
            <a class="nav-link" href="{{ route('admin.customers.index') }}" data-tooltip="Manajemen Customer">
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
                <a href="{{ route('admin.dashboard') }}" class="active">Dashboards</a>
                <a href="{{ route('admin.packages.index') }}">Pages</a>
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
                    @php
                        $now = \Carbon\Carbon::now('Asia/Jakarta');
                        $hour = $now->format('H');
                        $greeting = 'Selamat Datang';
                        if ($hour >= 5 && $hour < 12) {
                            $greeting = 'Selamat Pagi';
                        } elseif ($hour >= 12 && $hour < 15) {
                            $greeting = 'Selamat Siang';
                        } elseif ($hour >= 15 && $hour < 19) {
                            $greeting = 'Selamat Sore';
                        } else {
                            $greeting = 'Selamat Malam';
                        }
                    @endphp
                    <h1 class="page-title">{{ $greeting }}, {{ Auth::user()->name }}! 👋</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Dashboard Admin</li>
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
                            <i class="mdi mdi-cash-multiple"></i>
                        </div>
                    </div>
                    <h3 class="stat-card-value">{{ number_format($totalTransactions, 0, ',', '.') }}</h3>
                    <p class="stat-card-label">Total Transaksi</p>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-card-icon warning">
                            <i class="mdi mdi-clock-outline"></i>
                        </div>
                    </div>
                    <h3 class="stat-card-value">{{ number_format($pendingTransactions, 0, ',', '.') }}</h3>
                    <p class="stat-card-label">Pending</p>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-card-icon success">
                            <i class="mdi mdi-check-circle"></i>
                        </div>
                    </div>
                    <h3 class="stat-card-value">{{ number_format($settledTransactions, 0, ',', '.') }}</h3>
                    <p class="stat-card-label">Berhasil</p>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-card-icon info">
                            <i class="mdi mdi-currency-usd"></i>
                        </div>
                    </div>
                    <h3 class="stat-card-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                    <p class="stat-card-label">Total Pendapatan</p>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row mb-4">
            <!-- Monthly Revenue Chart -->
            <div class="col-md-8 mb-3">
                <div class="card">
                    <div class="card-header">
                <div>
                    <h5 class="card-title">Pendapatan Bulanan</h5>
                    <p class="card-subtitle mb-0">Ringkasan pendapatan bulanan (6 bulan terakhir)</p>
                </div>
                    </div>
                    <div class="card-body">
                        <canvas id="monthlyRevenueChart" height="100"></canvas>
                    </div>
                </div>
            </div>
            <!-- Transaction Status Chart -->
            <div class="col-md-4 mb-3">
                <div class="card" style="width: 300px; padding: 10px; font-size: 14px; margin-left: 20px;">
                    <div class="card-header">
                <div>
                    <h5 class="card-title">Status Transaksi</h5>
                    <p class="card-subtitle mb-0">Breakdown status transaksi</p>
                </div>
                    </div>
                    <div class="card-body">
                        <canvas id="transactionStatusChart" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row 2 -->
        <div class="row mb-4">
            <!-- Daily Sales Chart -->
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-header">
                <div>
                    <h5 class="card-title">Penjualan Harian</h5>
                    <p class="card-subtitle mb-0">Tren penjualan 7 hari terakhir</p>
                </div>
                    </div>
                    <div class="card-body">
                        <canvas id="dailySalesChart" height="150"></canvas>
                    </div>
                </div>
            </div>
            <!-- Package Sales Chart -->
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-header">
                <div>
                    <h5 class="card-title">Penjualan Paket</h5>
                    <p class="card-subtitle mb-0">Paket terlaris</p>
                </div>
                    </div>
                    <div class="card-body">
                        <canvas id="packageSalesChart" height="150"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="card">
            <div class="card-header">
                <div>
                    <h5 class="card-title">Transaksi Terbaru</h5>
                    <p class="card-subtitle mb-0">Riwayat transaksi terbaru</p>
                </div>
                <a href="{{ route('admin.transactions') }}" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body" style="padding: 0;">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="recentTransactionsTable">
                        <thead>
                            <tr>
                                <th>ORDER ID</th>
                                <th>CUSTOMER</th>
                                <th>PACKAGE</th>
                                <th>TOTAL</th>
                                <th>STATUS</th>
                                <th>CREATED</th>
                                <th class="no-export">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTransactions as $transaction)
                                <tr>
                                    <td><strong>{{ $transaction->order_id }}</strong></td>
                                    <td>
                                        <div>
                                            <strong>{{ $transaction->customer_name ?? $transaction->user->name ?? 'N/A' }}</strong><br>
                                            <small class="text-muted">{{ $transaction->customer_email ?? $transaction->user->email ?? '-' }}</small>
                                        </div>
                                    </td>
                                    <td>{{ $transaction->package_name ?? $transaction->package->name ?? 'N/A' }}</td>
                                    <td><strong>Rp {{ number_format($transaction->gross_amount, 0, ',', '.') }}</strong></td>
                                    <td>
                                        @if($transaction->transaction_status == 'settlement')
                                            <span class="badge badge-soft success">Settlement</span>
                                        @elseif($transaction->transaction_status == 'pending')
                                            <span class="badge badge-soft warning">Pending</span>
                                        @elseif($transaction->transaction_status == 'cancel')
                                            <span class="badge badge-soft danger">Cancel</span>
                                        @elseif($transaction->transaction_status == 'expire')
                                            <span class="badge badge-soft secondary">Expire</span>
                                        @else
                                            <span class="badge badge-soft secondary">{{ $transaction->transaction_status }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $transaction->created_at->format('d M Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('admin.transaction.detail', $transaction->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="mdi mdi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-5">
                                        <i class="mdi mdi-cash-multiple" style="font-size: 3rem; opacity: 0.3;"></i>
                                        <p class="mt-3">No transactions found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Packages Overview -->
        <div class="card">
            <div class="card-header">
                <div>
                    <h5 class="card-title">Paket Tersedia</h5>
                    <p class="card-subtitle mb-0">Semua paket aktif dan tidak aktif</p>
                </div>
                <a href="{{ route('admin.packages.index') }}" class="btn btn-sm btn-primary">Kelola Paket</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>PACKAGE NAME</th>
                                <th>PRICE</th>
                                <th>DURATION</th>
                                <th>STATUS</th>
                                <th>TOTAL TRANSACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($packages as $package)
                                <tr>
                                    <td><strong>{{ $package->name }}</strong></td>
                                    <td><strong>Rp {{ number_format($package->price, 0, ',', '.') }}</strong></td>
                                    <td>{{ $package->duration_days }} days</td>
                                    <td>
                                        @if($package->is_active)
                                            <span class="badge badge-soft success">Active</span>
                                        @else
                                            <span class="badge badge-soft secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>{{ $package->transactions()->count() }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-5">
                                        <i class="mdi mdi-package-variant" style="font-size: 3rem; opacity: 0.3;"></i>
                                        <p class="mt-3">No packages found</p>
                                        <a href="{{ route('admin.packages.create') }}" class="btn btn-primary mt-2">Add First Package</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    <script src="{{ asset('js/alert-auto-close.js') }}"></script>
    <script src="{{ asset('js/sidebar.js') }}"></script>
    <script>
        // Update sidebar toggle for new layout
        function toggleSidebarCollapse() {
            const sidebar = document.getElementById('sidebar');
            const toggleIcon = document.getElementById('sidebar-toggle-icon');
            
            if (!sidebar) return;
            
            sidebar.classList.toggle('collapsed');
            
            if (toggleIcon) {
                if (sidebar.classList.contains('collapsed')) {
                    toggleIcon.classList.remove('mdi-chevron-left');
                    toggleIcon.classList.add('mdi-chevron-right');
                } else {
                    toggleIcon.classList.remove('mdi-chevron-right');
                    toggleIcon.classList.add('mdi-chevron-left');
                }
            }
        }

        // Mobile sidebar toggle
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            if (sidebar && overlay) {
                sidebar.classList.toggle('open');
                overlay.classList.toggle('show');
            }
        }

        // Chart.js Configuration
        Chart.defaults.font.family = "'Inter', system-ui, sans-serif";
        Chart.defaults.font.size = 12;
        Chart.defaults.color = 'rgba(15, 23, 42, 0.7)';

        // Monthly Revenue Chart (Line)
        const monthlyRevenueCtx = document.getElementById('monthlyRevenueChart');
        if (monthlyRevenueCtx) {
            const monthlyRevenueData = @json($monthlyRevenue->map(function($item) {
                return [
                    \Carbon\Carbon::parse($item->month)->format('M Y'),
                    (float)$item->revenue
                ];
            }));
            
            new Chart(monthlyRevenueCtx, {
                type: 'line',
                data: {
                    labels: monthlyRevenueData.map(item => item[0]),
                    datasets: [{
                        label: 'Revenue',
                        data: monthlyRevenueData.map(item => item[1]),
                        borderColor: '#2f55d4',
                        backgroundColor: 'rgba(47, 85, 212, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 5,
                        pointBackgroundColor: '#2f55d4',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(15, 23, 42, 0.9)',
                            padding: 12,
                            titleFont: { size: 14, weight: '600' },
                            bodyFont: { size: 13 },
                            callbacks: {
                                label: function(context) {
                                    return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(15, 23, 42, 0.05)'
                            },
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

        // Transaction Status Chart (Doughnut)
        const transactionStatusCtx = document.getElementById('transactionStatusChart');
        if (transactionStatusCtx) {
            const statusData = @json($transactionsByStatus);
            const statusLabels = statusData.map(item => {
                const status = item.transaction_status;
                return status.charAt(0).toUpperCase() + status.slice(1);
            });
            const statusCounts = statusData.map(item => item.total);
            const colors = ['#16a34a', '#f59e0b', '#ef4444', '#6b7280', '#3b82f6'];
            
            new Chart(transactionStatusCtx, {
                type: 'doughnut',
                data: {
                    labels: statusLabels,
                    datasets: [{
                        data: statusCounts,
                        backgroundColor: colors.slice(0, statusLabels.length),
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 15,
                                usePointStyle: true,
                                font: { size: 12 }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(15, 23, 42, 0.9)',
                            padding: 12
                        }
                    }
                }
            });
        }

        // Daily Sales Chart (Bar)
        const dailySalesCtx = document.getElementById('dailySalesChart');
        if (dailySalesCtx) {
            const dailySalesData = @json($dailySales->map(function($item) {
                return [
                    \Carbon\Carbon::parse($item->date)->format('d M'),
                    (float)$item->revenue
                ];
            }));
            
            new Chart(dailySalesCtx, {
                type: 'bar',
                data: {
                    labels: dailySalesData.map(item => item[0]),
                    datasets: [{
                        label: 'Daily Sales',
                        data: dailySalesData.map(item => item[1]),
                        backgroundColor: 'rgba(47, 85, 212, 0.8)',
                        borderColor: '#2f55d4',
                        borderWidth: 2,
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(15, 23, 42, 0.9)',
                            padding: 12,
                            callbacks: {
                                label: function(context) {
                                    return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(15, 23, 42, 0.05)'
                            },
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

        // Package Sales Chart (Bar)
        const packageSalesCtx = document.getElementById('packageSalesChart');
        if (packageSalesCtx) {
            const packageSalesData = @json($packageSales);
            const packageNames = packageSalesData.map(item => item.name.length > 15 ? item.name.substring(0, 15) + '...' : item.name);
            const packageRevenues = packageSalesData.map(item => parseFloat(item.revenue));
            
            new Chart(packageSalesCtx, {
                type: 'bar',
                data: {
                    labels: packageNames,
                    datasets: [{
                        label: 'Revenue',
                        data: packageRevenues,
                        backgroundColor: [
                            'rgba(47, 85, 212, 0.8)',
                            'rgba(245, 158, 11, 0.8)',
                            'rgba(22, 163, 74, 0.8)',
                            'rgba(239, 68, 68, 0.8)',
                            'rgba(139, 92, 246, 0.8)'
                        ],
                        borderColor: [
                            '#2f55d4',
                            '#f59e0b',
                            '#16a34a',
                            '#ef4444',
                            '#8b5cf6'
                        ],
                        borderWidth: 2,
                        borderRadius: 8
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(15, 23, 42, 0.9)',
                            padding: 12,
                            callbacks: {
                                label: function(context) {
                                    return 'Rp ' + context.parsed.x.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(15, 23, 42, 0.05)'
                            },
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        },
                        y: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

        // Initialize DataTables - Ensure it loads after Chart.js
        (function() {
            function initDataTable() {
                // Check if already initialized
                if ($.fn.DataTable.isDataTable('#recentTransactionsTable')) {
                    console.log('DataTables already initialized');
                    return;
                }
                
                try {
                    // Destroy existing instance if any
                    if ($.fn.DataTable.isDataTable('#recentTransactionsTable')) {
                        $('#recentTransactionsTable').DataTable().destroy();
                    }
                    
                    var table = $('#recentTransactionsTable').DataTable({
                        language: {
                            search: "Cari:",
                            searchPlaceholder: "Cari transaksi...",
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
                            emptyTable: "Tidak ada data transaksi",
                            zeroRecords: "Tidak ada data yang cocok dengan pencarian"
                        },
                        responsive: true,
                        pageLength: 5,
                        lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Semua"]],
                        order: [[5, 'desc']],
                        columnDefs: [
                            { orderable: false, targets: 6 }
                        ],
                        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                        drawCallback: function() {
                            console.log('DataTables draw completed');
                            console.log('Search box visible:', $('.dataTables_filter').is(':visible'));
                            console.log('Pagination visible:', $('.dataTables_paginate').is(':visible'));
                        }
                    });
                    
                    console.log('DataTables recentTransactionsTable initialized successfully');
                    console.log('Table wrapper:', $('.dataTables_wrapper').length);
                    console.log('Search box:', $('.dataTables_filter').length);
                    console.log('Pagination:', $('.dataTables_paginate').length);
                } catch (error) {
                    console.error('Error initializing DataTables:', error);
                }
            }
            
            // Wait for Chart.js to finish, then init DataTables
            window.addEventListener('load', function() {
                setTimeout(initDataTable, 500);
            });
            
            // Also try on DOM ready
            $(document).ready(function() {
                setTimeout(initDataTable, 600);
            });
        })();
    </script>
</body>
</html>
