# 🔧 Fix CSS/JS Tidak Muncul di Railway

## Masalah
CSS dan JavaScript tidak muncul di website yang di-deploy di Railway, padahal di local bagus-bagus aja.

## ✅ Solusi

### Step 1: Pastikan APP_URL Benar di Railway

1. Railway Dashboard → Service **"omile-portal"** → Tab **"Variables"**
2. Cari variable `APP_URL`
3. Pastikan value-nya adalah URL Railway Anda:
   ```
   APP_URL=https://omile-portal-production.up.railway.app
   ```
   (Ganti dengan URL Railway Anda yang sebenarnya)
4. Klik **"Save"**

**PENTING**: `asset()` helper di Laravel menggunakan `APP_URL` untuk generate absolute URL. Jika `APP_URL` salah, semua asset paths akan salah!

### Step 2: Pastikan Semua File Assets Ter-Deploy

File CSS/JS sudah ter-commit ke GitHub:
- ✅ `public/css/auth.css`
- ✅ `public/css/dashboard.css`
- ✅ `public/css/payment.css`
- ✅ `public/js/sidebar.js`
- ✅ `public/assets/landing/css/*`
- ✅ `public/assets/landing/js/*`
- ✅ `public/assets/images/*`

### Step 3: Clear Config Cache dan Redeploy

1. Railway Dashboard → Service **"omile-portal"** → Tab **"Settings"**
2. Scroll ke **"Deploy Command"**
3. Pastikan ada clear cache commands:
   ```
   php artisan config:clear && php artisan cache:clear && php artisan view:clear && php artisan route:clear && php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT
   ```
4. Klik **"Save"**
5. Tab **"Deployments"** → **"Redeploy"** → **"Deploy latest commit"**

Setelah migrations berhasil, kembalikan start command ke:
```
php artisan serve --host=0.0.0.0 --port=$PORT
```

### Step 4: Verify Asset URLs

Setelah deploy, buka website dan:
1. Right click → **"Inspect"** → Tab **"Network"**
2. Refresh page
3. Cek apakah CSS/JS files di-load:
   - Harusnya ada request ke: `https://omile-portal-production.up.railway.app/css/dashboard.css`
   - Harusnya ada request ke: `https://omile-portal-production.up.railway.app/assets/landing/css/style.css`
4. Jika file tidak ditemukan (404), berarti path salah atau file tidak ter-deploy

### Step 5: Alternative - Use CDN untuk CSS/JS Utama

Jika masih bermasalah, bisa gunakan CDN untuk CSS/JS utama (Bootstrap sudah pakai CDN).

Tapi file custom CSS (`auth.css`, `dashboard.css`, `payment.css`) harus tetap dari local karena custom.

---

## 🔍 Troubleshooting

### Error 404 untuk CSS/JS files

**Penyebab**: File tidak ter-deploy atau path salah

**Solusi**:
1. Cek di Railway logs apakah file ada di server
2. Pastikan `APP_URL` benar
3. Pastikan semua file ter-commit dan ter-push ke GitHub

### CSS/JS load tapi tidak apply

**Penyebab**: Browser cache atau file corrupt

**Solusi**:
1. Hard refresh browser: `Ctrl + Shift + R` (Windows) atau `Cmd + Shift + R` (Mac)
2. Clear browser cache
3. Cek browser console untuk error JavaScript

### Asset paths menggunakan HTTP bukan HTTPS

**Penyebab**: `APP_URL` tidak menggunakan HTTPS

**Solusi**:
- Pastikan `APP_URL` menggunakan `https://` bukan `http://`

---

## 📋 Checklist

- [ ] `APP_URL` sudah di-set dengan URL Railway yang benar (HTTPS)
- [ ] Semua file CSS/JS ter-commit dan ter-push ke GitHub
- [ ] Clear cache commands ada di Deploy Command
- [ ] Redeploy dilakukan setelah update `APP_URL`
- [ ] Cek browser console untuk error
- [ ] Hard refresh browser untuk clear cache

---

**Setelah fix `APP_URL` dan redeploy, CSS/JS seharusnya muncul!** 🚀

