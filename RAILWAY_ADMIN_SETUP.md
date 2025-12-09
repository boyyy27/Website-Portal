# 👤 Setup Admin User di Railway

## 🎯 Membuat Admin User di Database Railway

Setelah migrations berhasil, kita perlu membuat admin user untuk bisa login ke dashboard admin.

---

## ✅ Opsi 1: Tambahkan ke Deploy Command (PALING MUDAH) ⭐

Seeder akan otomatis di-run saat deploy, jadi admin user akan dibuat otomatis!

1. Railway Dashboard → Service **"omile-portal"** → Tab **"Settings"**
2. Scroll ke **"Deploy Command"**
3. Set sebagai:
   ```
   php artisan migrate --force && php artisan db:seed --class=AdminSeeder --force && php artisan serve --host=0.0.0.0 --port=$PORT
   ```

   **PENTING**: Tambahkan flag `--force` pada `db:seed` untuk skip konfirmasi di production!
4. Klik **"Save"**
5. Tab **"Deployments"** → **"Redeploy"** → **"Deploy latest commit"**

**Admin credentials default:**
- **Email**: `admin@omile.id`
- **Password**: `Admin123!`

⚠️ **PENTING**: 
- Seeder akan skip jika admin sudah ada (tidak error)
- Ganti password setelah login pertama kali!

Setelah admin user dibuat, kembalikan start command ke:
```
php artisan serve --host=0.0.0.0 --port=$PORT
```

---

## ✅ Opsi 2: Run Seeder Manual di Railway Server

⚠️ **PENTING**: Command ini harus di-run di Railway server, bukan dari local machine!

Via Railway CLI:
```bash
# Pastikan sudah link ke project
railway link

# Run seeder di Railway server (bukan local!)
railway run php artisan db:seed --class=AdminSeeder
```

**Catatan**: `railway run` akan execute command di Railway server, bukan local machine.

Atau run semua seeders:
```bash
railway run php artisan db:seed
```

Ini akan create admin user dan seed packages.

---

## ✅ Opsi 3: Manual via Railway CLI (Tinker)

⚠️ **PENTING**: Command ini harus di-run di Railway server, bukan dari local machine!

1. Pastikan sudah link ke project:
   ```bash
   railway link
   ```

2. Run tinker di Railway server:
   ```bash
   railway run php artisan tinker
   ```

3. Di tinker, create admin user:
   ```php
   use App\Models\User;
   use Illuminate\Support\Facades\Hash;
   
   User::create([
       'name' => 'Administrator',
       'email' => 'admin@omile.id',
       'password' => Hash::make('Admin123!'),
       'role' => 'admin',
       'is_active' => true,
       'email_verified' => true,
       'email_verified_at' => now(),
       'verified_at' => now(),
   ]);
   ```

4. Type `exit` untuk keluar dari tinker

---

## ✅ Opsi 4: Via Database Direct (Jika punya akses PostgreSQL)

Jika punya akses langsung ke database Railway:

1. Connect ke database Railway (gunakan connection string dari Railway dashboard)
2. Insert admin user:
   ```sql
   INSERT INTO users (name, email, password, role, is_active, email_verified, email_verified_at, verified_at, created_at, updated_at)
   VALUES (
       'Administrator',
       'admin@omile.id',
       '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- Password: Admin123!
       'admin',
       true,
       true,
       NOW(),
       NOW(),
       NOW(),
       NOW()
   );
   ```

⚠️ **PENTING**: Hash password di atas adalah untuk password `Admin123!`. Jika ingin password berbeda, generate hash dulu.

---

## 🔐 Ganti Password Admin

Setelah login pertama kali, **WAJIB ganti password** untuk keamanan!

1. Login sebagai admin
2. Buka halaman profile atau buat halaman change password
3. Atau update via tinker:
   ```bash
   railway run php artisan tinker
   ```
   ```php
   use App\Models\User;
   use Illuminate\Support\Facades\Hash;
   
   $admin = User::where('email', 'admin@omile.id')->first();
   $admin->password = Hash::make('NewSecurePassword123!');
   $admin->save();
   ```

---

## 📋 Checklist

- [ ] Migration untuk add `role` field sudah di-run
- [ ] AdminSeeder sudah di-run
- [ ] Admin user sudah dibuat di database
- [ ] Bisa login dengan credentials:
  - Email: `admin@omile.id`
  - Password: `Admin123!`
- [ ] Password sudah diganti setelah login pertama kali

---

## 🔍 Verify Admin User

Cek apakah admin user sudah dibuat:

⚠️ **PENTING**: Command ini harus di-run di Railway server!

```bash
railway run php artisan tinker
```

```php
use App\Models\User;

$admin = User::where('email', 'admin@omile.id')->first();
if ($admin) {
    echo "Admin found!\n";
    echo "Name: " . $admin->name . "\n";
    echo "Email: " . $admin->email . "\n";
    echo "Role: " . $admin->role . "\n";
    echo "Is Active: " . ($admin->is_active ? 'Yes' : 'No') . "\n";
} else {
    echo "Admin not found!\n";
}
```

---

**Setelah admin user dibuat, Anda bisa login ke dashboard admin!** 🚀

