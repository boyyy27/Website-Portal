# Setup Vercel untuk OMILE Portal

## 📋 Prerequisites

Sebelum deploy ke Vercel, pastikan:
- ✅ Repository sudah di-push ke GitHub
- ✅ Database sudah di-setup (Vercel tidak menyediakan database, gunakan external database)
- ✅ Environment variables sudah disiapkan

## 🚀 Cara Deploy ke Vercel

### Opsi 1: Via Vercel Dashboard (Recommended)

1. **Sign up/Login ke Vercel**
   - Kunjungi [vercel.com](https://vercel.com)
   - Login dengan GitHub account

2. **Import Project**
   - Klik "Add New..." → "Project"
   - Pilih repository `boyyy27/Website-Portal`
   - Klik "Import"

3. **Configure Project**
   - **Framework Preset**: Other
   - **Root Directory**: `./` (default)
   - **Build Command**: `composer install --no-dev --optimize-autoloader`
   - **Output Directory**: `public` (tidak perlu, karena kita pakai PHP)
   - **Install Command**: (kosongkan)

4. **Environment Variables**
   Klik "Environment Variables" dan tambahkan:
   
   ```
   APP_NAME=OMILE
   APP_ENV=production
   APP_KEY=base64:YOUR_APP_KEY_HERE
   APP_DEBUG=false
   APP_URL=https://your-project.vercel.app
   
   DB_CONNECTION=pgsql
   DB_HOST=your-database-host
   DB_PORT=5432
   DB_DATABASE=your-database-name
   DB_USERNAME=your-database-user
   DB_PASSWORD=your-database-password
   
   MIDTRANS_CLIENT_KEY=your-client-key
   MIDTRANS_SERVER_KEY=your-server-key
   MIDTRANS_IS_PRODUCTION=false
   
   MAIL_MAILER=smtp
   MAIL_HOST=your-smtp-host
   MAIL_PORT=587
   MAIL_USERNAME=your-email
   MAIL_PASSWORD=your-password
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS=noreply@omile.id
   MAIL_FROM_NAME="${APP_NAME}"
   ```

5. **Deploy**
   - Klik "Deploy"
   - Tunggu proses build selesai
   - Link akan muncul: `https://your-project.vercel.app`

---

### Opsi 2: Via Vercel CLI

1. **Install Vercel CLI**
   ```bash
   npm i -g vercel
   ```

2. **Login**
   ```bash
   vercel login
   ```

3. **Deploy**
   ```bash
   cd "c:\Users\BOY SAHRANSYAH\skripsi"
   vercel
   ```

4. **Set Environment Variables**
   ```bash
   vercel env add APP_KEY
   vercel env add DB_HOST
   vercel env add DB_DATABASE
   # ... dan seterusnya
   ```

5. **Production Deploy**
   ```bash
   vercel --prod
   ```

---

## ⚠️ Catatan Penting

### 1. Database
Vercel **TIDAK menyediakan database**. Anda perlu menggunakan:
- **Supabase** (Recommended) - [supabase.com](https://supabase.com) - PostgreSQL gratis
- **Railway PostgreSQL** - [railway.app](https://railway.app)
- **Render PostgreSQL** - [render.com](https://render.com)
- **ElephantSQL** - [elephantsql.com](https://elephantsql.com) - PostgreSQL gratis

### 2. Generate APP_KEY
Sebelum deploy, generate APP_KEY:
```bash
php artisan key:generate --show
```
Copy hasilnya dan set sebagai `APP_KEY` di Vercel environment variables.

### 3. Storage Permissions
Vercel menggunakan read-only filesystem. Untuk storage yang writable, gunakan:
- **Vercel Blob Storage** (paid)
- **AWS S3** (recommended)
- **Cloudinary** (untuk images)

Atau gunakan database untuk menyimpan data yang biasanya di storage.

### 4. File Uploads
Jika ada file upload, gunakan external storage:
- AWS S3
- Cloudinary
- Vercel Blob Storage

---

## 🔧 Konfigurasi Database External

### Menggunakan Supabase (Recommended)

1. **Buat Project di Supabase**
   - Sign up di [supabase.com](https://supabase.com)
   - Create new project
   - Pilih region terdekat

2. **Dapatkan Connection String**
   - Settings → Database
   - Copy "Connection string" (URI)
   - Format: `postgresql://postgres:[PASSWORD]@[HOST]:5432/postgres`

3. **Set di Vercel Environment Variables**
   ```
   DB_CONNECTION=pgsql
   DB_HOST=db.xxxxx.supabase.co
   DB_PORT=5432
   DB_DATABASE=postgres
   DB_USERNAME=postgres
   DB_PASSWORD=your-password
   ```

4. **Run Migrations**
   Setelah deploy, jalankan migrations:
   ```bash
   vercel env pull .env.local
   php artisan migrate --force
   ```

---

## 📝 Post-Deployment Steps

Setelah deploy berhasil:

1. **Run Migrations**
   ```bash
   # Via Vercel CLI
   vercel env pull .env.local
   php artisan migrate --force
   ```

2. **Seed Packages (Optional)**
   ```bash
   php artisan db:seed --class=PackageSeeder
   ```

3. **Clear Cache**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan route:clear
   php artisan view:clear
   ```

4. **Optimize**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

---

## 🔍 Troubleshooting

### Error: "No application encryption key has been specified"
- **Solusi**: Set `APP_KEY` di Vercel environment variables
- Generate key: `php artisan key:generate --show`

### Error: Database Connection Failed
- **Solusi**: 
  - Pastikan database external sudah dibuat
  - Cek credentials di environment variables
  - Pastikan database host accessible dari internet
  - Cek firewall/security groups

### Error: Storage not writable
- **Solusi**: 
  - Gunakan external storage (S3, Cloudinary)
  - Atau gunakan database untuk data storage
  - Vercel filesystem adalah read-only

### Build Failed
- **Solusi**:
  - Cek build logs di Vercel dashboard
  - Pastikan `composer.json` valid
  - Pastikan PHP version compatible (7.3+ atau 8.0+)

### 404 Not Found
- **Solusi**:
  - Pastikan `vercel.json` sudah benar
  - Pastikan routes mengarah ke `public/index.php`
  - Cek Laravel routes: `php artisan route:list`

---

## 🌐 Custom Domain

Untuk menggunakan custom domain:

1. **Di Vercel Dashboard**
   - Settings → Domains
   - Add domain: `yourdomain.com`

2. **Update DNS**
   - Tambahkan CNAME record:
     - Name: `@` atau `www`
     - Value: `cname.vercel-dns.com`

3. **Update APP_URL**
   - Set `APP_URL=https://yourdomain.com` di environment variables
   - Redeploy

---

## 📊 Monitoring

Vercel menyediakan:
- **Analytics** - Traffic monitoring
- **Logs** - Real-time logs
- **Deployments** - Deployment history
- **Performance** - Performance metrics

---

## 💡 Tips

1. **Use Environment Variables** untuk semua sensitive data
2. **Enable Vercel Analytics** untuk monitoring
3. **Set up CI/CD** - Auto-deploy dari GitHub
4. **Use Vercel Blob** untuk file storage (jika perlu)
5. **Monitor Logs** untuk debugging

---

## 🔗 Links

- **Vercel Dashboard**: [vercel.com/dashboard](https://vercel.com/dashboard)
- **Vercel Docs**: [vercel.com/docs](https://vercel.com/docs)
- **Supabase**: [supabase.com](https://supabase.com)
- **Railway**: [railway.app](https://railway.app)

---

**Note**: Vercel adalah platform yang bagus untuk Laravel, tapi perlu setup database external. Untuk kemudahan, pertimbangkan Railway atau Render yang sudah include database.

