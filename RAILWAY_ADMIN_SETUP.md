# 👤 Setup Admin User di Railway

## 🎯 Membuat Admin User di Database Railway

Setelah migrations berhasil, kita perlu membuat admin user untuk bisa login ke dashboard admin.

---

## ✅ Opsi 1: Menggunakan Seeder (Recommended)

### Step 1: Run Admin Seeder

Via Railway CLI:
```bash
railway run php artisan db:seed --class=AdminSeeder
```

**Admin credentials default:**
- **Email**: `admin@omile.id`
- **Password**: `Admin123!`

⚠️ **PENTING**: Ganti password setelah login pertama kali!

### Step 2: Update DatabaseSeeder (Opsional)

Seeder sudah ditambahkan ke `DatabaseSeeder`, jadi bisa juga run:
```bash
railway run php artisan db:seed
```

Ini akan create admin user dan seed packages.

---

## ✅ Opsi 2: Via Deploy Command (Otomatis)

Tambahkan seeder ke Deploy Command di Railway:

1. Railway Dashboard → Service **"omile-portal"** → Tab **"Settings"**
2. Scroll ke **"Deploy Command"**
3. Set sebagai:
   ```
   php artisan migrate --force && php artisan db:seed --class=AdminSeeder && php artisan serve --host=0.0.0.0 --port=$PORT
   ```

**Catatan**: Ini akan create admin setiap deploy. Jika admin sudah ada, seeder akan skip (tidak error).

Setelah admin user dibuat, kembalikan start command ke:
```
php artisan serve --host=0.0.0.0 --port=$PORT
```

---

## ✅ Opsi 3: Manual via Railway CLI

1. Login Railway CLI:
   ```bash
   railway login
   ```

2. Link ke project:
   ```bash
   railway link
   ```

3. Run tinker:
   ```bash
   railway run php artisan tinker
   ```

4. Di tinker, create admin user:
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

5. Type `exit` untuk keluar dari tinker

---

## ✅ Opsi 4: Via Database Direct (Jika punya akses)

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

