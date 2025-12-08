# Troubleshooting Payment Status

## Masalah: Status Transaksi Masih Pending Setelah Pembayaran Berhasil

Jika pembayaran sudah sukses di Midtrans tapi status di web masih pending, ada beberapa kemungkinan:

### 1. Notification dari Midtrans Tidak Sampai

**Penyebab:**
- Endpoint notification tidak dapat diakses dari internet (untuk production)
- Server key tidak benar
- CSRF protection memblokir request

**Solusi:**
1. Pastikan endpoint `/payment/notification` dapat diakses dari internet
2. Untuk development, gunakan ngrok atau sejenisnya untuk expose local server
3. Pastikan Server Key di `.env` sudah benar
4. Endpoint notification sudah dikecualikan dari CSRF (sudah di-setup)

### 2. Manual Check Status

Jika notification tidak sampai, Anda bisa manual check status:

**Via Browser:**
1. Buka halaman detail transaksi
2. Klik tombol "Cek Status" (muncul jika status masih pending)
3. Sistem akan fetch status terbaru dari Midtrans dan update database

**Via URL:**
```
GET /payment/check-status/{orderId}
```

Contoh:
```
GET /payment/check-status/ORDER-1765174822-3-1
```

**Via Command Line (untuk testing):**
```bash
curl -X GET "http://127.0.0.1:8000/payment/check-status/ORDER-1765174822-3-1" \
  -H "Cookie: laravel_session=..." \
  -H "X-Requested-With: XMLHttpRequest"
```

### 3. Cek Log Laravel

Cek log untuk melihat apakah notification diterima:

```bash
tail -f storage/logs/laravel.log | grep -i midtrans
```

Atau cek log untuk order_id tertentu:
```bash
grep "ORDER-1765174822-3-1" storage/logs/laravel.log
```

### 4. Test Notification Endpoint

Untuk testing, Anda bisa simulate notification dari Midtrans dashboard:

1. Login ke Midtrans Dashboard (sandbox: https://dashboard.sandbox.midtrans.com)
2. Pilih transaksi yang ingin di-test
3. Klik "Simulate Payment" atau "Resend Notification"

### 5. Update Status Manual via Database

Jika semua cara di atas tidak berhasil, Anda bisa update manual:

```sql
-- Update status menjadi settlement
UPDATE transactions 
SET transaction_status = 'settlement',
    settlement_time = NOW(),
    notification_received = true
WHERE order_id = 'ORDER-1765174822-3-1';

-- Kemudian trigger akan otomatis membuat user_package
-- Atau buat manual:
INSERT INTO user_packages (
    user_id, 
    package_id, 
    transaction_id, 
    start_date, 
    end_date, 
    is_active
) 
SELECT 
    t.user_id,
    t.package_id,
    t.id,
    NOW(),
    NOW() + (p.duration_days || ' days')::INTERVAL,
    true
FROM transactions t
JOIN packages p ON t.package_id = p.id
WHERE t.order_id = 'ORDER-1765174822-3-1'
AND NOT EXISTS (
    SELECT 1 FROM user_packages 
    WHERE transaction_id = t.id
);
```

### 6. Perbaikan yang Sudah Dilakukan

1. ✅ Notification handler diperbaiki dengan logging lebih detail
2. ✅ Handler lebih robust dalam menangani berbagai status
3. ✅ Fraud status check lebih fleksibel (accept atau empty)
4. ✅ Auto-deactivate package lain sebelum activate yang baru
5. ✅ Manual check status endpoint ditambahkan
6. ✅ Tombol "Cek Status" di halaman detail transaksi

### 7. Testing Notification Endpoint

Untuk test notification endpoint secara manual:

```bash
curl -X POST "http://127.0.0.1:8000/payment/notification" \
  -H "Content-Type: application/json" \
  -d '{
    "transaction_time": "2025-12-08 06:20:22",
    "transaction_status": "settlement",
    "transaction_id": "test-transaction-id",
    "status_message": "midtrans payment notification",
    "status_code": "200",
    "signature_key": "test-signature",
    "payment_type": "credit_card",
    "order_id": "ORDER-1765174822-3-1",
    "gross_amount": "9999.00",
    "fraud_status": "accept"
  }'
```

**Catatan:** Signature key harus valid untuk production. Untuk sandbox, Midtrans tidak selalu require signature validation.

### 8. Konfigurasi Notification URL di Midtrans

Pastikan notification URL sudah di-set di Midtrans Dashboard:

1. Login ke Midtrans Dashboard
2. Settings > Configuration
3. Set Notification URL ke: `https://yourdomain.com/payment/notification`
4. Untuk development, gunakan ngrok: `https://your-ngrok-url.ngrok.io/payment/notification`

### 9. Cek Status Transaksi di Midtrans Dashboard

1. Login ke Midtrans Dashboard
2. Pilih transaksi berdasarkan Order ID
3. Cek status di dashboard
4. Jika status sudah "Settlement" di Midtrans tapi masih "Pending" di web, gunakan tombol "Cek Status"

## Tips

- Selalu cek log Laravel jika ada masalah
- Gunakan tombol "Cek Status" untuk sync status dari Midtrans
- Pastikan Server Key dan Client Key sudah benar di `.env`
- Untuk production, pastikan notification URL dapat diakses dari internet


