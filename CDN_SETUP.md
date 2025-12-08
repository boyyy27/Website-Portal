# CDN Setup untuk Landing Page OMILE

## Status
Landing page sekarang menggunakan CDN untuk library-library utama. File lokal tersedia sebagai fallback.

## Library yang Digunakan via CDN

### CSS Libraries
1. **Bootstrap 5.3.0**
   - CDN: `https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css`
   - Fallback: `public/assets/landing/css/bootstrap.min.css`

2. **Material Design Icons**
   - CDN: `https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css`
   - Fallback: `public/assets/landing/css/materialdesignicons.min.css`

3. **Owl Carousel CSS**
   - CDN: `https://cdn.jsdelivr.net/npm/owl.carousel@2.3.4/dist/assets/owl.carousel.min.css`
   - Fallback: `public/assets/landing/css/owl.carousel.min.css`

### JavaScript Libraries
1. **jQuery 3.6.0**
   - CDN: `https://code.jquery.com/jquery-3.6.0.min.js`
   - Fallback: `public/assets/landing/js/jquery.min.js`

2. **Bootstrap Bundle JS 5.3.0**
   - CDN: `https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js`
   - Fallback: `public/assets/landing/js/bootstrap.bundle.min.js`

3. **jQuery Easing**
   - CDN: `https://cdn.jsdelivr.net/npm/jquery.easing@1.4.1/jquery.easing.min.js`
   - Fallback: `public/assets/landing/js/jquery.easing.min.js`

4. **Feather Icons**
   - CDN: `https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js`
   - Fallback: `public/assets/landing/js/feather.min.js`

5. **Owl Carousel JS**
   - CDN: `https://cdn.jsdelivr.net/npm/owl.carousel@2.3.4/dist/owl.carousel.min.js`
   - Fallback: `public/assets/landing/js/owl.carousel.min.js`

## File Lokal yang Tersedia

Semua file CSS dan JS lokal sudah dibuat di:
- `public/assets/landing/css/` - untuk file CSS
- `public/assets/landing/js/` - untuk file JS

## Keuntungan Menggunakan CDN

1. **Faster Loading**: CDN biasanya lebih cepat karena cached di server terdekat
2. **Always Updated**: Versi terbaru dari library
3. **Reduced Server Load**: Tidak membebani server lokal
4. **Better Caching**: Browser cache CDN lebih efektif

## Jika Ingin Menggunakan File Lokal

Jika Anda ingin menggunakan file lokal (offline atau tanpa internet), edit file `resources/views/landing.blade.php`:

1. Comment/uncomment baris CDN dan fallback
2. Atau hapus baris CDN dan uncomment baris fallback

Contoh:
```html
<!-- Ganti dari: -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Menjadi: -->
<link rel="stylesheet" href="{{ asset('assets/landing/css/bootstrap.min.css') }}" type="text/css">
```

## Custom Files

File-file custom yang dibuat khusus untuk landing page:
- `public/assets/landing/css/style.css` - Base styles
- `public/assets/landing/css/custom.css` - Custom OMILE styles
- `public/assets/landing/js/app.js` - Custom JavaScript functionality

File-file ini selalu digunakan dari lokal dan tidak menggunakan CDN.

## Testing

Setelah setup, test landing page:
```bash
php artisan serve
```

Buka browser di `http://localhost:8000` dan check console untuk memastikan semua library ter-load dengan baik.

