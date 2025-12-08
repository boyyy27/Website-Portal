# Setup Midtrans Payment Gateway

## 1. Konfigurasi Environment Variables

Tambahkan konfigurasi berikut ke file `.env`:

```env
MIDTRANS_CLIENT_KEY=YOUR_CLIENT_KEY_HERE
MIDTRANS_SERVER_KEY=YOUR_SERVER_KEY_HERE
MIDTRANS_IS_PRODUCTION=false
```

**Catatan:**
- `MIDTRANS_CLIENT_KEY`: Client Key dari Midtrans (untuk frontend)
- `MIDTRANS_SERVER_KEY`: Server Key dari Midtrans (untuk backend)
- `MIDTRANS_IS_PRODUCTION`: Set `false` untuk sandbox mode, `true` untuk production

## 2. Routes yang Tersedia

### Payment Routes (Protected - requires authentication)
- `GET /checkout/{packageId}` - Halaman checkout
- `POST /payment/create/{packageId}` - Membuat transaksi pembayaran
- `GET /payment/finish` - Redirect setelah pembayaran berhasil
- `GET /payment/unfinish` - Redirect jika pembayaran belum selesai
- `GET /payment/error` - Redirect jika terjadi error

### Notification Route (Public - no auth required)
- `POST /payment/notification` - Endpoint untuk menerima notifikasi dari Midtrans

## 3. Flow Pembayaran

1. **User memilih paket** di landing page
2. **User diarahkan ke halaman checkout** (jika sudah login) atau login page (jika belum login)
3. **User mengisi data** (nama, email, telepon, alamat)
4. **Sistem membuat transaksi** dan mendapatkan Snap Token dari Midtrans
5. **User diarahkan ke halaman payment** dengan Snap.js
6. **User melakukan pembayaran** melalui Midtrans
7. **Midtrans mengirim notifikasi** ke endpoint `/payment/notification`
8. **Sistem memproses notifikasi** dan mengaktifkan paket user jika pembayaran berhasil

## 4. Testing di Sandbox Mode

Untuk testing pembayaran di sandbox mode, gunakan kartu kredit test berikut:

### Kartu Kredit Test (Berhasil)
- **Card Number**: `4811 1111 1111 1114`
- **CVV**: `123`
- **Expiry Date**: `12/25` (bulan/tahun di masa depan)
- **3D Secure Password**: `112233`

### Kartu Kredit Test (Gagal)
- **Card Number**: `4911 1111 1111 1113`
- **CVV**: `123`
- **Expiry Date**: `12/25`

## 5. Status Transaksi

Status transaksi yang didukung:
- `pending` - Menunggu pembayaran
- `settlement` - Pembayaran berhasil
- `capture` - Pembayaran berhasil (untuk credit card)
- `deny` - Pembayaran ditolak
- `cancel` - Pembayaran dibatalkan
- `expire` - Pembayaran kedaluwarsa
- `refund` - Pembayaran direfund
- `chargeback` - Chargeback

## 6. Notification Handling

Sistem akan otomatis:
1. Menerima notifikasi dari Midtrans
2. Memperbarui status transaksi
3. Mengaktifkan paket user jika pembayaran berhasil
4. Membuat record `UserPackage` dengan durasi sesuai paket

## 7. Security

- Endpoint notification (`/payment/notification`) dikecualikan dari CSRF verification
- Server Key hanya digunakan di backend (tidak pernah dikirim ke frontend)
- Client Key digunakan di frontend untuk Snap.js

## 8. Troubleshooting

### Payment tidak muncul
- Pastikan Client Key sudah benar di `.env`
- Pastikan menggunakan URL Midtrans yang benar (sandbox vs production)
- Cek console browser untuk error JavaScript

### Notification tidak diterima
- Pastikan Server Key sudah benar di `.env`
- Pastikan endpoint `/payment/notification` dapat diakses dari internet (untuk production)
- Cek log Laravel di `storage/logs/laravel.log`

### Paket tidak aktif setelah pembayaran
- Cek status transaksi di database
- Cek log untuk error saat memproses notification
- Pastikan `fraud_status` adalah `accept`


