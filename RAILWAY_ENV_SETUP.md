# Setup Environment Variables di Railway

## 📋 Environment Variables yang Perlu Ditambahkan

Buka **Railway Dashboard** → **App Service** → Tab **"Variables"**, lalu tambahkan:

### Database Configuration
```
DB_CONNECTION=mysql
DB_HOST=interchange.proxy.rlwy.net
DB_PORT=21355
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=dRnoMwEJjgKoaKOIeOdLFljYriEByjTs
```

**Test Connection:**
```bash
mysql -h interchange.proxy.rlwy.net -u root -p --port 21355 --protocol=TCP railway
# Password: dRnoMwEJjgKoaKOIeOdLFljYriEByjTs
```

### Application Configuration (jika belum ada)
```
APP_NAME="SMKN 4 Website"
APP_ENV=production
APP_KEY=base64:... (generate dengan: php artisan key:generate --show)
APP_DEBUG=false
APP_URL=https://your-app.railway.app
```

## 🚀 Langkah Setup

### 1. Tambahkan Variables
1. Buka Railway Dashboard
2. Pilih **App service** (bukan database service)
3. Klik tab **"Variables"**
4. Klik **"+ New Variable"** untuk setiap variable di atas
5. Copy-paste name dan value

### 2. Restart Service
Setelah semua variables ditambahkan:
1. Klik **"Settings"** di App service
2. Klik **"Restart"** untuk apply perubahan

### 3. Test Connection
Setelah restart, test koneksi database:
- Akses: `https://your-app.railway.app/test-db`
- Harus menampilkan: `"status": "success"`

### 4. Run Migration & Seeder
Setelah database connected:
```bash
railway run php artisan migrate
railway run php artisan db:seed
```

## ✅ Checklist

- [ ] Semua DB_* variables sudah ditambahkan
- [ ] APP_KEY sudah di-set (atau generate baru)
- [ ] APP_URL sudah di-set ke domain Railway
- [ ] Service sudah di-restart
- [ ] Test connection berhasil (`/test-db`)
- [ ] Migration sudah dijalankan
- [ ] Seeder sudah dijalankan

## 🔍 Troubleshooting

### Database masih "Connection refused"
1. Cek semua variables sudah benar (no typo)
2. Cek Database service status = ON
3. Restart App service lagi
4. Cek log di Railway untuk error details

### APP_KEY error
Generate APP_KEY baru:
```bash
railway run php artisan key:generate --show
```
Copy output dan set sebagai `APP_KEY` variable di Railway.

