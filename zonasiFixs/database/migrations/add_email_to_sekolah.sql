-- Menambahkan kolom email ke tabel sekolah
-- Jalankan query ini di phpMyAdmin atau MySQL console

ALTER TABLE sekolah ADD COLUMN email VARCHAR(255) NULL AFTER telepon;

-- Selesai! Sekarang tabel sekolah memiliki kolom email untuk menyimpan alamat email sekolah.
