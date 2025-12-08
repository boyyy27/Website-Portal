<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting to TMS - OMILE</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700&display=swap');
        body {
            font-family: 'Open Sans', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
        }
        .redirect-container {
            background: white;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            text-align: center;
            max-width: 500px;
        }
        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .credentials-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: left;
        }
        .credentials-box strong {
            display: block;
            margin-bottom: 10px;
            color: #667eea;
        }
    </style>
</head>
<body>
    <div class="redirect-container">
        <h2>Mengarahkan ke TMS...</h2>
        <div class="spinner"></div>
        <p>Mohon tunggu, Anda akan diarahkan ke halaman TMS.</p>
        
        <div class="credentials-box">
            <strong>Informasi Login TMS:</strong>
            <div><strong>Username:</strong> {{ $tmsUsername }}</div>
            <div><strong>Password:</strong> {{ $tmsPassword }}</div>
        </div>
        
        <p style="font-size: 0.9rem; color: #666;">
            Jika tidak otomatis redirect, <a href="{{ $tmsUrl }}" target="_blank">klik di sini</a>
        </p>
    </div>

    <!-- Auto-login form (hidden) -->
    <form id="tmsLoginForm" action="{{ $tmsUrl }}" method="POST" style="display: none;">
        <input type="hidden" name="username" value="{{ $tmsUsername }}">
        <input type="hidden" name="password" value="{{ $tmsPassword }}">
    </form>

    <script>
        // Try to auto-submit form after 2 seconds
        setTimeout(function() {
            // Try form POST first
            document.getElementById('tmsLoginForm').submit();
            
            // If form doesn't work, redirect to URL
            setTimeout(function() {
                window.location.href = '{{ $tmsUrl }}';
            }, 1000);
        }, 2000);
    </script>
</body>
</html>

