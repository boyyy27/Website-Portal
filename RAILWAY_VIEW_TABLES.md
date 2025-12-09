# 📊 Cara Melihat Tabel Database di Railway

## 🎯 Opsi 1: Via Railway CLI (Terminal) ⭐

### Step 1: Login dan Link ke Project

```bash
railway login
railway link
```

### Step 2: Run Tinker untuk Cek Tables

```bash
railway run php artisan tinker
```

Di dalam tinker, jalankan:

```php
// List semua tabel
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

// Method 1: Via Schema
$tables = DB::select("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'");
foreach($tables as $table) {
    echo $table->table_name . "\n";
}

// Method 2: Via DB facade
$tables = DB::connection()->getDoctrineSchemaManager()->listTableNames();
print_r($tables);

// Method 3: Cek kolom dari tabel tertentu
Schema::getColumnListing('users');
Schema::getColumnListing('packages');
Schema::getColumnListing('transactions');
```

Type `exit` untuk keluar dari tinker.

---

## 🎯 Opsi 2: Via Railway Dashboard (PostgreSQL)

### Step 1: Buka Railway Dashboard

1. Login ke [railway.app](https://railway.app)
2. Pilih project Anda
3. Klik service **"omile-db"** (PostgreSQL service)

### Step 2: Buka PostgreSQL Tab

1. Di service **"omile-db"**, scroll ke bawah
2. Klik tab **"Data"** atau **"Postgres"**
3. Railway akan menampilkan database browser

### Step 3: View Tables

Di database browser, Anda bisa:
- Lihat semua tabel di sidebar kiri
- Klik tabel untuk melihat data
- Run SQL queries
- Export data

---

## 🎯 Opsi 3: Via Laravel Artisan Commands

### List All Tables

```bash
railway run php artisan db:show
```

Atau:

```bash
railway run php artisan tinker
```

```php
use Illuminate\Support\Facades\DB;

// Get all table names
$tables = DB::select("SELECT tablename FROM pg_tables WHERE schemaname = 'public'");
foreach($tables as $table) {
    echo $table->tablename . "\n";
}
```

### Cek Struktur Tabel

```bash
railway run php artisan tinker
```

```php
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

// Cek kolom users table
$columns = Schema::getColumnListing('users');
print_r($columns);

// Cek kolom packages table
$columns = Schema::getColumnListing('packages');
print_r($columns);

// Cek kolom transactions table
$columns = Schema::getColumnListing('transactions');
print_r($columns);

// Cek kolom user_packages table
$columns = Schema::getColumnListing('user_packages');
print_r($columns);
```

### Cek Data di Tabel

```bash
railway run php artisan tinker
```

```php
use App\Models\User;
use App\Models\Package;
use App\Models\Transaction;

// Count records
echo "Users: " . User::count() . "\n";
echo "Packages: " . Package::count() . "\n";
echo "Transactions: " . Transaction::count() . "\n";

// Get all users
$users = User::all();
foreach($users as $user) {
    echo "ID: {$user->id}, Name: {$user->name}, Email: {$user->email}, Role: {$user->role}\n";
}

// Get all packages
$packages = Package::all();
foreach($packages as $package) {
    echo "ID: {$package->id}, Name: {$package->name}, Price: {$package->price}\n";
}
```

---

## 🎯 Opsi 4: Via External Database Client

### Menggunakan DBeaver, pgAdmin, atau TablePlus

1. **Dapatkan Connection String** dari Railway:
   - Railway Dashboard → Service **"omile-db"** → Tab **"Variables"**
   - Copy value dari `DATABASE_URL` atau gunakan individual variables:
     - `DB_HOST`
     - `DB_PORT`
     - `DB_DATABASE`
     - `DB_USERNAME`
     - `DB_PASSWORD`

2. **Connect ke Database**:
   - **Host**: Value dari `DB_HOST` (contoh: `containers-us-west-xxx.railway.app`)
   - **Port**: Value dari `DB_PORT` (biasanya `5432`)
   - **Database**: Value dari `DB_DATABASE` (biasanya `railway`)
   - **Username**: Value dari `DB_USERNAME` (biasanya `postgres`)
   - **Password**: Value dari `DB_PASSWORD`

3. **View Tables**:
   - Setelah connect, expand database → Schemas → public → Tables
   - Klik tabel untuk melihat data dan struktur

---

## 🎯 Opsi 5: Via SQL Query Langsung

### Run SQL Query via Railway CLI

```bash
railway run php artisan tinker
```

```php
use Illuminate\Support\Facades\DB;

// List semua tabel
$tables = DB::select("
    SELECT table_name 
    FROM information_schema.tables 
    WHERE table_schema = 'public' 
    AND table_type = 'BASE TABLE'
    ORDER BY table_name
");

foreach($tables as $table) {
    echo $table->table_name . "\n";
}

// Cek struktur tabel users
$columns = DB::select("
    SELECT column_name, data_type, is_nullable, column_default
    FROM information_schema.columns
    WHERE table_name = 'users'
    ORDER BY ordinal_position
");

foreach($columns as $column) {
    echo "{$column->column_name} ({$column->data_type}) - Nullable: {$column->is_nullable}\n";
}

// Count records per table
$tables = ['users', 'packages', 'transactions', 'user_packages', 'payment_logs'];
foreach($tables as $table) {
    $count = DB::table($table)->count();
    echo "{$table}: {$count} records\n";
}
```

---

## 📋 Quick Commands

### Cek Apakah Tabel Ada

```bash
railway run php artisan tinker
```

```php
use Illuminate\Support\Facades\Schema;

// Check if table exists
Schema::hasTable('users'); // Returns true/false
Schema::hasTable('packages');
Schema::hasTable('transactions');
```

### Cek Apakah Kolom Ada

```bash
railway run php artisan tinker
```

```php
use Illuminate\Support\Facades\Schema;

// Check if column exists
Schema::hasColumn('users', 'role'); // Returns true/false
Schema::hasColumn('users', 'email_verified');
Schema::hasColumn('users', 'is_active');
```

### List Semua Tabel dengan Row Count

```bash
railway run php artisan tinker
```

```php
use Illuminate\Support\Facades\DB;

$tables = DB::select("
    SELECT table_name 
    FROM information_schema.tables 
    WHERE table_schema = 'public' 
    AND table_type = 'BASE TABLE'
");

foreach($tables as $table) {
    $tableName = $table->table_name;
    $count = DB::table($tableName)->count();
    echo "{$tableName}: {$count} rows\n";
}
```

---

## 🔍 Troubleshooting

### Error: "Could not connect to database"

**Solusi**:
1. Pastikan service **"omile-db"** status **"Online"**
2. Cek environment variables di Railway:
   - `DB_HOST`
   - `DB_PORT`
   - `DB_DATABASE`
   - `DB_USERNAME`
   - `DB_PASSWORD`

### Error: "Table does not exist"

**Solusi**:
1. Pastikan migrations sudah di-run:
   ```bash
   railway run php artisan migrate:status
   ```
2. Run migrations jika belum:
   ```bash
   railway run php artisan migrate --force
   ```

### Tidak Bisa Akses via External Client

**Solusi**:
1. Pastikan connection string benar
2. Cek apakah Railway PostgreSQL service punya public access
3. Beberapa Railway plans mungkin tidak support external connections
4. Gunakan Railway Dashboard atau CLI sebagai alternatif

---

## ✅ Checklist

- [ ] Railway CLI sudah terinstall dan ter-login
- [ ] Project sudah di-link (`railway link`)
- [ ] Service **"omile-db"** status **"Online"**
- [ ] Bisa akses via Railway Dashboard atau CLI
- [ ] Tabel-tabel sudah terlihat:
  - [ ] `users`
  - [ ] `packages`
  - [ ] `transactions`
  - [ ] `user_packages`
  - [ ] `payment_logs`

---

**Pilih metode yang paling mudah untuk Anda!** 🚀

**Recommended**: Opsi 1 (Railway CLI + Tinker) untuk quick check, atau Opsi 2 (Railway Dashboard) untuk visual browsing.

