<?php 
$title = 'Dashboard';
include ROOT_PATH . 'app/Views/admin/layouts/header.php'; 
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1 text-primary">Kemajuan PPDB 2025/2026</h4>
        <p class="text-muted small mb-0">Selamat datang kembali, <strong><?php echo htmlspecialchars($_SESSION['admin_name']); ?></strong>.</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-white shadow-sm btn-sm px-3 rounded-pill border" onclick="location.reload()">
            <i class="bi bi-arrow-clockwise me-1"></i> Refresh Data
        </button>
        <div class="bg-white px-3 py-1 rounded-pill shadow-sm border small d-flex align-items-center">
            <span class="pulse-green me-2"></span> <?php echo date('H:i'); ?> WIB
        </div>
    </div>
</div>

<!-- Bento Grid Layout -->
<div class="bento-grid mb-4">
    <!-- Main Stats Card (Bento Large) -->
    <div class="bento-item bento-2x2 bg-primary text-white p-4 d-flex flex-column justify-content-between position-relative overflow-hidden">
        <div class="position-absolute end-0 top-0 p-4 opacity-10">
            <i class="bi bi-people-fill" style="font-size: 10rem; transform: rotate(-15deg);"></i>
        </div>
        <div class="position-relative z-1">
            <h6 class="text-uppercase small fw-bold opacity-75 mb-4">Total Calon Siswa Terdaftar</h6>
            <h1 class="display-3 fw-bold mb-0"><?php echo number_format($total_pendaftar ?? 0); ?></h1>
            <p class="mb-0 opacity-75 mt-2">Pendaftar Tersebar di Seluruh Jalur</p>
        </div>
        <div class="position-relative z-1 mt-4">
            <div class="d-flex justify-content-between small mb-2 opacity-75">
                <span>Progres Verifikasi</span>
                <span><?php echo ($total_pendaftar > 0) ? round(($verified_fisik / $total_pendaftar) * 100) : 0; ?>%</span>
            </div>
            <div class="progress bg-white bg-opacity-20" style="height: 8px; border-radius: 10px;">
                <div class="progress-bar bg-white" style="width: <?php echo ($total_pendaftar > 0) ? ($verified_fisik / $total_pendaftar) * 100 : 0; ?>%; border-radius: 10px;"></div>
            </div>
        </div>
    </div>

    <!-- Stats Small: Pending (Bento Small) -->
    <div class="bento-item bg-white p-4 d-flex flex-column justify-content-between border shadow-sm">
        <div>
            <div class="stats-icon-wrapper bg-warning bg-opacity-10 text-warning mb-3">
                <i class="bi bi-clock-history"></i>
            </div>
            <h6 class="text-muted small fw-bold text-uppercase mb-1">Verifikasi Berkas</h6>
            <h3 class="fw-bold mb-0 text-dark"><?php echo number_format($pending_verifikasi ?? 0); ?></h3>
        </div>
        <div class="mt-3">
            <a href="<?php echo url('/admin/verifikasi'); ?>" class="text-primary small fw-bold text-decoration-none">
                Cek Sekarang <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>

    <!-- Stats Small: Sisa Kuota (Bento Small) -->
    <div class="bento-item bg-white p-4 d-flex flex-column justify-content-between border shadow-sm">
        <div>
            <div class="stats-icon-wrapper bg-success bg-opacity-10 text-success mb-3">
                <i class="bi bi-pie-chart-fill"></i>
            </div>
            <h6 class="text-muted small fw-bold text-uppercase mb-1">Total Sisa Kuota</h6>
            <h3 class="fw-bold mb-0 text-dark"><?php echo number_format($sisa_kuota ?? 0); ?></h3>
        </div>
        <div class="mt-3">
            <div class="small text-muted">Kursi tersedia secara global</div>
        </div>
    </div>

    <!-- Quick Actions (Bento Wide) - Super Admin Only -->
    <?php if ($_SESSION['admin_role'] === 'super_admin'): ?>
    <div class="bento-item bento-wide bg-white p-4 border shadow-sm">
        <h6 class="text-muted small fw-bold text-uppercase mb-3">Aksi Cepat</h6>
        <div class="row g-3">
            <div class="col-6">
                <a href="<?php echo url('/admin/sekolah/tambah'); ?>" class="btn btn-primary-subtle text-primary w-100 py-3 rounded-4 d-flex flex-column align-items-center gap-2 border border-primary border-opacity-10">
                    <i class="bi bi-building-add fs-4"></i>
                    <span class="small fw-bold">Input Sekolah</span>
                </a>
            </div>
            <div class="col-6">
                <a href="<?php echo url('/admin/seleksi/proses'); ?>" class="btn btn-danger-subtle text-danger w-100 py-3 rounded-4 d-flex flex-column align-items-center gap-2 border border-danger border-opacity-10">
                    <i class="bi bi-lightning-charge-fill fs-4"></i>
                    <span class="small fw-bold">Jalankan Seleksi</span>
                </a>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<div class="row g-4">
    <!-- Pendaftar Terbaru (Bento Extended) -->
    <div class="<?php echo ($_SESSION['admin_role'] === 'super_admin') ? 'col-lg-8' : 'col-lg-12'; ?>">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-bottom py-4 px-4 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0">Pendaftar Terkini</h6>
                <a href="<?php echo url('/admin/pendaftar'); ?>" class="btn btn-sm btn-link text-primary fw-bold text-decoration-none p-0">
                    Review Semua <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light bg-opacity-50">
                        <tr>
                            <th class="ps-4 py-3 small text-muted text-uppercase fw-bold">Siswa</th>
                            <th class="py-3 small text-muted text-uppercase fw-bold">Jalur</th>
                            <th class="py-3 small text-muted text-uppercase fw-bold">Waktu</th>
                            <th class="pe-4 py-3 small text-muted text-uppercase fw-bold text-end">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($latest_pendaftar as $p): ?>
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center py-2">
                                    <div class="profile-avatar me-3" style="width: 38px; height: 38px; font-size: 0.9rem; background-color: var(--primary);">
                                        <?php echo substr($p['nama_siswa'], 0, 1); ?>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark small"><?php echo e($p['nama_siswa']); ?></div>
                                        <div class="text-muted" style="font-size: 0.7rem;">NISN: <?php echo e($p['nisn']); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge rounded-pill small fw-semibold" style="background-color: rgba(0, 82, 204, 0.1); color: #0052CC; border: 1px solid rgba(0, 82, 204, 0.2); padding: 5px 12px;">
                                    <?php echo ucfirst($p['jalur']); ?>
                                </span>
                            </td>
                            <td class="text-muted small">
                                <?php echo date('H:i', strtotime($p['tanggal_daftar'])); ?> <span class="opacity-50">WIB</span>
                            </td>
                            <td class="pe-4 text-end">
                                <?php 
                                $status = strtolower($p['status'] ?? 'pending');
                                $statusLabel = str_replace('_', ' ', $status);
                                $colorMap = [
                                    'pending'        => ['bg' => '#FFFBEB', 'text' => '#B45309', 'border' => '#FEF3C7'],
                                    'ditolak'        => ['bg' => '#FEF2F2', 'text' => '#B91C1C', 'border' => '#FEE2E2'],
                                    'tidak_diterima' => ['bg' => '#F1F5F9', 'text' => '#475569', 'border' => '#E2E8F0'],
                                    'diterima'       => ['bg' => '#F0FDF4', 'text' => '#15803D', 'border' => '#DCFCE7'],
                                    'terverifikasi'  => ['bg' => '#EFF6FF', 'text' => '#1D4ED8', 'border' => '#DBEAFE']
                                ];
                                $c = $colorMap[$status] ?? ['bg' => '#F8FAFC', 'text' => '#64748B', 'border' => '#E2E8F0'];
                                ?>
                                <span class="badge rounded-pill small fw-bold" style="background-color: <?php echo $c['bg']; ?>; color: <?php echo $c['text']; ?>; border: 1px solid <?php echo $c['border']; ?>; padding: 5px 15px;">
                                    <?php echo ucwords($statusLabel); ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Status Server & Audit - Super Admin Only -->
    <?php if ($_SESSION['admin_role'] === 'super_admin'): ?>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 bg-dark text-white overflow-hidden">
            <div class="card-body p-4 position-relative">
                <i class="bi bi-shield-lock-fill position-absolute end-0 bottom-0 opacity-10 mb-n4 me-n2" style="font-size: 6rem;"></i>
                <h6 class="fw-bold text-uppercase small opacity-50 mb-3">Keamanan</h6>
                <p class="small mb-4">Sistem PPDB Sumbar menggunakan enkripsi standar industri untuk melindungi data personal siswa.</p>
                <a href="<?php echo url('/admin/logs'); ?>" class="btn btn-outline-light btn-sm rounded-pill px-4 fw-bold">
                    Audit Log
                </a>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<style>
.bento-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    grid-template-rows: repeat(2, 1fr);
    gap: 1.5rem;
    height: 480px;
}
.bento-item {
    border-radius: 1.5rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.bento-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
}
.bento-2x2 {
    grid-column: span 2;
    grid-row: span 2;
}
.bento-wide {
    grid-column: span 2;
}
@media (max-width: 992px) {
    .bento-grid {
        display: flex;
        flex-direction: column;
        height: auto;
    }
}
.pulse-green {
    width: 8px;
    height: 8px;
    background: #10B981;
    border-radius: 50%;
    display: inline-block;
    box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
    animation: pulse 2s infinite;
}
@keyframes pulse {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
    70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
}
</style>

<?php include ROOT_PATH . 'app/Views/admin/layouts/footer.php'; ?>

