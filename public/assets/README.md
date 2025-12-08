# Assets Directory Structure

## Landing Page Assets

Struktur direktori untuk landing page OMILE:

```
public/assets/
├── landing/
│   ├── css/
│   │   ├── bootstrap.min.css
│   │   ├── materialdesignicons.min.css
│   │   ├── owl.carousel.min.css
│   │   ├── owl.theme.default.min.css
│   │   └── style.css
│   └── js/
│       ├── jquery.min.js
│       ├── bootstrap.bundle.min.js
│       ├── jquery.easing.min.js
│       ├── scrollspy.min.js
│       ├── feather.min.js
│       ├── owl.carousel.min.js
│       └── app.js
└── images/
    ├── favicon.ico
    ├── googleplay.png
    ├── jp-mobile.png
    └── odisys.png
```

## Cara Menambahkan Assets

1. Download atau copy file CSS dan JS dari template landing page
2. Letakkan file CSS di `public/assets/landing/css/`
3. Letakkan file JS di `public/assets/landing/js/`
4. Letakkan file gambar di `public/assets/images/`

## Catatan

- Pastikan semua file assets sudah diupload ke direktori yang sesuai
- File-file ini diperlukan agar landing page dapat ditampilkan dengan benar
- Jika menggunakan CDN, Anda bisa mengubah path di `resources/views/landing.blade.php`

