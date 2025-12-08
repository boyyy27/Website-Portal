# Deployment Guide - OMILE Portal

## ⚠️ Catatan Penting

**GitHub Pages TIDAK BISA digunakan untuk Laravel project** karena:
- GitHub Pages hanya support static files (HTML, CSS, JS)
- Laravel memerlukan PHP server dan database
- Laravel memerlukan environment variables (.env)

## 🚀 Opsi Deployment yang Tersedia

### 1. **Vercel** (Recommended untuk Laravel)
- ✅ Support PHP dan Laravel
- ✅ Gratis untuk personal projects
- ✅ Auto-deploy dari GitHub
- ✅ Environment variables support
- ⚠️ **TIDAK menyediakan database** (perlu external database)

**Setup:**
📖 **Lihat [VERCEL_SETUP.md](VERCEL_SETUP.md) untuk panduan lengkap**

Quick Start:
1. File `vercel.json` sudah dibuat di project
2. Sign up di [vercel.com](https://vercel.com)
3. Import project dari GitHub
4. Set environment variables
5. Deploy

**Link akan muncul:** `https://your-project.vercel.app`

**Catatan**: Vercel tidak menyediakan database. Gunakan Supabase, Railway, atau Render untuk database PostgreSQL.

---

### 2. **Railway** (Recommended)
- ✅ Support Laravel + PostgreSQL
- ✅ Auto-deploy dari GitHub
- ✅ Database included
- ✅ Environment variables support

**Setup:**
1. Sign up di [railway.app](https://railway.app)
2. New Project → Deploy from GitHub
3. Pilih repository
4. Add PostgreSQL service
5. Set environment variables
6. Deploy

**Link akan muncul:** `https://your-project.railway.app`

---

### 3. **Render** (Gratis)
- ✅ Support Laravel
- ✅ PostgreSQL support
- ✅ Auto-deploy dari GitHub
- ⚠️ Free tier akan sleep setelah 15 menit tidak aktif

**Setup:**
📖 **Lihat [RENDER_SETUP.md](RENDER_SETUP.md) untuk panduan lengkap step-by-step**

Quick Start:
1. Sign up di [render.com](https://render.com)
2. **Buat PostgreSQL database DULU** (penting!)
3. New → Web Service
4. Connect GitHub repository
5. Build command: `composer install --no-dev --optimize-autoloader && php artisan config:cache && php artisan route:cache && php artisan view:cache`
6. Start command: `php artisan serve --host=0.0.0.0 --port=$PORT`
7. Set environment variables (APP_KEY, database, dll)
8. Deploy

**Link akan muncul:** `https://your-project.onrender.com`

**Catatan**: File `render.yaml` sudah tersedia untuk konfigurasi otomatis (opsional).

---

### 4. **Heroku** (Paid/Free tier limited)
- ✅ Support Laravel
- ✅ PostgreSQL addon
- ✅ Auto-deploy dari GitHub

**Setup:**
1. Install Heroku CLI
2. `heroku create your-app-name`
3. `heroku addons:create heroku-postgresql:hobby-dev`
4. `git push heroku main`
5. Set config vars di dashboard

**Link akan muncul:** `https://your-app-name.herokuapp.com`

---

### 5. **Traditional VPS/Shared Hosting**
Jika Anda punya VPS atau shared hosting:

**Requirements:**
- PHP 7.3+ atau 8.0+
- PostgreSQL 10+
- Composer
- Node.js & NPM

**Steps:**
1. Upload semua file ke server (kecuali `vendor/`, `node_modules/`, `.env`)
2. Install dependencies: `composer install --no-dev`
3. Copy `.env.example` ke `.env`
4. Set environment variables
5. Generate key: `php artisan key:generate`
6. Run migrations: `php artisan migrate`
7. Set permissions: `chmod -R 755 storage bootstrap/cache`
8. Point web server (Apache/Nginx) ke folder `public/`

---

## 🔧 Setup GitHub Actions untuk Auto-Deploy

Jika ingin auto-deploy ke hosting tertentu, buat file `.github/workflows/deploy.yml`:

```yaml
name: Deploy to Production

on:
  push:
    branches:
      - main

jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.0'
      
      - name: Install Dependencies
        run: composer install --no-dev --optimize-autoloader
      
      - name: Build Assets
        run: |
          npm install
          npm run production
      
      - name: Deploy to Server
        uses: SamKirkland/FTP-Deploy-Action@4.3.0
        with:
          server: ${{ secrets.FTP_SERVER }}
          username: ${{ secrets.FTP_USERNAME }}
          password: ${{ secrets.FTP_PASSWORD }}
          local-dir: ./
          server-dir: /public_html/
```

---

## 📋 Checklist Sebelum Deploy

- [ ] File `.env` sudah di-set dengan benar
- [ ] Database sudah di-setup
- [ ] `APP_KEY` sudah di-generate
- [ ] `APP_ENV=production`
- [ ] `APP_DEBUG=false`
- [ ] Midtrans keys sudah di-set
- [ ] Email configuration sudah di-set
- [ ] Run `php artisan config:cache`
- [ ] Run `php artisan route:cache`
- [ ] Run `php artisan view:cache`
- [ ] Set permissions untuk `storage/` dan `bootstrap/cache/`

---

## 🔍 Troubleshooting

### Link tidak muncul setelah deploy
1. **Cek deployment logs** di platform yang digunakan
2. **Cek environment variables** sudah di-set dengan benar
3. **Cek database connection** - pastikan database sudah dibuat
4. **Cek build logs** - pastikan tidak ada error saat build
5. **Cek domain settings** - pastikan custom domain sudah di-set (jika ada)

### Error 500 setelah deploy
1. Cek `.env` file sudah ada dan benar
2. Run `php artisan config:clear`
3. Cek `storage/logs/laravel.log`
4. Pastikan permissions untuk `storage/` dan `bootstrap/cache/` sudah benar

### Database connection error
1. Pastikan database sudah dibuat
2. Cek credentials di `.env`
3. Pastikan database service sudah running
4. Cek firewall/security groups

---

## 💡 Rekomendasi

Untuk project ini, saya rekomendasikan menggunakan **Railway** atau **Render** karena:
- ✅ Gratis untuk start
- ✅ Support Laravel + PostgreSQL
- ✅ Auto-deploy dari GitHub
- ✅ Mudah setup
- ✅ Link langsung muncul setelah deploy

---

## 📞 Butuh Bantuan?

Jika masih ada masalah, cek:
1. Deployment logs di platform
2. Laravel logs: `storage/logs/laravel.log`
3. Server logs di hosting provider

