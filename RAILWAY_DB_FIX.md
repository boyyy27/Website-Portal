# 🔧 Fix Database Connection Error di Railway

## Error yang Terjadi

```
Database connection [postgresql://postgres:...@omile-db.railway.internal:5432/railway] not configured.
```

## Penyebab

Laravel mencoba menggunakan connection string sebagai nama connection. Ini terjadi karena:
- `DB_CONNECTION` variable kemungkinan di-set ke connection string (bukan `pgsql`)
- Atau `DB_CONNECTION` tidak di-set, dan Laravel default menggunakan `DATABASE_URL` sebagai connection name

## ✅ Solusi

### Step 1: Update Environment Variables di Railway Dashboard

1. Buka Railway Dashboard → Service **"omile-portal"** → Tab **"Variables"**

2. **Pastikan `DB_CONNECTION` adalah `pgsql` (BUKAN connection string!)**

   Cek apakah ada variable `DB_CONNECTION`:
   - Jika sudah ada, klik edit dan pastikan value-nya adalah: `pgsql` (bukan connection string!)
   - Jika belum ada, klik **"+ New Variable"**:
     - **Name**: `DB_CONNECTION`
     - **Value**: `pgsql` (huruf kecil, bukan connection string!)

3. **Pastikan `DATABASE_URL` ada**

   Railway biasanya otomatis inject `DATABASE_URL` dari database service. 
   
   Jika belum ada, tambahkan dengan reference:
   - **Name**: `DATABASE_URL`
   - **Value**: `${{omile-db.DATABASE_URL}}`

### Step 2: Parse DATABASE_URL ke Individual Variables (Alternatif)

Jika masih error, bisa juga parse `DATABASE_URL` menjadi individual variables:

Dari `DATABASE_URL`:
```
postgresql://postgres:JzZMvslevsTCapZfUsdfTqYiVdohQtHI@omile-db.railway.internal:5432/railway
```

Tambahkan variables:
```
DB_CONNECTION=pgsql
DB_HOST=omile-db.railway.internal
DB_PORT=5432
DB_DATABASE=railway
DB_USERNAME=postgres
DB_PASSWORD=JzZMvslevsTCapZfUsdfTqYiVdohQtHI
```

**Tapi seharusnya tidak perlu ini**, karena Laravel bisa parse `DATABASE_URL` otomatis jika `DB_CONNECTION=pgsql` sudah benar.

### Step 3: Clear Config Cache

Setelah update variables, clear config cache:

```bash
railway run php artisan config:clear
railway run php artisan cache:clear
```

### Step 4: Test Migrations Lagi

```bash
railway run php artisan migrate --force
```

---

## 📋 Checklist Variables yang Harus Ada

Di service **"omile-portal"** → Tab **"Variables"**, pastikan:

- [ ] `DB_CONNECTION=pgsql` ⭐ **PENTING: Harus string "pgsql", bukan connection string!**
- [ ] `DATABASE_URL=${{omile-db.DATABASE_URL}}` (atau otomatis dari Railway)
- [ ] `APP_KEY=base64:...` (sudah ada)
- [ ] `APP_ENV=production` (sudah ada)
- [ ] `APP_DEBUG=false` (sudah ada)
- [ ] `RAILPACK_PHP_VERSION=8.2` (sudah ada)

---

## 🔍 Cara Cek Variables

1. Railway Dashboard → Service **"omile-portal"** → Tab **"Variables"**
2. Scroll ke bawah, cari `DB_CONNECTION`
3. Pastikan value-nya adalah: `pgsql` (teks "pgsql", bukan connection string!)

---

## 💡 Tips

- `DB_CONNECTION` = nama connection (harus `pgsql`)
- `DATABASE_URL` = connection string lengkap (dari Railway)
- Laravel akan otomatis parse `DATABASE_URL` jika `DB_CONNECTION=pgsql` benar

---

**Setelah fix `DB_CONNECTION=pgsql`, migrations seharusnya berhasil!** 🚀

---

## ⚠️ Error: "could not translate host name to address: Unknown host"

Jika Anda mendapat error ini saat run migrations dari **local machine**:

```
SQLSTATE[08006] [7] could not translate host name "omile-db.railway.internal" to address: Unknown host
```

**Penyebab**: Anda mencoba connect ke database dari local machine menggunakan internal hostname Railway yang hanya bisa diakses dari dalam Railway network.

### Solusi 1: Run Migrations Langsung di Railway (Recommended) ⭐

**Jangan run dari local!** Run migrations langsung di Railway server:

#### Via Deploy Command (Paling Mudah):

1. Railway Dashboard → Service **"omile-portal"** → Tab **"Settings"**
2. Scroll ke **"Deploy Command"**
3. Set sebagai:
   ```
   php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT
   ```
4. Klik **"Save"**
5. Tab **"Deployments"** → **"Redeploy"** → **"Deploy latest commit"**

Setelah migrations berhasil, kembalikan start command ke:
```
php artisan serve --host=0.0.0.0 --port=$PORT
```

#### Via Railway CLI (Remote):

```bash
# Pastikan sudah link ke project
railway link

# Run migrations di Railway server (bukan local!)
railway run php artisan migrate --force
```

**Catatan**: `railway run` akan execute command di Railway server, bukan local machine.

### Solusi 2: Gunakan Public Database URL untuk Local Development

Jika ingin run migrations dari local untuk development:

1. Railway Dashboard → Service **"omile-db"** → Tab **"Variables"**
2. Copy **`DATABASE_PUBLIC_URL`** (bukan `DATABASE_URL`)
3. Di local `.env`, set:
   ```
   DB_CONNECTION=pgsql
   DATABASE_URL=<paste DATABASE_PUBLIC_URL di sini>
   ```
4. Atau parse manual:
   ```
   DB_CONNECTION=pgsql
   DB_HOST=trolley.proxy.rlwy.net
   DB_PORT=28317
   DB_DATABASE=railway
   DB_USERNAME=postgres
   DB_PASSWORD=JzZMvslevsTCapZfUsdfTqYiVdohQtHI
   ```

**Tapi untuk production, tetap gunakan internal URL di Railway!**

---

## 📋 Summary

- **Untuk Production (Railway)**: Gunakan `DATABASE_URL` dengan internal hostname (`omile-db.railway.internal`)
- **Untuk Local Development**: Gunakan `DATABASE_PUBLIC_URL` atau parse manual
- **Run Migrations**: Lebih baik run langsung di Railway via Deploy Command atau `railway run`

