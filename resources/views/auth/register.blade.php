<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - OMILE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
    <div class="auth-wrapper">
        <!-- Left Side - Register Form -->
        <div class="auth-left">
            <div>
                <h1 class="auth-title">Distribution System</h1>
                <p class="auth-subtitle">Welcome, Please create your account</p>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0" style="font-size: 0.9rem;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   placeholder="Enter your full name"
                                   required 
                                   autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   placeholder="Enter your email"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="password" class="form-label">Password</label>
                            <div class="password-wrapper">
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       placeholder="Enter your password"
                                       required>
                                <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                    <i class="mdi mdi-eye" id="eyeIconPassword"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <div class="password-wrapper">
                                <input type="password" 
                                       class="form-control" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       placeholder="Confirm your password"
                                       required>
                                <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
                                    <i class="mdi mdi-eye" id="eyeIconConfirm"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-auth">Sign Up</button>

                    <div class="auth-link">
                        Already have an account? <a href="{{ route('login') }}">Sign in</a>
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
        function togglePassword(fieldId) {
            const passwordInput = document.getElementById(fieldId);
            let eyeIcon;
            
            if (fieldId === 'password') {
                eyeIcon = document.getElementById('eyeIconPassword');
            } else {
                eyeIcon = document.getElementById('eyeIconConfirm');
            }
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('mdi-eye');
                eyeIcon.classList.add('mdi-eye-off');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('mdi-eye-off');
                eyeIcon.classList.add('mdi-eye');
            }
        }

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
