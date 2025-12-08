# OMILE - Transport Management System Portal

Portal pembayaran untuk Transport Management System (TMS) yang memungkinkan user untuk berlangganan paket dan mengakses sistem TMS.

## 🚀 Tech Stack

- **Framework**: Laravel 8.75
- **Database**: PostgreSQL
- **Payment Gateway**: Midtrans
- **Frontend**: Bootstrap 5.3.0, Material Design Icons
- **Styling**: Custom CSS (Open Sans font)

## 📋 Features

- ✅ User Authentication (Register, Login, Email Verification)
- ✅ Package Management (Admin)
- ✅ Dynamic Package Features
- ✅ Payment Integration (Midtrans)
- ✅ Transaction Management
- ✅ User Dashboard
- ✅ Admin Dashboard
- ✅ TMS Access Integration

## ⚠️ Important: GitHub Pages Limitation

**GitHub Pages TIDAK BISA digunakan untuk project ini** karena:
- GitHub Pages hanya support static files (HTML, CSS, JS)
- Laravel memerlukan PHP server dan database
- Laravel memerlukan environment variables (.env)

**Solusi**: Gunakan platform deployment yang support Laravel seperti:
- **Railway** (Recommended) - [railway.app](https://railway.app)
- **Render** - [render.com](https://render.com)
- **Vercel** - [vercel.com](https://vercel.com)
- **Heroku** - [heroku.com](https://heroku.com)

📖 **Lihat [DEPLOYMENT.md](DEPLOYMENT.md) untuk panduan deployment lengkap**

## 🛠️ Installation

### Requirements
- PHP 7.3+ atau 8.0+
- PostgreSQL 10+
- Composer
- Node.js & NPM

### Setup

1. Clone repository:
```bash
git clone https://github.com/boyyy27/Website-Portal.git
cd Website-Portal
```

2. Install dependencies:
```bash
composer install
npm install
```

3. Copy environment file:
```bash
cp .env.example .env
```

4. Generate application key:
```bash
php artisan key:generate
```

5. Setup database di `.env`:
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

6. Run migrations:
```bash
php artisan migrate
```

7. Seed packages (optional):
```bash
php artisan db:seed --class=PackageSeeder
```

8. Setup Midtrans keys di `.env`:
```env
MIDTRANS_CLIENT_KEY=your_client_key
MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_IS_PRODUCTION=false
```

9. Build assets:
```bash
npm run production
```

10. Start server:
```bash
php artisan serve
```

## 📚 Documentation

- [DEPLOYMENT.md](DEPLOYMENT.md) - Panduan deployment
- [DATABASE_SETUP.md](DATABASE_SETUP.md) - Setup database
- [MIDTRANS_SETUP.md](MIDTRANS_SETUP.md) - Setup payment gateway
- [EMAIL_SETUP.md](EMAIL_SETUP.md) - Setup email
- [PROJECT_DOCUMENTATION.md](PROJECT_DOCUMENTATION.md) - Dokumentasi lengkap project

## 🔐 Default Admin

Untuk membuat admin, jalankan:
```bash
php artisan tinker
```
Kemudian:
```php
$user = User::where('email', 'your-email@example.com')->first();
$user->role = 'admin';
$user->save();
```

## 📝 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 👤 Author

**BOY SAHRANSYAH**
- GitHub: [@boyyy27](https://github.com/boyyy27)

---

**Note**: Project ini adalah portal pembayaran untuk TMS. Untuk akses ke sistem TMS, user harus melakukan pembayaran terlebih dahulu melalui portal ini.
