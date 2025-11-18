# Panduan Optimasi Gambar

## Cara Mengoptimalkan Gambar

Untuk mengoptimalkan semua gambar di website, jalankan command berikut:

```bash
php artisan images:optimize
```

### Opsi Command

- `--quality=85` - Kualitas JPEG (1-100, default: 85)
- `--max-width=1920` - Lebar maksimal dalam pixel (default: 1920px)

### Contoh Penggunaan

```bash
# Optimasi dengan kualitas default (85%)
php artisan images:optimize

# Optimasi dengan kualitas lebih tinggi (90%)
php artisan images:optimize --quality=90

# Optimasi dengan ukuran maksimal lebih kecil (1200px)
php artisan images:optimize --max-width=1200

# Kombinasi
php artisan images:optimize --quality=80 --max-width=1600
```

## Apa yang Dilakukan Command Ini?

1. **Kompresi Gambar**: Mengurangi ukuran file tanpa mengurangi kualitas visual secara signifikan
2. **Resize Otomatis**: Mengurangi ukuran gambar yang terlalu besar (lebih dari max-width)
3. **Backup Otomatis**: Membuat file backup (.backup) sebelum mengoptimalkan
4. **Proses Semua Format**: Mendukung JPG, JPEG, PNG, dan GIF

## Catatan Penting

- Command ini akan memproses semua gambar di folder `public/images/` dan subfolder-nya
- File backup akan dibuat dengan ekstensi `.backup`
- Jika terjadi error, file asli tetap aman
- Untuk production, disarankan menjalankan command ini sebelum deploy

## Optimasi yang Sudah Diterapkan

✅ **Lazy Loading**: Semua gambar sudah menggunakan `loading="lazy"` untuk performa lebih baik
✅ **Width/Height Attributes**: Logo menggunakan atribut width/height untuk menghindari layout shift
✅ **Image Optimization Command**: Command untuk mengoptimalkan gambar tersedia

## Tips Performa

1. Jalankan `php artisan images:optimize` secara berkala untuk gambar baru
2. Gunakan format WebP jika memungkinkan (akan ditambahkan di update berikutnya)
3. Pastikan gambar tidak lebih dari 1920px untuk desktop
4. Gunakan lazy loading untuk gambar di bawah fold (sudah diterapkan)

