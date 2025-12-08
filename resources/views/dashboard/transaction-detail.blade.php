<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Transaksi - OMILE</title>
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
                    <small>{{ Auth::user()->isAdmin() ? 'Admin Panel' : 'User Dashboard' }}</small>
                </div>
            </div>
            <button class="sidebar-toggle" onclick="toggleSidebarCollapse()">
                <i class="mdi mdi-chevron-left" id="sidebar-toggle-icon"></i>
            </button>
        </div>
        <nav class="nav flex-column">
            @if(Auth::user()->isAdmin())
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
            @else
                <a class="nav-link" href="{{ route('user.dashboard') }}" data-tooltip="Dashboard">
                    <i class="mdi mdi-view-dashboard"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
            @endif
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
        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Detail Transaksi</h5>
                @if($transaction->transaction_status == 'pending')
                    <button type="button" class="btn btn-light btn-sm" onclick="checkStatus('{{ $transaction->order_id }}')" id="checkStatusBtn">
                        <i class="mdi mdi-refresh me-1"></i> Cek Status
                    </button>
                @endif
            </div>
            <div class="card-body">
                @if(session('status_check'))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        {{ session('status_check') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Order ID</h6>
                        <p class="h5">{{ $transaction->order_id }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Status</h6>
                        <p>
                            @if($transaction->transaction_status == 'settlement')
                                <span class="badge bg-success">Settlement</span>
                            @elseif($transaction->transaction_status == 'pending')
                                <span class="badge bg-warning">Pending</span>
                            @elseif($transaction->transaction_status == 'cancel')
                                <span class="badge bg-danger">Cancel</span>
                            @elseif($transaction->transaction_status == 'expire')
                                <span class="badge bg-secondary">Expire</span>
                            @else
                                <span class="badge bg-info">{{ $transaction->transaction_status }}</span>
                            @endif
                        </p>
                    </div>
                </div>

                <hr>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Paket</h6>
                        <p>{{ $transaction->package_name ?? $transaction->package->name ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Jumlah</h6>
                        <p class="h5">Rp {{ number_format($transaction->gross_amount, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Pelanggan</h6>
                        <p>
                            {{ $transaction->customer_name ?? $transaction->user->name }}<br>
                            <small class="text-muted">{{ $transaction->customer_email ?? $transaction->user->email }}</small>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Metode Pembayaran</h6>
                        <p>{{ $transaction->payment_method ?? $transaction->payment_type ?? 'N/A' }}</p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Tanggal Transaksi</h6>
                        <p>{{ $transaction->transaction_time ? $transaction->transaction_time->format('d M Y H:i') : $transaction->created_at->format('d M Y H:i') }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Tanggal Settlement</h6>
                        <p>{{ $transaction->settlement_time ? $transaction->settlement_time->format('d M Y H:i') : '-' }}</p>
                    </div>
                </div>

                @if($transaction->transaction_id)
                <div class="row mb-4">
                    <div class="col-md-12">
                        <h6 class="text-muted">Transaction ID (Midtrans)</h6>
                        <p>{{ $transaction->transaction_id }}</p>
                    </div>
                </div>
                @endif

                @if($transaction->fraud_status)
                <div class="row mb-4">
                    <div class="col-md-12">
                        <h6 class="text-muted">Fraud Status</h6>
                        <p>
                            @if($transaction->fraud_status == 'accept')
                                <span class="badge bg-success">Accept</span>
                            @else
                                <span class="badge bg-warning">{{ $transaction->fraud_status }}</span>
                            @endif
                        </p>
                    </div>
                </div>
                @endif

                @if($transaction->paymentLogs->count() > 0)
                <hr>
                <h6 class="mb-3">Payment Logs</h6>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Status</th>
                                <th>Message</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaction->paymentLogs as $log)
                                <tr>
                                    <td>{{ $log->action }}</td>
                                    <td>{{ $log->status }}</td>
                                    <td>{{ $log->message }}</td>
                                    <td>{{ $log->created_at->format('d M Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif

                @if($transaction->transaction_status == 'settlement')
                <div class="mt-4">
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.transaction.invoice', $transaction->id) }}" class="btn btn-primary" target="_blank">
                            <i class="mdi mdi-file-document me-2"></i> Lihat Invoice
                        </a>
                    @else
                        <a href="{{ route('user.invoice.download', $transaction->id) }}" class="btn btn-primary" target="_blank">
                            <i class="mdi mdi-file-document me-2"></i> Lihat Invoice
                        </a>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/sidebar.js') }}"></script>
    <script>
        function checkStatus(orderId) {
            const btn = document.getElementById('checkStatusBtn');
            const originalText = btn.innerHTML;
            
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Mengecek...';
            
            fetch(`/payment/check-status/${orderId}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Reload page to show updated status
                    window.location.reload();
                } else {
                    alert('Gagal mengecek status: ' + (data.message || 'Unknown error'));
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengecek status');
                btn.disabled = false;
                btn.innerHTML = originalText;
            });
        }
    </script>
</body>
</html>

