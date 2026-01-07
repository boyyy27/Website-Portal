<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semua Transaksi - OMILE</title>
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
            <a class="nav-link active" href="{{ route('admin.transactions') }}" data-tooltip="Semua Transaksi">
                <i class="mdi mdi-cash-multiple"></i>
                <span class="nav-text">Semua Transaksi</span>
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
                <a href="{{ route('admin.packages.index') }}">Pages</a>
                <a href="{{ route('admin.transactions') }}" class="active">Apps</a>
            </nav>
        </div>
        <div class="top-header-right">
            <div class="top-header-icon">
                <i class="mdi mdi-magnify"></i>
            </div>
            <div class="top-header-icon">
                <i class="mdi mdi-forum-outline"></i>
            </div>
            <div class="top-header-icon">
                <i class="mdi mdi-bell-outline"></i>
                <span class="badge">3</span>
            </div>
            <div class="top-header-icon">
                <i class="mdi mdi-view-grid-outline"></i>
            </div>
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
                    <h1 class="page-title">Semua Transaksi</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Transaksi</li>
                        </ol>
                    </nav>
                </div>
                <div class="page-actions">
                    <button class="btn btn-light btn-soft">
                        <i class="mdi mdi-filter-outline"></i> Filter
                    </button>
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
                    <p class="stat-card-label">Total Transactions</p>
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
                    <p class="stat-card-label">Settled</p>
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
                    <p class="stat-card-label">Total Revenue</p>
                </div>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="card">
            <div class="card-header">
                <div>
                    <h5 class="card-title">Daftar Transaksi</h5>
                    <p class="card-subtitle mb-0">Semua transaksi yang tercatat di sistem</p>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table" id="transactionsTable">
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
                            @forelse($transactions as $transaction)
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
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('admin.transaction.detail', $transaction->id) }}" class="btn btn-sm btn-outline-primary" title="View Detail">
                                                <i class="mdi mdi-eye"></i>
                                            </a>
                                            @if($transaction->transaction_status !== 'settlement')
                                            <form action="{{ route('admin.transaction.delete', $transaction->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this transaction? This action cannot be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Transaction">
                                                    <i class="mdi mdi-delete"></i>
                                                </button>
                                            </form>
                                            @else
                                            <button type="button" class="btn btn-sm btn-outline-secondary" disabled title="Cannot delete settled transaction">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                            @endif
                                        </div>
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
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            if (sidebar && overlay) {
                sidebar.classList.toggle('open');
                overlay.classList.toggle('show');
            }
        }

        // Initialize DataTables
        $(document).ready(function() {
            if (typeof $.fn.DataTable === 'undefined') {
                console.error('DataTables is not loaded');
                return;
            }
            
            $('#transactionsTable').DataTable({
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
                order: [[5, 'desc']], // Sort by CREATED column (index 5) descending
                columnDefs: [
                    { orderable: false, targets: 6 }, // Disable sorting on ACTIONS column
                    { responsivePriority: 1, targets: 0 }, // Order ID
                    { responsivePriority: 2, targets: 6 } // Actions
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
        });
    </script>
</body>
</html>
