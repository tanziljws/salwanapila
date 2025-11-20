# Import Database SQL ke Railway

## 📋 Informasi Database

**Host:** `interchange.proxy.rlwy.net`  
**Port:** `21355`  
**Username:** `root`  
**Password:** `dRnoMwEJjgKoaKOIeOdLFljYriEByjTs`  
**Database:** `railway`

## 🚀 Cara Import SQL File

### Metode 1: Via MySQL Client (Recommended)

1. **Install MySQL Client** (jika belum ada):
   ```bash
   # macOS
   brew install mysql-client
   
   # Linux
   sudo apt-get install mysql-client
   
   # Windows
   # Download MySQL Client dari mysql.com
   ```

2. **Import SQL file:**
   ```bash
   mysql -h interchange.proxy.rlwy.net -u root -p --port 21355 --protocol=TCP railway < "galeri-sekolahsal (1) (1).sql"
   ```
   
   Atau dengan password langsung:
   ```bash
   mysql -h interchange.proxy.rlwy.net -u root -p'dRnoMwEJjgKoaKOIeOdLFljYriEByjTs' --port 21355 --protocol=TCP railway < "galeri-sekolahsal (1) (1).sql"
   ```

### Metode 2: Via Railway CLI

1. **Install Railway CLI** (jika belum ada):
   ```bash
   npm i -g @railway/cli
   ```

2. **Login ke Railway:**
   ```bash
   railway login
   ```

3. **Connect ke database service:**
   ```bash
   railway connect
   ```

4. **Import SQL:**
   ```bash
   railway run mysql -h interchange.proxy.rlwy.net -u root -p --port 21355 railway < "galeri-sekolahsal (1) (1).sql"
   ```

### Metode 3: Via Railway Dashboard (Query Tab)

1. Buka Railway Dashboard
2. Pilih **Database service**
3. Klik tab **"Query"**
4. Copy-paste isi file SQL
5. Klik **"Run"**

**Note:** Metode ini mungkin tidak cocok untuk file SQL besar karena ada limit karakter.

### Metode 4: Via MySQL Workbench / phpMyAdmin

1. **Setup connection:**
   - Host: `interchange.proxy.rlwy.net`
   - Port: `21355`
   - Username: `root`
   - Password: `dRnoMwEJjgKoaKOIeOdLFljYriEByjTs`
   - Database: `railway`

2. **Import SQL file** via Import feature

## ⚠️ Catatan Penting

### Sebelum Import

1. **Backup database existing** (jika ada data penting):
   ```bash
   mysqldump -h interchange.proxy.rlwy.net -u root -p --port 21355 railway > backup.sql
   ```

2. **Hapus semua tabel existing** (jika perlu fresh start):
   ```bash
   mysql -h interchange.proxy.rlwy.net -u root -p --port 21355 railway -e "DROP DATABASE railway; CREATE DATABASE railway;"
   ```

### Setelah Import

1. **Test connection:**
   ```bash
   mysql -h interchange.proxy.rlwy.net -u root -p --port 21355 railway -e "SHOW TABLES;"
   ```

2. **Cek data:**
   ```bash
   mysql -h interchange.proxy.rlwy.net -u root -p --port 21355 railway -e "SELECT COUNT(*) FROM admins;"
   ```

3. **Update environment variables** di Railway App service (jika belum)

4. **Test aplikasi:**
   - Akses: `https://your-app.railway.app/test-db`
   - Harus menampilkan: `"status": "success"`

## 🔧 Troubleshooting

### Error: "Access denied"
- Pastikan password benar
- Pastikan username `root` benar
- Cek apakah IP diizinkan (Railway biasanya allow all)

### Error: "Connection refused"
- Cek Database service status = ON di Railway
- Cek host dan port sudah benar
- Cek firewall/network settings

### Error: "Table already exists"
- Hapus database dan buat ulang:
  ```bash
  mysql -h interchange.proxy.rlwy.net -u root -p --port 21355 -e "DROP DATABASE railway; CREATE DATABASE railway;"
  ```
- Atau import dengan `--force` flag

### File SQL terlalu besar
- Gunakan MySQL client (Metode 1) bukan dashboard
- Atau split file SQL menjadi beberapa bagian

## ✅ Checklist Setelah Import

- [ ] SQL file berhasil di-import
- [ ] Semua tabel terbuat (cek dengan `SHOW TABLES;`)
- [ ] Data sudah ada (cek dengan `SELECT COUNT(*) FROM admins;`)
- [ ] Environment variables sudah di-set di Railway
- [ ] App service sudah di-restart
- [ ] Test connection berhasil (`/test-db`)
- [ ] Login admin berhasil (username: `admin`, password: dari database)

## 📝 Credentials dari Database

Setelah import, cek credentials admin di database:
```sql
SELECT username, email FROM admins;
```

Password admin sudah di-hash, jadi gunakan password yang ada di database atau reset via seeder.

