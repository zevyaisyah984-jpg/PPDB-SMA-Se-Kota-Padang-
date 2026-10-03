<?php
// app/Views/admin/verifikasi_queue.php
?>
<div class="row g-4">
    <?php if(empty($pending_list)): ?>
    <div class="col-12">
        <div class="text-center py-5 bg-white rounded-4 shadow-sm border border-dashed">
            <i class="bi bi-clipboard-check display-1 text-light"></i>
            <h4 class="mt-3 text-secondary">Tidak ada antrean verifikasi</h4>
            <p class="text-muted">Semua pendaftaran telah diproses.</p>
        </div>
    </div>
    <?php endif; ?>

    <?php foreach ($pending_list as $p): ?>
    <div class="col-md-6 col-xl-4">
        <div class="bento-card bg-white h-100 p-4 border rounded-4 shadow-sm position-relative overflow-hidden group">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <span class="badge bg-warning bg-opacity-10 text-warning mb-2 border border-warning border-opacity-25 rounded-pill px-3">
                        <i class="bi bi-clock-history me-1"></i> PENDING
                    </span>
                    <h5 class="fw-bold mb-1 text-dark"><?php echo e($p['nama_siswa']); ?></h5>
                    <small class="text-secondary font-monospace bg-light px-2 py-1 rounded">
                        <?php echo e($p['nisn']); ?>
                    </small>
                </div>
                <?php if($p['jarak_km'] > 0): ?>
                    <div class="text-end">
                        <div class="fs-4 fw-bold text-primary"><?php echo e($p['jarak_km']); ?><span class="fs-6 text-muted ms-1">km</span></div>
                        <small class="text-muted d-block fw-medium" style="font-size:10px;">Jarak Domisili</small>
                    </div>
                <?php endif; ?>
            </div>
            
            <hr class="my-3 border-light">
            
            <div class="row g-2 mb-4">
                <div class="col-6">
                    <small class="text-muted d-block text-uppercase fw-bold" style="font-size:10px;">Jalur</small>
                    <span class="fw-bold text-dark"><?php echo ucfirst($p['jalur']); ?></span>
                </div>
                <div class="col-6">
                    <small class="text-muted d-block text-uppercase fw-bold" style="font-size:10px;">Tanggal Daftar</small>
                    <span class="fw-medium text-dark"><?php echo date('d M H:i', strtotime($p['created_at'])); ?></span>
                </div>
            </div>
            
            <div class="d-grid">
                <a href="<?php echo url('/admin/verifikasi/' . $p['id']); ?>" class="btn btn-primary rounded-pill fw-bold btn-sm-hover-shadow stretched-link">
                    <i class="bi bi-file-earmark-check me-2"></i> Verifikasi Berkas
                </a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<style>
.bento-card { transition: all 0.2s ease; }
.bento-card:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important; border-color: var(--primary) !important; }
</style>
