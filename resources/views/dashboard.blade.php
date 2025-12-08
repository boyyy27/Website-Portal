<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - OMILE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css">
    <style>
        body {
            background: #f8f9fa;
        }
        .navbar {
            background: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .dashboard-header {
            background: linear-gradient(135deg, #2f55d4 0%, #f58905 100%);
            color: #fff;
            padding: 40px 0;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('landing') }}">OMILE .</a>
            <div class="ms-auto">
                <span class="me-3">Halo, {{ Auth::user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="dashboard-header">
        <div class="container">
            <h1 class="mb-0">Dashboard</h1>
            <p class="mb-0">Selamat datang di OMILE Transport Management System</p>
        </div>
    </div>

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Selamat Datang!</h5>
                        <p class="card-text">Anda telah berhasil login. Halaman dashboard ini akan dikembangkan lebih lanjut.</p>
                        <a href="{{ route('landing') }}" class="btn btn-primary">Kembali ke Landing Page</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

