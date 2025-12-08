<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $transaction->order_id }} - OMILE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print { display: none; }
        }
        @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700&display=swap');
        body {
            background: #f8f9fa;
            font-family: 'Open Sans', sans-serif;
        }
        .invoice-container {
            max-width: 800px;
            margin: 20px auto;
            background: #fff;
            padding: 40px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .invoice-header {
            border-bottom: 3px solid #3598dc;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .invoice-footer {
            border-top: 2px solid #e0e0e0;
            padding-top: 20px;
            margin-top: 30px;
            text-align: center;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container my-4">
        <div class="text-end mb-3 no-print">
            <button onclick="window.print()" class="btn btn-primary me-2">
                <i class="mdi mdi-printer"></i> Print
            </button>
            <a href="{{ route('user.dashboard') }}" class="btn btn-secondary">Kembali</a>
        </div>

        <div class="invoice-container">
            <div class="invoice-header">
                <div class="row">
                    <div class="col-md-6">
                        <h2 class="mb-0">OMILE</h2>
                        <p class="text-muted mb-0">Distribution System</p>
                    </div>
                    <div class="col-md-6 text-end">
                        <h3 class="mb-0">INVOICE</h3>
                        <p class="text-muted mb-0">#{{ $transaction->order_id }}</p>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <h6 class="text-muted mb-2">Bill To:</h6>
                    <p class="mb-0">
                        <strong>{{ $transaction->customer_name ?? $transaction->user->name }}</strong><br>
                        {{ $transaction->customer_email ?? $transaction->user->email }}<br>
                        @if($transaction->customer_phone)
                            {{ $transaction->customer_phone }}<br>
                        @endif
                        @if($transaction->customer_address)
                            {{ $transaction->customer_address }}
                        @endif
                    </p>
                </div>
                <div class="col-md-6 text-end">
                    <h6 class="text-muted mb-2">Invoice Details:</h6>
                    <p class="mb-0">
                        <strong>Tanggal Invoice:</strong><br>
                        {{ $transaction->settlement_time ? $transaction->settlement_time->format('d M Y') : $transaction->created_at->format('d M Y') }}<br><br>
                        <strong>Tanggal Transaksi:</strong><br>
                        {{ $transaction->transaction_time ? $transaction->transaction_time->format('d M Y H:i') : $transaction->created_at->format('d M Y H:i') }}
                    </p>
                </div>
            </div>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Deskripsi</th>
                        <th class="text-end">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <strong>{{ $transaction->package_name ?? $transaction->package->name ?? 'Paket' }}</strong><br>
                            <small class="text-muted">
                                @if($transaction->package)
                                    Durasi: {{ $transaction->package->duration_days }} hari
                                @endif
                            </small>
                        </td>
                        <td class="text-end">Rp {{ number_format($transaction->gross_amount, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th class="text-end">Total</th>
                        <th class="text-end">Rp {{ number_format($transaction->gross_amount, 0, ',', '.') }}</th>
                    </tr>
                </tfoot>
            </table>

            <div class="mt-4">
                <h6 class="mb-2">Informasi Pembayaran:</h6>
                <p class="mb-1">
                    <strong>Metode Pembayaran:</strong> {{ $transaction->payment_method ?? $transaction->payment_type ?? 'N/A' }}
                </p>
                <p class="mb-1">
                    <strong>Status:</strong> 
                    <span class="badge bg-success">Paid</span>
                </p>
                @if($transaction->transaction_id)
                    <p class="mb-0">
                        <strong>Transaction ID:</strong> {{ $transaction->transaction_id }}
                    </p>
                @endif
            </div>

            <div class="invoice-footer">
                <p class="mb-0">
                    Terima kasih telah menggunakan layanan OMILE<br>
                    <small>© {{ date('Y') }} OMILE. All rights reserved.</small>
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css">
</body>
</html>

