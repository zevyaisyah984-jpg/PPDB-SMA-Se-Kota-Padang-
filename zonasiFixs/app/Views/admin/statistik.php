<?php 
$title = 'Statistik Penerimaan';
include ROOT_PATH . 'app/Views/admin/layouts/header.php'; 
?>

<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 bg-primary text-white">
            <div class="card-body p-4">
                <h4 class="fw-bold mb-1"><i class="bi bi-bar-chart me-2"></i> Statistik Penerimaan</h4>
                <p class="mb-0 opacity-75"><?php echo e($sekolah['nama']); ?> - Tahun Ajaran <?php echo date('Y'); ?>/<?php echo date('Y')+1; ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Status Summary -->
<div class="row g-3 mb-4">
    <?php 
    $statusColors = ['pending' => 'secondary', 'verifikasi' => 'info', 'wait_verification' => 'info', 'diterima' => 'success', 'cadangan' => 'warning', 'ditolak' => 'danger'];
    $statusLabels = ['pending' => 'Menunggu', 'verifikasi' => 'Verifikasi', 'wait_verification' => 'Menunggu Verifikasi', 'diterima' => 'Diterima', 'cadangan' => 'Cadangan', 'ditolak' => 'Ditolak'];
    // Filter to standard statuses if needed, or mapping logic
    $displayStatuses = ['pending', 'verifikasi', 'diterima', 'cadangan', 'ditolak'];
    foreach ($displayStatuses as $status): 
        $count = $status_stats[$status] ?? 0;
    ?>
    <div class="col">
        <div class="card border-0 shadow-sm rounded-4 text-center py-3">
            <div class="card-body">
                <div class="display-5 fw-bold text-<?php echo $statusColors[$status]; ?>" id="status-<?php echo $status; ?>"><?php echo $count; ?></div>
                <div class="small text-muted fw-semibold"><?php echo $statusLabels[$status]; ?></div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Per Jalur Statistics -->
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-header bg-white border-0 p-4 d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0"><i class="bi bi-pie-chart me-2 text-primary"></i>Detail Per Jalur</h6>
        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 small fw-bold">
            <i class="bi bi-broadcast me-1"></i> Update: Real-time
        </span>
    </div>
    <div class="card-body p-4 pt-0">
        <?php foreach ($stats as $jalur => $info): 
            $percent = $info['kuota'] > 0 ? round(($info['diterima'] / $info['kuota']) * 100) : 0;
        ?>
        <div class="mb-4 pb-3 border-bottom">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div>
                    <span class="badge bg-<?php echo $info['color']; ?> me-2"><?php echo strtoupper(substr($jalur, 0, 3)); ?></span>
                    <span class="fw-bold"><?php echo $info['name']; ?></span>
                </div>
                <div class="text-end">
                    <span class="fw-bold text-<?php echo $info['color']; ?>" id="diterima-<?php echo $jalur; ?>"><?php echo $info['diterima']; ?></span>
                    <span class="text-muted">/ <?php echo $info['kuota']; ?> kuota</span>
                    <span class="badge bg-light text-dark ms-2"><span id="sisa-<?php echo $jalur; ?>"><?php echo $info['sisa']; ?></span> sisa</span>
                </div>
            </div>
            <div class="progress" style="height: 12px;">
                <div id="progress-<?php echo $jalur; ?>" class="progress-bar bg-<?php echo $info['color']; ?>" style="width: <?php echo min(100, $percent); ?>%">
                    <?php echo $percent; ?>%
                </div>
            </div>
            <div class="d-flex justify-content-between small text-muted mt-1">
                <span>Update otomatis setiap 30 detik</span>
                <span><span id="diterima-text-<?php echo $jalur; ?>"><?php echo $info['diterima']; ?></span> diterima</span>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Quick Actions -->
<div class="row g-3">
    <div class="col-md-4">
        <a href="<?php echo url('/admin/pendaftar'); ?>" class="card border-0 shadow-sm rounded-4 text-decoration-none h-100">
            <div class="card-body p-4 text-center">
                <i class="bi bi-people fs-1 text-primary mb-2"></i>
                <h6 class="fw-bold mb-0">Lihat Pendaftar</h6>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="<?php echo url('/admin/seleksi/hasil'); ?>" class="card border-0 shadow-sm rounded-4 text-decoration-none h-100">
            <div class="card-body p-4 text-center">
                <i class="bi bi-award fs-1 text-success mb-2"></i>
                <h6 class="fw-bold mb-0">Hasil Seleksi</h6>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="<?php echo url('/admin/cetak-bukti'); ?>" class="card border-0 shadow-sm rounded-4 text-decoration-none h-100">
            <div class="card-body p-4 text-center">
                <i class="bi bi-printer fs-1 text-warning mb-2"></i>
                <h6 class="fw-bold mb-0">Cetak Bukti</h6>
            </div>
        </a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function updateStats() {
        fetch('<?php echo url('/admin/api/stats'); ?>')
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    // Update Status Cards
                    for (const [status, count] of Object.entries(data.status_stats)) {
                        const el = document.getElementById(`status-${status}`);
                        if(el && el.innerText != count) {
                            el.innerText = count;
                            el.classList.add('text-opacity-50'); // visual feedback
                            setTimeout(() => el.classList.remove('text-opacity-50'), 500);
                        }
                    }

                    // Update Jalur Statistics
                    for (const [jalur, info] of Object.entries(data.jalur_stats)) {
                        // Update numbers
                        const diterimaEl = document.getElementById(`diterima-${jalur}`);
                        if(diterimaEl) diterimaEl.innerText = info.diterima;
                        
                        const diterimaTextEl = document.getElementById(`diterima-text-${jalur}`);
                        if(diterimaTextEl) diterimaTextEl.innerText = info.diterima;

                        const sisaEl = document.getElementById(`sisa-${jalur}`);
                        if(sisaEl) sisaEl.innerText = info.sisa;

                        // Update Progress Bar
                        const progressEl = document.getElementById(`progress-${jalur}`);
                        if(progressEl) {
                            progressEl.style.width = `${Math.min(100, info.percent)}%`;
                            progressEl.innerText = `${info.percent}%`;
                        }
                    }
                }
            })
            .catch(err => console.error('Failed to fetch stats:', err));
    }

    // Initial update after 30 seconds, then every 30s
    setInterval(updateStats, 30000);
});
</script>

<?php include ROOT_PATH . 'app/Views/admin/layouts/footer.php'; ?>
