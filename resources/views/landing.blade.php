<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>OMILE - Transport Management System</title>
    <meta name="description" content="Optimalkan pengelolaan armada Anda dengan Transport Management System (TMS) yang efisien dan terintegrasi. Pantau pengiriman, kurangi biaya operasional, dan tingkatkan produktivitas dengan solusi TMS kami yang fleksibel dan mudah digunakan." />
    <meta name="keywords" content="aplikasi logistik, aplikasi kurir, transport management system" />
    <meta name="author" content="Themesdesign" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

    <!-- Bootstrap core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <!-- Fallback: <link rel="stylesheet" href="{{ asset('assets/landing/css/bootstrap.min.css') }}" type="text/css"> -->

    <!-- Material Icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css">
    <!-- Fallback: <link rel="stylesheet" type="text/css" href="{{ asset('assets/landing/css/materialdesignicons.min.css') }}" /> -->

    <!-- owl carousel -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/owl.carousel@2.3.4/dist/assets/owl.carousel.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/owl.carousel@2.3.4/dist/assets/owl.theme.default.min.css" />
    <!-- Fallback: <link rel="stylesheet" type="text/css" href="{{ asset('assets/landing/css/owl.carousel.min.css') }}" /> -->

    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/landing/css/style.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/landing/css/custom.css') }}" />

    <!-- Custom Styles -->
    <style>
        /* Hero Section Styling */
        .hero-section {
            padding: 120px 0 80px;
            background: #f8f9fa;
        }
        .hero-title {
            font-size: 2.5rem;
            font-weight: 700;
            line-height: 1.2;
            color: #2d3748;
        }
        .hero-section .text-primary {
            color: #f58905 !important;
        }
        
        /* Mobile Image with Circular Background */
        .home-img {
            position: relative;
        }
        .home-img::before {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            background: linear-gradient(135deg, #2f55d4 0%, #f58905 100%);
            border-radius: 50%;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 0;
        }
        .home-img img {
            position: relative;
            z-index: 1;
            max-width: 250px;
            transform: rotate(-5deg);
        }
        
        /* Hero Section Mobile Image */
        .hero-section img.img-fluid {
            max-width: 400px;
            width: 100%;
            height: auto;
        }
        
        /* Navbar Styling */
        .navbar-custom {
            background: transparent !important; /* Transparent before scroll - default */
            box-shadow: none;
            transition: all 0.3s ease-in-out;
        }
        .navbar-custom.sticky {
            background: #fff !important; /* White background after scroll */
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .navbar-brand h1 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #4a5568; /* Gray text before scroll - more visible */
            margin: 0;
            transition: color 0.3s ease-in-out;
        }
        .navbar-custom.sticky .navbar-brand h1 {
            color: #2d3748; /* Darker gray text after scroll */
        }
        .navbar-btn {
            background: #f58905;
            border: none;
            color: #fff;
            padding: 8px 24px;
            border-radius: 6px;
            font-weight: 500;
        }
        .navbar-btn:hover {
            background: #d47904;
            color: #fff;
        }
        .nav-link {
            color: #4a5568 !important; /* Gray text before scroll - more visible */
            font-weight: 500;
            margin: 0 8px;
            transition: color 0.3s ease-in-out;
        }
        .navbar-custom.sticky .nav-link {
            color: #4a5568 !important; /* Gray text after scroll */
        }
        .navbar-custom.sticky .nav-link:hover {
            color: #f58905 !important;
        }
        
        /* User Avatar Dropdown */
        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #2f55d4 0%, #1e3fa8 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            border: 2px solid #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        .user-avatar:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .user-avatar img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }
        .dropdown-menu {
            border: none;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            border-radius: 8px;
            padding: 8px 0;
            margin-top: 10px;
        }
        .dropdown-item {
            padding: 10px 20px;
            font-size: 14px;
            color: #4a5568;
            transition: all 0.2s;
        }
        .dropdown-item:hover {
            background: #f8f9fa;
            color: #2f55d4;
        }
        .dropdown-item i {
            margin-right: 8px;
            width: 18px;
        }
        .dropdown-divider {
            margin: 8px 0;
        }
        .user-info {
            padding: 12px 20px;
            border-bottom: 1px solid #e9ecef;
            margin-bottom: 4px;
        }
        .user-info .user-name {
            font-weight: 600;
            color: #2d3748;
            font-size: 14px;
            margin: 0;
        }
        .user-info .user-email {
            font-size: 12px;
            color: #718096;
            margin: 4px 0 0 0;
        }
        
        /* Features Section */
        .section {
            padding: 80px 0;
        }
        .bg-light {
            background: #f8f9fa !important;
        }
        .small-title {
            color: #ff6b35;
            font-size: 0.875rem;
            font-weight: 600;
            letter-spacing: 1px;
        }
        .avatar-title {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(102, 126, 234, 0.1) !important;
        }
        .icon-dual-primary {
            color: #2f55d4;
        }
        
        /* Pricing Box Styles - Enhanced hover effects are in custom.css */
        .plan-features {
            text-align: left;
        }
        .plan-features li {
            padding: 10px 0;
            border-bottom: 1px solid #f1f3f5;
            display: flex;
            align-items: center;
        }
        .plan-features li:last-child {
            border-bottom: none;
        }
        .plan-features i {
            width: 20px;
            height: 20px;
        }
        .icon-dual-success {
            color: #10b981;
        }
        .icon-dual-danger {
            color: #ef4444;
        }
        
        /* Ribbon */
        .ribbon {
            position: absolute;
            top: 20px;
            right: -5px;
            z-index: 1;
            overflow: hidden;
            width: 100px;
            height: 100px;
            text-align: right;
        }
        .ribbon span {
            font-size: 11px;
            font-weight: bold;
            color: #fff;
            text-transform: uppercase;
            text-align: center;
            line-height: 20px;
            transform: rotate(45deg);
            -webkit-transform: rotate(45deg);
            width: 120px;
            display: block;
            background: #2f55d4;
            box-shadow: 0 3px 10px -5px rgba(0, 0, 0, 1);
            position: absolute;
            top: 25px;
            right: -30px;
        }
        .ribbon-primary span {
            background: #2f55d4;
        }
        
        /* Footer */
        .footer {
            background: #0f1b42;
            color: #fff;
            padding: 60px 0 0;
        }
        .footer-list-title {
            color: #fff;
            font-weight: 600;
            margin-bottom: 20px;
        }
        .footer-list-menu a {
            color: #cbd5e0;
            text-decoration: none;
            transition: color 0.3s;
        }
        .footer-list-menu a:hover {
            color: #fff;
        }
        .footer p {
            color: #cbd5e0;
            line-height: 1.8;
        }
        .bg-odi {
            background: #f58905 !important; /* Orange background for copyright */
        }
        .icon-dual-light {
            color: #cbd5e0;
        }
        
        /* Button Styling */
        .btn-rounded {
            border-radius: 6px;
            padding: 10px 24px;
            font-weight: 500;
        }
        .btn-primary {
            background: #2f55d4;
            border: none;
        }
        .btn-primary:hover {
            background: #5568d3;
        }
        .btn-outline-primary {
            border: 2px solid #2f55d4;
            color: #2f55d4;
            background: transparent;
        }
        .btn-outline-primary:hover {
            background: #2f55d4;
            color: #fff;
        }
        
        /* Text Colors */
        .text-primary {
            color: #f58905 !important;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2rem;
            }
            .home-img::before {
                width: 350px;
                height: 350px;
            }
            .home-img img {
                max-width: 200px;
            }
            .hero-section img.img-fluid {
                max-width: 280px;
            }
        }
        
        @media (max-width: 576px) {
            .home-img img {
                max-width: 180px;
            }
            .hero-section img.img-fluid {
                max-width: 220px;
            }
        }

   
    </style>

</head>

<body>

    <!--Navbar Start-->
    <nav class="navbar navbar-expand-lg fixed-top navbar-custom sticky sticky-dark">
        <div class="container">
            <a class="navbar-brand logo" href="#">
                <h1>OMILE .</h1>    
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="mdi mdi-menu"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item active">
                        <a href="#home" class="nav-link">Home</a>
                    </li>
                    <li class="nav-item">
                        <a href="#services" class="nav-link">Features</a>
                    </li>
                    <li class="nav-item">
                        <a href="#pricing" class="nav-link">Pricing</a>
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
                <a class="btn btn-rounded navbar-btn" href="https://tms.omile.id/demo/login" style="background: #f58905; color: #fff;">Demo</a>
            </div>
        </div>
    </nav>
    <!-- Navbar End -->

    <!-- Hero section Start -->
    <section class="hero-section" id="home">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="hero-wrapper mb-4" style="margin-top: 30%;">
                        <p class="font-16 text-uppercase">Welcome to</p>
                        <h1 class="hero-title mb-4">Transaport Managament System -<span class="text-primary"> Omile . </span></h1>

                        <p>We are a comprehensive system that accompanies you in every step of the process and every mile of your journey, ensuring seamless and efficient delivery efforts.</p>

                        <div class="mt-4">
                            <a href="#" target="_blank">
                                <img src="{{ asset('assets/images/googleplay.png') }}" alt="Get it on Google Play" style="max-width: 180px; height: auto;">
                            </a>
                        </div>
                    </div>

                </div>

                <div class="col-lg-6 col-sm-8">
                        <img src="{{ asset('assets/images/jp-mobile.png') }}" alt="" class="img-fluid mx-auto d-block">
                    </div>
                </div>
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </section>
    <!-- Hero section End -->

    <!-- Features start -->
    <section class="section bg-light" id="services">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="text-center mb-5">
                        <h5 class="text-primary text-uppercase small-title" style="color: #ff6b35;">Features</h5>
                        <h4 class="mb-3">Services We Provide</h4>
                        <p>It will be as simple as occidental in fact, it will be Occidental.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-4 col-sm-6">
                    <div class="text-center p-4 mt-3">
                        <div class="avatar-md mx-auto mb-4">
                            <span class="avatar-title rounded-circle bg-soft-primary">
                                <i data-feather="grid" class="icon-dual-primary"></i>
                            </span>
                        </div>
                        <h5 class="font-18">Order Management</h5>
                        <p class="mb-0">Create order/shipment, cash management and Account</p>
                    </div>
                </div>
                <div class="col-xl-4 col-sm-6">
                    <div class="text-center p-4 mt-3">
                        <div class="avatar-md mx-auto mb-4">
                            <span class="avatar-title rounded-circle bg-soft-primary">
                                <i data-feather="truck" class="icon-dual-primary"></i>
                            </span>
                        </div>
                        <h5 class="font-18">Operation Checkpoint</h5>
                        <p class="mb-0">First Miles, Middle Miles, Last Miles.</p>
                    </div>
                </div>
                <div class="col-xl-4 col-sm-6">
                    <div class="text-center p-4 mt-3">
                        <div class="avatar-md mx-auto mb-4">
                            <span class="avatar-title rounded-circle bg-soft-primary">
                                <i data-feather="book" class="icon-dual-primary"></i>
                            </span>
                        </div>
                        <h5 class="font-18">Finance</h5>
                        <p class="mb-0">AR & AP Management. Invoicing, Payment, Aging and Reporting</p>
                    </div>
                </div>
                <div class="col-xl-4 col-sm-6">
                    <div class="text-center p-4 mt-3">
                        <div class="avatar-md mx-auto mb-4">
                            <span class="avatar-title rounded-circle bg-soft-primary">
                                <i data-feather="smartphone" class="icon-dual-primary"></i>
                            </span>
                        </div>
                        <h5 class="font-18">Mobile Courier</h5>
                        <p class="mb-0">Manage Pickup & Delivery on your mobile.</p>
                    </div>
                </div>
                <div class="col-xl-4 col-sm-6">
                    <div class="text-center p-4 mt-3">
                        <div class="avatar-md mx-auto mb-4">
                            <span class="avatar-title rounded-circle bg-soft-primary">
                                <i data-feather="map-pin" class="icon-dual-primary"></i>
                            </span>
                        </div>
                        <h5 class="font-18">Tracking Shipment</h5>
                        <p class="mb-0">Monitoring every single checkpoint on your delivery process.</p>
                    </div>
                </div>
                <div class="col-xl-4 col-sm-6">
                    <div class="text-center p-4 mt-3">
                        <div class="avatar-md mx-auto mb-4">
                            <span class="avatar-title rounded-circle bg-soft-primary">
                                <i data-feather="terminal" class="icon-dual-primary"></i>
                            </span>
                        </div>
                        <h5 class="font-18">API</h5>
                        <p class="mb-0">Include API Shipment Order, Tracking & Check Rate</p>
                    </div>
                </div>
            </div>
            <!-- end row -->

        </div>
        <!-- end container -->
    </section>
    <!-- Features end -->

    <!-- Pricing/Subscription Plan start -->
    <section class="section" id="pricing">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="text-center mb-5">
                        <h5 class="text-primary text-uppercase small-title" style="color: #ff6b35;">Pricing</h5>
                        <h4 class="mb-3">Choose Your Plan</h4>
                        <p>Pilih paket yang sesuai dengan kebutuhan bisnis Anda. Semua paket termasuk dukungan dan update berkala.</p>
                    </div>
                </div>
            </div>
            <!-- end row -->

            <div class="row">
                @forelse($packages as $index => $package)
                    @php
                        // Determine icon based on package name
                        $icon = 'package';
                        if (stripos($package->name, 'professional') !== false) {
                            $icon = 'briefcase';
                        } elseif (stripos($package->name, 'enterprise') !== false) {
                            $icon = 'zap';
                        }
                        
                        // Determine if this is the popular plan (middle package)
                        $isPopular = ($index == 1 && count($packages) >= 2);
                        
                        // Check if price is custom (Enterprise with high price)
                        $isCustomPrice = (stripos($package->name, 'enterprise') !== false && $package->price >= 10000000);
                    @endphp
                    
                    <div class="col-lg-4 col-md-6">
                        <div class="card pricing-box mt-4">
                            <div class="card-body p-4 {{ $isPopular ? 'position-relative' : '' }}">
                                @if($isPopular)
                                    <div class="ribbon ribbon-primary"><span>POPULAR</span></div>
                                @endif
                                <div class="text-center">
                                    <div class="avatar-md mx-auto mb-4">
                                        <span class="avatar-title rounded-circle bg-soft-primary">
                                            <i data-feather="{{ $icon }}" class="icon-dual-primary"></i>
                                        </span>
                                    </div>
                                    <h5 class="mb-2">{{ $package->name }}</h5>
                                    <p class="text-muted mb-4">{{ $package->description ?: 'Paket yang sesuai untuk kebutuhan Anda' }}</p>
                                </div>
                                <div class="text-center mb-4">
                                    @if($isCustomPrice)
                                        <h2 class="mb-0">Custom<span class="font-16 text-muted">/bulan</span></h2>
                                        <p class="text-muted font-14">Hubungi kami untuk penawaran</p>
                                    @else
                                        <h2 class="mb-0">Rp {{ number_format($package->price, 0, ',', '.') }}<span class="font-16 text-muted">/bulan</span></h2>
                                    @endif
                                </div>
                                @if($package->features && count($package->features) > 0)
                                    <ul class="list-unstyled plan-features mb-4">
                                        @foreach($package->features as $feature)
                                            <li>
                                                @if($feature['included'] ?? true)
                                                    <i data-feather="check" class="icon-dual-success me-2"></i> {{ $feature['text'] }}
                                                @else
                                                    <i data-feather="x" class="icon-dual-danger me-2"></i> 
                                                    <span style="text-decoration: line-through; opacity: 0.6;">{{ $feature['text'] }}</span>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                                <div class="text-center">
                                    @if($isCustomPrice)
                                        <a href="mailto:marketing@odisys.id" class="btn btn-outline-primary btn-rounded">Contact Sales</a>
                                    @else
                                        @auth
                                            <a href="{{ route('payment.checkout', $package->id) }}" class="btn {{ $isPopular ? 'btn-primary' : 'btn-outline-primary' }} btn-rounded">Beli Sekarang</a>
                                        @else
                                            <a href="{{ route('login') }}" class="btn {{ $isPopular ? 'btn-primary' : 'btn-outline-primary' }} btn-rounded">Get Started</a>
                                        @endauth
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end col -->
                @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <p class="text-muted">Tidak ada paket tersedia saat ini.</p>
                        </div>
                    </div>
                @endforelse
            </div>
            <!-- end row -->

            <div class="row mt-5">
                <div class="col-lg-12">
                    <div class="text-center">
                        <p class="text-muted mb-4">Semua paket termasuk update berkala, backup data, dan dukungan teknis</p>
                        <p class="text-muted">Butuh paket khusus? <a href="mailto:marketing@odisys.id" class="text-primary">Hubungi tim kami</a></p>
                    </div>
                </div>
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </section>
    <!-- Pricing/Subscription Plan end -->

    <!-- Footer start -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-sm-6">
                    <div>
                        <img src="{{ asset('assets/images/odisys.png') }}" alt="" >
                        <h5 class="mt-2 mb-4 footer-list-title">PT ODISYS INDONESIA</h5>
                        <p>OMILE - TMS power by PT ODISYS INDONESIA.</p> 
                        <p>An integrated information technology services & consulting companies, which offers the most complete end to end solutions for Logistics industry.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div>
                        <h5 class="mb-4 footer-list-title">Product</h5>
                        <ul class="list-unstyled footer-list-menu">
                            <li><a href="#">Transport Management System</a></li>
                            <li><a href="#">Warehouse Management System</a></li>
                            <li><a href="#">Route Optimization</a></li>
                            <li><a href="#">Customs </a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-sm-6">
                    <div>
                        <h5 class="mb-4 footer-list-title">Resources</h5>
                        <ul class="list-unstyled footer-list-menu">
                            <li><a href="https://portal.omile.id">Portal</a></li>
                            <li><a href="#">Documentation</a></li>
                            <li><a href="https://documenter.getpostman.com/view/24301392/2sA3s3HX1T">API</a></li>
                        </ul>
                    </div>   
                    <div>
                        <h5 class="mb-4 footer-list-title">Use Cases</h5>
                        <ul class="list-unstyled footer-list-menu">
                            <li><a href="#">Courier Company</a></li>
                            <li><a href="#">Forwarder</a></li>
                            <li><a href="#">e-Commerce</a></li>
                            <li><a href="#">Agregator</a></li>
                        </ul>
                    </div> 
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div>
                        <h5 class="mb-4 footer-list-title">Contact</h5>

                        <div>
                            <div class="d-flex">
                                <i data-feather="map-pin" class="icon-dual-light icons-sm mt-1 me-2 flex-shrink-0"></i>
                                <div class="flex-grow-1">
                                    <p>GP PLAZA, 3rd Floor - Unit 8, Jl. Gelora II No. 1, Tanah Abang, Jakarta Pusat 10270</p>
                                </div>
                            </div>
                            <div class="d-flex">
                                <i data-feather="map-pin" class="icon-dual-light icons-sm mt-1 me-2 flex-shrink-0"></i>
                                <div class="flex-grow-1">
                                    <p>Ruko Grand Galaxy City, Blok RSN-2 No. 66, Jaka Setia, Bekasi Selatan, Jawa Barat 17147</p>
                                </div>
                            </div>
                            <div class="d-flex">
                                <i data-feather="mail" class="icon-dual-light icons-sm mt-1 me-2 flex-shrink-0"></i>
                                <div class="flex-grow-1">
                                    <p>marketing@odisys.id</p>
                                </div>
                            </div>
                            <div class="d-flex">
                                <i data-feather="phone" class="icon-dual-light icons-sm mt-1 me-2 flex-shrink-0"></i>
                                <div class="flex-grow-1">
                                    <p>+62 21 8946 5222</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->

    </footer>
    <!-- Footer end -->

    <!-- Footer alt start -->
    <section class="bg-odi py-3">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="float-sm-start">
                        <p class="copyright-desc text-white mb-0">
                            Copyright <script>document.write(new Date().getFullYear())</script> © PT Odisys Indonesia All rights reserved.
                        </p>
                    </div>
                    <div class="float-sm-end mt-4 mt-sm-0">
                    </div>
                </div>
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </section>
    <!-- Footer alt start -->

    <!-- Javascript -->
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <!-- Fallback: <script src="{{ asset('assets/landing/js/jquery.min.js') }}"></script> -->
    
    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <!-- Fallback: <script src="{{ asset('assets/landing/js/bootstrap.bundle.min.js') }}"></script> -->
    
    <!-- jQuery Easing -->
    <script src="https://cdn.jsdelivr.net/npm/jquery.easing@1.4.1/jquery.easing.min.js"></script>
    <!-- Fallback: <script src="{{ asset('assets/landing/js/jquery.easing.min.js') }}"></script> -->
    
    <!-- Feather Icons -->
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <!-- Fallback: <script src="{{ asset('assets/landing/js/feather.min.js') }}"></script> -->

    <!-- owl carousel -->
    <script src="https://cdn.jsdelivr.net/npm/owl.carousel@2.3.4/dist/owl.carousel.min.js"></script>
    <!-- Fallback: <script src="{{ asset('assets/landing/js/owl.carousel.min.js') }}"></script> -->

    <!-- app js -->
    <script src="{{ asset('assets/landing/js/app.js') }}"></script>

</body>

</html>

