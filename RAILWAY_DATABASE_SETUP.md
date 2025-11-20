# Setup Database di Railway

## 🔥 Error: SQLSTATE[HY000] [2002] Connection refused

Error ini berarti Laravel **tidak bisa connect ke database**. Bukan masalah query atau kode, tapi **database tidak bisa dijangkau**.

## ✅ Langkah-langkah Setup Database di Railway

### 1. Tambah Database Service di Railway

1. Buka **Railway Dashboard** → Project kamu
2. Klik **"+ New"** → Pilih **"Database"** → Pilih **"MySQL"**
3. Railway akan otomatis membuat database service

### 2. Connect Database ke App Service

1. Di **Database service** → Klik tab **"Connect"**
2. Railway akan otomatis inject environment variables ke **App service**
3. Pastikan App service dan Database service **dalam 1 project yang sama**

### 3. Cek Environment Variables

Buka **App service** → Tab **"Variables"**

Pastikan ada variables berikut (Railway biasanya inject otomatis):

```
DB_CONNECTION=mysql
DB_HOST=containers-us-west-xx.railway.app
DB_PORT=xxxx
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=xxxx
```

**Atau** Railway menggunakan `DATABASE_URL`:

```
DATABASE_URL=mysql://root:password@containers-us-west-xx.railway.app:xxxx/railway
```

### 4. Jika Variables Kosong

Jika variables tidak ada, tambahkan manual:

1. Buka **App service** → **Variables**
2. Klik **"+ New Variable"**
3. Tambahkan satu per satu:
   - `DB_CONNECTION` = `mysql`
   - `DB_HOST` = (ambil dari Database service → Connect)
   - `DB_PORT` = (ambil dari Database service → Connect)
   - `DB_DATABASE` = `railway` (atau nama database yang ditampilkan)
   - `DB_USERNAME` = `root` (atau username yang ditampilkan)
   - `DB_PASSWORD` = (ambil dari Database service → Connect)

### 5. Run Migration & Seeder

Setelah database connected, jalankan migration:

**Via Railway CLI:**
```bash
railway run php artisan migrate
railway run php artisan db:seed
```

**Atau tambahkan ke build script** (tidak disarankan untuk production):
```bash
# Di build.sh, tambahkan setelah composer install
php artisan migrate --force
php artisan db:seed --force
```

## 🔍 Troubleshooting

### Database Service Mati/Sleep

**Cek:**
- Railway Dashboard → Database service → Status harus **"ON"**
- Jika **"OFF"** atau **"Sleep"** → Klik untuk hidupkan

**Solusi:**
- Railway free plan kadang sleep setelah idle
- Upgrade ke paid plan untuk prevent sleep
- Atau restart service secara berkala

### Environment Variables Salah

**Cek:**
1. Buka App service → Variables
2. Pastikan semua DB_* variables ada
3. Bandingkan dengan Database service → Connect

**Solusi:**
- Copy-paste exact values dari Database service → Connect
- Jangan ada typo atau spasi extra

### Database Belum Dibuat

**Cek:**
- Buka Database service → Tab "Query"
- Coba query: `SHOW DATABASES;`

**Solusi:**
- Jalankan migration: `php artisan migrate`
- Atau buat database manual via Railway Query tab

### Port Diblokir / Network Issue

**Cek:**
- App service dan Database service harus dalam **1 project Railway**
- Jangan connect dari luar Railway

**Solusi:**
- Pastikan semua service dalam 1 project
- Railway internal network akan handle connection

## 🧪 Test Database Connection

Buat file test (temporary):

```php
// routes/web.php (temporary test route)
Route::get('/test-db', function() {
    try {
        DB::connection()->getPdo();
        return "Database connected!";
    } catch (\Exception $e) {
        return "Database connection failed: " . $e->getMessage();
    }
});
```

Akses: `https://your-app.railway.app/test-db`

**Hapus route ini setelah testing!**

## 📝 Checklist

- [ ] Database service sudah dibuat di Railway
- [ ] Database service status = ON (tidak sleep)
- [ ] App service dan Database service dalam 1 project
- [ ] Environment variables sudah di-set (DB_HOST, DB_PORT, dll)
- [ ] Migration sudah dijalankan (`php artisan migrate`)
- [ ] Seeder sudah dijalankan (`php artisan db:seed`)
- [ ] Test connection berhasil

## 🚀 Quick Fix

Jika masih error setelah setup:

1. **Restart App service** di Railway
2. **Restart Database service** di Railway
3. **Clear config cache:**
   ```bash
   railway run php artisan config:clear
   railway run php artisan cache:clear
   ```
4. **Test connection** via test route di atas

---

**Catatan:** Railway free plan kadang sleep database setelah idle. Untuk production, pertimbangkan upgrade ke paid plan atau setup database eksternal (PlanetScale, Supabase, dll).

