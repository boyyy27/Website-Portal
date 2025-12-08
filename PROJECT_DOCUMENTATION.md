# Dokumentasi Project OMILE - Transport Management System Portal

## 📋 Ringkasan Project

**Nama Project:** OMILE - Transport Management System Portal  
**Deskripsi:** Portal pembayaran dan manajemen paket untuk sistem Transport Management System (TMS)  
**Tipe:** Web Application (Portal Payment & Subscription Management)  
**Status:** Development/Production Ready

---

## 🛠️ Teknologi yang Digunakan

### Backend Framework
- **Laravel 8.75** - PHP Web Framework
  - MVC Architecture
  - Eloquent ORM
  - Blade Templating Engine
  - Route Model Binding
  - Middleware Authentication

### Frontend Technologies
- **HTML5** - Markup Language
- **CSS3** - Styling
  - Custom CSS (Terpusat: `auth.css`, `dashboard.css`, `payment.css`)
  - Bootstrap 5.3.0 (CDN)
  - Custom Landing Page CSS
- **JavaScript (Vanilla)** - Client-side scripting
  - jQuery 3.6.0 (untuk landing page)
  - Custom JavaScript untuk sidebar, features management, countdown timer

### Database
- **PostgreSQL** - Relational Database Management System
  - JSONB support untuk features package
  - Transaction support
  - Foreign key constraints

### PHP Version
- **PHP 7.3+ atau 8.0+** (Compatible dengan Laravel 8)

---

## 📦 Dependencies & Packages

### Composer Packages (PHP)

#### Core Laravel Packages
- `laravel/framework: ^8.75` - Laravel Framework
- `laravel/sanctum: ^2.11` - API Authentication
- `laravel/tinker: ^2.5` - REPL untuk Laravel

#### Third-Party Packages
- **`midtrans/midtrans-php: ^2.6`** - Payment Gateway Integration
  - Snap API untuk payment processing
  - Notification handler untuk status update
  - Sandbox & Production mode support

#### Development Packages
- `facade/ignition: ^2.5` - Error page handler
- `fakerphp/faker: ^1.9.1` - Fake data generator
- `phpunit/phpunit: ^9.5.10` - Unit testing
- `mockery/mockery: ^1.4.4` - Mocking framework

#### Other Packages
- `fruitcake/laravel-cors: ^2.0` - CORS middleware
- `guzzlehttp/guzzle: ^7.0.1` - HTTP client

### NPM Packages (JavaScript)

#### Build Tools
- `laravel-mix: ^6.0.6` - Asset compilation
- `axios: ^0.21` - HTTP client
- `lodash: ^4.17.19` - Utility library
- `postcss: ^8.1.14` - CSS processing

---

## 🎨 Frontend Libraries & Plugins

### CSS Frameworks & Libraries
1. **Bootstrap 5.3.0** (CDN)
   - Grid system
   - Components (cards, buttons, forms, modals)
   - Utilities classes
   - Responsive design

2. **Material Design Icons (MDI) 7.2.96** (CDN)
   - Icon library untuk dashboard dan UI
   - 7000+ icons
   - Font-based icons

3. **Feather Icons** (Landing Page)
   - Lightweight icon set
   - SVG-based icons
   - Customizable

### JavaScript Libraries
1. **jQuery 3.6.0** (Landing Page)
   - DOM manipulation
   - Event handling
   - AJAX requests

2. **Owl Carousel 2.3.4** (Landing Page)
   - Image/content carousel
   - Responsive slider
   - Touch support

3. **Midtrans Snap.js** (Payment)
   - Payment widget
   - Multiple payment methods
   - Real-time payment processing

### Google Fonts
- **Open Sans** - Primary font family
  - Weights: 300, 400, 500, 600, 700
  - Used across all pages

---

## 🎯 Fitur-Fitur Utama

### 1. Authentication & Authorization
- **Login/Register System**
  - Email & password authentication
  - Role-based access (Admin/User)
  - Session management
  - Remember me functionality

- **Email Verification**
  - Verification code system
  - Email sending via Laravel Mail
  - Resend verification code
  - Protected routes

### 2. Landing Page
- **Hero Section**
  - Dynamic content
  - Call-to-action buttons
  - Responsive design

- **Features Section**
  - Service showcase
  - Icon-based presentation
  - Smooth animations

- **Pricing Section**
  - Dynamic package display dari database
  - Feature comparison
  - Smooth hover effects
  - Popular badge
  - Get Started buttons

- **Footer**
  - Company information
  - Contact details
  - Social links

### 3. Admin Dashboard
- **Statistics Overview**
  - Total transactions
  - Total revenue
  - Active packages
  - Recent transactions

- **Package Management (CRUD)**
  - Create new packages
  - Edit existing packages
  - Delete packages (with validation)
  - Dynamic features management
  - JSONB features storage
  - Active/Inactive toggle

- **Transaction Management**
  - View all transactions
  - Transaction details
  - Status monitoring
  - Invoice generation
  - Manual status check

- **Sidebar Navigation**
  - Collapsible sidebar
  - Logo integration
  - Mobile responsive
  - Tooltip support

### 4. User Dashboard
- **Active Subscription Display**
  - Package information
  - Subscription duration
  - Countdown timer
  - Status badge

- **Subscription History**
  - Past subscriptions
  - Transaction history
  - Status tracking

- **Invoice Management**
  - Download invoices
  - Invoice list
  - PDF generation

- **TMS Access**
  - Credentials display
  - Auto-redirect functionality
  - External system integration

### 5. Payment System (Midtrans Integration)
- **Checkout Process**
  - Package selection
  - Customer information form
  - Data validation

- **Payment Processing**
  - Midtrans Snap integration
  - Multiple payment methods
  - Real-time status update
  - Notification handler

- **Transaction Status**
  - Pending
  - Settlement
  - Cancel
  - Expire

- **Payment Callbacks**
  - Success handler
  - Pending handler
  - Error handler
  - Notification webhook

### 6. Package System
- **Package Features**
  - Dynamic feature list
  - Included/Excluded toggle
  - JSONB storage
  - Feature management UI

- **Package Pricing**
  - Flexible pricing
  - Duration-based (days)
  - Custom pricing support

### 7. Invoice System
- **Invoice Generation**
  - PDF download
  - Transaction details
  - Company information
  - Print-friendly design

### 8. UI/UX Features
- **Responsive Design**
  - Mobile-first approach
  - Tablet optimization
  - Desktop enhancement

- **Smooth Animations**
  - CSS transitions
  - Hover effects
  - Loading states
  - Page transitions

- **Color Palette**
  - Primary Blue: #2f55d4
  - Primary Orange: #f58905
  - Consistent across all pages

- **Typography**
  - Open Sans font family
  - Consistent sizing
  - Readable hierarchy

---

## 📁 Struktur Project

### Directory Structure
```
skripsi/
├── app/
│   ├── Console/
│   ├── Exceptions/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── LandingController.php
│   │   │   ├── PackageController.php
│   │   │   ├── PaymentController.php
│   │   │   ├── TmsController.php
│   │   │   └── VerificationController.php
│   │   └── Middleware/
│   ├── Mail/
│   ├── Models/
│   │   ├── Package.php
│   │   ├── PaymentLog.php
│   │   ├── Transaction.php
│   │   ├── User.php
│   │   └── UserPackage.php
│   └── Providers/
├── config/
│   ├── app.php
│   ├── database.php
│   ├── services.php (Midtrans config)
│   └── ...
├── database/
│   ├── migrations/
│   └── seeders/
├── public/
│   ├── assets/
│   │   ├── images/
│   │   └── landing/
│   │       ├── css/
│   │       └── js/
│   ├── css/
│   │   ├── auth.css
│   │   ├── dashboard.css
│   │   └── payment.css
│   └── js/
│       └── sidebar.js
├── resources/
│   ├── views/
│   │   ├── auth/
│   │   ├── dashboard/
│   │   ├── emails/
│   │   ├── payment/
│   │   └── landing.blade.php
│   └── ...
└── routes/
    └── web.php
```

---

## 🗄️ Database Schema

### Tables

#### 1. `users`
- `id` (Primary Key)
- `name`
- `email` (Unique)
- `email_verified_at`
- `password`
- `role` (admin/user)
- `remember_token`
- `created_at`, `updated_at`

#### 2. `packages`
- `id` (Primary Key)
- `name`
- `description`
- `price` (Decimal)
- `duration_days` (Integer)
- `is_active` (Boolean)
- `features` (JSONB) - Array of features
- `created_at`, `updated_at`

#### 3. `transactions`
- `id` (Primary Key)
- `user_id` (Foreign Key)
- `package_id` (Foreign Key)
- `order_id` (Unique)
- `transaction_status`
- `gross_amount`
- `payment_type`
- `transaction_time`
- `fraud_status`
- `customer_name`
- `customer_email`
- `customer_phone`
- `customer_address`
- `package_name`
- `created_at`, `updated_at`

#### 4. `user_packages`
- `id` (Primary Key)
- `user_id` (Foreign Key)
- `package_id` (Foreign Key)
- `transaction_id` (Foreign Key)
- `start_date`
- `end_date`
- `is_active` (Boolean)
- `created_at`, `updated_at`

#### 5. `payment_logs`
- `id` (Primary Key)
- `transaction_id` (Foreign Key)
- `status`
- `message`
- `raw_data` (JSON)
- `created_at`, `updated_at`

---

## 🔌 Integrations

### 1. Midtrans Payment Gateway
- **Service:** Payment Processing
- **Integration Type:** Snap API
- **Features:**
  - Credit Card
  - Bank Transfer
  - E-Wallet
  - Virtual Account
  - Notification Webhook
  - Status Checking

### 2. External TMS System
- **Service:** Transport Management System
- **Integration Type:** Redirect with Credentials
- **Features:**
  - Auto-login attempt
  - Credentials display
  - Access control (active subscription required)

### 3. Email Service
- **Service:** Laravel Mail
- **Features:**
  - Verification code emails
  - Transaction notifications
  - Custom email templates

---

## 🎨 Design & Styling

### Color Palette
- **Primary Blue:** `#2f55d4`
- **Primary Orange:** `#f58905`
- **Dark Gray:** `#2d3748`
- **Gray:** `#4a5568`, `#718096`
- **Background:** `#f8f9fa`
- **Success:** `#10b981`
- **Danger:** `#ef4444`

### Typography
- **Font Family:** 'Open Sans', sans-serif
- **Font Weights:** 300, 400, 500, 600, 700
- **Font Sizes:** Responsive (0.875rem - 2.5rem)

### Components Styling
- **Cards:** Rounded corners (8px, 12px, 16px)
- **Buttons:** Gradient backgrounds, hover effects
- **Forms:** Focus states, validation styles
- **Sidebar:** Collapsible, tooltip support
- **Tables:** Responsive, hover effects

---

## 🔐 Security Features

1. **CSRF Protection**
   - Laravel CSRF tokens
   - Excluded routes for webhooks

2. **Authentication**
   - Password hashing (bcrypt)
   - Session management
   - Remember me tokens

3. **Authorization**
   - Role-based access control
   - Middleware protection
   - Route guards

4. **Input Validation**
   - Form validation
   - SQL injection prevention (Eloquent ORM)
   - XSS protection (Blade escaping)

5. **Payment Security**
   - Server-side validation
   - Secure API communication
   - Transaction logging

---

## 📱 Responsive Design

### Breakpoints
- **Mobile:** < 576px
- **Tablet:** 576px - 768px
- **Desktop:** > 768px

### Responsive Features
- Collapsible sidebar (mobile)
- Responsive tables
- Flexible grid system
- Touch-friendly buttons
- Mobile-optimized forms

---

## 🚀 Deployment Considerations

### Environment Variables
- `APP_ENV` - Environment (local/production)
- `APP_DEBUG` - Debug mode
- `DB_CONNECTION` - Database driver
- `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
- `MIDTRANS_CLIENT_KEY` - Midtrans client key
- `MIDTRANS_SERVER_KEY` - Midtrans server key
- `MIDTRANS_IS_PRODUCTION` - Production mode flag
- `MAIL_*` - Email configuration

### Server Requirements
- PHP 7.3+ atau 8.0+
- PostgreSQL 10+
- Composer
- Node.js & NPM (untuk asset compilation)
- Web server (Apache/Nginx)

---

## 📝 File Documentation

### CSS Files
- `public/css/auth.css` - Authentication pages styling
- `public/css/dashboard.css` - Dashboard pages styling
- `public/css/payment.css` - Payment pages styling
- `public/assets/landing/css/style.css` - Landing page base styles
- `public/assets/landing/css/custom.css` - Landing page custom styles

### JavaScript Files
- `public/js/sidebar.js` - Sidebar toggle functionality
- Custom scripts in Blade templates

### Documentation Files
- `ASSETS_INSTRUCTIONS.md` - Assets setup guide
- `CDN_SETUP.md` - CDN configuration
- `DATABASE_MODELS.md` - Database models documentation
- `DATABASE_SETUP.md` - Database setup guide
- `EMAIL_SETUP.md` - Email configuration
- `MIDTRANS_SETUP.md` - Midtrans integration guide
- `PAYMENT_TROUBLESHOOTING.md` - Payment troubleshooting

---

## 🔄 Workflow & Features Flow

### User Registration Flow
1. User fills registration form
2. Email verification code sent
3. User verifies email
4. Account activated
5. Redirect to dashboard

### Package Purchase Flow
1. User browses packages on landing page
2. Clicks "Get Started" or "Beli Sekarang"
3. Redirected to checkout (if authenticated) or login
4. Fills customer information
5. Creates transaction
6. Redirected to Midtrans payment page
7. Completes payment
8. Notification received
9. Subscription activated
10. User can access TMS

### Admin Package Management Flow
1. Admin logs in
2. Navigates to Package Management
3. Creates/Edits/Deletes packages
4. Manages features dynamically
5. Activates/Deactivates packages

---

## 🎯 Key Features Summary

✅ **Authentication & Authorization**
✅ **Role-based Dashboard (Admin/User)**
✅ **Package Management (CRUD)**
✅ **Dynamic Features Management**
✅ **Payment Gateway Integration (Midtrans)**
✅ **Transaction Management**
✅ **Invoice Generation**
✅ **Email Verification**
✅ **Subscription Management**
✅ **TMS Access Control**
✅ **Responsive Design**
✅ **Smooth Animations**
✅ **Modern UI/UX**

---

## 📞 Support & Contact

**Project:** OMILE - Transport Management System Portal  
**Company:** PT ODISYS INDONESIA  
**Email:** marketing@odisys.id

---

*Dokumentasi ini dibuat untuk memberikan overview lengkap tentang struktur, teknologi, dan fitur-fitur yang ada dalam project OMILE.*

