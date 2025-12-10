# 🔧 Fix Register 500 Error - Step by Step

## Masalah

Register masih error 500 saat klik "Sign Up" di Railway.

## ✅ Langkah Debugging

### Step 1: Cek Railway Logs (PENTING!)

**Via Railway Dashboard:**
1. Railway Dashboard → Service **"omile-portal"**
2. Tab **"Deploy Logs"** atau **"HTTP Logs"**
3. Scroll ke error terakhir saat register
4. **Copy error message yang muncul**

**Via Railway CLI:**
```bash
railway logs --follow
```

Atau cek log terakhir:
```bash
railway logs | tail -n 100
```

### Step 2: Enable Debug Mode (Temporary)

Untuk melihat error detail:

1. Railway Dashboard → Service **"omile-portal"** → Tab **"Variables"**
2. Set `APP_DEBUG=true`
3. Redeploy
4. Coba register lagi - sekarang akan muncul error message yang lebih detail
5. **PENTING**: Set kembali ke `APP_DEBUG=false` setelah debug selesai!

### Step 3: Cek Laravel Logs

```bash
railway run tail -n 100 storage/logs/laravel.log
```

Atau via tinker:
```bash
railway run php artisan tinker
```

```php
$logFile = storage_path('logs/laravel.log');
if (file_exists($logFile)) {
    $lines = file($logFile);
    $lastLines = array_slice($lines, -50);
    echo implode('', $lastLines);
} else {
    echo "Log file not found\n";
}
```

---

## 🔍 Common Errors & Solutions

### Error 1: "Class 'Schema' not found"

**Penyebab**: Missing import

**Solusi**: Sudah diperbaiki, pastikan code terbaru sudah di-deploy.

### Error 2: "Column 'role' does not exist"

**Penyebab**: Migration belum di-run

**Solusi**:
```bash
railway run php artisan migrate --force
```

### Error 3: "Maximum execution time exceeded"

**Penyebab**: Email timeout

**Solusi**: 
- Setup queue (recommended)
- Atau increase timeout
- Atau disable email sementara untuk test

### Error 4: "Call to undefined method"

**Penyebab**: Method tidak tersedia

**Solusi**: Cek kode di `AuthController.php` - pastikan semua method ada.

### Error 5: "SQLSTATE[42P01]: Undefined table"

**Penyebab**: Tabel belum dibuat

**Solusi**:
```bash
railway run php artisan migrate --force
```

### Error 6: "Mail::queue() method not found"

**Penyebab**: Queue not properly configured

**Solusi**: 
- Set `QUEUE_CONNECTION=sync` di Railway variables
- Atau setup queue properly

---

## 🚀 Quick Fixes

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

### Fix 3: Rebuild Config

```bash
railway run php artisan config:cache
```

### Fix 4: Disable Queue Sementara (Jika Error Queue)

Railway Dashboard → Service **"omile-portal"** → Tab **"Variables"**

Set:
```
QUEUE_CONNECTION=sync
```

Redeploy.

### Fix 5: Disable Email Sementara (Untuk Test)

Jika error karena email, disable sementara:

Railway Dashboard → Service **"omile-portal"** → Tab **"Variables"**

Set:
```
MAIL_MAILER=log
```

Ini akan log email ke file instead of sending.

---

## 📋 Debugging Checklist

- [ ] Cek Railway logs untuk error message spesifik
- [ ] Enable `APP_DEBUG=true` untuk melihat error detail
- [ ] Cek Laravel logs (`storage/logs/laravel.log`)
- [ ] Verify semua migrations sudah di-run
- [ ] Clear cache dan rebuild config
- [ ] Test register lagi setelah fix
- [ ] Set `APP_DEBUG=false` kembali setelah debug selesai

---

## 🎯 Test Register Flow

1. **Enable Debug Mode**:
   ```
   APP_DEBUG=true
   ```

2. **Redeploy**

3. **Test Register**:
   - Buka `/register`
   - Fill form dan submit
   - **Lihat error message yang muncul** (sekarang akan detail karena debug mode)

4. **Fix Error** sesuai dengan error message

5. **Disable Debug Mode**:
   ```
   APP_DEBUG=false
   ```

6. **Redeploy**

---

## 💡 Tips

1. **Selalu cek logs pertama** - Error message akan memberitahu masalah sebenarnya
2. **Enable debug mode sementara** - Untuk melihat error detail
3. **Test step by step** - Fix satu error dulu sebelum test lagi
4. **Keep logs clean** - Clear old logs jika terlalu banyak

---

**Setelah cek logs dan fix error, register seharusnya bekerja!** 🚀

**PENTING**: Share error message dari Railway logs agar saya bisa bantu fix lebih spesifik!

