# Setup Render untuk OMILE Portal

## 🚀 Panduan Lengkap Deploy ke Render

### Step 1: Daftar di Render

1. Kunjungi [render.com](https://render.com)
2. Sign up dengan GitHub account (gratis)
3. Verifikasi email jika diperlukan

---

### Step 2: Buat PostgreSQL Database

**PENTING**: Buat database dulu sebelum web service!

1. Di Render Dashboard, klik **"New +"**
2. Pilih **"PostgreSQL"**
3. Isi form:
   - **Name**: `omile-db` (atau nama lain)
   - **Database**: `omile` (atau nama lain)
   - **User**: `omile_user` (atau nama lain)
   - **Region**: Pilih yang terdekat (Singapore recommended)
   - **Plan**: **Free** (untuk testing)
4. Klik **"Create Database"**
5. **Tunggu sampai database selesai dibuat** (biasanya 1-2 menit)
6. **Copy connection string**:
   - Klik database yang sudah dibuat
   - Di tab "Connections", copy **"Internal Database URL"**
   - Format: `postgresql://user:password@host:5432/database`

---

### Step 3: Buat Web Service

1. Di Render Dashboard, klik **"New +"**
2. Pilih **"Web Service"**
3. **Connect Repository**:
   - Pilih **"Connect GitHub"** (jika belum)
   - Authorize Render untuk akses GitHub
   - Pilih repository: `boyyy27/Website-Portal`
   - Klik **"Connect"**

4. **Configure Web Service**:
   
   **Basic Settings:**
   - **Name**: `omile-portal` (atau nama lain)
   - **Region**: Pilih yang sama dengan database
   - **Branch**: `main`
   - **Root Directory**: (kosongkan, atau `./`)
   - **Runtime**: **Docker** (atau **PHP** jika tersedia)
   
   **Build & Deploy:**
   - **Build Command**: 
     ```
     composer install --no-dev --optimize-autoloader && php artisan config:cache && php artisan route:cache && php artisan view:cache
     ```
   - **Start Command**: 
     ```
     php artisan serve --host=0.0.0.0 --port=$PORT
     ```
   
   **Plan**: **Free** (untuk testing)

5. **Environment Variables**:
   
   Klik **"Advanced"** → **"Add Environment Variable"** dan tambahkan:
   
   ```
   APP_NAME=OMILE
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://your-service-name.onrender.com
   LOG_LEVEL=error
   
   APP_KEY=base64:YOUR_APP_KEY_HERE
   ```
   
   **Untuk APP_KEY**, generate dulu:
   ```bash
   php artisan key:generate --show
   ```
   Copy hasilnya dan paste sebagai value `APP_KEY`
   
   **Database Connection**:
   ```
   DB_CONNECTION=pgsql
   ```
   
   Untuk `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`:
   - Kembali ke database yang sudah dibuat
   - Di tab "Connections", gunakan **"Internal Database URL"**
   - Parse connection string tersebut:
     - Format: `postgresql://user:password@host:5432/database`
     - `DB_HOST` = bagian `host` (tanpa port)
     - `DB_PORT` = `5432`
     - `DB_DATABASE` = bagian `database`
     - `DB_USERNAME` = bagian `user`
     - `DB_PASSWORD` = bagian `password`
   
   **Midtrans**:
   ```
   MIDTRANS_CLIENT_KEY=your-client-key
   MIDTRANS_SERVER_KEY=your-server-key
   MIDTRANS_IS_PRODUCTION=false
   ```
   
   **Email (Optional)**:
   ```
   MAIL_MAILER=smtp
   MAIL_HOST=your-smtp-host
   MAIL_PORT=587
   MAIL_USERNAME=your-email
   MAIL_PASSWORD=your-password
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS=noreply@omile.id
   MAIL_FROM_NAME=OMILE
   ```

6. **Klik "Create Web Service"**

---

### Step 4: Tunggu Deploy

1. Render akan mulai build dan deploy
2. Proses biasanya memakan waktu **3-5 menit**
3. Anda bisa melihat progress di **"Events"** tab
4. Jika ada error, cek **"Logs"** tab

---

### Step 5: Run Migrations

Setelah deploy berhasil:

1. Buka **"Shell"** tab di web service
2. Atau gunakan Render CLI:
   ```bash
   render run --service omile-portal php artisan migrate --force
   ```
3. Atau via SSH (jika tersedia):
   ```bash
   php artisan migrate --force
   ```

---

## 🔧 Troubleshooting

### Deploy tidak terjadi apa-apa / stuck

**Kemungkinan penyebab:**
1. **Build command salah** - Pastikan command benar
2. **Start command salah** - Pastikan menggunakan `$PORT`
3. **Environment variables belum di-set** - Pastikan semua required vars sudah di-set
4. **Database belum dibuat** - Buat database dulu sebelum web service

**Solusi:**
1. Cek **"Logs"** tab untuk melihat error
2. Pastikan build command dan start command benar
3. Pastikan semua environment variables sudah di-set
4. Coba cancel dan buat ulang web service

---

### Error: "No application encryption key"

**Solusi:**
1. Generate APP_KEY:
   ```bash
   php artisan key:generate --show
   ```
2. Set sebagai environment variable `APP_KEY` di Render
3. Redeploy

---

### Error: Database Connection Failed

**Solusi:**
1. Pastikan database sudah dibuat dan running
2. Pastikan menggunakan **"Internal Database URL"** (bukan External)
3. Cek environment variables:
   - `DB_CONNECTION=pgsql`
   - `DB_HOST` = host dari connection string
   - `DB_PORT=5432`
   - `DB_DATABASE` = database name
   - `DB_USERNAME` = user dari connection string
   - `DB_PASSWORD` = password dari connection string
4. Pastikan web service dan database di region yang sama

---

### Error: Build Failed

**Solusi:**
1. Cek **"Logs"** tab untuk detail error
2. Pastikan `composer.json` valid
3. Pastikan PHP version compatible (7.3+ atau 8.0+)
4. Coba build command secara manual:
   ```bash
   composer install --no-dev --optimize-autoloader
   ```

---

### Error: 500 Internal Server Error

**Solusi:**
1. Cek **"Logs"** tab untuk detail error
2. Pastikan `APP_KEY` sudah di-set
3. Pastikan database connection benar
4. Cek Laravel logs (jika bisa akses):
   ```bash
   render run --service omile-portal tail -f storage/logs/laravel.log
   ```

---

### Service tidak bisa diakses

**Solusi:**
1. Pastikan service status adalah **"Live"** (bukan "Stopped")
2. Cek **"Events"** tab untuk melihat status deploy
3. Jika service stopped, klik **"Manual Deploy"** → **"Deploy latest commit"**
4. Pastikan URL benar: `https://your-service-name.onrender.com`

---

## 📝 Checklist Sebelum Deploy

- [ ] Database PostgreSQL sudah dibuat di Render
- [ ] Connection string sudah di-copy
- [ ] APP_KEY sudah di-generate dan di-set sebagai environment variable
- [ ] Semua environment variables sudah di-set:
  - [ ] APP_NAME
  - [ ] APP_ENV=production
  - [ ] APP_DEBUG=false
  - [ ] APP_URL
  - [ ] APP_KEY
  - [ ] DB_CONNECTION=pgsql
  - [ ] DB_HOST
  - [ ] DB_PORT=5432
  - [ ] DB_DATABASE
  - [ ] DB_USERNAME
  - [ ] DB_PASSWORD
  - [ ] MIDTRANS_CLIENT_KEY
  - [ ] MIDTRANS_SERVER_KEY
  - [ ] MIDTRANS_IS_PRODUCTION=false
- [ ] Build command sudah benar
- [ ] Start command sudah benar (menggunakan `$PORT`)

---

## 🎯 Tips

1. **Gunakan Internal Database URL** - Lebih cepat dan aman
2. **Set APP_DEBUG=false** untuk production
3. **Monitor Logs** - Cek logs secara berkala untuk error
4. **Free tier limitations**:
   - Service akan sleep setelah 15 menit tidak aktif
   - First request setelah sleep akan lambat (~30 detik)
   - Upgrade ke paid plan untuk menghindari sleep

---

## 🔗 Links

- **Render Dashboard**: [dashboard.render.com](https://dashboard.render.com)
- **Render Docs**: [render.com/docs](https://render.com/docs)
- **Render Status**: [status.render.com](https://status.render.com)

---

## 💡 Alternatif: Gunakan Railway

Jika masih mengalami masalah dengan Render, coba **Railway** yang lebih mudah:
- Lihat [VERCEL_ALTERNATIVE.md](VERCEL_ALTERNATIVE.md) untuk panduan Railway
- Railway lebih mudah setup dan lebih reliable untuk Laravel

---

**Good luck dengan deployment!** 🚀

