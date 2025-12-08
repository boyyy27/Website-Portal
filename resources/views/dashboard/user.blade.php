<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - OMILE</title>
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
                    <small>User Dashboard</small>
                </div>
            </div>
            <button class="sidebar-toggle" onclick="toggleSidebarCollapse()">
                <i class="mdi mdi-chevron-left" id="sidebar-toggle-icon"></i>
            </button>
        </div>
        <nav class="nav flex-column">
            <a class="nav-link active" href="{{ route('user.dashboard') }}" data-tooltip="Dashboard">
                <i class="mdi mdi-view-dashboard"></i>
                <span class="nav-text">Dashboard</span>
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
                    <div class="greeting-subtitle">Selamat datang kembali di Dashboard Anda</div>
                    <div class="header-date">
                        <i class="mdi mdi-calendar-clock me-1"></i>
                        {{ $currentDate }} | {{ $currentTime }} WIB
                    </div>
                </div>
                <div class="text-end">
                    <i class="mdi mdi-account-circle" style="font-size: 4rem; opacity: 0.3;"></i>
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

        <!-- Active Subscription Card -->
        @if($activeSubscription)
            <div class="subscription-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h4 class="mb-2">
                            <i class="mdi mdi-crown me-2"></i>
                            Langganan Aktif
                        </h4>
                        <h3 class="mb-3">{{ $activeSubscription->package->name }}</h3>
                        <p class="mb-2">
                            <i class="mdi mdi-calendar me-2"></i>
                            Mulai: {{ $activeSubscription->start_date->format('d M Y') }}
                        </p>
                        <p class="mb-2">
                            <i class="mdi mdi-calendar-check me-2"></i>
                            Berakhir: {{ $activeSubscription->end_date->format('d M Y') }}
                        </p>
                        <div class="mt-3">
                            <small class="opacity-75">Masa aktif tersisa:</small>
                            <div class="countdown" id="countdown">
                                <span id="days">{{ $activeSubscription->end_date->diffInDays(now()) }}</span> hari
                            </div>
                        </div>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-light text-dark px-3 py-2">
                            <i class="mdi mdi-check-circle me-1"></i> Aktif
                        </span>
                    </div>
                </div>
            </div>
        @else
            <div class="subscription-card expired">
                <div class="text-center">
                    <i class="mdi mdi-alert-circle" style="font-size: 3rem;"></i>
                    <h4 class="mt-3 mb-2">Tidak Ada Langganan Aktif</h4>
                    <p class="mb-3">Anda belum memiliki paket aktif. Silakan berlangganan untuk menikmati layanan kami.</p>
                    <a href="{{ route('landing') }}" class="btn btn-light">
                        <i class="mdi mdi-package-variant me-2"></i> Lihat Paket
                    </a>
                </div>
            </div>
        @endif

        <!-- TMS Access Section (only show if user has active subscription) -->
        @if($activeSubscription)
        <div class="card mt-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="mdi mdi-web me-2"></i>
                    Akses TMS (Transportation Management System)
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-4">
                    Setelah pembayaran berhasil, Anda dapat mengakses sistem TMS untuk mengelola distribusi dan logistik Anda.
                </p>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="border rounded p-3">
                            <h6 class="mb-3">
                                <i class="mdi mdi-information-outline me-2 text-primary"></i>
                                Informasi Login
                            </h6>
                            <div class="mb-2">
                                <strong>URL:</strong>
                                <div class="mt-1">
                                    <code class="bg-light p-2 rounded d-block">https://tms.omile.id/demo/dashboard</code>
                                </div>
                            </div>
                            <div class="mb-2">
                                <strong>Username:</strong>
                                <div class="mt-1">
                                    <code class="bg-light p-2 rounded d-block">DEVODI</code>
                                </div>
                            </div>
                            <div class="mb-2">
                                <strong>Password:</strong>
                                <div class="mt-1">
                                    <code class="bg-light p-2 rounded d-block">XRandom20</code>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="border rounded p-3 h-100 d-flex flex-column justify-content-center">
                            <h6 class="mb-3">
                                <i class="mdi mdi-rocket-launch me-2 text-success"></i>
                                Akses Cepat
                            </h6>
                            <p class="text-muted small mb-3">
                                Klik tombol di bawah untuk langsung mengakses TMS. Anda akan diarahkan ke halaman login TMS.
                            </p>
                            <a href="{{ route('tms.access') }}" class="btn btn-primary btn-lg w-100" target="_blank">
                                <i class="mdi mdi-open-in-new me-2"></i>
                                Buka TMS Dashboard
                            </a>
                            <small class="text-muted mt-2 d-block text-center">
                                <i class="mdi mdi-information me-1"></i>
                                Gunakan credentials di atas untuk login
                            </small>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info mt-3 mb-0">
                    <i class="mdi mdi-lightbulb-on me-2"></i>
                    <strong>Tips:</strong> Simpan informasi login ini dengan aman. Anda dapat mengakses TMS selama langganan Anda masih aktif.
                </div>
            </div>
        </div>
        @endif

        <div class="row">
            <!-- Invoices Section -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <i class="mdi mdi-file-document-multiple me-2"></i> Invoice
                    </div>
                    <div class="card-body p-0">
                        @forelse($invoices as $invoice)
                            <div class="invoice-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $invoice->package_name ?? $invoice->package->name ?? 'N/A' }}</h6>
                                        <small class="text-muted">
                                            Order ID: {{ $invoice->order_id }}<br>
                                            Tanggal: {{ $invoice->settlement_time ? $invoice->settlement_time->format('d M Y H:i') : $invoice->created_at->format('d M Y H:i') }}
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        <h6 class="mb-1">Rp {{ number_format($invoice->gross_amount, 0, ',', '.') }}</h6>
                                        <span class="badge badge-status badge-settlement">Paid</span>
                                        <div class="mt-2">
                                            <a href="{{ route('user.invoice.download', $invoice->id) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                                <i class="mdi mdi-download me-1"></i> Download
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5 text-muted">
                                <i class="mdi mdi-file-document-outline" style="font-size: 3rem;"></i>
                                <p class="mt-3">Belum ada invoice</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Transaction History -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <i class="mdi mdi-history me-2"></i> Riwayat Transaksi
                    </div>
                    <div class="card-body p-0">
                        @forelse($transactions->take(5) as $transaction)
                            <div class="invoice-item">
                                <div>
                                    <h6 class="mb-1">{{ $transaction->package_name ?? $transaction->package->name ?? 'N/A' }}</h6>
                                    <small class="text-muted">
                                        {{ $transaction->created_at->format('d M Y') }}
                                    </small>
                                    <div class="mt-2">
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
                                        <span class="ms-2">Rp {{ number_format($transaction->gross_amount, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5 text-muted">
                                <i class="mdi mdi-information-outline" style="font-size: 2rem;"></i>
                                <p class="mt-2 small">Belum ada transaksi</p>
                            </div>
                        @endforelse
                    </div>
                    @if($transactions->count() > 5)
                        <div class="card-footer text-center">
                            <a href="#" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- All Subscriptions -->
        <div class="card mt-4">
            <div class="card-header">
                <i class="mdi mdi-package-variant-closed me-2"></i> Semua Langganan
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Paket</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Berakhir</th>
                                <th>Status</th>
                                <th>Invoice</th>
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
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Berakhir</span>
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
                                    <td colspan="5" class="text-center text-muted">Belum ada langganan</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @if($activeSubscription)
    <script>
        // Countdown timer
        function updateCountdown() {
            const endDate = new Date('{{ $activeSubscription->end_date->toIso8601String() }}');
            const now = new Date();
            const diff = endDate - now;

            if (diff > 0) {
                const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                
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
</body>
</html>

