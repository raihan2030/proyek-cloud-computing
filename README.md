# Proyek Cloud Computing - Sistem Manajemen Sumber Daya Cloud

Aplikasi web berbasis Laravel untuk mengelola paket langganan, pengguna, sumber daya sewaan, dan log aktivitas dalam sistem cloud computing.

## Teknologi yang Digunakan

- **Backend**: Laravel 13 + PHP 8.5
- **Frontend**: Blade Template + Tailwind CSS v4
- **Build Tool**: Vite
- **Database**: MySQL 8.0
- **Server**: Nginx
- **Containerization**: Docker & Docker Compose

## Prerequisites

Sebelum memulai, pastikan Anda sudah menginstall:

- [Docker Desktop](https://www.docker.com/products/docker-desktop) (termasuk Docker Compose)
- [Git](https://git-scm.com/)

Jika tidak menggunakan Docker:

- PHP 8.5
- Composer
- Node.js 18+ dan npm
- MySQL 8.0

## Instalasi dan Setup

### 1. Clone Repository

```bash
git clone <repository-url>
cd proyek-cloud-computing
```

### 2. Copy File Environment

```bash
cp .env.example .env
```

Sesuaikan konfigurasi di file `.env` jika diperlukan (terutama konfigurasi database).

### 3. Build dan Jalankan Docker Containers

```bash
docker-compose up -d --build
```

Perintah ini akan:

- Membangun image Docker untuk aplikasi PHP
- Menjalankan Nginx, PHP-FPM, dan MySQL dalam container terpisah
- Melakukan mounting volume untuk hot-reload

### 4. Install PHP Dependencies

```bash
docker-compose exec app composer install
```

### 5. Generate Application Key

```bash
docker-compose exec app php artisan key:generate
```

### 6. Run Database Migrations

```bash
docker-compose exec app php artisan migrate
```

Ini akan membuat tabel-tabel berikut di database:

- `users` - Data pengguna
- `paket_langganan` - Paket langganan cloud
- `pengguna` - Profil pengguna tambahan
- `sumber_daya_sewaan` - Data sumber daya yang disewa
- `log_aktivitas` - Log semua aktivitas sistem
- `jobs`, `cache` - Tabel internal Laravel

### 7. Install Node Dependencies dan Tailwind CSS

Jalankan di terminal Windows (PowerShell/CMD), bukan di dalam container:

```bash
npm install
```

Setup Tailwind CSS v4 dengan Vite sudah dikonfigurasi otomatis di `vite.config.js`.

### 8. Compile Frontend Assets

Jalankan di terminal Windows (PowerShell/CMD), bukan di dalam container:

**Untuk Development** (dengan hot reload):

```bash
npm run dev
```

**Untuk Production**:

```bash
npm run build
```

## Mengakses Aplikasi

Setelah semua setup selesai, akses aplikasi di:

```
http://localhost:8000
```

Database MySQL dapat diakses di:

- **Host**: localhost
- **Port**: 3306
- **User**: root (sesuaikan di `.env`)
- **Password**: password (sesuaikan di `.env`)

## Daily Startup Guide

Panduan langkah-demi-langkah untuk memulai project setelah istirahat atau setelah menutup aplikasi:

### ☐ Langkah 1: Start MySQL Service

- Buka **Laragon** (atau XAMPP jika menggunakan XAMPP)
- Klik tombol **Start All** atau pastikan **MySQL** service sudah berjalan
- Tunggu sampai status menunjukkan bahwa database siap digunakan

### ☐ Langkah 2: Buka Docker Desktop

- Buka aplikasi **Docker Desktop**
- Tunggu sampai Docker Engine sepenuhnya dimulai (biasanya 1-2 menit)
- Pastikan status bar menunjukkan Docker siap (tanpa error)

### ☐ Langkah 3: Start MiniStack/LocalStack Container

Pilih salah satu cara:

**Cara 1: Melalui Docker Desktop UI**
- Buka Docker Desktop
- Cari container dengan nama project (biasanya `proyek-cloud-computing-*`)
- Klik tombol **Start** untuk menjalankan container

**Cara 2: Melalui Terminal**
```bash
docker-compose up -d
```
Tunggu sampai output menunjukkan semua service berjalan (app, web, db, redis, dll).

### ☐ Langkah 4: Start Laravel Backend

- Buka terminal baru di root project folder
- Jalankan perintah berikut:

```bash
php artisan serve
```

Output yang diharapkan:
```
   INFO  Server running on [http://127.0.0.1:8000].
```

**Catatan**: Server akan berjalan di background. Jangan tutup terminal ini.

### ☐ Langkah 5: Start Vite Development Server (Frontend Assets)

- Buka **terminal tab baru** (jangan tutup terminal Laravel)
- Jalankan salah satu perintah sesuai kebutuhan:

**Untuk Development** (dengan hot reload untuk CSS/JS):
```bash
npm run dev
```

**Untuk Production** (compile dan build):
```bash
npm run build
```

Output yang diharapkan untuk `npm run dev`:
```
  VITE v... ready in ... ms

  ➜  Local:   http://localhost:5173/
  ➜  press h + enter to show help
```

### ☐ Langkah 6: Akses Dashboard

Buka browser dan akses aplikasi di:

```
http://127.0.0.1:8000
```

Aplikasi sekarang sudah siap digunakan! 🎉

### Tips Shutdown

Untuk menghentikan project dengan benar:

1. Tekan `Ctrl+C` di terminal Laravel (Step 4)
2. Tekan `Ctrl+C` di terminal Vite (Step 5)
3. Jalankan di terminal baru:
   ```bash
   docker-compose down
   ```
4. Stop MySQL service di Laragon/XAMPP

## Struktur Project

```
├── app/                    # Kode aplikasi Laravel
│   ├── Http/Controllers/   # Controllers
│   └── Models/             # Eloquent Models
├── database/
│   ├── migrations/         # Database migrations
│   ├── seeders/            # Database seeders
│   └── factories/          # Model factories
├── resources/
│   ├── views/              # Blade templates
│   ├── js/                 # JavaScript files
│   └── css/                # CSS files (Tailwind)
├── routes/                 # Route definitions
├── config/                 # Konfigurasi aplikasi
├── storage/                # File storage
├── tests/                  # Unit & Feature tests
├── docker-compose.yml      # Docker Compose configuration
├── Dockerfile              # Docker image definition
└── vite.config.js          # Vite configuration
```

## Perintah Artisan Useful

```bash
# Jalankan server development
docker-compose exec app php artisan serve

# Buat migration baru
docker-compose exec app php artisan make:migration create_table_name

# Buat model baru dengan migration dan factory
docker-compose exec app php artisan make:model ModelName -mf

# Buat controller
docker-compose exec app php artisan make:controller ControllerName

# Reset database (hapus semua data dan migrate ulang)
docker-compose exec app php artisan migrate:fresh
```

## Troubleshooting

### Container tidak bisa dijalankan

```bash
# Cek status container
docker-compose ps

# Lihat logs
docker-compose logs app
docker-compose logs db
docker-compose logs web
```

### Port sudah terpakai

Jika port 8000 atau 3306 sudah digunakan, ubah di `docker-compose.yml`:

```yaml
ports:
    - "8001:80" # Port baru: 8001
    - "3307:3306" # Port baru: 3307
```

### Reset Database

```bash
docker-compose exec app php artisan migrate:fresh
```

### Clear Cache

```bash
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan view:clear
```

## Menghentikan Aplikasi

```bash
docker-compose down
```

Untuk menghapus semua data dan images:

```bash
docker-compose down -v
```

## Development Tips

1. **Hot Reload CSS/JS**: Jalankan `npm run dev` di terminal Windows untuk auto-compile saat ada perubahan file
2. **Database GUI**: Gunakan MySQL Workbench atau tools lainnya untuk connect ke localhost:3306
3. **Laravel Tinker**: Jalankan `docker-compose exec app php artisan tinker` untuk interactive shell
4. **Npm Commands**: Semua npm commands (`npm install`, `npm run dev`, `npm run build`) dijalankan di terminal Windows, bukan di dalam container Docker

## Lisensi

MIT License

## Kontribusi

Untuk kontribusi, silakan buat fork dan submit pull request dengan deskripsi perubahan yang jelas.
