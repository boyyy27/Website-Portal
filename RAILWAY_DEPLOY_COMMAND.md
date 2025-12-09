# 🔧 Railway Deploy Command - Fix

## Error yang Terjadi

```
/bin/bash: -c: line 1: unexpected EOF while looking for matching `"'
```

Ini terjadi karena Deploy Command memiliki quote yang tidak tertutup atau escaping yang salah.

## ✅ Solusi: Deploy Command yang Benar

### Untuk Railway Dashboard

1. Railway Dashboard → Service **"omile-portal"** → Tab **"Settings"**
2. Scroll ke **"Deploy Command"**
3. **Hapus semua isi** yang ada
4. **Copy dan paste command ini** (satu baris, tanpa line break):

```
php artisan config:clear && php artisan cache:clear && php artisan view:clear && php artisan route:clear && php artisan config:cache && php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT
```

5. Klik **"Save"**
6. Tab **"Deployments"** → **"Redeploy"** → **"Deploy latest commit"**

---

## 📋 Deploy Command Breakdown

Command di atas melakukan:
1. `php artisan config:clear` - Clear config cache
2. `php artisan cache:clear` - Clear application cache
3. `php artisan view:clear` - Clear compiled views
4. `php artisan route:clear` - Clear route cache
5. `php artisan config:cache` - Cache config (untuk performance)
6. `php artisan migrate --force` - Run database migrations
7. `php artisan serve --host=0.0.0.0 --port=$PORT` - Start web server

---

## 🔄 Setelah Migrations Berhasil

Setelah semua migrations berhasil dan website sudah running, Anda bisa sederhanakan Deploy Command menjadi:

```
php artisan config:cache && php artisan serve --host=0.0.0.0 --port=$PORT
```

Atau bahkan cukup:

```
php artisan serve --host=0.0.0.0 --port=$PORT
```

(Tapi tetap disarankan keep config:cache untuk performance)

---

## ⚠️ Catatan Penting

1. **Jangan ada line break** di Deploy Command - harus satu baris
2. **Jangan ada quote tambahan** - hanya paste command seperti di atas
3. **Gunakan && untuk chain commands** - ini akan execute secara berurutan
4. **$PORT adalah variable Railway** - jangan diganti dengan angka

---

## 🔍 Troubleshooting

### Masih Error?

1. **Hapus semua isi Deploy Command**
2. **Tulis ulang dari awal** - jangan copy-paste dengan formatting
3. **Pastikan tidak ada quote** di awal atau akhir
4. **Pastikan satu baris** - tidak ada enter/newline

### Migrations Error?

Jika migrations error dan ingin skip migrations di deploy:

```
php artisan config:cache && php artisan serve --host=0.0.0.0 --port=$PORT
```

Jalankan migrations manual via Railway CLI setelah deploy berhasil.

---

**Setelah fix Deploy Command, deploy seharusnya berhasil!** 🚀

