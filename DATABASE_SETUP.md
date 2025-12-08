# Setup Database PostgreSQL untuk Laravel

## Konfigurasi Database

File `.env` sudah dikonfigurasi untuk menggunakan PostgreSQL dengan kredensial berikut:

```
DB_CONNECTION=pgsql
DB_HOST=localhost
DB_PORT=5432
DB_DATABASE=skripsi
DB_USERNAME=postgres
DB_PASSWORD=boy270104
```

## Langkah Setup

### 1. Pastikan PostgreSQL sudah terinstall dan berjalan
- Pastikan PostgreSQL service berjalan di localhost:5432
- Database `skripsi` sudah ada (atau buat database baru dengan nama `skripsi`)

### 2. Install PHP PostgreSQL Extension
Pastikan extension PostgreSQL sudah terinstall di PHP:

**Untuk Windows:**
- Edit file `php.ini` (biasanya di `C:\xampp\php\php.ini` atau `C:\wamp\bin\php\phpX.X.X\php.ini`)
- Uncomment atau tambahkan:
```ini
extension=pdo_pgsql
extension=pgsql
```
- Restart web server (Apache/Nginx)

**Cek extension sudah loaded:**
```bash
php -m | findstr pgsql
```

### 3. Generate Application Key
```bash
php artisan key:generate
```

### 4. Clear Config Cache
```bash
php artisan config:clear
php artisan cache:clear
```

### 5. Test Koneksi Database
```bash
php artisan migrate:status
```

Atau test dengan tinker:
```bash
php artisan tinker
>>> DB::connection()->getPdo();
```

### 6. Jalankan Migrations
```bash
php artisan migrate
```

## Troubleshooting

### Error: "could not find driver"
**Solusi:**
- Install PHP PostgreSQL extension (pdo_pgsql)
- Edit `php.ini` dan uncomment `extension=pdo_pgsql` dan `extension=pgsql`
- Restart web server setelah install extension
- Cek dengan `php -m` untuk memastikan extension loaded

### Error: "password authentication failed"
**Solusi:**
- Pastikan password PostgreSQL sesuai dengan yang di `.env`
- Cek file `pg_hba.conf` untuk konfigurasi authentication
- Pastikan user `postgres` memiliki akses ke database `postgres`

### Error: "could not connect to server"
**Solusi:**
- Pastikan PostgreSQL service berjalan
- Cek dengan: `Get-Service postgresql*` (Windows) atau `sudo systemctl status postgresql` (Linux)
- Cek firewall jika menggunakan remote host
- Pastikan port 5432 tidak diblokir
- Cek `DB_HOST` di `.env` apakah benar `localhost` atau `127.0.0.1`

### Error: "database does not exist"
**Solusi:**
- Buat database `skripsi` jika belum ada:
  ```sql
  CREATE DATABASE skripsi;
  ```
- Atau ubah `DB_DATABASE` di `.env` ke database yang sudah ada

## Verifikasi Setup

Setelah setup, verifikasi dengan:

```bash
# Test koneksi
php artisan tinker
>>> DB::connection()->getPdo();

# Cek migrations
php artisan migrate:status

# Jalankan migrations
php artisan migrate
```

## Catatan

- File `.env` sudah dibuat dengan konfigurasi PostgreSQL
- Default connection sudah diubah ke `pgsql` di `config/database.php`
- Pastikan extension `pdo_pgsql` dan `pgsql` sudah enabled di PHP
- Restart web server setelah mengubah `php.ini`
