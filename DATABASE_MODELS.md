# Database Models Documentation

## Overview

Laravel models telah dibuat untuk mengintegrasikan dengan database PostgreSQL yang sudah ada. Semua model sudah disesuaikan dengan struktur database yang ada.

## Models

### 1. User Model (`app/Models/User.php`)

**Table:** `users`

**Fields:**
- `id` (integer, primary key)
- `email` (string, unique)
- `name` (string)
- `password` (string, hashed)
- `role` (string: 'admin' | 'user', default: 'user')
- `is_active` (boolean, default: true)
- `email_verified` (boolean, default: false)
- `verification_token` (string, nullable)
- `verification_token_expires` (timestamp, nullable)
- `verification_token_expires_at` (timestamp, nullable)
- `verified_at` (timestamp, nullable)
- `google_id` (string, nullable)
- `google_email` (string, nullable)
- `avatar_url` (text, nullable)
- `created_at` (timestamp)
- `updated_at` (timestamp)

**Relationships:**
- `userPackages()` - Has many UserPackage
- `activePackage()` - Has one active UserPackage
- `transactions()` - Has many Transaction

**Methods:**
- `isAdmin()` - Check if user is admin
- `hasActivePackage()` - Check if user has active premium package

**Usage:**
```php
use App\Models\User;

// Get user with active package
$user = User::with('activePackage')->find(1);

// Check if user is admin
if ($user->isAdmin()) {
    // Admin logic
}

// Check if user has active package
if ($user->hasActivePackage()) {
    // Premium user logic
}
```

### 2. Package Model (`app/Models/Package.php`)

**Table:** `packages`

**Fields:**
- `id` (integer, primary key)
- `name` (string)
- `description` (text, nullable)
- `price` (decimal:2)
- `duration_days` (integer, default: 30)
- `is_active` (boolean, default: true)
- `created_at` (timestamp)
- `updated_at` (timestamp)

**Relationships:**
- `userPackages()` - Has many UserPackage
- `transactions()` - Has many Transaction

**Scopes:**
- `active()` - Only active packages

**Usage:**
```php
use App\Models\Package;

// Get all active packages
$packages = Package::active()->get();

// Get package with user packages
$package = Package::with('userPackages')->find(1);
```

### 3. Transaction Model (`app/Models/Transaction.php`)

**Table:** `transactions`

**Fields:**
- `id` (integer, primary key)
- `user_id` (integer, foreign key to users)
- `order_id` (string, unique)
- `transaction_id` (string, nullable)
- `package_id` (integer, foreign key to packages, nullable)
- `package_name` (string, nullable)
- `package_price` (decimal:2, nullable)
- `transaction_status` (string: 'pending' | 'settlement' | 'capture' | 'deny' | 'cancel' | 'expire' | 'refund' | 'partial_refund' | 'chargeback' | 'partial_chargeback' | 'authorize')
- `payment_type` (string, nullable)
- `payment_method` (string, nullable)
- `transaction_time` (timestamp, nullable)
- `settlement_time` (timestamp, nullable)
- `gross_amount` (decimal:2)
- `currency` (string, default: 'IDR')
- `fraud_status` (string, nullable)
- `customer_name` (string, nullable)
- `customer_email` (string, nullable)
- `customer_phone` (string, nullable)
- `customer_address` (text, nullable)
- `midtrans_response` (jsonb, nullable)
- `notification_received` (boolean, default: false)
- `notification_count` (integer, default: 0)
- `last_notification_at` (timestamp, nullable)
- `created_at` (timestamp)
- `updated_at` (timestamp)

**Relationships:**
- `user()` - Belongs to User
- `package()` - Belongs to Package
- `userPackages()` - Has many UserPackage
- `paymentLogs()` - Has many PaymentLog

**Scopes:**
- `settled()` - Only settled transactions
- `pending()` - Only pending transactions

**Usage:**
```php
use App\Models\Transaction;

// Get settled transactions
$settled = Transaction::settled()->get();

// Get transaction with user and package
$transaction = Transaction::with(['user', 'package'])->find(1);
```

### 4. UserPackage Model (`app/Models/UserPackage.php`)

**Table:** `user_packages`

**Fields:**
- `id` (integer, primary key)
- `user_id` (integer, foreign key to users)
- `package_id` (integer, foreign key to packages, nullable)
- `transaction_id` (integer, foreign key to transactions, nullable)
- `start_date` (timestamp)
- `end_date` (timestamp)
- `is_active` (boolean, default: true)
- `auto_renew` (boolean, default: false)
- `created_at` (timestamp)
- `updated_at` (timestamp)

**Relationships:**
- `user()` - Belongs to User
- `package()` - Belongs to Package
- `transaction()` - Belongs to Transaction

**Scopes:**
- `active()` - Only active packages
- `valid()` - Only valid (not expired) packages

**Methods:**
- `isValid()` - Check if package is still valid (not expired)

**Usage:**
```php
use App\Models\UserPackage;

// Get valid user packages
$validPackages = UserPackage::valid()->get();

// Check if package is valid
if ($userPackage->isValid()) {
    // Package is still active
}
```

### 5. PaymentLog Model (`app/Models/PaymentLog.php`)

**Table:** `payment_logs`

**Fields:**
- `id` (integer, primary key)
- `transaction_id` (integer, foreign key to transactions, nullable)
- `order_id` (string, nullable)
- `action` (string)
- `status` (string, nullable)
- `message` (text, nullable)
- `request_data` (jsonb, nullable)
- `response_data` (jsonb, nullable)
- `ip_address` (string, nullable)
- `user_agent` (text, nullable)
- `created_at` (timestamp)

**Relationships:**
- `transaction()` - Belongs to Transaction

**Scopes:**
- `byOrderId($orderId)` - Filter by order_id
- `byAction($action)` - Filter by action

**Usage:**
```php
use App\Models\PaymentLog;

// Get logs by order_id
$logs = PaymentLog::byOrderId('ORDER-123')->get();

// Get logs by action
$createLogs = PaymentLog::byAction('create')->get();
```

## Database Views

Database juga memiliki views yang dapat diakses langsung:

### 1. `v_active_premium_users`
View untuk melihat user yang memiliki paket premium aktif.

### 2. `v_transaction_summary`
View untuk melihat ringkasan transaksi per hari dan status.

## Database Functions & Triggers

### Functions:
- `create_user_package_on_settlement()` - Otomatis membuat user_package ketika transaction status menjadi 'settlement'
- `update_updated_at_column()` - Otomatis update `updated_at` column

### Triggers:
- `trigger_create_user_package` - Trigger untuk create user package on settlement
- `update_packages_updated_at` - Auto update packages.updated_at
- `update_transactions_updated_at` - Auto update transactions.updated_at
- `update_user_packages_updated_at` - Auto update user_packages.updated_at
- `update_users_updated_at` - Auto update users.updated_at

## Authentication

User model sudah terintegrasi dengan Laravel Authentication. Login menggunakan `email` dan `password`.

**Note:** Database tidak memiliki kolom `remember_token`, jadi fitur "Remember Me" tidak akan menyimpan token di database, tetapi session akan tetap berfungsi.

## Example Usage

```php
use App\Models\User;
use App\Models\Package;
use App\Models\Transaction;
use App\Models\UserPackage;

// Get user with active package
$user = User::with('activePackage.package')->find(1);

// Check if user has premium
if ($user->hasActivePackage()) {
    $package = $user->activePackage->package;
    echo "User has {$package->name} package";
}

// Get all active packages
$packages = Package::active()->get();

// Get user transactions
$transactions = Transaction::where('user_id', $user->id)
    ->settled()
    ->with('package')
    ->get();

// Get valid user packages
$validPackages = UserPackage::valid()
    ->with(['user', 'package'])
    ->get();
```

## Migration Notes

**Important:** Database sudah ada dan sudah memiliki data. Jangan jalankan `php artisan migrate` karena akan mencoba membuat tabel yang sudah ada.

Jika perlu membuat migration files untuk dokumentasi, gunakan:
```bash
php artisan make:migration create_packages_table --create=packages
```

Tetapi jangan jalankan `php artisan migrate` karena database sudah ada.

