# Credentials untuk Login

## Admin Login
**URL:** `/admin/login`

**Username:** `admin`  
**Password:** `admin123`

## User Login
**URL:** `/user/login`

### User 1
**Email:** `user@test.com`  
**Password:** `user123`  
**NISN:** `1234567890`

### User 2
**Email:** `salwa@smkn4.com`  
**Password:** `salwa123`  
**NISN:** `0987654321`

---

## Cara Menjalankan Seeder

Untuk membuat atau update credentials di database, jalankan:

```bash
php artisan db:seed --class=AdminSeeder
php artisan db:seed --class=UserSeeder
```

Atau jalankan semua seeder:

```bash
php artisan db:seed
```

---

## Catatan Keamanan

⚠️ **PENTING:** Ganti password default setelah login pertama kali untuk keamanan!

Untuk admin, gunakan fitur "Ganti Password" di dashboard setelah login.

