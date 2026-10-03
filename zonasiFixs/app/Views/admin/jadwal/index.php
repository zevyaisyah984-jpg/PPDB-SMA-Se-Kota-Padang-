<?php 
$title = 'Jadwal Jalur PPDB';
include ROOT_PATH . 'app/Views/admin/layouts/header.php'; 

// Helper function to map DB status to Badge & Label
function getStatusBadge($status) {
    if ($status == 'akan_datang' || $status == 'belum_dibuka') return '<span class="badge bg-secondary"><i class="bi bi-lock-fill me-1"></i> Belum Dibuka</span>';
    if ($status == 'berlangsung' || $status == 'dibuka' || $status == 'seleksi' || $status == 'pengumuman') return '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Dibuka / Berlangsung</span>';
    if ($status == 'selesai' || $status == 'ditutup') return '<span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i> Ditutup</span>';
    return '<span class="badge bg-secondary">Unknown</span>';
}
?>

<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 bg-primary text-white">
            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="fw-bold mb-1"><i class="bi bi-calendar-check me-2"></i> Jadwal & Pengaturan PPDB</h4>
                    <p class="mb-0 opacity-75">Kelola tanggal pendaftaran, status jalur, dan konfigurasi global sistem.</p>
                </div>
                <div class="d-flex gap-2 align-items-center">
                    <a href="<?php echo url('/admin/settings'); ?>" class="btn btn-light btn-sm text-primary fw-bold rounded-pill px-3">
                        <i class="bi bi-gear me-1"></i> Konfigurasi Global
                    </a>
                    <div class="bg-white text-primary px-3 py-2 rounded-pill fw-bold small">
                        <?php echo date('Y') . '/' . (date('Y')+1); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show mb-4 rounded-4 shadow-sm border-0" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['warning'])): ?>
    <div class="alert alert-warning alert-dismissible fade show mb-4 rounded-4 shadow-sm border-0" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo $_SESSION['warning']; unset($_SESSION['warning']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- STAGE BASED CONTROLS (Juknis 2025) -->
<div class="row g-4 mb-4">
    <?php for($i=1; $i<=2; $i++): ?>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0 text-primary">KONTROL TAHAP <?php echo $i; ?></h6>
                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3">
                        <?php echo ($i == 1) ? 'Afirmasi, Prestasi, Mutasi' : 'Jalur Zonasi'; ?>
                    </span>
                </div>
                <form action="<?php echo url('/admin/jadwal/update-status'); ?>" method="POST" class="d-flex gap-2">
                    <input type="hidden" name="tahap" value="<?php echo $i; ?>">
                    <select name="status" class="form-select form-select-sm rounded-3">
                        <option value="dibuka">✅ Dibuka</option>
                        <option value="ditutup">⛔ Ditutup</option>
                    </select>
                    <button type="submit" class="btn btn-primary btn-sm px-3 fw-bold rounded-3">Update</button>
                </form>
            </div>
        </div>
    </div>
    <?php endfor; ?>
</div>

<!-- LIST JALUR GROUPED BY TAHAP -->
<?php 
// Group schedules by tahap
$groupedJadwal = [];
foreach ($jadwal as $j) {
    if ($j['jalur'] == 'semua') continue;
    $groupedJadwal[$j['tahap']][] = $j;
}
?>

<?php foreach ($groupedJadwal as $tahapIdx => $items): ?>
<div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
    <div class="card-header bg-primary bg-opacity-10 border-0 p-4">
         <h5 class="mb-0 fw-bold text-primary">
            <i class="bi bi-stack me-2"></i> TAHAP <?php echo $tahapIdx; ?> 
            <small class="text-muted fw-normal ms-2" style="font-size: 0.9rem;">
                (<?php echo ($tahapIdx == 1) ? 'Jalur Prestasi, Afirmasi & Perpindahan' : 'Jalur Zonasi Umum'; ?>)
            </small>
         </h5>
    </div>
    <div class="card-body p-0">
        <div class="list-group list-group-flush">
            <?php foreach ($items as $j): ?>
                <div class="list-group-item p-4">
                    <form action="<?php echo url('/admin/jadwal/update-jalur'); ?>" method="POST">
                        <input type="hidden" name="id" value="<?php echo $j['id']; ?>">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <h6 class="fw-bold mb-1">
                                    <?php echo htmlspecialchars($j['nama_kegiatan']); ?> 
                                    <span class="badge bg-white border text-dark ms-1"><?php echo strtoupper($j['jalur']); ?></span>
                                    <!-- Real-time status badge -->
                                    <?php 
                                        $statusBadgeClass = ($j['status'] == 'berlangsung') ? 'bg-success' : 'bg-danger';
                                        $statusBadgeText = ($j['status'] == 'berlangsung') ? '✅ Dibuka' : '⛔ Ditutup';
                                    ?>
                                    <span class="badge <?php echo $statusBadgeClass; ?> ms-2"><?php echo $statusBadgeText; ?></span>
                                </h6>
                                <div class="small text-muted">
                                    <i class="bi bi-calendar3 me-1"></i>
                                    <?php echo date('d M Y', strtotime($j['tanggal_mulai'])); ?> - <?php echo date('d M Y', strtotime($j['tanggal_selesai'])); ?>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <?php 
                                    $currentStatus = $j['status']; // 'berlangsung' or 'selesai'
                                    $selectedStatus = ($currentStatus == 'berlangsung') ? 'dibuka' : 'ditutup';
                                    $borderColor = ($currentStatus == 'berlangsung') ? 'border-success text-success' : 'border-danger text-danger';
                                ?>
                                <select name="status" class="form-select form-select-sm status-select <?php echo $borderColor; ?> fw-bold rounded-pill px-3">
                                    <option value="dibuka" <?php echo $selectedStatus == 'dibuka' ? 'selected' : ''; ?>>✅ Dibuka</option>
                                    <option value="ditutup" <?php echo $selectedStatus == 'ditutup' ? 'selected' : ''; ?>>⛔ Ditutup</option>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-center gap-2 px-lg-4">
                                <label class="small text-muted fw-bold">Prioritas:</label>
                                <input type="number" name="urutan" class="form-control form-control-sm rounded-pill text-center" value="<?php echo $j['urutan']; ?>" style="width: 60px;">
                            </div>
                            <div class="col-md-2 text-end">
                                <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4 fw-bold">
                                    Update
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endforeach; ?>

<?php include ROOT_PATH . 'app/Views/admin/layouts/footer.php'; ?>
