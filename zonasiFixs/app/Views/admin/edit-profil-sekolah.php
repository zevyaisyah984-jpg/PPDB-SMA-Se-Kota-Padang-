<?php 
$title = 'Edit Profil Sekolah';
include ROOT_PATH . 'app/Views/admin/layouts/header.php'; 
?>

<div class="mb-4">
    <a href="<?php echo url('/admin/profil-sekolah'); ?>" class="text-muted text-decoration-none small">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Profil
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4"><i class="bi bi-pencil me-2 text-primary"></i>Edit Profil Sekolah</h5>
                
                <div class="alert alert-info border-0 rounded-3 mb-4">
                    <i class="bi bi-info-circle me-2"></i>
                    Anda dapat mengedit informasi kontak dan data kepala sekolah. Untuk perubahan data lainnya (nama, NPSN, kuota), hubungi Super Admin.
                </div>
                
                <form action="<?php echo url('/admin/profil-sekolah/update'); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    
                    <!-- Read-only info -->
                    <div class="mb-4 p-3 bg-light rounded-3">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label small text-muted">Nama Sekolah</label>
                                <p class="fw-bold mb-0"><?php echo e($sekolah['nama']); ?></p>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small text-muted">NPSN</label>
                                <p class="fw-bold mb-0"><?php echo e($sekolah['npsn']); ?></p>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small text-muted">Kuota</label>
                                <p class="fw-bold mb-0 text-primary"><?php echo $sekolah['kuota']; ?> siswa</p>
                            </div>
                        </div>
                    </div>

                    <h6 class="fw-bold mb-3 text-muted">Informasi Kontak</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nomor Telepon</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                <input type="text" name="telepon" class="form-control" value="<?php echo e($sekolah['telepon'] ?? ''); ?>" placeholder="0751-123456">
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Website</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-globe"></i></span>
                                <input type="url" name="website" class="form-control" value="<?php echo e($sekolah['website'] ?? ''); ?>" placeholder="https://sekolah.sch.id">
                            </div>
                        </div>
                    </div>

                    <h6 class="fw-bold mb-3 text-muted">Data Kepala Sekolah</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Kepala Sekolah</label>
                            <input type="text" name="kepala_sekolah" class="form-control" value="<?php echo e($sekolah['kepala_sekolah'] ?? ''); ?>" placeholder="Dr. Ahmad, M.Pd">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">NIP Kepala Sekolah</label>
                            <input type="text" name="nip_kepala_sekolah" class="form-control" value="<?php echo e($sekolah['nip_kepala_sekolah'] ?? ''); ?>" placeholder="196501011990031001">
                        </div>
                    </div>

                    <div class="d-flex gap-2 pt-3 border-top">
                        <button type="submit" class="btn btn-primary px-4 rounded-3">
                            <i class="bi bi-check-lg me-2"></i>Simpan Perubahan
                        </button>
                        <a href="<?php echo url('/admin/profil-sekolah'); ?>" class="btn btn-outline-secondary rounded-3">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include ROOT_PATH . 'app/Views/admin/layouts/footer.php'; ?>
