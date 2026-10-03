<?php 
$title = 'Profil Sekolah';
include ROOT_PATH . 'app/Views/admin/layouts/header.php'; 
?>

<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 bg-primary text-white">
            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="fw-bold mb-1"><i class="bi bi-building-fill me-2"></i> <?php echo e($sekolah['nama']); ?></h4>
                    <p class="mb-0 opacity-75">NPSN: <?php echo e($sekolah['npsn']); ?> | <?php echo e($sekolah['alamat'] ?? '-'); ?></p>
                </div>
                <a href="<?php echo url('/admin/profil-sekolah/edit'); ?>" class="btn btn-light text-primary fw-bold rounded-pill px-4">
                    <i class="bi bi-pencil me-2"></i>Edit Profil
                </a>
            </div>
        </div>
    </div>
</div>

<?php if (isset($_SESSION['success'])): ?>
<div class="alert alert-success alert-dismissible fade show mb-4 rounded-4 border-0" role="alert">
    <i class="bi bi-check-circle me-2"></i><?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="row g-4">
    <!-- School Info Card -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-0 p-4 pb-0">
                <h6 class="fw-bold mb-0"><i class="bi bi-info-circle me-2 text-primary"></i>Informasi Sekolah</h6>
            </div>
            <div class="card-body p-4">
                <table class="table table-borderless">
                    <tr>
                        <td class="text-muted" style="width:40%">Nama Sekolah</td>
                        <td class="fw-semibold"><?php echo e($sekolah['nama']); ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">NPSN</td>
                        <td class="fw-semibold"><?php echo e($sekolah['npsn']); ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Alamat</td>
                        <td><?php echo e($sekolah['alamat'] ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Kecamatan</td>
                        <td><?php echo e($sekolah['kecamatan'] ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Akreditasi</td>
                        <td><span class="badge bg-success"><?php echo e($sekolah['akreditasi'] ?? 'A'); ?></span></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Total Kuota</td>
                        <td class="fw-bold text-primary fs-5"><?php echo $sekolah['kuota'] ?? 0; ?> siswa</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Contact Card -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-0 p-4 pb-0">
                <h6 class="fw-bold mb-0"><i class="bi bi-telephone me-2 text-primary"></i>Kontak & Pejabat</h6>
            </div>
            <div class="card-body p-4">
                <table class="table table-borderless">
                    <tr>
                        <td class="text-muted" style="width:40%">Telepon</td>
                        <td><?php echo e($sekolah['telepon'] ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Website</td>
                        <td>
                            <?php if (!empty($sekolah['website'])): ?>
                            <a href="<?php echo e($sekolah['website']); ?>" target="_blank"><?php echo e($sekolah['website']); ?></a>
                            <?php else: ?>
                            -
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Kepala Sekolah</td>
                        <td class="fw-semibold"><?php echo e($sekolah['kepala_sekolah'] ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">NIP Kepsek</td>
                        <td><?php echo e($sekolah['nip_kepala_sekolah'] ?? '-'); ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Quota Per Jalur -->
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-0 p-4 pb-0">
                <h6 class="fw-bold mb-0"><i class="bi bi-pie-chart me-2 text-primary"></i>Kuota & Pendaftar Per Jalur</h6>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <?php 
                    $jalurInfo = [
                        'zonasi' => ['name' => 'Zonasi', 'color' => 'primary', 'quota' => $sekolah['kuota_domisili'] ?? 0],
                        'afirmasi' => ['name' => 'Afirmasi', 'color' => 'info', 'quota' => $sekolah['kuota_afirmasi'] ?? 0],
                        'prestasi' => ['name' => 'Prestasi', 'color' => 'warning', 'quota' => ($sekolah['kuota_prestasi_akademik'] ?? 0) + ($sekolah['kuota_prestasi_nonakademik'] ?? 0)],
                        'mutasi' => ['name' => 'Mutasi', 'color' => 'danger', 'quota' => $sekolah['kuota_mutasi'] ?? 0]
                    ];
                    foreach ($jalurInfo as $key => $info):
                        $pendaftar = $stats[$key] ?? 0;
                        $percent = $info['quota'] > 0 ? round(($pendaftar / $info['quota']) * 100) : 0;
                    ?>
                    <div class="col-md-3 col-6">
                        <div class="border rounded-3 p-3 text-center">
                            <div class="fw-bold text-<?php echo $info['color']; ?>"><?php echo $info['name']; ?></div>
                            <div class="display-6 fw-bold mt-2"><?php echo $pendaftar; ?><span class="fs-6 text-muted">/<?php echo $info['quota']; ?></span></div>
                            <div class="progress mt-2" style="height: 6px;">
                                <div class="progress-bar bg-<?php echo $info['color']; ?>" style="width: <?php echo min(100, $percent); ?>%"></div>
                            </div>
                            <small class="text-muted"><?php echo $percent; ?>% terisi</small>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include ROOT_PATH . 'app/Views/admin/layouts/footer.php'; ?>
