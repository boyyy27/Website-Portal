# 🔧 Fix Database Name Error: "database railway) does not exist"

## Error yang Terjadi

```
FATAL: database "railway)" does not exist
```

**Penyebab**: Parsing `DATABASE_URL` menghasilkan nama database dengan karakter tambahan `)`. Ini terjadi karena format connection string atau parsing yang salah.

## ✅ Solusi: Parse DATABASE_URL Manual

Karena parsing otomatis `DATABASE_URL` bermasalah, kita perlu set individual database variables secara manual.

### Step 1: Dapatkan Database Credentials dari Railway

1. Railway Dashboard → Service **"omile-db"** → Tab **"Variables"**
2. Copy nilai dari variables berikut:
   - `PGHOST` atau dari `DATABASE_URL` internal
   - `PGPORT` (biasanya `5432`)
   - `PGDATABASE` (biasanya `railway`)
   - `PGUSER` (biasanya `postgres`)
   - `PGPASSWORD` (copy dari variable ini)

Atau dari `DATABASE_URL`:
```
postgresql://postgres:PASSWORD@omile-db.railway.internal:5432/railway
```

Parse menjadi:
- Host: `omile-db.railway.internal`
- Port: `5432`
- Database: `railway` (tanpa tanda kurung!)
- Username: `postgres`
- Password: `<password dari Railway>`

### Step 2: Set Individual Database Variables di Railway

1. Railway Dashboard → Service **"omile-portal"** → Tab **"Variables"**
2. **Hapus atau ignore `DATABASE_URL`** (kita akan gunakan individual variables)
3. Tambahkan variables berikut (klik **"+ New Variable"** untuk setiap variable):

```
DB_CONNECTION=pgsql
DB_HOST=omile-db.railway.internal
DB_PORT=5432
DB_DATABASE=railway
DB_USERNAME=postgres
DB_PASSWORD=<paste password dari PGPASSWORD>
```

**PENTING**: 
- `DB_DATABASE` harus `railway` (tanpa tanda kurung atau karakter tambahan!)
- Copy `DB_PASSWORD` dari variable `PGPASSWORD` di service `omile-db`

### Step 3: Clear Config Cache dan Redeploy

1. Railway Dashboard → Service **"omile-portal"** → Tab **"Settings"**
2. Scroll ke **"Deploy Command"**
3. Set sebagai:
   ```
   php artisan config:clear && php artisan cache:clear && php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT
   ```
4. Klik **"Save"**
5. Tab **"Deployments"** → **"Redeploy"** → **"Deploy latest commit"**

Setelah migrations berhasil, kembalikan start command ke:
```
php artisan serve --host=0.0.0.0 --port=$PORT
```

---

## 📋 Checklist Variables yang Harus Ada

Di service **"omile-portal"** → Tab **"Variables"**:

- [ ] `DB_CONNECTION=pgsql` ⭐
- [ ] `DB_HOST=omile-db.railway.internal` ⭐
- [ ] `DB_PORT=5432` ⭐
- [ ] `DB_DATABASE=railway` ⭐ **PENTING: Tanpa tanda kurung!**
- [ ] `DB_USERNAME=postgres` ⭐
- [ ] `DB_PASSWORD=<password dari Railway>` ⭐
- [ ] `APP_KEY=base64:...`
- [ ] `RAILPACK_PHP_VERSION=8.2`

**Catatan**: Jika ada `DATABASE_URL`, bisa dihapus atau dibiarkan (Laravel akan prioritaskan individual variables).

---

## 🔍 Cara Dapatkan Password Database

1. Railway Dashboard → Service **"omile-db"** → Tab **"Variables"**
2. Cari variable `PGPASSWORD`
3. Klik icon mata untuk reveal password
4. Copy password tersebut
5. Paste sebagai value `DB_PASSWORD` di service `omile-portal`

---

## 💡 Mengapa Ini Terjadi?

Laravel's automatic `DATABASE_URL` parsing kadang bermasalah dengan format tertentu, terutama jika ada karakter khusus. Dengan set individual variables, kita bypass parsing otomatis dan langsung set values yang benar.

---

## ✅ Setelah Fix

Setelah set semua individual database variables dan redeploy, migrations seharusnya berhasil dan database connection akan bekerja dengan benar!

**Pastikan `DB_DATABASE=railway` tanpa karakter tambahan!** 🚀

