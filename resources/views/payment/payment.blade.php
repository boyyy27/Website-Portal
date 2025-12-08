<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - OMILE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="{{ asset('css/payment.css') }}">
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ $clientKey }}"></script>
</head>
<body>
    <div class="container payment-container">
        <div class="payment-header">
            <h2>Pembayaran</h2>
            <p>Selesaikan pembayaran Anda</p>
        </div>

        <div class="payment-card">
            <div class="transaction-info">
                <h5 class="mb-3">Detail Transaksi</h5>
                <div class="row mb-2">
                    <div class="col-5"><strong>Order ID:</strong></div>
                    <div class="col-7">{{ $transaction->order_id }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-5"><strong>Paket:</strong></div>
                    <div class="col-7">{{ $transaction->package_name }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-5"><strong>Total:</strong></div>
                    <div class="col-7">
                        <span class="text-primary">Rp {{ number_format($transaction->gross_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <div id="snap-container" class="snap-container">
                <div class="loading">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3">Memuat halaman pembayaran...</p>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('user.dashboard') }}" class="btn btn-secondary">
                    <i class="mdi mdi-arrow-left me-2"></i> Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>

    <script>
        window.snap.pay('{{ $snapToken }}', {
            onSuccess: function(result) {
                console.log('success', result);
                window.location.href = '{{ route('payment.finish') }}?order_id=' + result.order_id;
            },
            onPending: function(result) {
                console.log('pending', result);
                window.location.href = '{{ route('payment.unfinish') }}?order_id=' + result.order_id;
            },
            onError: function(result) {
                console.log('error', result);
                window.location.href = '{{ route('payment.error') }}?order_id=' + result.order_id;
            },
            onClose: function() {
                console.log('customer closed the popup without finishing the payment');
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

