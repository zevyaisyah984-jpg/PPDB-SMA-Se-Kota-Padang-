# Walkthrough: Implementasi Strict Juknis PPDB Sumbar 2025

Dokumen ini merangkum pembaruan sistem besar-besaran untuk mematuhi aturan teknis (Juknis) PPDB Sumbar 2025, khususnya pada Jalur Zonasi (Domisili) yang kini memprioritaskan Nilai Rapor.

## 1. Pembaruan Frontend (`form-zonasi.php`)

Kami telah meredesain formulir pendaftaran Zonasi menjadi 5 Langkah Progresif untuk mengakomodasi input data baru.

### Fitur Baru:
*   **Step 2: Nilai Rapor**: Langkah baru khusus untuk menginput rata-rata nilai rapor Semester 1 s.d 5. Dilengkapi dengan kalkulator rata-rata otomatis (Real-time Preview).
*   **Validasi Usia Strict**: Validasi JavaScript langsung yang menolak pendaftar jika usia > 21 tahun per **1 Juli 2025**.
*   **Peta Interaktif**: Diperbarui dengan Custom Marker "Deep Cobalt Blue" dan inisialisasi yang lebih mulus pada Step 3.
*   **Upload Dokumen**: Menambahkan field `file_rapor` (Wajib) dan `file_kk_lama` (Opsional).

```javascript
// Contoh Logic Validasi Frontend Baru
if (years > 21) {
    showError("Maaf, usia Anda melebihi batas maksimal (21 tahun per 1 Juli 2025)...");
}
```

## 2. Pembaruan Backend & Database

Sisi server telah diperketat untuk menangani data baru dan validasi keamanan.

### Database Schema
File migrasi SQL telah dibuat di `tools/alter_siswa_2025.sql`. Anda perlu menjalankan skrip ini di database Anda:

```sql
ALTER TABLE siswa 
ADD COLUMN nilai_sem1 DECIMAL(5,2) DEFAULT 0,
-- ... (sem2-5)
ADD COLUMN file_rapor VARCHAR(255) NULL,
ADD COLUMN file_kk_lama VARCHAR(255) NULL;
```

### Model (`Siswa.php`)
Diperbarui untuk menyimpan 5 kolom nilai rapor dan 2 kolom file baru ke dalam tabel `siswa`.

### Controller (`SiswaController.php`)
*   **Input Handling**: Menangkap `$_POST['nilai_sem1']` dst. dan menyimpannya.
*   **Validasi Server-Side**: Memastikan manipulasi frontend tidak bisa menembus aturan usia. Logika usia menggunakan `DateTime` dengan target fixed **1 Juli 2025**.

## Tindakan Selanjutnya
1.  **Jalankan SQL**: Eksekusi file `tools/alter_siswa_2025.sql` di database lokal Anda.
2.  **Test Submission**: Coba lakukan pendaftaran jalur Zonasi.
3.  **Verifikasi Data**: Cek database apakah kolom `nilai_sem1` terisi dengan benar.
