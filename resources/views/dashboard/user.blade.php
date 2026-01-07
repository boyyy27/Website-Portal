<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - OMILE</title>
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
                    <small>User Dashboard</small>
                </div>
            </div>
            <button class="sidebar-toggle" onclick="toggleSidebarCollapse()">
                <i class="mdi mdi-chevron-left" id="sidebar-toggle-icon"></i>
            </button>
        </div>
        <nav class="sidebar-nav">
            <a class="nav-link active" href="{{ route('user.dashboard') }}" data-tooltip="Dashboard">
                <i class="mdi mdi-view-dashboard"></i>
                <span class="nav-text">Dashboard</span>
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
                <a href="{{ route('user.dashboard') }}" class="active">Dashboards</a>
                <a href="{{ route('landing') }}">Pages</a>
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
                            <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
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
                    <h3 class="stat-card-value">Rp {{ number_format($totalSpent, 0, ',', '.') }}</h3>
                    <p class="stat-card-label">Total Pengeluaran</p>
                </div>
            </div>
        </div>

        <!-- Active Subscription Card -->
        @if($activeSubscription)
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <div>
                        <h5 class="card-title mb-0">
                            <i class="mdi mdi-crown me-2"></i>
                            Langganan Aktif
                        </h5>
                        <p class="card-subtitle mb-0 text-white" style="opacity: 0.9;">Detail paket Anda saat ini</p>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h3 class="mb-3">{{ $activeSubscription->package->name }}</h3>
                            <p class="mb-2">
                                <i class="mdi mdi-calendar me-2"></i>
                                <strong>Mulai:</strong> {{ $activeSubscription->start_date->format('d M Y') }}
                            </p>
                            <p class="mb-2">
                                <i class="mdi mdi-calendar-check me-2"></i>
                                <strong>Berakhir:</strong> {{ $activeSubscription->end_date->format('d M Y') }}
                            </p>
                            <div class="mt-3">
                                <small class="text-muted">Masa aktif tersisa:</small>
                                <div class="h4 mb-0" id="days">{{ $activeSubscription->end_date->diffInDays(now()) }}</div>
                                <small class="text-muted">hari</small>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <span class="badge badge-soft success" style="font-size: 1rem; padding: 10px 16px;">
                                <i class="mdi mdi-check-circle me-1"></i> Aktif
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TMS Access Section -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <div>
                        <h5 class="card-title mb-0">
                            <i class="mdi mdi-web me-2"></i>
                            Akses TMS
                        </h5>
                        <p class="card-subtitle mb-0 text-white" style="opacity: 0.9;">Transportation Management System</p>
                    </div>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">
                        Setelah pembayaran berhasil, Anda dapat mengakses sistem TMS untuk mengelola distribusi dan logistik Anda.
                    </p>
                    <a href="{{ route('tms.access') }}" class="btn btn-primary btn-lg" target="_blank">
                        <i class="mdi mdi-open-in-new me-2"></i>
                        Buka TMS Dashboard
                    </a>
                </div>
            </div>
        @else
            <div class="card mb-4">
                <div class="card-body text-center py-5">
                    <i class="mdi mdi-alert-circle text-warning" style="font-size: 3rem;"></i>
                    <h4 class="mt-3 mb-2">Tidak Ada Langganan Aktif</h4>
                    <p class="mb-3 text-muted">Anda belum memiliki paket aktif. Silakan berlangganan untuk menikmati layanan kami.</p>
                    <a href="{{ route('landing') }}" class="btn btn-primary">
                        <i class="mdi mdi-package-variant me-2"></i> Lihat Paket
                    </a>
                </div>
            </div>
        @endif

        <!-- Charts Row -->
        @if($transactions->count() > 0)
        <div class="row mb-4">
            <!-- Transaction History Chart -->
            <div class="col-md-8 mb-3">
                <div class="card">
                    <div class="card-header">
                        <div>
                            <h5 class="card-title">Riwayat Transaksi</h5>
                            <p class="card-subtitle mb-0">Transaksi Anda selama 6 bulan terakhir</p>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="userTransactionChart" height="100"></canvas>
                    </div>
                </div>
            </div>
            <!-- Transaction Status Chart -->
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-header">
                        <div>
                            <h5 class="card-title">Status Transaksi</h5>
                            <p class="card-subtitle mb-0">Breakdown status transaksi</p>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="userStatusChart" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Invoices Section -->
        <div class="card mb-4">
            <div class="card-header">
                <div>
                    <h5 class="card-title">Invoice</h5>
                    <p class="card-subtitle mb-0">Invoice pembayaran Anda</p>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table" id="invoicesTable">
                        <thead>
                            <tr>
                                <th>PACKAGE</th>
                                <th>ORDER ID</th>
                                <th>DATE</th>
                                <th>AMOUNT</th>
                                <th>STATUS</th>
                                <th class="no-export">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($invoices as $invoice)
                                <tr>
                                    <td><strong>{{ $invoice->package_name ?? $invoice->package->name ?? 'N/A' }}</strong></td>
                                    <td>{{ $invoice->order_id }}</td>
                                    <td>{{ $invoice->settlement_time ? $invoice->settlement_time->format('d M Y H:i') : $invoice->created_at->format('d M Y H:i') }}</td>
                                    <td><strong>Rp {{ number_format($invoice->gross_amount, 0, ',', '.') }}</strong></td>
                                    <td>
                                        <span class="badge badge-soft success">Paid</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('user.invoice.download', $invoice->id) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                            <i class="mdi mdi-download"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-5">
                                        <i class="mdi mdi-file-document-outline" style="font-size: 3rem; opacity: 0.3;"></i>
                                        <p class="mt-3">No invoices found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Transaction History -->
        <div class="card mb-4">
            <div class="card-header">
                <div>
                    <h5 class="card-title">Riwayat Transaksi</h5>
                    <p class="card-subtitle mb-0">Semua riwayat transaksi Anda</p>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table" id="transactionHistoryTable">
                        <thead>
                            <tr>
                                <th>PACKAGE</th>
                                <th>ORDER ID</th>
                                <th>DATE</th>
                                <th>AMOUNT</th>
                                <th>STATUS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $transaction)
                                <tr>
                                    <td><strong>{{ $transaction->package_name ?? $transaction->package->name ?? 'N/A' }}</strong></td>
                                    <td>{{ $transaction->order_id }}</td>
                                    <td>{{ $transaction->created_at->format('d M Y H:i') }}</td>
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
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-5">
                                        <i class="mdi mdi-information-outline" style="font-size: 3rem; opacity: 0.3;"></i>
                                        <p class="mt-3">No transactions found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- All Subscriptions -->
        <div class="card">
            <div class="card-header">
                <div>
                    <h5 class="card-title">Semua Langganan</h5>
                    <p class="card-subtitle mb-0">Riwayat langganan Anda</p>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>PACKAGE</th>
                                <th>START DATE</th>
                                <th>END DATE</th>
                                <th>STATUS</th>
                                <th>INVOICE</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($subscriptions as $subscription)
                                <tr>
                                    <td><strong>{{ $subscription->package->name }}</strong></td>
                                    <td>{{ $subscription->start_date->format('d M Y') }}</td>
                                    <td>{{ $subscription->end_date->format('d M Y') }}</td>
                                    <td>
                                        @if($subscription->is_active && $subscription->end_date > now())
                                            <span class="badge badge-soft success">Active</span>
                                        @else
                                            <span class="badge badge-soft secondary">Expired</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($subscription->transaction && $subscription->transaction->transaction_status == 'settlement')
                                            <a href="{{ route('user.invoice.download', $subscription->transaction->id) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                                <i class="mdi mdi-download"></i>
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-5">
                                        <i class="mdi mdi-package-variant" style="font-size: 3rem; opacity: 0.3;"></i>
                                        <p class="mt-3">No subscriptions found</p>
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
    @if($activeSubscription)
    <script>
        // Countdown timer
        function updateCountdown() {
            const endDate = new Date('{{ $activeSubscription->end_date->toIso8601String() }}');
            const now = new Date();
            const diff = endDate - now;

            if (diff > 0) {
                const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                document.getElementById('days').textContent = days;
            } else {
                document.getElementById('days').textContent = '0';
            }
        }

        updateCountdown();
        setInterval(updateCountdown, 60000); // Update every minute
    </script>
    @endif
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

        @if($transactions->count() > 0)
        // User Transaction History Chart (Line)
        const userTransactionCtx = document.getElementById('userTransactionChart');
        if (userTransactionCtx) {
            const userTransactionData = @json($userMonthlyTransactions->map(function($item) {
                return [
                    \Carbon\Carbon::parse($item->month)->format('M Y'),
                    (int)$item->count
                ];
            }));
            
            new Chart(userTransactionCtx, {
                type: 'line',
                data: {
                    labels: userTransactionData.map(item => item[0]),
                    datasets: [{
                        label: 'Transactions',
                        data: userTransactionData.map(item => item[1]),
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
                            padding: 12
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(15, 23, 42, 0.05)'
                            },
                            ticks: {
                                stepSize: 1
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

        // User Transaction Status Chart (Doughnut)
        const userStatusCtx = document.getElementById('userStatusChart');
        if (userStatusCtx) {
            const userStatusData = @json($userTransactionStatus);
            const userStatusLabels = userStatusData.map(item => {
                const status = item.transaction_status;
                return status.charAt(0).toUpperCase() + status.slice(1);
            });
            const userStatusCounts = userStatusData.map(item => item.total);
            const colors = ['#16a34a', '#f59e0b', '#ef4444', '#6b7280', '#3b82f6'];
            
            new Chart(userStatusCtx, {
                type: 'doughnut',
                data: {
                    labels: userStatusLabels,
                    datasets: [{
                        data: userStatusCounts,
                        backgroundColor: colors.slice(0, userStatusLabels.length),
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
        @endif

        // Initialize DataTables
        $(document).ready(function() {
            if (typeof $.fn.DataTable === 'undefined') {
                console.error('DataTables is not loaded');
                return;
            }
            
            // Invoices Table
            $('#invoicesTable').DataTable({
                language: {
                    search: "Cari:",
                    searchPlaceholder: "Cari invoice...",
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
                    emptyTable: "Tidak ada data invoice",
                    zeroRecords: "Tidak ada data yang cocok dengan pencarian",
                    buttons: {
                        copy: "Salin",
                        csv: "CSV",
                        excel: "Excel",
                        pdf: "PDF",
                        print: "Cetak",
                        colvis: "Tampilkan Kolom"
                    }
                },
                responsive: true,
                pageLength: 10,
                lengthMenu: [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, "Semua"]],
                order: [[2, 'desc']], // Sort by DATE column (index 2) descending
                columnDefs: [
                    { orderable: false, targets: 5 }, // Disable sorting on ACTIONS column
                    { responsivePriority: 1, targets: 0 }, // Package
                    { responsivePriority: 2, targets: 5 } // Actions
                ],
                dom: 'Bfrtip',
                buttons: [
                    { extend: 'colvis', text: 'Kolom', className: 'btn btn-light btn-sm' },
                    { extend: 'copy', text: 'Salin', className: 'btn btn-light btn-sm' },
                    { extend: 'csv', text: 'CSV', className: 'btn btn-light btn-sm' },
                    { extend: 'excel', text: 'Excel', className: 'btn btn-light btn-sm' },
                    { extend: 'pdf', text: 'PDF', className: 'btn btn-light btn-sm', orientation: 'landscape' },
                    { extend: 'print', text: 'Cetak', className: 'btn btn-light btn-sm' }
                ]
            });

            // Transaction History Table
            $('#transactionHistoryTable').DataTable({
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
                    zeroRecords: "Tidak ada data yang cocok dengan pencarian",
                    buttons: {
                        copy: "Salin",
                        csv: "CSV",
                        excel: "Excel",
                        pdf: "PDF",
                        print: "Cetak",
                        colvis: "Tampilkan Kolom"
                    }
                },
                responsive: true,
                pageLength: 10,
                lengthMenu: [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, "Semua"]],
                order: [[2, 'desc']], // Sort by DATE column (index 2) descending
                dom: 'Bfrtip',
                buttons: [
                    { extend: 'colvis', text: 'Kolom', className: 'btn btn-light btn-sm' },
                    { extend: 'copy', text: 'Salin', className: 'btn btn-light btn-sm' },
                    { extend: 'csv', text: 'CSV', className: 'btn btn-light btn-sm' },
                    { extend: 'excel', text: 'Excel', className: 'btn btn-light btn-sm' },
                    { extend: 'pdf', text: 'PDF', className: 'btn btn-light btn-sm', orientation: 'landscape' },
                    { extend: 'print', text: 'Cetak', className: 'btn btn-light btn-sm' }
                ]
            });
        });
    </script>
</body>
</html>
