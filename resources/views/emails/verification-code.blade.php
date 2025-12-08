<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode Verifikasi Email - OMILE</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700&display=swap');
        body {
            font-family: 'Open Sans', sans-serif;
            line-height: 1.6;
            color: #333;
            background: #f5f7fa;
            padding: 40px 20px;
        }
        .email-wrapper {
            max-width: 500px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        .email-header {
            background: linear-gradient(135deg,rgb(255, 255, 255) 0%, #1e3fa8 100%);
            padding: 30px;
            text-align: center;
        }
        .email-header h1 {
            font-size: 28px;
            font-weight: 700;
            margin: 0;
            color: #ff6b35;
            letter-spacing: 1px;
        }
        .email-body {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 16px;
            color: #2d3748;
            margin-bottom: 20px;
            font-weight: 500;
        }
        .greeting .name {
            color:rgb(0, 0, 0);
            font-weight: 600;
        }
        .message {
            color: #4a5568;
            font-size: 14px;
            line-height: 1.7;
            margin-bottom: 25px;
        }
        .code-container {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 25px;
            margin: 25px 0;
            text-align: center;
        }
        .code-label {
            font-size: 11px;
            color: #718096;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 12px;
            font-weight: 600;
        }
        .verification-code {
            background: linear-gradient(135deg,rgb(255, 167, 36) 0%,rgb(227, 161, 19) 100%);
            color: #ffffff;
            font-size: 36px;
            font-weight: 700;
            text-align: center;
            padding: 20px;
            border-radius: 10px;
            letter-spacing: 10px;
            margin: 0 auto;
            font-family: 'Courier New', monospace;
            box-shadow: 0 4px 12px rgba(47, 85, 212, 0.25);
            display: inline-block;
            min-width: 260px;
        }
        .code-hint {
            font-size: 12px;
            color: #718096;
            margin-top: 12px;
        }
        .warning-box {
            background: #fff5e6;
            border-left: 3px solid #f58905;
            padding: 15px;
            margin: 25px 0;
            border-radius: 6px;
        }
        .warning-box p {
            color: #856404;
            font-size: 12px;
            line-height: 1.6;
            margin: 0;
        }
        .footer {
            background: #f8f9fa;
            padding: 25px;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }
        .footer .brand {
            font-size: 18px;
            font-weight: 700;
            color: #ff6b35;
            margin-bottom: 8px;
        }
        .footer .copyright {
            font-size: 11px;
            color: #718096;
            margin: 5px 0;
        }
        .footer .disclaimer {
            font-size: 10px;
            color: #a0aec0;
            margin-top: 12px;
            font-style: italic;
        }
        @media only screen and (max-width: 600px) {
            body {
                padding: 20px 10px;
            }
            .email-body {
                padding: 30px 20px;
            }
            .verification-code {
                font-size: 28px;
                letter-spacing: 8px;
                padding: 18px;
                min-width: 220px;
            }
            .email-header {
                padding: 25px 20px;
            }
            .email-header h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <!-- Header -->
        <div class="email-header">
            <h1>OMILE</h1>
        </div>
        
        <!-- Body -->
        <div class="email-body">
            <div class="greeting">
                Halo, <span class="name">{{ $userName }}</span>!
            </div>
            
            <div class="message">
                Terima kasih telah mendaftar. Untuk menyelesaikan registrasi, masukkan kode verifikasi berikut:
            </div>
            
            <!-- Verification Code -->
            <div class="code-container">
                <div class="code-label">Kode Verifikasi</div>
                <div class="verification-code">
                    {{ $verificationCode }}
                </div>
                <div class="code-hint">
                    Berlaku selama 15 menit
                </div>
            </div>
            
            <!-- Warning Box -->
            <div class="warning-box">
                <p>
                    <strong>⚠️ Penting:</strong> Jangan bagikan kode ini kepada siapapun. Kode ini bersifat rahasia.
                </p>
            </div>
            
            <div class="message" style="font-size: 13px; color: #718096; margin-top: 20px;">
                Jika Anda tidak melakukan registrasi, abaikan email ini.
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div class="brand">OMILE</div>
            <div class="copyright">
                © {{ date('Y') }} OMILE. All rights reserved.
            </div>
            <div class="copyright">
                Powered by <strong style="color: #f58905;">ODISYS</strong>
            </div>
            <div class="disclaimer">
                Email ini dikirim secara otomatis, mohon jangan membalas.
            </div>
        </div>
    </div>
</body>
</html>
