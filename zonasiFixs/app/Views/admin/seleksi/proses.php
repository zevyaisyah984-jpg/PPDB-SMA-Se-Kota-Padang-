<?php 
$title = 'Proses Seleksi';
include ROOT_PATH . 'app/Views/admin/layouts/header.php'; 
?>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
    <div class="card-body p-5 text-center">
        <div class="badge bg-primary bg-opacity-10 text-primary p-4 rounded-circle mb-4">
            <i class="bi bi-cpu fs-1"></i>
        </div>
        <h3 class="fw-bold">Pusat Kendali Seleksi Otomatis</h3>
        <p class="text-muted mx-auto" style="max-width: 600px;">
            Halaman ini digunakan untuk menjalankan algoritma perankingan PPDB 2025/2026 secara serentak. 
            Sistem akan melakukan kalkulasi jarak (Zonasi) dan skor (Prestasi) secara real-time.
        </p>
        
        <div class="d-flex justify-content-center gap-3 mt-4">
            <button type="button" class="btn btn-primary px-5 py-3 rounded-pill fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#runSelectionModal">
                <i class="bi bi-play-fill me-2"></i> JALANKAN SELEKSI SEKARANG
            </button>
            <?php if ($_SESSION['admin_role'] === 'super_admin'): ?>
            <button type="button" class="btn btn-outline-danger px-5 py-3 rounded-pill fw-bold" data-bs-toggle="modal" data-bs-target="#resetSelectionModal">
                <i class="bi bi-arrow-counterclockwise me-2"></i> RESET SEMUA HASIL
            </button>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Quick Audit Log -->
<div class="card border-0 shadow-sm rounded-4 p-4">
    <h5 class="fw-bold mb-3">Status Seleksi Terakhir</h5>
    <div class="table-responsive">
        <table class="table table-borderless align-middle">
            <thead>
                <tr class="text-muted small text-uppercase">
                    <th>Aktivitas</th>
                    <th>Eksekutor</th>
                    <th>Waktu</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><span class="fw-semibold">Kalkulasi Ranking Global</span></td>
                    <td>Super Admin</td>
                    <td>Baru Saja</td>
                    <td><span class="badge bg-success-subtle text-success rounded-pill px-3">Ready</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Run Selection -->
<div class="modal fade" id="runSelectionModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-body p-5 text-center">
                <div class="text-primary mb-4">
                    <i class="bi bi-info-circle-fill display-1"></i>
                </div>
                <h4 class="fw-bold">Konfirmasi Seleksi Serentak</h4>
                <p class="text-muted mb-4">
                    Sistem akan memproses ribuan data pendaftar. Pastikan seluruh batas waktu pendaftaran sudah berakhir agar hasil akurat.
                </p>
                <form action="<?php echo url('/admin/seleksi/eksekusi'); ?>" method="POST">
                    <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold mb-2">Ya, Jalankan Sekarang</button>
                    <button type="button" class="btn btn-light w-100 py-3 rounded-pill fw-bold text-muted" data-bs-dismiss="modal">Batalkan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Reset Selection (SAFETY FIRST) -->
<div class="modal fade" id="resetSelectionModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-body p-5 text-center">
                <div class="text-danger mb-4">
                    <i class="bi bi-exclamation-triangle-fill display-1"></i>
                </div>
                <h4 class="fw-bold">Reset Hasil Seleksi?</h4>
                <p class="text-muted mb-4">
                    Aksi ini akan menghapus seluruh status 'Diterima' dan 'Cadangan'. Data pendaftar tidak hilang, namun ranking akan kosong kembali.
                </p>
                <form action="<?php echo url('/admin/seleksi/reset'); ?>" method="POST">
                    <button type="submit" class="btn btn-danger w-100 py-3 rounded-pill fw-bold mb-2">Hapus & Reset Hasil</button>
                    <button type="button" class="btn btn-light w-100 py-3 rounded-pill fw-bold text-muted" data-bs-dismiss="modal">Kembali</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include ROOT_PATH . 'app/Views/admin/layouts/footer.php'; ?>
