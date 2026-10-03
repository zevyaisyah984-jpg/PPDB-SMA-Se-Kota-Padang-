# Sistem PPDB SMA Padang

Sistem Informasi Penerimaan Peserta Didik Baru (PPDB) SMA Negeri di Kota Padang dengan fitur pemetaan zonasi sekolah.

## 📝 Deskripsi
Aplikasi ini dirancang untuk memudahkan calon siswa dan orang tua dalam menemukan Sekolah Menengah Atas (SMA) Negeri di Kota Padang serta memahami pembagian zona sekolah berdasarkan radius tempat tinggal (zonasi).

## ✨ Fitur Utama
- **Pencarian Sekolah:** Cari sekolah berdasarkan nama atau NPSN.
- **Filter Wilayah:** Filter sekolah berdasarkan kecamatan di Kota Padang.
- **Peta Interaktif:** Visualisasi lokasi sekolah menggunakan Leaflet JS.
- **Visualisasi Zonasi:** Menampilkan radius zonasi sekolah (2 km) pada peta.
- **Detail Sekolah:** Informasi lengkap mengenai kuota, alamat, dan koordinat sekolah.
- **Manajemen User:** Sistem login untuk User (Pendaftar) dan Admin.
- **Formulir Pendaftaran:** Berbagai jalur pendaftaran (Zonasi, Afirmasi, Mutasi, dll).

## 🛠️ Teknologi
- **Backend:** PHP 8.x
- **Database:** MySQL/MariaDB
- **Frontend:** HTML5, CSS3, JavaScript
- **Framework & Library:**
  - Bootstrap 5 (UI Framework)
  - Leaflet JS (Interactive Maps)
  - Bootstrap Icons

## 🚀 Instalasi
1. Kloning repositori ini atau download source code.
2. Pindahkan folder project ke direktori web server (contoh: `C:\xampp\htdocs\zonasi`).
3. Buat database baru bernama `smapadang` di MySQL.
4. Import file database (jika ada) atau sesuaikan tabel di `config.php`.
5. Sesuaikan konfigurasi database di file `config.php`:
   ```php
   $host = "localhost";
   $user = "root";
   $pass = "";
   $db   = "smapadang";
   ```
6. Buka browser dan akses `http://localhost/zonasi`.

## 📁 Struktur Proyek
- `/admin`: Halaman pengelolaan untuk Administrator.
- `/assets`: File statis (CSS, JS, Images).
- `/includes`: Komponen PHP yang digunakan berulang (header, footer, navbar, dll).
- `/user`: Halaman untuk calon siswa/pendaftar.
- `/uploads`: Tempat penyimpanan berkas unggahan.
- `index.php`: Halaman utama aplikasi.

## 📄 Lisensi
[Sebutkan lisensi jika ada, misal: MIT License]

---
Dikembangkan untuk mempermudah akses informasi PPDB SMA di Kota Padang.
