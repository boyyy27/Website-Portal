<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - OMILE</title>
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
            <a class="nav-link active" href="{{ route('admin.packages.index') }}" data-tooltip="Manajemen Paket">
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
                <a href="{{ route('admin.packages.index') }}" class="active">Pages</a>
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
                    <h1 class="page-title">Tambah Paket</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.packages.index') }}">Manajemen Paket</a></li>
                            <li class="breadcrumb-item active">Tambah Paket</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div>
                    <h5 class="card-title">Tambah Paket Baru</h5>
                    <p class="card-subtitle mb-0">Tambahkan paket baru ke dalam sistem</p>
                </div>
            </div>
            <div class="card-body">
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

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('admin.packages.store') }}" method="POST" id="packageForm">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Paket <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">Price (Rp) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                   id="price" name="price" value="{{ old('price') }}" min="0" step="1" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="duration_days" class="form-label">Durasi (Hari) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('duration_days') is-invalid @enderror" 
                                   id="duration_days" name="duration_days" value="{{ old('duration_days', 30) }}" min="1" required>
                            @error('duration_days')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" 
                               {{ old('is_active') ? 'checked' : 'checked' }}>
                        <label class="form-check-label" for="is_active">
                            Aktifkan paket
                        </label>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <label class="form-label mb-0">Fitur Paket <small class="text-muted">(Opsional)</small></label>
                            <button type="button" class="btn btn-sm btn-success" onclick="addFeature()">
                                <i class="mdi mdi-plus me-1"></i> Tambah Fitur
                            </button>
                        </div>
                        <div id="featuresContainer">
                            <!-- Features will be added here dynamically -->
                        </div>
                        @error('features')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                        @error('features.*')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.packages.index') }}" class="btn btn-light btn-soft">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-content-save me-2"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/alert-auto-close.js') }}"></script>
    <script src="{{ asset('js/sidebar.js') }}"></script>
    <script>
        let featureIndex = 0;

        function addFeature(text = '', included = true) {
            const container = document.getElementById('featuresContainer');
            const featureDiv = document.createElement('div');
            featureDiv.className = 'feature-item';
            featureDiv.id = `feature-${featureIndex}`;
            
            featureDiv.innerHTML = `
                <div class="row align-items-center g-3">
                    <div class="col-md-8">
                        <input type="text" 
                               class="form-control" 
                               name="features[${featureIndex}][text]" 
                               placeholder="Nama fitur (contoh: Hingga 5 user)" 
                               value="${text}">
                    </div>
                    <div class="col-md-3">
                        <div class="form-check d-flex align-items-center h-100">
                            <input type="hidden" name="features[${featureIndex}][included]" value="0">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   name="features[${featureIndex}][included]" 
                                   value="1" 
                                   id="included-${featureIndex}"
                                   ${included ? 'checked' : ''}>
                            <label class="form-check-label" for="included-${featureIndex}">
                                Included
                            </label>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-sm btn-outline-danger w-100" onclick="removeFeature(${featureIndex})" title="Hapus fitur">
                            <i class="mdi mdi-delete"></i>
                        </button>
                    </div>
                </div>
            `;
            
            container.appendChild(featureDiv);
            featureIndex++;
        }

        function removeFeature(index) {
            const featureDiv = document.getElementById(`feature-${index}`);
            if (featureDiv) {
                featureDiv.remove();
            }
        }

        // Form submission handler - remove empty feature inputs
        document.getElementById('packageForm').addEventListener('submit', function(e) {
            const featureInputs = document.querySelectorAll('#featuresContainer input[name*="[text]"]');
            featureInputs.forEach(function(input) {
                if (!input.value.trim()) {
                    const featureItem = input.closest('.feature-item');
                    if (featureItem) {
                        featureItem.remove();
                    }
                }
            });
        });

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
