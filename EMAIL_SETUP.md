# Setup Email Verification dengan Gmail SMTP

## Konfigurasi Email di .env

Tambahkan konfigurasi berikut ke file `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="OMILE"
```

## Setup Gmail App Password

### 1. Aktifkan 2-Step Verification
1. Buka [Google Account Security](https://myaccount.google.com/security)
2. Aktifkan "2-Step Verification" jika belum aktif

### 2. Buat App Password
1. Buka [App Passwords](https://myaccount.google.com/apppasswords)
2. Pilih "Mail" dan "Other (Custom name)"
3. Masukkan nama: "OMILE Laravel"
4. Klik "Generate"
5. Copy password yang dihasilkan (16 karakter tanpa spasi)
6. Gunakan password ini sebagai `MAIL_PASSWORD` di `.env`

**Catatan:** Jangan gunakan password Gmail biasa, harus menggunakan App Password!

## Testing Email

Setelah setup, test dengan:

```bash
php artisan tinker
>>> Mail::raw('Test email', function($message) {
    $message->to('your-email@gmail.com')->subject('Test');
});
```

## Troubleshooting

### Error: "SMTP connect() failed"
**Solusi:**
- Pastikan menggunakan App Password, bukan password Gmail biasa
- Pastikan 2-Step Verification sudah aktif
- Cek firewall tidak memblokir port 587

### Error: "Authentication failed"
**Solusi:**
- Pastikan `MAIL_USERNAME` adalah email Gmail lengkap
- Pastikan `MAIL_PASSWORD` adalah App Password (16 karakter)
- Pastikan "Less secure app access" sudah diaktifkan (jika masih tersedia)

### Email tidak terkirim
**Solusi:**
- Cek log: `storage/logs/laravel.log`
- Pastikan konfigurasi di `.env` sudah benar
- Clear config cache: `php artisan config:clear`
- Test dengan Mailtrap atau MailHog untuk development

## Alternatif untuk Development

### Menggunakan Mailtrap
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
MAIL_ENCRYPTION=tls
```

### Menggunakan MailHog (Local)
```env
MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
```

## Fitur Email Verification

1. **Generate Code**: Kode 6 digit otomatis dibuat saat registrasi
2. **Send Email**: Email dikirim ke user dengan kode verifikasi
3. **Verify Code**: User memasukkan kode untuk verifikasi
4. **Resend Code**: User bisa minta kirim ulang kode
5. **Auto Login**: Setelah verifikasi berhasil, user otomatis login
6. **Expiry**: Kode berlaku 15 menit

## Flow Registrasi

1. User daftar → Form registrasi
2. Generate code → Kode 6 digit dibuat
3. Send email → Email dengan kode dikirim
4. Show verification page → User diminta input kode
5. Verify code → User input kode
6. Auto login → Jika kode benar, user login otomatis

## Security Features

- Kode hanya tersimpan di session (tidak di database)
- Kode berlaku 15 menit
- Kode 6 digit random
- User harus verifikasi sebelum bisa login
- Resend code dengan rate limiting

