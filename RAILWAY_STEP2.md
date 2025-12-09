# 🚀 Railway Step 2: Setup Database & Run Migrations

Setelah deployment berhasil (Step 1), lanjutkan ke Step 2 untuk setup database dan run migrations.

---

## ✅ Step 2A: Pastikan Database Connection

### 1. Setup Database Variable di Web Service

1. Di Railway Dashboard, klik service **"omile-portal"** (bukan `omile-db`)
2. Klik tab **"Variables"**
3. Pastikan ada variable:
   ```
   DB_CONNECTION=pgsql
   ```
   Jika belum ada, klik **"+ New Variable"** dan tambahkan.

### 2. Connect Database ke Web Service

Railway biasanya otomatis connect database, tapi pastikan:

1. Di service **"omile-portal"** → tab **"Variables"**
2. Cari variable `DB_CONNECTION`
3. Jika kosong atau tidak ada, tambahkan:
   - **Name**: `DB_CONNECTION`
   - **Value**: `pgsql`
4. Railway akan otomatis inject database variables dari `omile-db` service:
   - `DATABASE_URL` (otomatis dari Railway)
   - Atau bisa juga set manual dengan reference: `${{omile-db.DATABASE_URL}}`

**Catatan**: Railway otomatis set `DATABASE_URL` dari PostgreSQL service, jadi biasanya cukup set `DB_CONNECTION=pgsql` saja.

---

## ✅ Step 2B: Run Migrations

Ada 3 cara untuk run migrations:

### Opsi 1: Via Railway CLI (Recommended) ⭐

1. **Install Railway CLI** (jika belum):
   ```bash
   npm i -g @railway/cli
   ```

2. **Login ke Railway**:
   ```bash
   railway login
   ```

3. **Link ke project**:
   ```bash
   railway link
   ```
   Pilih project `carefree-benevolence` dan service `omile-portal`

4. **Run migrations**:
   ```bash
   railway run php artisan migrate --force
   ```

5. **Cek hasil**:
   - Jika berhasil, akan muncul pesan "Migration table created successfully" atau "Nothing to migrate"
   - Jika error, cek pesan error yang muncul

### Opsi 2: Via Railway Dashboard (Deploy Command)

1. Klik service **"omile-portal"**
2. Klik tab **"Settings"**
3. Scroll ke **"Deploy Command"**
4. Set sebagai:
   ```
   php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT
   ```
5. Klik **"Save"**
6. Klik tab **"Deployments"** → **"Redeploy"** → **"Deploy latest commit"**

**Catatan**: Setelah migrations berhasil, bisa kembalikan start command ke:
```
php artisan serve --host=0.0.0.0 --port=$PORT
```

### Opsi 3: Via Railway Shell (Jika Tersedia)

1. Klik service **"omile-portal"**
2. Klik tab **"Deployments"**
3. Klik deployment terbaru
4. Jika ada tombol **"Shell"** atau **"Open Shell"**, klik
5. Run command:
   ```bash
   php artisan migrate --force
   ```

---

## ✅ Step 2C: Verify Setup

### 1. Cek Database Connection

Via Railway CLI:
```bash
railway run php artisan tinker
```

Di tinker, coba:
```php
DB::connection()->getPdo();
```

Jika tidak error, berarti database connection berhasil!

### 2. Cek Migrations

Via Railway CLI:
```bash
railway run php artisan migrate:status
```

Harusnya semua migrations sudah di-run.

### 3. Test Website

1. Buka URL Railway: `https://omile-portal-production.up.railway.app`
2. Jika muncul halaman Laravel atau landing page, berarti berhasil!
3. Jika error, cek tab **"Logs"** di Railway dashboard

---

## 🔧 Troubleshooting

### Error: "SQLSTATE[HY000] [2002] Connection refused"

**Solusi:**
1. Pastikan `DB_CONNECTION=pgsql` sudah di-set
2. Pastikan database service `omile-db` status **"Online"** (hijau)
3. Pastikan Railway sudah inject `DATABASE_URL` dari database service
4. Cek di service `omile-portal` → tab "Variables", harusnya ada `DATABASE_URL` yang otomatis dari Railway

### Error: "No connection could be made because the target machine actively refused it"

**Solusi:**
1. Pastikan menggunakan **internal database URL** (bukan public URL)
2. Railway otomatis set `DATABASE_URL` dengan internal connection
3. Jika set manual, gunakan format: `${{omile-db.DATABASE_URL}}`

### Error: "Base table or view already exists"

**Solusi:**
- Migrations sudah pernah di-run sebelumnya
- Ini normal, tidak perlu khawatir
- Atau bisa reset: `railway run php artisan migrate:fresh --force` (⚠️ HATI-HATI: ini akan hapus semua data!)

### Migrations Tidak Berjalan

**Solusi:**
1. Pastikan database sudah dibuat dan running
2. Pastikan `DB_CONNECTION=pgsql` sudah di-set
3. Pastikan `APP_KEY` sudah di-set
4. Cek logs: `railway run php artisan migrate --force -v`

---

## 📋 Checklist Step 2

- [ ] `DB_CONNECTION=pgsql` sudah di-set di service `omile-portal`
- [ ] Database service `omile-db` status **"Online"**
- [ ] Migrations sudah di-run (via CLI atau Deploy Command)
- [ ] Website bisa diakses di URL Railway
- [ ] Tidak ada error di tab "Logs"

---

## 🎯 Next Steps (Step 3)

Setelah Step 2 selesai, lanjutkan ke:
- ✅ Setup environment variables lainnya (Midtrans, Email, dll)
- ✅ Test semua fitur website
- ✅ Setup custom domain (optional)

---

**Good luck!** 🚀

