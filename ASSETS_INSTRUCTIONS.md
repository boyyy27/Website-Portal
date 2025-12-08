# Instruksi Setup Assets Landing Page

## Struktur Direktori

Landing page OMILE memerlukan file-file assets berikut yang harus ditempatkan di direktori yang sesuai:

### CSS Files
Letakkan di `public/assets/landing/css/`:
- `bootstrap.min.css` - Bootstrap framework
- `materialdesignicons.min.css` - Material Design Icons
- `owl.carousel.min.css` - Owl Carousel CSS
- `owl.theme.default.min.css` - Owl Carousel Theme
- `style.css` - Custom styles untuk landing page

### JavaScript Files
Letakkan di `public/assets/landing/js/`:
- `jquery.min.js` - jQuery library
- `bootstrap.bundle.min.js` - Bootstrap JS bundle
- `jquery.easing.min.js` - jQuery Easing plugin
- `scrollspy.min.js` - Scrollspy plugin
- `feather.min.js` - Feather icons
- `owl.carousel.min.js` - Owl Carousel JS
- `app.js` - Custom JavaScript untuk landing page

### Image Files
Letakkan di `public/assets/images/`:
- `favicon.ico` - Favicon website
- `googleplay.png` - Logo Google Play Store
- `jp-mobile.png` - Screenshot/gambar mobile app
- `odisys.png` - Logo PT ODISYS INDONESIA

## Cara Menambahkan Assets

### Opsi 1: Download dari Template Original
Jika Anda memiliki template landing page original:
1. Copy semua file CSS dari folder `css/` template ke `public/assets/landing/css/`
2. Copy semua file JS dari folder `js/` template ke `public/assets/landing/js/`
3. Copy semua file gambar ke `public/assets/images/`

### Opsi 2: Download dari CDN (Alternatif)
Jika Anda ingin menggunakan CDN untuk beberapa library, edit file `resources/views/landing.blade.php` dan ganti path asset dengan URL CDN.

Contoh untuk Bootstrap:
```html
<!-- Ganti dari: -->
<link rel="stylesheet" href="{{ asset('assets/landing/css/bootstrap.min.css') }}">

<!-- Menjadi: -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
```

## Testing

Setelah semua assets ditambahkan:
1. Pastikan server Laravel berjalan: `php artisan serve`
2. Buka browser dan akses: `http://localhost:8000`
3. Landing page seharusnya sudah tampil dengan styling yang benar

## Troubleshooting

### Assets tidak muncul
- Pastikan file sudah ada di direktori yang benar
- Clear cache browser (Ctrl+F5)
- Check console browser untuk error 404
- Pastikan path di `landing.blade.php` sesuai dengan struktur direktori

### Styling tidak sesuai
- Pastikan semua file CSS sudah diupload
- Check apakah `style.css` custom sudah ada
- Pastikan urutan loading CSS sudah benar

### JavaScript tidak berfungsi
- Pastikan semua file JS sudah diupload
- Check console browser untuk error JavaScript
- Pastikan jQuery dimuat sebelum library lain yang membutuhkannya

## Catatan Penting

- Jangan hapus direktori `.gitkeep` files jika menggunakan Git
- Pastikan file permission sudah benar (readable)
- Untuk production, pertimbangkan menggunakan CDN untuk library besar seperti Bootstrap dan jQuery

