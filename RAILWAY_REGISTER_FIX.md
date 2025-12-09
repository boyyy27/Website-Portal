# 🔧 Fix Register Error di Railway

## Masalah

Register tidak bisa di Railway - saat klik button "Sign Up" terjadi error.

## ✅ Solusi yang Sudah Diterapkan

### 1. Improved Error Handling

Register controller sudah di-update dengan:
- Try-catch untuk handle database errors
- Check kolom database sebelum insert
- Error messages yang lebih jelas

### 2. Pastikan Migrations Sudah Di-Run

Register membutuhkan kolom-kolom berikut di tabel `users`:
- `role` (string)
- `is_active` (boolean)
- `email_verified` (boolean)
- `verification_token` (string, nullable)
- `verification_token_expires` (timestamp, nullable)
- `verified_at` (timestamp, nullable)

**Pastikan Deploy Command sudah include migrations:**

```
php artisan migrate --force && php artisan db:seed --class=AdminSeeder --force && php artisan serve --host=0.0.0.0 --port=$PORT
```

### 3. Verify Migrations

Cek apakah migration untuk add role sudah di-run:

Via Railway CLI:
```bash
railway run php artisan migrate:status
```

Harusnya ada migration `2024_12_09_000000_add_role_to_users_table` dengan status "Ran".

---

## 🔍 Troubleshooting

### Error: "Column 'role' does not exist"

**Penyebab**: Migration belum di-run

**Solusi**:
1. Pastikan Deploy Command include `php artisan migrate --force`
2. Redeploy atau run manual:
   ```bash
   railway run php artisan migrate --force
   ```

### Error: "Failed to send verification email"

**Penyebab**: Email tidak terkonfigurasi di Railway

**Solusi**: 
- Error ini tidak akan menghentikan registrasi
- User tetap akan dibuat, hanya email tidak terkirim
- Setup email config di Railway environment variables jika diperlukan

### Error: "Database schema belum lengkap"

**Penyebab**: Migration belum di-run atau ada kolom yang hilang

**Solusi**:
1. Run migrations:
   ```bash
   railway run php artisan migrate --force
   ```
2. Verify semua kolom ada:
   ```bash
   railway run php artisan tinker
   ```
   ```php
   use Illuminate\Support\Facades\Schema;
   Schema::hasColumn('users', 'role'); // Should return true
   Schema::hasColumn('users', 'is_active'); // Should return true
   ```

### Register Berhasil Tapi Email Verification Page Error

**Penyebab**: Route `verification.show` tidak ditemukan atau session issue

**Solusi**:
1. Verify routes:
   ```bash
   railway run php artisan route:list | grep verification
   ```
2. Pastikan session driver sudah di-set di Railway environment:
   ```
   SESSION_DRIVER=database
   ```
   Atau gunakan file/cookie session.

---

## ✅ Checklist

- [ ] Migration `2024_12_09_000000_add_role_to_users_table` sudah di-run
- [ ] Deploy Command include `php artisan migrate --force`
- [ ] Semua kolom users table sudah ada (role, is_active, email_verified, dll)
- [ ] Register controller sudah di-update dengan error handling
- [ ] Test register flow:
  - [ ] Fill form dan submit
  - [ ] User created successfully
  - [ ] Redirect ke verification page
  - [ ] Session stored correctly

---

## 📋 Quick Fix

Jika masih error setelah semua di atas:

1. **Run migrations manual**:
   ```bash
   railway run php artisan migrate:refresh --force
   ```
   ⚠️ **WARNING**: Ini akan drop semua tabel dan re-create. Hanya untuk fresh setup!

2. **Atau run specific migration**:
   ```bash
   railway run php artisan migrate --path=/database/migrations/2024_12_09_000000_add_role_to_users_table.php --force
   ```

3. **Clear cache**:
   ```bash
   railway run php artisan config:clear && php artisan cache:clear
   ```

---

**Setelah migrations berhasil, register seharusnya bekerja!** 🚀

