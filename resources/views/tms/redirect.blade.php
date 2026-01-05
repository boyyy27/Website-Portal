<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mengarahkan ke TMS - OMILE</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700&display=swap');
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
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
            z-index: 10;
            position: relative;
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
        .status-message {
            margin-top: 20px;
            padding: 15px;
            border-radius: 8px;
            font-size: 0.9rem;
            min-height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .status-message.info {
            background: #e3f2fd;
            color: #1976d2;
        }
        .status-message.success {
            background: #e8f5e9;
            color: #388e3c;
        }
        .status-message.error {
            background: #ffebee;
            color: #d32f2f;
        }
        .hidden {
            display: none;
        }
        .btn-manual {
            margin-top: 15px;
            padding: 12px 24px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-weight: 500;
            transition: background 0.3s;
        }
        .btn-manual:hover {
            background: #5568d3;
        }
    </style>
</head>
<body>
    <div class="redirect-container" id="redirectContainer">
        <h2>Mengarahkan ke TMS Dashboard...</h2>
        <div class="spinner"></div>
        <div class="status-message info" id="statusMessage">
            Membuka dashboard TMS...
        </div>
        
        <div id="manualSection" class="hidden" style="margin-top: 20px;">
            <p style="font-size: 0.9rem; color: #666; margin-bottom: 15px;">
                Jika tidak otomatis terbuka, klik tombol di bawah:
            </p>
            <a href="{{ $tmsDashboardUrl }}" target="_blank" class="btn-manual" id="manualLink">
                Buka TMS Dashboard
            </a>
        </div>
    </div>

    <script>
        const statusMessage = document.getElementById('statusMessage');
        const manualSection = document.getElementById('manualSection');
        const manualLink = document.getElementById('manualLink');
        let dashboardOpened = false;

        function updateStatus(message, type = 'info') {
            statusMessage.textContent = message;
            statusMessage.className = 'status-message ' + type;
        }

        // Function untuk membuka dashboard TMS
        function openTmsDashboard() {
            if (dashboardOpened) return;
            
            try {
                // Buka dashboard di window baru/tab baru
                const dashboardWindow = window.open('{{ $tmsDashboardUrl }}', '_blank');
                
                if (dashboardWindow) {
                    dashboardOpened = true;
                    updateStatus('Dashboard TMS dibuka di tab baru!', 'success');
                    
                    // Setelah 2 detik, tutup halaman redirect ini (optional)
                    setTimeout(() => {
                        // Bisa tutup window ini atau biarkan terbuka
                        // window.close(); // Uncomment jika ingin auto-close
                    }, 2000);
                } else {
                    // Popup blocked
                    updateStatus('Popup diblokir. Silakan klik tombol di bawah.', 'error');
                    manualSection.classList.remove('hidden');
                }
            } catch (e) {
                updateStatus('Terjadi error. Silakan klik tombol di bawah.', 'error');
                manualSection.classList.remove('hidden');
            }
        }

        // Auto-open saat halaman load
        window.addEventListener('load', function() {
            // Tunggu sebentar untuk memastikan halaman sudah fully loaded
            setTimeout(() => {
                openTmsDashboard();
            }, 500);
        });

        // Fallback: jika setelah 3 detik belum terbuka, tampilkan manual link
        setTimeout(() => {
            if (!dashboardOpened) {
                updateStatus('Silakan klik tombol di bawah untuk membuka dashboard.', 'info');
                manualSection.classList.remove('hidden');
            }
        }, 3000);

        // Handle manual link click
        manualLink.addEventListener('click', function() {
            dashboardOpened = true;
            updateStatus('Membuka dashboard...', 'info');
        });
    </script>
</body>
</html>
