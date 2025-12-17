# Setup Email Verification dengan Laravel Queue di Railway

## Apa itu Queue?

Queue memungkinkan Laravel mengirim email di **background** (asynchronous), sehingga **tidak akan timeout** saat registrasi. User langsung redirect ke halaman verifikasi, sementara email dikirim di background oleh worker.

---

## 📋 Langkah-langkah Setup

### 1. Update Environment Variables di Railway

Buka **Railway Dashboard** → Pilih service **omile-portal** → Tab **Variables** → Tambahkan/update:

```env
QUEUE_CONNECTION=database
```

**Penjelasan:**
- `QUEUE_CONNECTION=database`: Laravel akan menyimpan job queue di database PostgreSQL (bukan sync/langsung)
- Pastikan variabel MAIL masih lengkap:
  ```env
  MAIL_MAILER=smtp
  MAIL_HOST=smtp.gmail.com
  MAIL_PORT=587
  MAIL_USERNAME=your-email@gmail.com
  MAIL_PASSWORD=your-app-password
  MAIL_ENCRYPTION=tls
  MAIL_FROM_ADDRESS=your-email@gmail.com
  MAIL_FROM_NAME="OMILE Portal"
  ```

---

### 2. Update Deploy Command di Railway

Buka **Railway Dashboard** → Service **omile-portal** → Tab **Settings** → Scroll ke **Deploy**

**Ganti Deploy Command dengan:**

```bash
php artisan config:clear && php artisan cache:clear && php artisan view:clear && php artisan route:clear && php artisan migrate --force && php artisan db:seed --class=AdminSeeder --force && php artisan db:seed --class=PackageSeeder --force && php artisan config:cache && php artisan serve --host=0.0.0.0 --port=$PORT
```

**Penjelasan:**
- Command ini akan otomatis menjalankan migration untuk tabel `jobs` dan `failed_jobs`
- Tabel `jobs` akan menyimpan queue jobs
- Tabel `failed_jobs` akan menyimpan jobs yang gagal

---

### 3. Buat Service Baru untuk Queue Worker

**Queue Worker** adalah proses terpisah yang mengambil jobs dari database dan menjalankannya. Di Railway, kita perlu buat **service terpisah** untuk worker.

#### Cara membuat Queue Worker Service:

**Opsi A: Dari Railway Dashboard (Recommended)**

1. Buka **Project** Railway Anda
2. Klik **+ New** → **Empty Service**
3. Klik service baru tersebut → Tab **Settings**
4. Di bagian **Service Name**, beri nama: `omile-queue-worker`
5. Di bagian **Source** → **Connect to Repo** → Pilih repository GitHub yang sama dengan `omile-portal`
6. Scroll ke **Build** → Di **Build Command**, isi:
   ```bash
   composer install --optimize-autoloader --no-dev
   ```
7. Scroll ke **Deploy** → Di **Start Command**, isi:
   ```bash
   php artisan queue:work --tries=3 --timeout=90
   ```
8. Tab **Variables** → Klik **+ New Variable** → **Add Reference**
   - Pilih service `omile-portal`
   - Centang **SEMUA** variabel yang ada
   - Klik **Add**
   
   Ini akan meng-copy semua environment variables dari service utama ke worker.

9. **Deploy** → Service akan otomatis deploy

**Opsi B: Dari Railway CLI**

```bash
# Di terminal lokal
railway service create
# Nama: omile-queue-worker

railway service
# Pilih: omile-queue-worker

# Link ke repo yang sama
railway up

# Set start command
railway run --service omile-queue-worker -- sh -c "php artisan queue:work --tries=3 --timeout=90"
```

---

### 4. Penjelasan Queue Worker Command

```bash
php artisan queue:work --tries=3 --timeout=90
```

- `queue:work`: Menjalankan worker yang terus menerus mengambil jobs dari database
- `--tries=3`: Jika job gagal, akan di-retry maksimal 3 kali
- `--timeout=90`: Timeout per job adalah 90 detik (cukup untuk kirim email)

---

## 🔍 Cara Cek Apakah Queue Bekerja

### 1. Cek Log di Railway

**Service: omile-portal** (aplikasi utama)
- Setelah user registrasi, cek log apakah ada pesan:
  ```
  Verification email job dispatched to queue for: user@example.com
  ```

**Service: omile-queue-worker** (worker)
- Cek log apakah ada pesan:
  ```
  Sending verification email to: user@example.com
  Verification email sent successfully to: user@example.com
  ```

### 2. Cek Database Jobs Table

Dari Railway Dashboard → Service **omile-portal** → Tab **Data** (jika ada) atau gunakan Railway CLI:

```bash
railway run php artisan tinker
# Di tinker:
>>> DB::table('jobs')->count()
# Jika 0, berarti semua jobs sudah diproses
# Jika > 0, berarti ada jobs yang sedang dalam antrian

>>> DB::table('failed_jobs')->count()
# Jika 0, berarti tidak ada jobs yang gagal
# Jika > 0, berarti ada jobs yang gagal, cek detailnya
```

### 3. Test Registrasi

1. Buka: `https://omile-portal-production.up.railway.app/register`
2. Isi form dan klik **Sign Up**
3. Cek email Anda, seharusnya email verifikasi terkirim dalam **beberapa detik**
4. Jika email belum sampai:
   - Cek **Spam** folder
   - Cek log Railway untuk error
   - Cek tabel `failed_jobs` untuk jobs yang gagal

---

## 🐛 Troubleshooting

### Problem 1: Email Tidak Terkirim

**Cek Log Worker:**
- Buka Railway Dashboard → Service **omile-queue-worker** → Tab **Logs**
- Cari error message terkait SMTP atau email

**Solusi:**
1. Pastikan `MAIL_USERNAME` dan `MAIL_PASSWORD` benar
2. Untuk Gmail, pastikan menggunakan **App Password**, bukan password biasa
3. Pastikan `MAIL_PORT=587` dan `MAIL_ENCRYPTION=tls`

### Problem 2: Queue Worker Tidak Berjalan

**Cek Status Service:**
- Buka Railway Dashboard → Service **omile-queue-worker**
- Pastikan status **Active** (hijau)
- Jika **Crashed** (merah), cek **Logs** untuk error

**Solusi:**
1. Pastikan Start Command benar: `php artisan queue:work --tries=3 --timeout=90`
2. Pastikan semua environment variables ter-copy dari service utama
3. Redeploy service worker

### Problem 3: Jobs Stuck di Database

**Cek Jobs Table:**
```bash
railway run php artisan queue:failed
# Ini akan show semua failed jobs
```

**Solusi:**
1. Jika ada failed jobs, retry dengan:
   ```bash
   railway run php artisan queue:retry all
   ```
2. Atau clear semua failed jobs:
   ```bash
   railway run php artisan queue:flush
   ```

### Problem 4: Worker Menggunakan Banyak Resource

**Solusi:**
Batasi jumlah concurrent jobs dengan menambahkan flag `--max-jobs`:

Update Start Command di **omile-queue-worker**:
```bash
php artisan queue:work --tries=3 --timeout=90 --max-jobs=1000 --max-time=3600
```

- `--max-jobs=1000`: Worker akan restart setelah memproses 1000 jobs (mencegah memory leak)
- `--max-time=3600`: Worker akan restart setelah 1 jam (3600 detik)

---

## ✅ Checklist Setup Queue

- [ ] Environment variable `QUEUE_CONNECTION=database` sudah ditambahkan
- [ ] Deploy Command sudah update untuk menjalankan migration
- [ ] Service `omile-queue-worker` sudah dibuat
- [ ] Queue Worker Start Command: `php artisan queue:work --tries=3 --timeout=90`
- [ ] Semua environment variables ter-copy ke worker service
- [ ] Kedua service (omile-portal & omile-queue-worker) status **Active**
- [ ] Test registrasi berhasil dan email terkirim

---

## 📌 Catatan Penting

1. **Worker harus selalu running** agar email terkirim. Jika worker stop, jobs akan menumpuk di database tapi tidak diproses.

2. **Biaya Railway**: Worker adalah service terpisah, jadi akan menambah usage Railway. Tapi karena worker hanya memproses jobs (tidak handle HTTP requests), resource usage-nya minimal.

3. **Alternative**: Jika tidak ingin setup worker terpisah, bisa gunakan **Railway Cron Jobs** untuk menjalankan `php artisan queue:work` secara berkala. Tapi ini tidak realtime.

4. **Production Best Practice**: 
   - Gunakan Redis untuk queue (lebih cepat dari database)
   - Monitor failed_jobs table secara berkala
   - Setup alerting jika worker crash

---

## 🎯 Next Steps

Setelah setup selesai:

1. **Commit dan Push** semua perubahan ke GitHub:
   ```bash
   git add .
   git commit -m "Setup Laravel Queue for email verification"
   git push origin main
   ```

2. **Redeploy** di Railway (otomatis setelah push ke GitHub)

3. **Test** registrasi dan cek apakah email terkirim

4. **Monitor** logs untuk memastikan tidak ada error

---

Jika ada error atau butuh bantuan, kirim screenshot log dari Railway!




