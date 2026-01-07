<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Transaksi - OMILE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css">
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
                    <small>{{ Auth::user()->isAdmin() ? 'Admin Panel' : 'User Dashboard' }}</small>
                </div>
            </div>
            <button class="sidebar-toggle" onclick="toggleSidebarCollapse()">
                <i class="mdi mdi-chevron-left" id="sidebar-toggle-icon"></i>
            </button>
        </div>
        <nav class="sidebar-nav">
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
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}">Dashboards</a>
                    <a href="{{ route('admin.packages.index') }}">Pages</a>
                    <a href="{{ route('admin.transactions') }}" class="active">Apps</a>
                @else
                    <a href="{{ route('user.dashboard') }}" class="active">Dashboards</a>
                    <a href="{{ route('landing') }}">Pages</a>
                @endif
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
                    <h1 class="page-title">Transaction Detail</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ Auth::user()->isAdmin() ? route('admin.dashboard') : route('user.dashboard') }}">Home</a></li>
                            @if(Auth::user()->isAdmin())
                                <li class="breadcrumb-item"><a href="{{ route('admin.transactions') }}">Transaksi</a></li>
                            @else
                                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
                            @endif
                            <li class="breadcrumb-item active">Detail Transaksi</li>
                        </ol>
                    </nav>
                </div>
                <div class="page-actions">
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.transactions') }}" class="btn btn-light btn-soft">
                            <i class="mdi mdi-arrow-left me-1"></i> Back
                        </a>
                    @else
                        <a href="{{ route('user.dashboard') }}" class="btn btn-light btn-soft">
                            <i class="mdi mdi-arrow-left me-1"></i> Back
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Transaction Details</h5>
                        <p class="card-subtitle mb-0">Complete transaction information</p>
                    </div>
                @if($transaction->transaction_status == 'pending')
                        <button type="button" class="btn btn-sm btn-primary" onclick="checkStatus('{{ $transaction->order_id }}')" id="checkStatusBtn">
                            <i class="mdi mdi-refresh me-1"></i> Check Status
                    </button>
                @endif
                </div>
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
                        <h6 class="text-muted mb-2">Order ID</h6>
                        <p class="h5 mb-0">{{ $transaction->order_id }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Status</h6>
                        <p class="mb-0">
                            @if($transaction->transaction_status == 'settlement')
                                <span class="badge badge-soft success">Settlement</span>
                            @elseif($transaction->transaction_status == 'pending')
                                <span class="badge badge-soft warning">Pending</span>
                            @elseif($transaction->transaction_status == 'cancel')
                                <span class="badge badge-soft danger">Cancel</span>
                            @elseif($transaction->transaction_status == 'expire')
                                <span class="badge badge-soft secondary">Expire</span>
                            @else
                                <span class="badge badge-soft info">{{ $transaction->transaction_status }}</span>
                            @endif
                        </p>
                    </div>
                </div>

                <hr>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Package</h6>
                        <p class="mb-0">{{ $transaction->package_name ?? $transaction->package->name ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Amount</h6>
                        <p class="h5 mb-0">Rp {{ number_format($transaction->gross_amount, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Customer</h6>
                        <p class="mb-0">
                            <strong>{{ $transaction->customer_name ?? $transaction->user->name }}</strong><br>
                            <small class="text-muted">{{ $transaction->customer_email ?? $transaction->user->email }}</small>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Payment Method</h6>
                        <p class="mb-0">{{ $transaction->payment_method ?? $transaction->payment_type ?? 'N/A' }}</p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Transaction Date</h6>
                        <p class="mb-0">{{ $transaction->transaction_time ? $transaction->transaction_time->format('d M Y H:i') : $transaction->created_at->format('d M Y H:i') }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Settlement Date</h6>
                        <p class="mb-0">{{ $transaction->settlement_time ? $transaction->settlement_time->format('d M Y H:i') : '-' }}</p>
                    </div>
                </div>

                @if($transaction->transaction_id)
                <div class="row mb-4">
                    <div class="col-md-12">
                        <h6 class="text-muted mb-2">Transaction ID (Midtrans)</h6>
                        <p class="mb-0">{{ $transaction->transaction_id }}</p>
                    </div>
                </div>
                @endif

                @if($transaction->fraud_status)
                <div class="row mb-4">
                    <div class="col-md-12">
                        <h6 class="text-muted mb-2">Fraud Status</h6>
                        <p class="mb-0">
                            @if($transaction->fraud_status == 'accept')
                                <span class="badge badge-soft success">Accept</span>
                            @else
                                <span class="badge badge-soft warning">{{ $transaction->fraud_status }}</span>
                            @endif
                        </p>
                    </div>
                </div>
                @endif

                @if($transaction->paymentLogs->count() > 0)
                <hr>
                <div class="mb-3">
                    <h5 class="card-title mb-3">Payment Logs</h5>
                <div class="table-responsive">
                        <table class="table">
                        <thead>
                            <tr>
                                    <th>ACTION</th>
                                    <th>STATUS</th>
                                    <th>MESSAGE</th>
                                    <th>DATE</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaction->paymentLogs as $log)
                                <tr>
                                        <td><strong>{{ $log->action }}</strong></td>
                                        <td>
                                            @if($log->status == 'success')
                                                <span class="badge badge-soft success">Success</span>
                                            @else
                                                <span class="badge badge-soft danger">{{ $log->status }}</span>
                                            @endif
                                        </td>
                                    <td>{{ $log->message }}</td>
                                    <td>{{ $log->created_at->format('d M Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                </div>
                @endif

                @if($transaction->transaction_status == 'settlement')
                <div class="mt-4">
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.transaction.invoice', $transaction->id) }}" class="btn btn-primary" target="_blank">
                            <i class="mdi mdi-file-document me-2"></i> View Invoice
                        </a>
                    @else
                        <a href="{{ route('user.invoice.download', $transaction->id) }}" class="btn btn-primary" target="_blank">
                            <i class="mdi mdi-file-document me-2"></i> View Invoice
                        </a>
                    @endif
                </div>
                @endif
        </div>
    </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/alert-auto-close.js') }}"></script>
    <script src="{{ asset('js/sidebar.js') }}"></script>
    <script>
        function checkStatus(orderId) {
            const btn = document.getElementById('checkStatusBtn');
            const originalText = btn.innerHTML;
            
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Checking...';
            
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
                    window.location.reload();
                } else {
                    alert('Failed to check status: ' + (data.message || 'Unknown error'));
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while checking status');
                btn.disabled = false;
                btn.innerHTML = originalText;
            });
        }

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
    </script>
</body>
</html>
