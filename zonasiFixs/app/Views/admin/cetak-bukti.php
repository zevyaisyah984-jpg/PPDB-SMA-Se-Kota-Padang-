<?php 
$title = 'Cetak Bukti Penerimaan';
include ROOT_PATH . 'app/Views/admin/layouts/header.php'; 
?>

<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 bg-success text-white">
            <div class="card-body p-4 d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="fw-bold mb-1"><i class="bi bi-printer me-2"></i> Cetak Bukti Penerimaan</h4>
                    <p class="mb-0 opacity-75"><?php echo e($sekolah['nama']); ?> - Daftar siswa yang diterima</p>
                </div>
                <div class="bg-white text-success px-3 py-2 rounded-pill fw-bold">
                    <?php echo count($siswa); ?> Siswa Diterima
                </div>
            </div>
        </div>
    </div>
</div>

<?php if (empty($siswa)): ?>
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-5 text-center">
        <i class="bi bi-inbox fs-1 text-muted mb-3 d-block"></i>
        <h5 class="fw-bold">Belum Ada Siswa Diterima</h5>
        <p class="text-muted mb-0">Tunggu hingga proses seleksi selesai.</p>
    </div>
</div>
<?php else: ?>
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3">Nama Siswa</th>
                        <th class="px-4 py-3">NISN</th>
                        <th class="px-4 py-3">Jalur</th>
                        <th class="px-4 py-3">No Pendaftaran</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($siswa as $s): ?>
                    <tr>
                        <td class="px-4 py-3"><?php echo $no++; ?></td>
                        <td class="px-4 py-3">
                            <div class="fw-bold"><?php echo e($s['nama']); ?></div>
                            <small class="text-muted"><?php echo e($s['alamat'] ?? ''); ?></small>
                        </td>
                        <td class="px-4 py-3 font-monospace"><?php echo e($s['nisn']); ?></td>
                        <td class="px-4 py-3">
                            <?php 
                            $jalurColors = ['zonasi' => 'primary', 'afirmasi' => 'info', 'prestasi' => 'warning', 'mutasi' => 'danger'];
                            ?>
                            <span class="badge bg-<?php echo $jalurColors[$s['jalur']] ?? 'secondary'; ?>"><?php echo ucfirst($s['jalur']); ?></span>
                        </td>
                        <td class="px-4 py-3 font-monospace small"><?php echo e($s['no_pendaftaran'] ?? '-'); ?></td>
                        <td class="px-4 py-3 text-center">
                            <a href="<?php echo url('/admin/cetak-bukti/' . $s['id']); ?>" class="btn btn-outline-success btn-sm rounded-pill" target="_blank">
                                <i class="bi bi-printer me-1"></i> Cetak
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>

<?php include ROOT_PATH . 'app/Views/admin/layouts/footer.php'; ?>
