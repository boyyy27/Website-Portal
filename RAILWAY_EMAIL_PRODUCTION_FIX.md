# 🔧 Fix Email di Production - Railway

## Masalah

Email terkirim di local tapi error 500 di Railway/deployment. Email verification harus tetap bekerja di production.

## ✅ Solusi yang Sudah Diterapkan

### 1. Email Queue Support

Register controller sudah di-update dengan:
- **Auto-detect queue connection** - Jika queue tersedia, gunakan queue (async)
- **Fallback ke sync** - Jika queue tidak tersedia, kirim langsung dengan timeout protection
- **Email selalu dikirim** - Tidak skip di production
- **Error handling** - Registration tetap berhasil meskipun email gagal (tapi email tetap dicoba)

### 2. Email Timeout Configuration

Set email timeout di `config/mail.php`:
- `MAIL_TIMEOUT=25` (25 seconds) - Cukup untuk SMTP connection

---

## 🎯 Setup Email di Railway

### Step 1: Set Environment Variables

Railway Dashboard → Service **"omile-portal"** → Tab **"Variables"**

**Pastikan semua variable ini sudah di-set:**

```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="OMILE Portal"
MAIL_TIMEOUT=25
```

### Step 2: Gmail Setup

1. **Enable 2-Step Verification** di Google Account
2. **Generate App Password**:
   - Google Account → Security → 2-Step Verification → App passwords
   - Generate password untuk "Mail"
   - Gunakan password ini sebagai `MAIL_PASSWORD` (bukan password Gmail biasa!)

### Step 3: Setup Queue (Recommended untuk Production)

Untuk mencegah timeout, gunakan queue:

**Option A: Database Queue (Paling Mudah)**

1. Railway Dashboard → Service **"omile-portal"** → Tab **"Variables"**
2. Tambahkan:
   ```
   QUEUE_CONNECTION=database
   ```

3. Run migration untuk queue:
   ```bash
   railway run php artisan queue:table
   railway run php artisan migrate --force
   ```

4. Start queue worker (tambahkan ke Deploy Command atau run manual):
   ```bash
   php artisan queue:work --tries=3 --timeout=60
   ```

**Option B: Sync (Default - Tidak Recommended untuk Production)**

Jika tidak setup queue, email akan dikirim synchronously dengan timeout protection.

---

## 🔍 Verify Email Configuration

### Cek Email Config di Railway

```bash
railway run php artisan tinker
```

```php
// Cek mail config
echo "Mail Driver: " . config('mail.default') . "\n";
echo "Mail Host: " . config('mail.mailers.smtp.host') . "\n";
echo "Mail Port: " . config('mail.mailers.smtp.port') . "\n";
echo "Mail Username: " . config('mail.mailers.smtp.username') . "\n";
echo "Mail Timeout: " . config('mail.mailers.smtp.timeout') . "\n";
echo "Queue Connection: " . config('queue.default') . "\n";
```

### Test Email Sending

```bash
railway run php artisan tinker
```

```php
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationCodeMail;

try {
    Mail::to('test@example.com')->send(new VerificationCodeMail('123456', 'Test User'));
    echo "Email sent successfully!\n";
} catch (\Exception $e) {
    echo "Email failed: " . $e->getMessage() . "\n";
}
```

---

## 🚀 Deploy Command dengan Queue Worker

Jika menggunakan queue, update Deploy Command:

**Option 1: Queue Worker di Background (Recommended)**

Deploy Command:
```
php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT
```

Start queue worker secara terpisah (via Railway CLI atau separate service):
```bash
railway run php artisan queue:work --tries=3 --timeout=60
```

**Option 2: Queue Worker di Deploy Command (Not Recommended)**

Deploy Command:
```
php artisan migrate --force && php artisan queue:work --tries=3 --timeout=60 & php artisan serve --host=0.0.0.0 --port=$PORT
```

⚠️ **Note**: Ini tidak ideal karena queue worker akan berjalan di background dan mungkin tidak reliable.

**Option 3: Sync (No Queue) - Current Setup**

Deploy Command:
```
php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT
```

Email akan dikirim synchronously dengan timeout protection (25 seconds).

---

## 📋 Checklist

- [ ] Semua email environment variables sudah di-set di Railway
- [ ] Gmail App Password sudah di-generate dan di-set sebagai `MAIL_PASSWORD`
- [ ] `MAIL_TIMEOUT=25` sudah di-set
- [ ] Test email sending via tinker
- [ ] Test register - email harus terkirim
- [ ] Cek logs untuk verify email sent successfully

---

## 🔍 Troubleshooting

### Email Masih Timeout

**Solusi**:
1. Setup queue (recommended)
2. Atau increase timeout:
   ```
   MAIL_TIMEOUT=30
   ```
3. Atau gunakan email service yang lebih cepat (SendGrid, Mailgun, dll)

### Email Tidak Terkirim

**Cek**:
1. SMTP credentials benar
2. Gmail App Password benar (bukan password biasa)
3. Port dan encryption benar (587, tls)
4. Cek Railway logs untuk error message

### Queue Worker Tidak Running

**Solusi**:
1. Start queue worker:
   ```bash
   railway run php artisan queue:work --tries=3 --timeout=60
   ```
2. Atau gunakan sync connection (tidak recommended untuk production)

---

## 💡 Tips

1. **Gunakan Queue untuk Production** - Mencegah timeout dan lebih reliable
2. **Monitor Queue Jobs** - Cek `jobs` table untuk pending jobs
3. **Setup Failed Jobs Table** - Untuk track failed email jobs
4. **Use Email Service** - Consider SendGrid, Mailgun, atau SES untuk lebih reliable

---

**Setelah setup email config dan queue, email seharusnya terkirim di production!** 🚀

