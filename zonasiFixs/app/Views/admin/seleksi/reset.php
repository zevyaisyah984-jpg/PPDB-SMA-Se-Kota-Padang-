<?php 
$title = 'Reset Hasil Seleksi';
include ROOT_PATH . 'app/Views/admin/layouts/header.php'; 
?>

<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 bg-danger text-white">
            <div class="card-body p-4">
                <h4 class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-2"></i> Reset Hasil Seleksi</h4>
                <p class="mb-0 opacity-75">Halaman ini akan menghapus semua hasil seleksi dan mengembalikan status pendaftar ke "Pending".</p>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Before Reset -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 text-center py-4">
            <div class="card-body">
                <div class="display-4 fw-bold text-success"><?php echo $stats['diterima']; ?></div>
                <div class="text-muted small fw-semibold">Siswa Diterima</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 text-center py-4">
            <div class="card-body">
                <div class="display-4 fw-bold text-warning"><?php echo $stats['cadangan']; ?></div>
                <div class="text-muted small fw-semibold">Siswa Cadangan</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 text-center py-4">
            <div class="card-body">
                <div class="display-4 fw-bold text-danger"><?php echo $stats['ditolak']; ?></div>
                <div class="text-muted small fw-semibold">Siswa Ditolak</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 text-center py-4 bg-dark text-white">
            <div class="card-body">
                <div class="display-4 fw-bold"><?php echo $stats['total']; ?></div>
                <div class="small fw-semibold opacity-75">Total Terproses</div>
            </div>
        </div>
    </div>
</div>

<!-- Warning Card -->
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4">
        <div class="alert alert-danger border-0 rounded-4 mb-4">
            <div class="d-flex align-items-start">
                <i class="bi bi-exclamation-octagon-fill fs-3 me-3 mt-1"></i>
                <div>
                    <h5 class="fw-bold mb-2">Peringatan!</h5>
                    <p class="mb-2">Aksi ini akan:</p>
                    <ul class="mb-0">
                        <li>Menghapus <strong>semua hasil seleksi</strong> yang sudah diproses</li>
                        <li>Mengubah status semua pendaftar kembali ke <strong>"Pending"</strong></li>
                        <li>Menghapus peringkat (ranking) yang sudah ditetapkan</li>
                        <li><strong>Aksi ini tidak dapat dibatalkan!</strong></li>
                    </ul>
                </div>
            </div>
        </div>
        
        <?php if ($stats['total'] > 0): ?>
        <div class="text-center">
            <p class="text-muted mb-4">Untuk melanjutkan, ketik <strong>"RESET"</strong> di kolom di bawah ini:</p>
            
            <form action="<?php echo url('/admin/seleksi/reset'); ?>" method="POST" id="resetForm">
                <?php echo csrf_field(); ?>
                <div class="row justify-content-center">
                    <div class="col-md-4 mb-3">
                        <input type="text" id="confirmInput" class="form-control form-control-lg text-center" 
                               placeholder="Ketik RESET" autocomplete="off" required>
                    </div>
                </div>
                <div class="d-flex justify-content-center gap-3">
                    <a href="<?php echo url('/admin/seleksi/proses'); ?>" class="btn btn-secondary px-4 rounded-pill">
                        <i class="bi bi-x-lg me-2"></i>Batal
                    </a>
                    <button type="submit" id="resetBtn" class="btn btn-danger px-4 rounded-pill" disabled>
                        <i class="bi bi-trash me-2"></i>Reset Semua Hasil
                    </button>
                </div>
            </form>
        </div>
        <?php else: ?>
        <div class="text-center py-4">
            <i class="bi bi-check-circle-fill text-success fs-1 mb-3 d-block"></i>
            <h5 class="fw-bold">Tidak Ada Data Untuk Di-Reset</h5>
            <p class="text-muted mb-4">Belum ada hasil seleksi yang perlu dihapus.</p>
            <a href="<?php echo url('/admin/seleksi/proses'); ?>" class="btn btn-primary px-4 rounded-pill">
                <i class="bi bi-arrow-left me-2"></i>Kembali ke Proses Seleksi
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.getElementById('confirmInput').addEventListener('input', function() {
    const btn = document.getElementById('resetBtn');
    if (this.value.trim().toUpperCase() === 'RESET') {
        btn.disabled = false;
        btn.classList.remove('btn-danger');
        btn.classList.add('btn-danger');
    } else {
        btn.disabled = true;
    }
});

document.getElementById('resetForm').addEventListener('submit', function(e) {
    const confirmVal = document.getElementById('confirmInput').value.trim().toUpperCase();
    if (confirmVal !== 'RESET') {
        e.preventDefault();
        alert('Ketik RESET untuk konfirmasi!');
    }
});
</script>

<?php include ROOT_PATH . 'app/Views/admin/layouts/footer.php'; ?>
