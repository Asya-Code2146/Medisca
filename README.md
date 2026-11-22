# Medisca – Sistem Informasi Unit Kesehatan Sekolah (UKS)

<p align="center">
  <img src="public/logo.png" width="100" alt="Logo Medisca" />
</p>

<p align="center">
  <strong>Platform Digital Manajemen Layanan Kesehatan & Edukasi Siswa SMKN 5 Telkom Banda Aceh</strong>
</p>

<p align="center">
  <a href="https://medisca-eta.vercel.app"><img src="https://img.shields.io/badge/Live_Demo-medisca--eta.vercel.app-0F4C45?style=for-the-badge&logo=vercel" alt="Vercel Deployment" /></a>
  <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel" alt="Laravel 12" />
  <img src="https://img.shields.io/badge/Database-Supabase_PostgreSQL-3ECF8E?style=for-the-badge&logo=supabase" alt="Supabase PostgreSQL" />
  <img src="https://img.shields.io/badge/PWA-Supported-5A0FC8?style=for-the-badge&logo=pwa" alt="PWA" />
</p>

---

## Tentang Medisca

**Medisca** adalah aplikasi web sistem informasi Unit Kesehatan Sekolah (UKS) yang dirancang khusus untuk mempermudah operasional harian petugas kesehatan sekolah, monitoring pihak manajemen sekolah, serta edukasi kesehatan bagi seluruh siswa.

Dibangun dengan antarmuka yang ramah pengguna, cepat, dan responsif, Medisca menggantikan pencatatan manual berbasis buku fisik menjadi sistem digital yang aman, transparan, dan tersentralisasi.

---

## Fitur Utama

### 1. Pencatatan Rekam Medis Siswa
- Pencatatan keluhan siswa sakit secara terstruktur (nama, kelas, gejala, penanganan awal).
- Riwayat pemberian obat yang otomatis terintegrasi dengan pengurangan stok obat.
- Status observasi siswa yang fleksibel (Pulih / Istirahat di UKS / Rujuk ke Fasilitas Kesehatan).

### 2. Manajemen Stok Obat & Peringatan Dini
- Pendataan 23 jenis obat dan perlengkapan P3K yang bersumber dari inventaris nyata UKS sekolah.
- Kategori obat lengkap: Cair/Sirup, Tablet/Kapsul, Salep/Krim, Minyak Herbal, dan Perlengkapan Medis.
- Fitur restock dan pengurangan cepat dengan indikator peringatan stok menipis secara otomatis.

### 3. Role-Based Access Control (RBAC)
Sistem memiliki 3 tingkat hak akses pengguna:
- **Admin**: Akses penuh seluruh sistem, pengelolaan user, serta panel monitoring bug dan keamanan website.
- **Petugas UKS (`guru_uks`)**: Mengelola rekam medis siswa, data obat, dan pembaruan stok harian.
- **Pihak Sekolah (`pihak_sekolah`)**: Hak akses read-only untuk memantau data rekap kesehatan dan mencetak laporan.

### 4. Otentikasi Terintegrasi Google OAuth 2.0
- Login instan satu klik menggunakan akun Google resmi yang terdaftar sebagai penanggung jawab UKS.
- Integrasi *stateless session* yang kompatibel penuh dengan arsitektur cloud serverless.
- Foto profil Google otomatis sinkron ke profil navbar dan Panel Admin.

### 5. Progressive Web App (PWA)
- Dapat diinstal langsung (*Add to Home Screen*) di perangkat Android, iOS, maupun desktop PC.
- Dilengkapi dengan *animated splash screen* dan penanganan instalasi otomatis.
- Berjalan responsif di berbagai resolusi layar dari ponsel cerdas hingga monitor komputer.

### 6. Panel Admin Pemantauan Masalah Website
- Halaman pemantauan kendala teknis (`/admin`) untuk memfilter laporan bug tampilan, error sistem, dan aktivitas mencurigakan.
- Status penanganan laporan (*Perlu Ditinjau* atau *Selesai*) dengan modal detail interaktif.

---

## Teknologi yang Digunakan

- **Backend Framework**: Laravel 12 (PHP 8.3+)
- **Database Utama**: Supabase Cloud PostgreSQL (via Transaction Pooler)
- **Database Alternatif**: MySQL / SQLite
- **Autentikasi**: Laravel Breeze & Laravel Socialite (Google OAuth 2.0)
- **Deployment**: Vercel Serverless Platform
- **PWA**: Service Worker & Web App Manifest
- **Desain & UI**: HTML5 Semantik, CSS3 Modern (CSS Grid/Flexbox), Tipografi Plus Jakarta Sans

---

## Akun Demo Pengujian

Anda dapat mencoba masuk menggunakan akun demo berikut:

| Peran | Username / Email | Kata Sandi |
|---|---|---|
| **Admin** | `admin` *(atau admin@medisca.sch.id)* | `admin123` |
| **Petugas UKS** | `petugas` *(atau petugas@medisca.sch.id)* | `uks123` |
| **Pihak Sekolah** | `sekolah` *(atau sekolah@medisca.sch.id)* | `sekolah123` |

Atau klik tombol **Masuk dengan Google** untuk masuk menggunakan akun Gmail yang telah didaftarkan.

---

## Panduan Instalasi Lokal

### Prasyarat
- PHP >= 8.2 (ekstensi `pdo`, `pdo_pgsql` / `pdo_mysql`, `curl`, `mbstring` aktif)
- Composer
- Node.js & NPM

### Langkah-langkah

1. **Clone repositori:**
```bash
git clone https://github.com/Asya-Code2146/Medisca.git
cd Medisca
```

2. **Pasang dependensi PHP:**
```bash
composer install
```

3. **Salin konfigurasi environment:**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Konfigurasi Database di file `.env`:**
```env
DB_CONNECTION=pgsql
DB_HOST=aws-1-ap-southeast-2.pooler.supabase.com
DB_PORT=6543
DB_DATABASE=postgres
DB_USERNAME=postgres.ynnhyuzsrakwmzqoqnkh
DB_PASSWORD=mediscauks76
DB_SSLMODE=require
```

5. **Jalankan migrasi tabel dan data awal (seeder):**
```bash
php artisan migrate:fresh --seed
```

6. **Jalankan server aplikasi:**
```bash
php artisan serve
```

Akses aplikasi di browser melalui `http://localhost:8000`.

---

## Informasi Sekolah & Kontak Resmi

Aplikasi ini dikembangkan untuk mendukung operasional:

- **Instansi**: SMK Negeri 5 Telkom Banda Aceh
- **Lokasi**: Ruang UKS, Lantai 1
- **Telepon**: 0651-7552314
- **Email Resmi**: smkn5telkombandaaceh@gmail.com
- **Instagram**: [@smkn5telkomaceh](https://www.instagram.com/smkn5telkomaceh/)
- **TikTok**: [@smkn5telkomaceh](https://www.tiktok.com/@smkn5telkomaceh/)
- **Facebook**: SMK Negeri 5 Telkom Banda Aceh

---

## Lisensi

Proyek ini dilisensikan di bawah lisensi terbuka [MIT](LICENSE).
