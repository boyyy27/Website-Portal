# 🚨 Quick Fix: Railway PHP Version Error

## Error yang Terjadi
```
✖ No version available for php 8.0
```

## ✅ Solusi Cepat

### Step 1: Tambahkan Environment Variable `RAILPACK_PHP_VERSION`

1. Di Railway Dashboard, klik service **"omile-portal"**
2. Klik tab **"Variables"** (yang sedang Anda buka)
3. Klik tombol **"+ New Variable"** (di kanan atas)
4. Isi form:
   - **Name**: `RAILPACK_PHP_VERSION`
   - **Value**: `8.1`
5. Klik **"Add"**

### Step 2: Pastikan Variable Penting Lainnya Ada

Tambahkan juga variable-variable ini jika belum ada:

#### A. APP_KEY (WAJIB!)
- **Name**: `APP_KEY`
- **Value**: Generate dulu dengan command:
  ```bash
  php artisan key:generate --show
  ```
  Copy hasilnya (format: `base64:...`) dan paste sebagai value

#### B. DB_CONNECTION (WAJIB!)
- **Name**: `DB_CONNECTION`
- **Value**: `pgsql`

#### C. APP_URL
- **Name**: `APP_URL`
- **Value**: `https://omile-portal-production.up.railway.app` (atau domain Railway Anda)

### Step 3: Redeploy

1. Setelah semua variable ditambahkan, klik tab **"Deployments"**
2. Klik **"Redeploy"** → **"Deploy latest commit"**
3. Tunggu build selesai (2-3 menit)

## 📋 Checklist Variables yang Harus Ada

- [ ] `RAILPACK_PHP_VERSION=8.1` ⭐ **PENTING untuk fix PHP error**
- [ ] `APP_KEY=base64:...` ⭐ **WAJIB untuk Laravel**
- [ ] `APP_NAME=OMILE`
- [ ] `APP_ENV=production`
- [ ] `APP_DEBUG=false`
- [ ] `APP_URL=https://your-domain.railway.app`
- [ ] `DB_CONNECTION=pgsql` ⭐ **WAJIB untuk database**
- [ ] `LOG_LEVEL=error`
- [ ] `MIDTRANS_CLIENT_KEY=your-key`
- [ ] `MIDTRANS_SERVER_KEY=your-key`
- [ ] `MIDTRANS_IS_PRODUCTION=false`

**Catatan**: Railway otomatis menambahkan database variables dari PostgreSQL service, tapi pastikan `DB_CONNECTION=pgsql` sudah di-set.

---

## 🔍 Jika Masih Error

1. Cek tab **"Build Logs"** untuk detail error
2. Pastikan semua variable di atas sudah ada
3. Pastikan `composer.json` sudah diupdate dengan `"php": "^8.1"`
4. Pastikan file `.php-version` ada dengan isi `8.1`

---

**Setelah menambahkan `RAILPACK_PHP_VERSION=8.1`, build seharusnya berhasil!** 🚀

