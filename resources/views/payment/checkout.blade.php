<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - OMILE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="{{ asset('css/payment.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">
</head>
<body>
    <!--Navbar Start-->
    <nav class="navbar navbar-expand-lg fixed-top navbar-custom">
        <div class="container">
            <a class="navbar-brand logo" href="{{ route('landing') }}">
                <h1>OMILE .</h1>    
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="mdi mdi-menu"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a href="{{ route('landing') }}" class="nav-link">Home</a>
                    </li>
                    @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            @if(Auth::user()->avatar_url)
                                <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" class="user-avatar">
                            @else
                                <div class="user-avatar">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li>
                                <div class="user-info">
                                    <p class="user-name">{{ Auth::user()->name }}</p>
                                    <p class="user-email">{{ Auth::user()->email }}</p>
                                </div>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('dashboard') }}">
                                    <i class="mdi mdi-view-dashboard"></i> Dashboard
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="mdi mdi-account"></i> Profile
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item" style="border: none; background: none; width: 100%; text-align: left;">
                                        <i class="mdi mdi-logout"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    @else
                    <li class="nav-item">
                        <a href="{{ route('login') }}" class="nav-link">Login</a>
                    </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>
    <!-- Navbar End -->

    <div class="container checkout-container">
        <div class="checkout-header">
            <h2>Checkout</h2>
            <p>Lengkapi data Anda untuk melanjutkan pembayaran</p>
        </div>

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="package-card">
            <h3>{{ $package->name }}</h3>
            <p>{{ $package->description }}</p>
            <div class="package-info">
                <div class="package-info-item">
                    <small>Harga</small>
                    <h4>Rp {{ number_format($package->price, 0, ',', '.') }}</h4>
                </div>
                <div class="package-info-item">
                    <small>Durasi</small>
                    <h5>{{ $package->duration_days }} Hari</h5>
                </div>
            </div>
        </div>

        <div class="form-card">
            <form action="{{ route('payment.create', $package->id) }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="customer_name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('customer_name') is-invalid @enderror" 
                           id="customer_name" name="customer_name" 
                           value="{{ old('customer_name', $user->name) }}" required>
                    @error('customer_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="customer_email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control @error('customer_email') is-invalid @enderror" 
                           id="customer_email" name="customer_email" 
                           value="{{ old('customer_email', $user->email) }}" required>
                    @error('customer_email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="customer_phone" class="form-label">No. Telepon <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('customer_phone') is-invalid @enderror" 
                           id="customer_phone" name="customer_phone" 
                           value="{{ old('customer_phone') }}" 
                           placeholder="08xxxxxxxxxx" required>
                    @error('customer_phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="customer_address" class="form-label">Alamat</label>
                    <textarea class="form-control @error('customer_address') is-invalid @enderror" 
                              id="customer_address" name="customer_address" 
                              rows="3" placeholder="Alamat lengkap (opsional)">{{ old('customer_address') }}</textarea>
                    @error('customer_address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="action-buttons">
                    <a href="{{ route('landing') }}" class="btn btn-secondary">
                        <i class="mdi mdi-arrow-left me-2"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Lanjutkan Pembayaran <i class="mdi mdi-arrow-right ms-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

