# 🔍 Debug 500 Error di Railway - Register Page

## Masalah

Register error 500 di Railway tapi bekerja di local.

## ✅ Step-by-Step Debugging

### Step 1: Cek Railway Logs

**Via Railway Dashboard:**
1. Railway Dashboard → Service **"omile-portal"**
2. Tab **"Deploy Logs"** atau **"HTTP Logs"**
3. Scroll ke error terakhir
4. Cari error message yang muncul saat register

**Via Railway CLI:**
```bash
railway logs
```

Atau untuk real-time logs:
```bash
railway logs --follow
```

### Step 2: Cek Error Details

Error 500 biasanya disebabkan oleh:
1. **Database schema berbeda** - Migration belum di-run
2. **Missing environment variables**
3. **Missing columns** di database
4. **PHP errors** yang tidak ter-handle

### Step 3: Verify Database Schema

Cek apakah semua kolom yang diperlukan sudah ada:

```bash
railway run php artisan tinker
```

```php
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

// Cek apakah kolom users table sudah ada
$columns = Schema::getColumnListing('users');
print_r($columns);

// Harusnya ada: id, name, email, password, role, is_active, email_verified, verification_token, verification_token_expires, verified_at, created_at, updated_at

// Cek apakah kolom 'role' ada
echo Schema::hasColumn('users', 'role') ? "role column exists\n" : "role column MISSING!\n";
echo Schema::hasColumn('users', 'is_active') ? "is_active column exists\n" : "is_active column MISSING!\n";
echo Schema::hasColumn('users', 'email_verified') ? "email_verified column exists\n" : "email_verified column MISSING!\n";
```

### Step 4: Run Migrations

Jika kolom missing, run migrations:

```bash
railway run php artisan migrate --force
```

Atau run specific migration:

```bash
railway run php artisan migrate --path=/database/migrations/2024_12_09_000000_add_role_to_users_table.php --force
```

### Step 5: Cek Environment Variables

Pastikan environment variables sudah di-set di Railway:

1. Railway Dashboard → Service **"omile-portal"** → Tab **"Variables"**
2. Pastikan ada:
   - `APP_ENV=production`
   - `APP_DEBUG=false` (atau `true` untuk debug)
   - `DB_CONNECTION=pgsql`
   - `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
   - `APP_URL=https://omile-portal-production.up.railway.app`

### Step 6: Enable Debug Mode (Temporary)

Untuk melihat error message yang lebih detail:

1. Railway Dashboard → Service **"omile-portal"** → Tab **"Variables"**
2. Set `APP_DEBUG=true`
3. Redeploy
4. Coba register lagi - sekarang akan muncul error message yang lebih detail
5. **PENTING**: Set kembali ke `APP_DEBUG=false` setelah debug selesai!

### Step 7: Cek Laravel Logs

```bash
railway run php artisan tinker
```

```php
// Read last 50 lines of log
$logFile = storage_path('logs/laravel.log');
if (file_exists($logFile)) {
    $lines = file($logFile);
    $lastLines = array_slice($lines, -50);
    echo implode('', $lastLines);
} else {
    echo "Log file not found\n";
}
```

Atau via Railway CLI:

```bash
railway run tail -n 100 storage/logs/laravel.log
```

---

## 🔧 Quick Fixes

### Fix 1: Pastikan Migrations Sudah Di-Run

```bash
railway run php artisan migrate:status
```

Jika ada migration yang belum di-run:

```bash
railway run php artisan migrate --force
```

### Fix 2: Clear Cache

```bash
railway run php artisan config:clear
railway run php artisan cache:clear
railway run php artisan view:clear
railway run php artisan route:clear
```

### Fix 3: Rebuild Config Cache

```bash
railway run php artisan config:cache
```

### Fix 4: Cek Database Connection

```bash
railway run php artisan tinker
```

```php
use Illuminate\Support\Facades\DB;

try {
    DB::connection()->getPdo();
    echo "Database connection OK\n";
} catch (\Exception $e) {
    echo "Database connection FAILED: " . $e->getMessage() . "\n";
}
```

---

## 🎯 Common Causes & Solutions

### Error: "Column 'role' does not exist"

**Penyebab**: Migration belum di-run

**Solusi**:
```bash
railway run php artisan migrate --force
```

### Error: "Class 'Schema' not found"

**Penyebab**: Missing import di controller

**Solusi**: Sudah diperbaiki di `AuthController.php` dengan menambahkan:
```php
use Illuminate\Support\Facades\Schema;
```

### Error: "Call to undefined method"

**Penyebab**: Method tidak tersedia atau typo

**Solusi**: Cek kode di `AuthController.php` - sudah di-update dengan error handling

### Error: "SQLSTATE[42P01]: Undefined table"

**Penyebab**: Tabel belum dibuat

**Solusi**:
```bash
railway run php artisan migrate --force
```

### Error: "SQLSTATE[23505]: Unique violation"

**Penyebab**: Email sudah terdaftar

**Solusi**: Ini bukan error server - ini validation error yang seharusnya ditampilkan ke user

---

## 📋 Debugging Checklist

- [ ] Cek Railway logs untuk error message spesifik
- [ ] Enable `APP_DEBUG=true` untuk melihat error detail
- [ ] Verify semua kolom users table sudah ada
- [ ] Run migrations jika ada yang missing
- [ ] Cek environment variables sudah benar
- [ ] Clear cache dan rebuild config
- [ ] Test register lagi setelah fix
- [ ] Set `APP_DEBUG=false` kembali setelah debug selesai

---

## 🔍 Compare Local vs Production

### Cek Database Schema di Local

```bash
php artisan tinker
```

```php
use Illuminate\Support\Facades\Schema;
Schema::getColumnListing('users');
```

### Cek Database Schema di Railway

```bash
railway run php artisan tinker
```

```php
use Illuminate\Support\Facades\Schema;
Schema::getColumnListing('users');
```

**Compare hasilnya** - harus sama!

### Cek Migrations Status

**Local:**
```bash
php artisan migrate:status
```

**Railway:**
```bash
railway run php artisan migrate:status
```

**Compare** - semua migration harus "Ran" di kedua environment!

---

## 🚀 Quick Test

Setelah fix, test register:

1. Buka `https://omile-portal-production.up.railway.app/register`
2. Isi form:
   - Name: Test User
   - Email: test@example.com
   - Password: password123
   - Confirm Password: password123
3. Klik "Sign Up"
4. Seharusnya redirect ke verification page, bukan 500 error

---

**Setelah cek logs dan fix issues, register seharusnya bekerja!** 🚀

