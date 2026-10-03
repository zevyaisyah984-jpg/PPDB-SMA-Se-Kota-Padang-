<?php 
$title = 'Hasil Seleksi';
include ROOT_PATH . 'app/Views/admin/layouts/header.php'; 

// School Admin can change status, Super Admin only views
$canChangeStatus = ($_SESSION['admin_role'] === 'school_admin');
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Hasil Seleksi Pengumuman Akhir</h4>
        <p class="text-muted small mb-0">
            <?php if ($canChangeStatus): ?>
                Anda dapat mengubah status kelulusan siswa di sekolah Anda.
            <?php else: ?>
                Halaman ini memuat data perankingan final pendaftar. (Hanya melihat)
            <?php endif; ?>
        </p>
    </div>
    <div class="d-flex gap-2">
        <?php if ($_SESSION['admin_role'] === 'super_admin'): ?>
        <a href="<?php echo url('/admin/seleksi/proses'); ?>" class="btn btn-primary rounded-pill px-4">
            <i class="bi bi-cpu me-2"></i> Proses Seleksi
        </a>
        <a href="<?php echo url('/admin/seleksi/promote'); ?>" class="btn btn-warning rounded-pill px-4 fw-bold" onclick="return confirm('Promosikan semua siswa cadangan ke slot yang masih tersedia?')">
            <i class="bi bi-arrow-up-circle me-2"></i> Naikkan Cadangan
        </a>
        <?php endif; ?>
        <a href="<?php echo url('/admin/seleksi/export'); ?>" class="btn btn-outline-success rounded-pill px-4">
            <i class="bi bi-file-earmark-excel me-2"></i> Export Excel
        </a>
        <a href="<?php echo url('/admin/seleksi/cetak'); ?>" target="_blank" class="btn btn-light rounded-pill px-4">
            <i class="bi bi-printer me-2"></i> Cetak PDF
        </a>
    </div>
</div>

<?php if (isset($_SESSION['success'])): ?>
<div class="alert alert-success alert-dismissible fade show rounded-4 border-0" role="alert">
    <i class="bi bi-check-circle me-2"></i><?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
<div class="alert alert-danger alert-dismissible fade show rounded-4 border-0" role="alert">
    <i class="bi bi-exclamation-circle me-2"></i><?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr class="text-secondary small text-uppercase">
                    <th class="border-0 ps-4">Rank</th>
                    <th class="border-0">No. Pendaftaran</th>
                    <th class="border-0">Nama Siswa</th>
                    <th class="border-0">NISN</th>
                    <th class="border-0">Jalur</th>
                    <th class="border-0">Skor/Jarak</th>
                    <th class="border-0 text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($hasil)): ?>
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <i class="bi bi-search fs-1 d-block mb-3 opacity-25"></i>
                        Belum ada data seleksi. Silakan jalankan proses seleksi terlebih dahulu.
                    </td>
                </tr>
                <?php else: ?>
                    <?php $rank = 1; foreach ($hasil as $h): ?>
                    <tr>
                        <td class="ps-4">
                            <span class="badge <?php echo $rank <= 3 ? 'bg-warning' : 'bg-light text-dark'; ?> rounded-circle d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                                <?php echo $rank++; ?>
                            </span>
                        </td>
                        <td class="fw-semibold text-primary"><?php echo $h['no_pendaftaran'] ?? 'N/A'; ?></td>
                        <td><?php echo htmlspecialchars($h['nama_siswa'] ?? $h['nama'] ?? 'N/A'); ?></td>
                        <td><?php echo $h['nisn'] ?? 'N/A'; ?></td>
                        <td>
                            <span class="badge border border-primary text-primary bg-primary bg-opacity-10 rounded-pill px-3">
                                <?php echo ucfirst($h['jalur'] ?? 'zonasi'); ?>
                            </span>
                        </td>
                        <td class="small">
                            <?php if (($h['jalur'] ?? 'zonasi') == 'zonasi'): ?>
                                <i class="bi bi-geo-alt me-1"></i> <?php echo number_format($h['jarak_meter'] ?? 0, 0); ?> m
                            <?php else: ?>
                                <i class="bi bi-star me-1"></i> <?php echo number_format($h['skor'] ?? 0, 2); ?>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <?php 
                            $status = $h['status'] ?? 'pending';
                            $statusConfig = [
                                'diterima' => ['class' => 'bg-success', 'icon' => 'check-circle', 'label' => 'DITERIMA'],
                                'cadangan' => ['class' => 'bg-warning text-dark', 'icon' => 'hourglass-split', 'label' => 'CADANGAN'],
                                'ditolak' => ['class' => 'bg-danger', 'icon' => 'x-circle', 'label' => 'DITOLAK'],
                                'pending' => ['class' => 'bg-secondary', 'icon' => 'clock', 'label' => 'PENDING']
                            ];
                            $sc = $statusConfig[$status] ?? $statusConfig['pending'];
                            ?>
                            <span class="badge <?php echo $sc['class']; ?> rounded-pill px-3 py-2 fw-bold">
                                <i class="bi bi-<?php echo $sc['icon']; ?> me-1"></i> <?php echo $sc['label']; ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if (!$canChangeStatus && $_SESSION['admin_role'] === 'super_admin'): ?>
<div class="alert alert-info mt-4 border-0 rounded-4">
    <div class="d-flex align-items-center">
        <i class="bi bi-info-circle fs-4 me-3"></i>
        <div>
            <strong>Info:</strong> Sebagai Super Admin, Anda hanya dapat melihat data hasil seleksi. 
            Penentuan lulus/tidak lulus dilakukan oleh masing-masing Admin Sekolah.
        </div>
    </div>
</div>
<?php endif; ?>

<style>
@media print {
    #sidebar, .navbar, .btn, .breadcrumb { display: none !important; }
    #main-content { margin-left: 0 !important; padding: 0 !important; }
    .card { box-shadow: none !important; border: 1px solid #ddd !important; }
}
</style>

<?php include ROOT_PATH . 'app/Views/admin/layouts/footer.php'; ?>
