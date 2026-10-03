<?php 
$title = 'Pengaturan Kuota Jalur';
include ROOT_PATH . 'app/Views/admin/layouts/header.php'; 
?>

<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 bg-primary text-white">
            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="fw-bold mb-1"><i class="bi bi-percent me-2"></i> Pengaturan Kuota Jalur PPDB</h4>
                    <p class="mb-0 opacity-75">Kelola distribusi persentase dan jumlah kursi untuk setiap jalur pendaftaran.</p>
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

<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4">
        <label class="fw-bold mb-2">Pilih Sekolah:</label>
        <form action="" method="GET">
            <select name="id_sekolah" class="form-select form-select-lg rounded-3" onchange="this.form.submit()">
                <?php foreach ($sekolah_list as $s): ?>
                    <option value="<?php echo $s['id']; ?>" <?php echo (isset($_GET['id_sekolah']) && $_GET['id_sekolah'] == $s['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($s['nama']); ?> (Kuota: <?php echo $s['kuota']; ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>
</div>

<?php if ($selected_sekolah): ?>
<form action="<?php echo url('/admin/kuota/update'); ?>" method="POST">
    <input type="hidden" name="id_sekolah" value="<?php echo $selected_sekolah['id']; ?>">
    
    <div class="row g-4">
        <!-- School Info -->
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5 class="fw-bold mb-1"><?php echo htmlspecialchars($selected_sekolah['nama']); ?></h5>
                            <div class="text-muted small mb-1">NPSN: <?php echo htmlspecialchars($selected_sekolah['npsn']); ?></div>
                            <div class="text-muted small"><i class="bi bi-geo-alt me-1"></i> <?php echo htmlspecialchars($selected_sekolah['alamat']); ?></div>
                        </div>
                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            <label class="small text-muted fw-bold d-block mb-1">Kuota Total</label>
                            <div class="input-group">
                                <input type="number" name="total_kuota" value="<?php echo $selected_sekolah['kuota']; ?>" class="form-control form-control-lg fw-bold text-center" min="0">
                                <span class="input-group-text bg-primary text-white"><i class="bi bi-lock-fill"></i></span>
                            </div>
                            <div class="small text-muted mt-1">Total kursi tersedia</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Distributions -->
        <div class="col-12">
            <h6 class="fw-bold mb-3 ms-1 text-muted text-uppercase small">Distribusi Kuota per Jalur:</h6>
            
            <?php 
            $total = $selected_sekolah['kuota'];
            $jalur = [
                [
                    'code' => 'ZON', 'label' => 'Jalur Zonasi', 'color' => 'primary', 
                    'desc' => 'Berdasarkan jarak terdekat (Min. 50% - Permendikbud)',
                    'field' => 'kuota_domisili', 'value' => $selected_sekolah['kuota_domisili']
                ],
                [
                    'code' => 'AFI', 'label' => 'Jalur Afirmasi', 'color' => 'danger', 
                    'desc' => 'Keluarga tidak mampu & Penyandang Disabilitas (Min. 15%)',
                    'field' => 'kuota_afirmasi', 'value' => $selected_sekolah['kuota_afirmasi']
                ],
                [
                    'code' => 'PRA', 'label' => 'Jalur Prestasi Akademik', 'color' => 'success', 
                    'desc' => 'Berdasarkan nilai rapor tertinggi',
                    'field' => 'kuota_prestasi_akademik', 'value' => $selected_sekolah['kuota_prestasi_akademik']
                ],
                [
                    'code' => 'PRN', 'label' => 'Jalur Prestasi Non-Akademik', 'color' => 'info', 
                    'desc' => 'Berdasarkan sertifikat kejuaraan/lomba',
                    'field' => 'kuota_prestasi_nonakademik', 'value' => $selected_sekolah['kuota_prestasi_nonakademik']
                ],
                [
                    'code' => 'MUT', 'label' => 'Jalur Mutasi', 'color' => 'warning', 
                    'desc' => 'Perpindahan tugas orang tua/wali (Max. 5%)',
                    'field' => 'kuota_mutasi', 'value' => $selected_sekolah['kuota_mutasi']
                ]
            ];
            ?>

            <?php foreach ($jalur as $j): ?>
            <?php $percent = $total > 0 ? round(($j['value'] / $total) * 100, 1) : 0; ?>
            <div class="card border-0 shadow-sm rounded-4 mb-3">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-1 text-center">
                            <div class="badge-soft badge-soft-<?php echo $j['color']; ?> rounded-3 fs-5 p-3 w-100 fw-bold">
                                <?php echo $j['code']; ?>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <h6 class="fw-bold mb-1"><?php echo $j['label']; ?></h6>
                            <div class="text-muted small mb-2"><?php echo $j['desc']; ?></div>
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar bg-<?php echo $j['color']; ?> fw-bold" role="progressbar" style="width: <?php echo $percent; ?>%">
                                    <?php echo $percent; ?>%
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                             <input type="number" name="<?php echo $j['field']; ?>" value="<?php echo $j['value']; ?>" class="form-control form-control-lg text-center" min="0">
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>

            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold shadow-sm">
                    <i class="bi bi-save me-2"></i> Simpan Perubahan
                </button>
            </div>

        </div>
    </div>
</form>
<?php endif; ?>

<?php include ROOT_PATH . 'app/Views/admin/layouts/footer.php'; ?>
