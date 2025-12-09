# 🚂 Setup Railway untuk OMILE Portal

## 🎯 Kenapa Railway?

Railway adalah **platform PALING RECOMMENDED** untuk deploy Laravel karena:
- ✅ **Auto-detect Laravel** - Railway menggunakan Railpack untuk otomatis mengenali project Laravel
- ✅ **PostgreSQL included** - Database langsung tersedia
- ✅ **Auto-deploy dari GitHub** - Setiap push langsung deploy
- ✅ **Environment variables** - Mudah di-manage
- ✅ **Persistent storage** - Support file uploads dan sessions
- ✅ **Gratis $5 credit/bulan** - Cukup untuk testing
- ✅ **Setup sangat mudah** - Hanya 5 menit!

**Catatan**: File `railway.json` dan `.php-version` sudah dibuat untuk konfigurasi PHP version.

---

## 🚀 Step-by-Step Deployment

### Step 1: Daftar di Railway

1. Kunjungi [railway.app](https://railway.app)
2. Klik **"Start a New Project"** atau **"Login"**
3. Sign up dengan **GitHub account** (recommended)
4. Authorize Railway untuk akses GitHub
5. Anda akan mendapat **$5 free credit** setiap bulan

---

### Step 2: Buat Project Baru

1. Di Railway Dashboard, klik **"New Project"**
2. Pilih **"Deploy from GitHub repo"**
3. Pilih repository: **`boyyy27/Website-Portal`**
4. Railway akan otomatis:
   - Detect bahwa ini adalah Laravel project
   - Setup build configuration
   - Mulai deploy pertama

**Tunggu beberapa detik** - Railway akan mulai build project.

---

### Step 3: Tambahkan PostgreSQL Database

**PENTING**: Tambahkan database sebelum setup environment variables!

1. Di project dashboard, klik **"+ New"**
2. Pilih **"Database"** → **"Add PostgreSQL"**
3. Railway akan otomatis:
   - Membuat PostgreSQL database
   - Generate connection string
   - Set environment variables untuk database

**Tunggu sampai database selesai dibuat** (biasanya 30 detik - 1 menit).

---

### Step 4: Setup Environment Variables

1. Klik service **"Website-Portal"** (bukan database)
2. Klik tab **"Variables"**
3. Klik **"New Variable"** dan tambahkan satu per satu:

**PENTING - Tambahkan ini DULU untuk fix PHP version:**
```
RAILPACK_PHP_VERSION=8.1
```

#### A. Application Variables (Wajib)

```
APP_NAME=OMILE
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-project.railway.app
LOG_LEVEL=error
```

**Untuk APP_KEY:**
- Generate dulu di local:
  ```bash
  php artisan key:generate --show
  ```
- Copy hasilnya (format: `base64:...`)
- Set sebagai: `APP_KEY=base64:...` (paste hasil generate)

#### B. Database Variables

**Railway otomatis set ini dari PostgreSQL service!** Tapi pastikan ada:

```
DB_CONNECTION=pgsql
```

Database variables lainnya (`DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`) akan **otomatis di-set oleh Railway** dari PostgreSQL service yang sudah dibuat.

**Cara cek:**
- Klik PostgreSQL service
- Di tab "Variables", Railway sudah set:
  - `PGHOST`
  - `PGPORT`
  - `PGDATABASE`
  - `PGUSER`
  - `PGPASSWORD`
- Railway juga otomatis set di web service sebagai:
  - `DATABASE_URL` (connection string lengkap)

**Jika perlu set manual:**
- Klik PostgreSQL service → tab "Connect" → copy connection string
- Parse dan set manual di web service (tapi biasanya tidak perlu)

#### C. Midtrans Variables

```
MIDTRANS_CLIENT_KEY=your-midtrans-client-key
MIDTRANS_SERVER_KEY=your-midtrans-server-key
MIDTRANS_IS_PRODUCTION=false
```

#### D. Email Variables (Optional)

Jika ingin setup email verification:

```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@omile.id
MAIL_FROM_NAME=OMILE
```

---

### Step 5: Generate APP_KEY (Jika Belum)

Jika belum generate APP_KEY:

1. Klik service **"Website-Portal"**
2. Klik tab **"Deployments"**
3. Klik deployment terbaru
4. Klik **"View Logs"**
5. Atau gunakan Railway CLI:
   ```bash
   railway run php artisan key:generate --show
   ```
6. Copy hasilnya dan set sebagai environment variable `APP_KEY`

**Atau via Railway Dashboard:**
1. Klik service → tab "Settings"
2. Scroll ke "Deploy Command"
3. Tambahkan: `php artisan key:generate` (hanya sekali, lalu hapus)

---

### Step 6: Run Migrations

Setelah deploy berhasil:

1. Klik service **"Website-Portal"**
2. Klik tab **"Deployments"**
3. Klik deployment terbaru
4. Klik **"View Logs"** untuk cek status

**Run migrations via Railway CLI:**
```bash
# Install Railway CLI (jika belum)
npm i -g @railway/cli

# Login
railway login

# Link ke project
railway link

# Run migrations
railway run php artisan migrate --force
```

**Atau via Railway Dashboard:**
1. Klik service → tab "Settings"
2. Scroll ke "Deploy Command"
3. Set sebagai:
   ```
   php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT
   ```
4. Redeploy

**Atau via Deploy Hook:**
1. Klik service → tab "Settings"
2. Scroll ke "Deploy Hooks"
3. Create hook untuk run migrations (opsional)

---

### Step 7: Setup Custom Domain (Optional)

1. Klik service **"Website-Portal"**
2. Klik tab **"Settings"**
3. Scroll ke **"Domains"**
4. Klik **"Generate Domain"** untuk mendapat domain Railway
   - Format: `your-project.railway.app`
5. Atau **"Custom Domain"** untuk domain sendiri
   - Tambahkan domain
   - Setup DNS records sesuai instruksi Railway

---

### Step 8: Deploy!

Railway akan **otomatis deploy** setiap kali Anda push ke GitHub!

**Manual Deploy:**
1. Klik service → tab "Deployments"
2. Klik **"Redeploy"** → **"Deploy latest commit"**

**Tunggu deploy selesai** (biasanya 2-3 menit).

---

## ✅ Checklist Sebelum Deploy

- [ ] Railway account sudah dibuat
- [ ] Project sudah dibuat dari GitHub repo
- [ ] PostgreSQL database sudah ditambahkan
- [ ] Environment variables sudah di-set:
  - [ ] `APP_NAME`
  - [ ] `APP_ENV=production`
  - [ ] `APP_DEBUG=false`
  - [ ] `APP_URL` (dengan domain Railway)
  - [ ] `APP_KEY` (sudah di-generate)
  - [ ] `DB_CONNECTION=pgsql`
  - [ ] `MIDTRANS_CLIENT_KEY`
  - [ ] `MIDTRANS_SERVER_KEY`
  - [ ] `MIDTRANS_IS_PRODUCTION=false`
- [ ] Migrations sudah di-run
- [ ] Deploy berhasil (cek di tab "Deployments")

---

## 🔧 Troubleshooting

### Deploy Gagal / Build Error

**Kemungkinan penyebab:**
1. APP_KEY belum di-set
2. Database connection error
3. Composer dependencies error
4. PHP version tidak terdeteksi

**Solusi:**
1. Cek tab **"Logs"** untuk detail error
2. Pastikan semua environment variables sudah di-set
3. Pastikan PostgreSQL sudah dibuat dan running
4. Cek `composer.json` valid
5. **Jika error "No version available for php"**:
   - File `railway.json` dan `.php-version` sudah dibuat di project
   - File `composer.json` sudah diupdate dengan `config.platform.php`
   - Pastikan semua file sudah di-commit dan push ke GitHub
   - Redeploy setelah push
   - Railway menggunakan **Railpack** (bukan Nixpacks) untuk Laravel detection

---

### Error: "No application encryption key"

**Solusi:**
1. Generate APP_KEY:
   ```bash
   php artisan key:generate --show
   ```
2. Set sebagai environment variable `APP_KEY` di Railway
3. Redeploy

---

### Error: Database Connection Failed

**Solusi:**
1. Pastikan PostgreSQL service sudah dibuat dan running
2. Pastikan `DB_CONNECTION=pgsql` sudah di-set
3. Railway otomatis set database variables dari PostgreSQL service
4. Jika masih error, cek connection string di PostgreSQL service → tab "Connect"
5. Pastikan web service dan database di project yang sama

---

### Error: 500 Internal Server Error

**Solusi:**
1. Cek tab **"Logs"** untuk detail error
2. Pastikan `APP_KEY` sudah di-set
3. Pastikan `APP_DEBUG=false` untuk production
4. Pastikan migrations sudah di-run
5. Cek Laravel logs (jika bisa akses):
   ```bash
   railway run tail -f storage/logs/laravel.log
   ```

---

### Service Tidak Bisa Diakses

**Solusi:**
1. Pastikan service status adalah **"Active"** (bukan "Stopped")
2. Cek tab **"Deployments"** untuk melihat status deploy
3. Pastikan domain sudah di-generate
4. Cek **"Logs"** untuk melihat apakah service running
5. Pastikan start command benar: `php artisan serve --host=0.0.0.0 --port=$PORT`

---

### Migrations Tidak Berjalan

**Solusi:**
1. Run migrations manual via Railway CLI:
   ```bash
   railway run php artisan migrate --force
   ```
2. Atau tambahkan di Deploy Command:
   ```
   php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT
   ```
3. Pastikan database sudah dibuat dan running
4. Pastikan database variables sudah di-set

---

## 📊 Railway vs Platform Lain

| Feature | Railway | Render | Vercel |
|---------|---------|--------|--------|
| Laravel Support | ✅ Native | ✅ Good | ❌ Limited |
| PostgreSQL | ✅ Included | ✅ Included | ❌ External |
| Auto-deploy | ✅ Yes | ✅ Yes | ✅ Yes |
| Setup Time | ⭐⭐⭐⭐⭐ 5 min | ⭐⭐⭐⭐ 10 min | ⭐⭐ 15+ min |
| Free Tier | ✅ $5 credit | ✅ Free tier | ✅ Free tier |
| Ease of Use | ⭐⭐⭐⭐⭐ | ⭐⭐⭐⭐ | ⭐⭐ |

**Kesimpulan**: Railway adalah pilihan terbaik untuk Laravel! 🚀

---

## 🎯 Tips & Best Practices

1. **Gunakan Railway CLI** - Lebih mudah untuk run commands
2. **Monitor Logs** - Cek logs secara berkala untuk error
3. **Set APP_DEBUG=false** - Untuk production
4. **Backup Database** - Railway free tier tidak include backup otomatis
5. **Monitor Usage** - Cek usage di dashboard untuk avoid overage
6. **Use Deploy Hooks** - Untuk automate tasks seperti migrations

---

## 🔗 Links

- **Railway Dashboard**: [railway.app](https://railway.app)
- **Railway Docs**: [docs.railway.app](https://docs.railway.app)
- **Railway CLI**: [docs.railway.app/cli](https://docs.railway.app/cli)
- **Railway Status**: [status.railway.app](https://status.railway.app)

---

## 💡 Quick Commands

```bash
# Install Railway CLI
npm i -g @railway/cli

# Login
railway login

# Link ke project
railway link

# Run migrations
railway run php artisan migrate --force

# Run tinker
railway run php artisan tinker

# View logs
railway logs

# Open shell
railway shell
```

---

**Good luck dengan deployment!** 🚂✨

Jika ada pertanyaan atau error, cek tab **"Logs"** di Railway dashboard atau buka issue di GitHub.

