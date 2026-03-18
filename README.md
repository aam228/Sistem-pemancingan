<p align="center">
  <img src="docs/screenshots/Dashboard.png" width="720" alt="Combro Fishing Management System">
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11-red">
  <img src="https://img.shields.io/badge/PHP-8.2-blue">
  <img src="https://img.shields.io/badge/Database-MySQL-orange">
  <img src="https://img.shields.io/badge/License-MIT-green">
</p>

---

# Combro Fishing Management System

**Combro Fishing Management System** adalah aplikasi manajemen operasional kolam pemancingan berbasis web yang dibangun menggunakan **Laravel 11**.  
Sistem ini dirancang untuk mendigitalisasi proses operasional harian secara terintegrasi, mulai dari pengelolaan lapak, member, transaksi sesi pancing, hingga laporan dan analisis bisnis.

---

## Fitur Utama

- **Dashboard Real-Time**  
  Panel kendali utama untuk memantau seluruh lapak secara langsung, membuka sesi, mencatat pesanan, dan memproses pembayaran.

- **Manajemen Spot (Lapak)**  
  Pengelolaan lapak pancing dengan tarif dinamis berdasarkan waktu (Pagi / Siang / Sore / Malam) yang otomatis tampil di Dashboard.

- **Mulai Sesi (Transaksi Baru)**  
  Form pendaftaran pelanggan yang diakses langsung dari lapak kosong di Dashboard dan terhubung dengan data member.

- **Selesai Sesi & Pembayaran**  
  Penutupan sesi pancing dengan perhitungan otomatis serta pilihan metode pembayaran yang telah dikonfigurasi.

- **Manajemen Member**  
  Pengelolaan pelanggan setia dengan sistem diskon dan poin otomatis saat transaksi.

- **Kelola Produk & Pesanan**  
  CRUD menu makanan dan minuman serta pencatatan pesanan tambahan selama sesi pancing.

- **Laporan Keuangan**  
  Rekap transaksi otomatis dengan fitur ekspor laporan ke PDF.

- **Analytics**  
  Visualisasi data transaksi berupa grafik tren pendapatan, jam sibuk, dan performa tiap lapak.

---

## Screenshots

<p align="center">
  <img src="docs/screenshots/Dashboard.png" width="400">
  <img src="docs/screenshots/Doashboard_bg.png" width="400">
</p>

<p align="center">
  <img src="docs/screenshots/Members.png" width="400">
  <img src="docs/screenshots/Produk.png" width="400">
</p>

<p align="center">
  <img src="docs/screenshots/Histori.png" width="400">
  <img src="docs/screenshots/Laporan.png" width="400">
</p>

<p align="center">
  <img src="docs/screenshots/Payment.png" width="400">
  <img src="docs/screenshots/Analis.png" width="400">
</p>

---

## Tech Stack

| Komponen | Teknologi |
|--------|-----------|
| Framework | Laravel 11 |
| Frontend | Blade, Bootstrap 5, Alpine.js |
| Database | MySQL |
| Charts | Chart.js |
| PDF | Laravel DomPDF |
| Icons | Font Awesome |

---

## Installation

### Option A (Recommended): Docker + Laravel Sail

```bash
git clone https://github.com/aam228/Sistem-pemancingan.git
cd Sistem-pemancingan
composer install
cp .env.example .env

# set docker database credentials in .env
# DB_CONNECTION=mysql
# DB_HOST=mysql
# DB_PORT=3306
# DB_DATABASE=sistem_pemancingan
# DB_USERNAME=sail
# DB_PASSWORD=password

./vendor/bin/sail up -d
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan storage:link
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev
```

Open:

- App: http://localhost
- phpMyAdmin: http://localhost:8080

### Option B: Local PHP (without Docker)

```bash
git clone https://github.com/aam228/Sistem-pemancingan.git
cd Sistem-pemancingan
composer install
cp .env.example .env
php artisan key:generate

# use your local MySQL credentials in .env
php artisan migrate
php artisan storage:link

npm install
npm run dev
php artisan serve
```

## Project Structure

```bash
app/
 ├── Http/Controllers
 ├── Models
database/
 ├── migrations
 ├── seeders
resources/
 ├── views
routes/
 └── web.php

