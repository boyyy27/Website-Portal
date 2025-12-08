<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - OMILE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>
    <button class="sidebar-mobile-toggle" onclick="toggleSidebar()">
        <i class="mdi mdi-menu"></i>
    </button>
    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>
    
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <img src="{{ asset('assets/images/favicon.ico') }}" alt="OMILE Logo" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div style="display: none; width: 40px; height: 40px; background: linear-gradient(135deg, #2f55d4 0%, #f58905 100%); border-radius: 8px; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 1.2rem;">O</div>
                <div class="sidebar-logo-text">
                    <h4>OMILE</h4>
                    <small>Admin Panel</small>
                </div>
            </div>
            <button class="sidebar-toggle" onclick="toggleSidebarCollapse()">
                <i class="mdi mdi-chevron-left" id="sidebar-toggle-icon"></i>
            </button>
        </div>
        <nav class="nav flex-column">
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
            <a class="nav-link" href="{{ route('landing') }}" data-tooltip="Landing Page">
                <i class="mdi mdi-home"></i>
                <span class="nav-text">Landing Page</span>
            </a>
            <form action="{{ route('logout') }}" method="POST" class="px-3 mt-3">
                @csrf
                <button type="submit" class="btn btn-outline-light w-100" id="logout-btn">
                    <i class="mdi mdi-logout me-2"></i>
                    <span class="logout-text">Logout</span>
                </button>
            </form>
        </nav>
    </div>

    <div class="main-content" id="main-content">
        @php
            // Set timezone ke Asia/Jakarta (WIB)
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
            
            // Format tanggal Indonesia
            $days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            $months = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            
            $dayName = $days[$now->format('w')];
            $day = $now->format('d');
            $month = $months[(int)$now->format('m')];
            $year = $now->format('Y');
            $currentDate = $dayName . ', ' . $day . ' ' . $month . ' ' . $year;
            $currentTime = $now->format('H:i');
        @endphp
        
        <div class="dashboard-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="greeting-text">{{ $greeting }}, {{ Auth::user()->name }}! 👋</div>
                    <div class="greeting-subtitle">Selamat datang kembali di Admin Dashboard</div>
                    <div class="header-date">
                        <i class="mdi mdi-calendar-clock me-1"></i>
                        {{ $currentDate }} | {{ $currentTime }} WIB
                    </div>
                </div>
                <div class="text-end">
                    <i class="mdi mdi-view-dashboard" style="font-size: 4rem; opacity: 0.3;"></i>
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
                    <div class="stat-icon primary">
                        <i class="mdi mdi-cash-multiple"></i>
                    </div>
                    <h3 class="mb-1">{{ number_format($totalTransactions, 0, ',', '.') }}</h3>
                    <p class="text-muted mb-0">Total Transaksi</p>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="stat-icon warning">
                        <i class="mdi mdi-clock-outline"></i>
                    </div>
                    <h3 class="mb-1">{{ number_format($pendingTransactions, 0, ',', '.') }}</h3>
                    <p class="text-muted mb-0">Pending</p>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="stat-icon success">
                        <i class="mdi mdi-check-circle"></i>
                    </div>
                    <h3 class="mb-1">{{ number_format($settledTransactions, 0, ',', '.') }}</h3>
                    <p class="text-muted mb-0">Berhasil</p>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="stat-icon info">
                        <i class="mdi mdi-currency-usd"></i>
                    </div>
                    <h3 class="mb-1">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                    <p class="text-muted mb-0">Total Pendapatan</p>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="table-responsive mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Transaksi Terbaru</h5>
                <a href="{{ route('admin.transactions') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
            </div>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Pelanggan</th>
                        <th>Paket</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentTransactions as $transaction)
                        <tr>
                            <td><strong>{{ $transaction->order_id }}</strong></td>
                            <td>
                                {{ $transaction->customer_name ?? $transaction->user->name }}<br>
                                <small class="text-muted">{{ $transaction->customer_email ?? $transaction->user->email }}</small>
                            </td>
                            <td>{{ $transaction->package_name ?? $transaction->package->name ?? 'N/A' }}</td>
                            <td>Rp {{ number_format($transaction->gross_amount, 0, ',', '.') }}</td>
                            <td>
                                @if($transaction->transaction_status == 'settlement')
                                    <span class="badge badge-status badge-settlement">Settlement</span>
                                @elseif($transaction->transaction_status == 'pending')
                                    <span class="badge badge-status badge-pending">Pending</span>
                                @elseif($transaction->transaction_status == 'cancel')
                                    <span class="badge badge-status badge-cancel">Cancel</span>
                                @elseif($transaction->transaction_status == 'expire')
                                    <span class="badge badge-status badge-expire">Expire</span>
                                @else
                                    <span class="badge badge-status">{{ $transaction->transaction_status }}</span>
                                @endif
                            </td>
                            <td>{{ $transaction->created_at->format('d M Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.transaction.detail', $transaction->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="mdi mdi-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Tidak ada transaksi</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Packages Overview -->
        <div class="table-responsive">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Paket Tersedia</h5>
                <a href="{{ route('admin.packages.index') }}" class="btn btn-sm btn-primary">Kelola Paket</a>
            </div>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nama Paket</th>
                        <th>Harga</th>
                        <th>Durasi</th>
                        <th>Status</th>
                        <th>Total Transaksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($packages as $package)
                        <tr>
                            <td><strong>{{ $package->name }}</strong></td>
                            <td>Rp {{ number_format($package->price, 0, ',', '.') }}</td>
                            <td>{{ $package->duration_days }} hari</td>
                            <td>
                                @if($package->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Tidak Aktif</span>
                                @endif
                            </td>
                            <td>{{ $package->transactions()->count() }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Tidak ada paket</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/sidebar.js') }}"></script>
</body>
</html>

