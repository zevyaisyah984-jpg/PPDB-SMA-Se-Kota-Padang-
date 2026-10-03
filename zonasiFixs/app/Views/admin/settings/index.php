<?php include ROOT_PATH . 'app/Views/admin/layouts/header.php'; ?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4"><i class="bi bi-gear-fill text-primary me-2"></i>Konfigurasi Global PPDB</h5>
                    
                    <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show rounded-4 mb-4" role="alert">
                        <i class="bi bi-check-circle me-2"></i><?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>

                    <form action="<?php echo url('/admin/settings/update'); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        
                        <!-- A. Manajemen Konfigurasi Global (System Engine) -->
                        <div class="mb-5">
                            <h6 class="fw-bold mb-3 border-bottom pb-2"><i class="bi bi-clock-history me-2"></i>Time Control (Jadwal Sistem)</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label small fw-semibold">Buka Pendaftaran</label>
                                    <input type="date" name="settings[tgl_mulai_pendaftaran]" class="form-control" value="<?php echo e($settings['tgl_mulai_pendaftaran'] ?? ''); ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label small fw-semibold">Tutup Pendaftaran</label>
                                    <input type="date" name="settings[tgl_selesai_pendaftaran]" class="form-control" value="<?php echo e($settings['tgl_selesai_pendaftaran'] ?? ''); ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label small fw-semibold">Mulai Verifikasi</label>
                                    <input type="date" name="settings[tgl_mulai_verifikasi]" class="form-control" value="<?php echo e($settings['tgl_mulai_verifikasi'] ?? ''); ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label small fw-semibold">Selesai Verifikasi</label>
                                    <input type="date" name="settings[tgl_selesai_verifikasi]" class="form-control" value="<?php echo e($settings['tgl_selesai_verifikasi'] ?? ''); ?>" required>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label small fw-semibold text-primary">Tanggal Pengumuman Serentak</label>
                                    <input type="date" name="settings[tgl_pengumuman]" class="form-control border-primary" value="<?php echo e($settings['tgl_pengumuman'] ?? ''); ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-5">
                            <h6 class="fw-bold mb-3 border-bottom pb-2"><i class="bi bi-shield-check me-2"></i>Policy Settings (Parameter Juknis)</h6>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Batas Usia Maksimal (SMP)</label>
                                <div class="input-group">
                                    <input type="number" name="settings[max_umur]" class="form-control" value="<?php echo e($settings['max_umur'] ?? '15'); ?>" required>
                                    <span class="input-group-text">Tahun</span>
                                </div>
                                <small class="text-muted">Batas usia per 1 Juli tahun berjalan.</small>
                            </div>
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label class="form-label small fw-semibold">Zonasi (%)</label>
                                    <input type="number" name="settings[quota_zonasi]" class="form-control" value="<?php echo e($settings['quota_zonasi'] ?? '50'); ?>" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label small fw-semibold">Afirmasi (%)</label>
                                    <input type="number" name="settings[quota_afirmasi]" class="form-control" value="<?php echo e($settings['quota_afirmasi'] ?? '15'); ?>" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label small fw-semibold">Prestasi (%)</label>
                                    <input type="number" name="settings[quota_prestasi]" class="form-control" value="<?php echo e($settings['quota_prestasi'] ?? '30'); ?>" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label small fw-semibold">Mutasi (%)</label>
                                    <input type="number" name="settings[quota_mutasi]" class="form-control" value="<?php echo e($settings['quota_mutasi'] ?? '5'); ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-5">
                            <h6 class="fw-bold mb-3 border-bottom pb-2"><i class="bi bi-headset me-2"></i>Helpdesk Center</h6>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">WhatsApp Contact Center</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-success text-white border-success"><i class="bi bi-whatsapp"></i></span>
                                    <input type="text" name="settings[helpdesk_wa]" class="form-control border-success" value="<?php echo e($settings['helpdesk_wa'] ?? ''); ?>" placeholder="Contoh: 628123456789" required>
                                </div>
                                <small class="text-muted">Nomor ini akan terhubung ke seluruh tombol bantuan di portal siswa.</small>
                            </div>
                        </div>

                        <div class="alert alert-warning rounded-4 border-0 d-flex align-items-start">
                            <i class="bi bi-exclamation-triangle-fill me-3 mt-1 fs-5"></i>
                            <div>
                                <h6 class="fw-bold mb-1 text-dark">Peringatan Penting!</h6>
                                <p class="small mb-0 text-muted">Perubahan parameter kuota dan jadwal akan berdampak langsung pada sistem perankingan otomatis dan akses pendaftaran siswa secara nasional.</p>
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary px-5 py-2 rounded-pill shadow">
                                <i class="bi bi-save me-2"></i>Simpan Seluruh Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 bg-primary text-white">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3"><i class="bi bi-shield-lock me-2"></i>Keamanan Pengaturan</h6>
                    <p class="small opacity-75 mb-0">Hanya Super Admin yang memiliki akses untuk mengubah konfigurasi global ini. Pastikan data yang dimasukkan sudah sesuai dengan Juknis PPDB terbaru.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include ROOT_PATH . 'app/Views/admin/layouts/footer.php'; ?>
