<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Paket - OMILE</title>
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

        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Edit Paket</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.packages.update', $package->id) }}" method="POST" id="packageForm">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Paket <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $package->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3">{{ old('description', $package->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                   id="price" name="price" value="{{ old('price', $package->price) }}" min="0" step="1" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="duration_days" class="form-label">Durasi (Hari) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('duration_days') is-invalid @enderror" 
                                   id="duration_days" name="duration_days" value="{{ old('duration_days', $package->duration_days) }}" min="1" required>
                            @error('duration_days')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" 
                               {{ old('is_active', $package->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            Aktifkan paket
                        </label>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <label class="form-label mb-0">Fitur Paket</label>
                            <button type="button" class="btn btn-sm btn-success" onclick="addFeature()">
                                <i class="mdi mdi-plus me-1"></i> Tambah Fitur
                            </button>
                        </div>
                        <div id="featuresContainer">
                            <!-- Features will be loaded here -->
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.packages.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-content-save me-2"></i> Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let featureIndex = 0;
        const existingFeatures = @json($package->features ?? []);

        function addFeature(text = '', included = true) {
            const container = document.getElementById('featuresContainer');
            const featureDiv = document.createElement('div');
            featureDiv.className = 'feature-item';
            featureDiv.id = `feature-${featureIndex}`;
            
            featureDiv.innerHTML = `
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <input type="text" 
                               class="form-control" 
                               name="features[${featureIndex}][text]" 
                               placeholder="Nama fitur (contoh: Hingga 5 user)" 
                               value="${text}" 
                               required>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check">
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
                        <button type="button" class="btn btn-sm btn-danger" onclick="removeFeature(${featureIndex})">
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

        // Load existing features on page load
        document.addEventListener('DOMContentLoaded', function() {
            if (existingFeatures && existingFeatures.length > 0) {
                existingFeatures.forEach(feature => {
                    addFeature(feature.text, feature.included ?? true);
                });
            } else {
                addFeature(); // Add one empty feature if none exist
            }
        });
    </script>
</body>
</html>
