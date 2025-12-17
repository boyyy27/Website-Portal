<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - OMILE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700&display=swap');
        body {
            font-family: 'Open Sans', sans-serif;
            overflow-x: hidden;
            overflow-y: auto;
            background: linear-gradient(135deg, #2f55d4 0%, #f58905 100%);
            min-height: 100vh;
        }
        .auth-wrapper {
            display: flex;
            min-height: 100vh;
        }
        .auth-left {
            flex: 1;
            max-width: 600px;
            background: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px 80px;
            overflow-y: auto;
            position: relative;
        }
        .auth-right {
            flex: 1;
            position: relative;
            overflow: hidden;
        }
        .auth-carousel {
            width: 100%;
            height: 100%;
            position: relative;
        }
        .auth-carousel-item {
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: absolute;
            top: 0;
            left: 0;
            opacity: 0;
            transition: opacity 1s ease-in-out;
        }
        .auth-carousel-item.active {
            opacity: 1;
        }
        .auth-carousel-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.3);
        }
        .carousel-indicators {
            position: absolute;
            bottom: 60px;
            left: 35%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
            z-index: 10;
        }
        .carousel-indicator {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            border: 2px solid rgba(255, 255, 255, 0.8);
            cursor: pointer;
            transition: all 0.3s;
        }
        .carousel-indicator.active {
            background: #fff;
            width: 30px;
            border-radius: 5px;
        }
        .auth-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #ff6b35;
            margin-bottom: 12px;
            background: linear-gradient(135deg,rgb(255, 167, 36) 0%,rgb(227, 161, 19) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .auth-subtitle {
            color: #718096;
            margin-bottom: 40px;
            font-size: 1rem;
            line-height: 1.6;
        }
        .verification-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg,rgb(212, 140, 47) 0%,rgb(73, 109, 227) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            box-shadow: 0 8px 24px rgba(47, 85, 212, 0.3);
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                box-shadow: 0 8px 24px rgba(47, 85, 212, 0.3);
            }
            50% {
                transform: scale(1.05);
                box-shadow: 0 12px 32px rgba(47, 85, 212, 0.4);
            }
        }
        .verification-icon i {
            font-size: 40px;
            color: #fff;
        }
        .email-info-box {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border-left: 4px solid #2196f3;
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(33, 150, 243, 0.1);
        }
        .email-info-box .icon {
            font-size: 24px;
            margin-right: 12px;
            vertical-align: middle;
        }
        .email-info-box .email-text {
            color: #1565c0;
            font-size: 0.95rem;
            font-weight: 600;
            margin: 0;
            word-break: break-all;
        }
        .code-input-container {
            margin-bottom: 30px;
        }
        .code-input-label {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 15px;
            font-size: 0.95rem;
            text-align: center;
        }
        .code-inputs {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-bottom: 20px;
        }
        .code-input {
            width: 60px;
            height: 70px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            text-align: center;
            font-size: 32px;
            font-weight: 700;
            font-family: 'Courier New', monospace;
            color:rgb(251, 251, 251);
            transition: all 0.3s;
            background: #f8f9fa;
        }
        .code-input:focus {
            border-color:#f58905;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(47, 85, 212, 0.1);
            outline: none;
            transform: scale(1.05);
        }
        .code-input.filled {
            border-color: #f58905;
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            color: #1565c0;
        }
        .code-hint {
            text-align: center;
            color:rgb(255, 255, 255);
            font-size: 0.85rem;
            margin-top: 10px;
        }
        .btn-auth {
            background: linear-gradient(135deg,rgb(255, 167, 36) 0%,rgb(227, 161, 19) 100%);
            border: none;
            color: #fff;
            padding: 14px 30px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            width: 100%;
            transition: all 0.3s;
            margin-top: 20px;
            box-shadow: 0 4px 12px rgba(255, 255, 255, 0.3);
        }
        .btn-auth:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(244, 246, 255, 0.4);
            color: #fff;
        }
        .btn-auth:active {
            transform: translateY(0);
        }
        .btn-resend {
            background: transparent;
            border: 2px solid #f58905;
            color:rgb(0, 0, 0);
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.95rem;
            width: 100%;
            transition: all 0.3s;
            margin-top: 12px;
        }
        .btn-resend:hover {
            background: linear-gradient(135deg,rgb(255, 167, 36) 0%,rgb(227, 161, 19) 100%);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(47, 85, 212, 0.3);
        }
        .auth-footer {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
            color: #fff;
            font-size: 0.85rem;
            z-index: 10;
            padding: 0 16px;
            white-space: nowrap;
        }
        .auth-footer .powered-by {
            color: #f58905;
            font-weight: 700;
        }
        .alert {
            border-radius: 12px;
            border: none;
            padding: 16px 20px;
            margin-bottom: 25px;
        }
        .alert-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border-left: 4px solid #28a745;
            color: #155724;
        }
        .alert-danger {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            border-left: 4px solid #dc3545;
            color: #721c24;
        }
        .info-box {
            background: linear-gradient(135deg, #fff5e6 0%, #ffe8cc 100%);
            border-left: 4px solid #ff9800;
            padding: 18px;
            margin-bottom: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(255, 152, 0, 0.1);
        }
        .info-box .icon {
            font-size: 20px;
            margin-right: 10px;
            vertical-align: middle;
        }
        .info-box p {
            margin: 0;
            color: #e65100;
            font-size: 0.9rem;
            line-height: 1.6;
        }
        .resend-link {
            text-align: center;
            margin-top: 25px;
            color: #718096;
            font-size: 0.9rem;
        }
        .resend-link a {
            color: #ff6b35;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }
        .resend-link a:hover {
            color: #ff6b35;
            text-decoration: underline;
        }
        /* Responsive layout */
        @media (max-width: 992px) {
            .auth-wrapper {
                flex-direction: column;
            }
            .auth-left,
            .auth-right {
                width: 100%;
                max-width: 100%;
            }
            .auth-left {
                padding: 32px 20px 40px;
                min-height: auto;
            }
            .auth-right {
                order: -1;
                min-height: 260px;
            }
            .auth-footer {
                position: static;
                margin-top: 16px;
                transform: none;
                left: auto;
            }
        }
        @media (max-width: 576px) {
            .auth-wrapper {
                flex-direction: column;
            }
            .auth-left {
                padding: 24px 16px 32px;
            }
            .auth-right {
                display: none; /* Sembunyikan carousel di layar sangat kecil agar fokus ke form */
            }
            .auth-title {
                font-size: 1.8rem;
            }
            .code-inputs {
                gap: 6px;
            }
            .code-input {
                width: 44px;
                height: 56px;
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="auth-wrapper">
        <!-- Left Side - Verification Form -->
        <div class="auth-left">
            <div>
                <div class="verification-icon">
                    <i class="mdi mdi-email-check"></i>
                </div>
                
                @php
                    $verificationCode = session('verification_code_' . $user->id);
                @endphp

                <h1 class="auth-title">Verifikasi Email Anda</h1>
                
                @if(!$verificationCode)
                    <p class="auth-subtitle">
                        Sesi verifikasi tidak ditemukan atau sudah kedaluwarsa. 
                        Silakan lakukan pendaftaran ulang untuk mendapatkan kode verifikasi baru.
                    </p>
                    <div class="alert alert-danger">
                        <i class="mdi mdi-alert-circle me-2"></i>
                        Sesi verifikasi tidak ditemukan. Silakan daftar ulang.
                    </div>
                @else
                    <p class="auth-subtitle">
                        Kami telah mengirimkan kode verifikasi 6 digit ke email Anda. 
                        Silakan cek folder <strong>Inbox dan Spam/Promotions</strong>, lalu masukkan kode tersebut di bawah ini.
                    </p>
                    <div class="info-box">
                        <p>
                            <i class="mdi mdi-information icon"></i>
                            <strong>Catatan:</strong> Email mungkin membutuhkan waktu beberapa menit untuk sampai. 
                            Jika tidak menerima email, klik tombol "Kirim Ulang Kode" di bawah.
                        </p>
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="mdi mdi-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="mdi mdi-alert-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="email-info-box">
                    <p class="email-text">
                        <i class="mdi mdi-email icon"></i>
                        <strong>Email:</strong> {{ $user->email }}
                    </p>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="mdi mdi-alert-circle me-2"></i>
                        <ul class="mb-0" style="font-size: 0.9rem; padding-left: 20px;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('verification.verify') }}" id="verificationForm">
                    @csrf
                    
                    <div class="code-input-container">
                        <label class="code-input-label">Masukkan Kode Verifikasi (6 digit)</label>
                        <div class="code-inputs" id="codeInputs">
                            <input type="text" class="code-input" maxlength="1" pattern="[0-9]" inputmode="numeric" autocomplete="off" required>
                            <input type="text" class="code-input" maxlength="1" pattern="[0-9]" inputmode="numeric" autocomplete="off" required>
                            <input type="text" class="code-input" maxlength="1" pattern="[0-9]" inputmode="numeric" autocomplete="off" required>
                            <input type="text" class="code-input" maxlength="1" pattern="[0-9]" inputmode="numeric" autocomplete="off" required>
                            <input type="text" class="code-input" maxlength="1" pattern="[0-9]" inputmode="numeric" autocomplete="off" required>
                            <input type="text" class="code-input" maxlength="1" pattern="[0-9]" inputmode="numeric" autocomplete="off" required>
                        </div>
                        <input type="hidden" name="verification_code" id="verificationCode" required>
                        <p class="code-hint">
                            <i class="mdi mdi-information-outline"></i> Kode berlaku selama 15 menit
                        </p>
                    </div>

                    <button type="submit" class="btn btn-auth">
                        <i class="mdi mdi-check-circle me-2"></i> Verifikasi Email
                    </button>

                    <form method="POST" action="{{ route('verification.resend') }}" class="mt-2">
                        @csrf
                        <button type="submit" class="btn btn-resend">
                            <i class="mdi mdi-email-send me-2"></i> Kirim Ulang Kode
                        </button>
                    </form>

                    <div class="resend-link">
                        <p class="mb-0">
                            Belum menerima email? 
                            <a href="{{ route('verification.resend') }}" 
                               onclick="event.preventDefault(); document.getElementById('resend-form').submit();">
                                Klik di sini
                            </a>
                        </p>
                        <form id="resend-form" action="{{ route('verification.resend') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Right Side - Carousel Images -->
        <div class="auth-right">
            <div class="auth-carousel" id="authCarousel">
            <div class="auth-carousel-item active" style="background-image: url('https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80');"></div>
                <div class="auth-carousel-item" style="background-image: url('https://images.unsplash.com/photo-1603199766980-fdd4ac568a11?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');"></div>
                <div class="auth-carousel-item" style="background-image: url('https://images.unsplash.com/photo-1634638022845-1ab614a94128?q=80&w=764&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');"></div>
                <div class="auth-carousel-item" style="background-image: url('https://images.unsplash.com/photo-1535916707207-35f97e715e1c?q=80&w=1074&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');"></div>
                <div class="auth-carousel-item" style="background-image: url('https://images.unsplash.com/photo-1726315185844-b4cb8e95cab3?q=80&w=1219&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');"></div>
            </div>
            <div class="carousel-indicators" id="carouselIndicators">
                <span class="carousel-indicator active" onclick="goToSlide(0)"></span>
                <span class="carousel-indicator" onclick="goToSlide(1)"></span>
                <span class="carousel-indicator" onclick="goToSlide(2)"></span>
                <span class="carousel-indicator" onclick="goToSlide(3)"></span>
                <span class="carousel-indicator" onclick="goToSlide(4)"></span>
            </div>
            <div class="auth-footer">
                <p class="mb-0">© Powered By <span class="powered-by">ODISYS</span></p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Code input handling
        const codeInputs = document.querySelectorAll('.code-input');
        const hiddenInput = document.getElementById('verificationCode');
        
        codeInputs.forEach((input, index) => {
            // Only allow numbers
            input.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9]/g, '');
                
                if (this.value) {
                    this.classList.add('filled');
                    
                    // Move to next input
                    if (index < codeInputs.length - 1) {
                        codeInputs[index + 1].focus();
                    }
                } else {
                    this.classList.remove('filled');
                }
                
                updateHiddenInput();
            });
            
            // Handle backspace
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' && !this.value && index > 0) {
                    codeInputs[index - 1].focus();
                    codeInputs[index - 1].value = '';
                    codeInputs[index - 1].classList.remove('filled');
                    updateHiddenInput();
                }
            });
            
            // Handle paste
            input.addEventListener('paste', function(e) {
                e.preventDefault();
                const pastedData = e.clipboardData.getData('text').replace(/[^0-9]/g, '').slice(0, 6);
                
                pastedData.split('').forEach((char, i) => {
                    if (codeInputs[i]) {
                        codeInputs[i].value = char;
                        codeInputs[i].classList.add('filled');
                    }
                });
                
                if (pastedData.length === 6) {
                    codeInputs[5].focus();
                } else if (pastedData.length > 0) {
                    codeInputs[pastedData.length].focus();
                }
                
                updateHiddenInput();
            });
        });
        
        function updateHiddenInput() {
            const code = Array.from(codeInputs).map(input => input.value).join('');
            hiddenInput.value = code;
        }
        
        // Form submission
        document.getElementById('verificationForm').addEventListener('submit', function(e) {
            const code = hiddenInput.value;
            if (code.length !== 6) {
                e.preventDefault();
                alert('Silakan masukkan kode verifikasi 6 digit');
                codeInputs[0].focus();
            }
        });
        
        // Auto focus first input on load
        window.addEventListener('load', function() {
            codeInputs[0].focus();
        });
        
        // Carousel functionality
        let currentSlide = 0;
        const slides = document.querySelectorAll('.auth-carousel-item');
        const indicators = document.querySelectorAll('.carousel-indicator');
        const totalSlides = slides.length;

        function showSlide(index) {
            slides.forEach(slide => slide.classList.remove('active'));
            indicators.forEach(indicator => indicator.classList.remove('active'));
            
            slides[index].classList.add('active');
            indicators[index].classList.add('active');
            
            currentSlide = index;
        }

        function goToSlide(index) {
            showSlide(index);
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % totalSlides;
            showSlide(currentSlide);
        }

        // Auto-play carousel every 5 seconds
        setInterval(nextSlide, 5000);

        // Initialize carousel
        showSlide(0);
    </script>
</body>
</html>
