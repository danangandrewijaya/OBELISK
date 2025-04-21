## Instalasi Obelisk

Berikut adalah langkah-langkah untuk menginstal dan menjalankan proyek Obelisk:

### Persyaratan Sistem
- PHP >= 8.2
- Composer
- Node.js & NPM
- Database (MySQL, PostgreSQL, SQLite, atau SQL Server)

### Langkah-langkah Instalasi

1. **Kloning Repositori**
   ```bash
   git clone [url-repositori] nama-proyek
   cd nama-proyek
   ```

2. **Instal Dependensi PHP**
   ```bash
   composer install
   ```

3. **Salin File .env**
   ```bash
   cp .env.example .env
   ```

4. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

5. **Konfigurasi Database**
   - Buka file `.env` dan sesuaikan konfigurasi database:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nama_database
   DB_USERNAME=username
   DB_PASSWORD=password
   ```

6. **Jalankan Migrasi dan Seeder**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

7. **Instal Dependensi JavaScript dan Kompilasi Asset**
   ```bash
   npm install
   npm run dev
   ```

8. **Jalankan Server Laravel**
   ```bash
   php artisan serve
   ```
   Aplikasi akan tersedia di http://localhost:8000

   Untuk di Server Production mungkin caranya sedikit berbeda.

## Pemeliharaan

- **Update Repositori**
  ```bash
  git pull origin main
  php artisan migrate
  ```

- **Update Dependensi**
  ```bash
  composer update
  npm update
  ```

- **Cache dan Optimize**
  ```bash
  php artisan optimize
  php artisan config:cache
  php artisan route:cache
  ```

## Troubleshooting

- Jika terjadi error pada saat instalasi, pastikan semua persyaratan sistem terpenuhi.
- Jika terjadi masalah izin pada `storage` atau `bootstrap/cache`, jalankan perintah `chmod -R 775 storage bootstrap/cache`.
- Untuk masalah database, pastikan konfigurasi di file `.env` benar dan database sudah dibuat.
