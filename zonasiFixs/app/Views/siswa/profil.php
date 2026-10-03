<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?> - PPDB SMA Padang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fbff; color: #334155; }
        .navbar { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); }
        .card { border: none; border-radius: 16px; transition: all 0.3s ease; }
        .nav-pills .nav-link { border-radius: 12px; padding: 12px 24px; color: #64748b; font-weight: 500; }
        .nav-pills .nav-link.active { background-color: #0d6efd; color: white; box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2); }
        .btn-primary { border-radius: 12px; padding: 12px 24px; font-weight: 600; box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2); }
        .form-control, .form-select { border-radius: 12px; padding: 12px 16px; border: 1px solid #e2e8f0; background-color: #f8fafc; }
        .form-control:focus { border-color: #0d6efd; box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1); }
        .touch-target { min-height: 48px; }
        .profile-photo { width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 4px solid #fff; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .profile-photo-placeholder { width: 120px; height: 120px; border-radius: 50%; background: linear-gradient(135deg, #0d6efd, #0056b3); display: flex; align-items: center; justify-content: center; font-size: 3rem; color: white; font-weight: bold; border: 4px solid #fff; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .photo-upload-btn { position: absolute; bottom: 0; right: 0; width: 36px; height: 36px; border-radius: 50%; background: #0d6efd; border: 3px solid #fff; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s; }
        .photo-upload-btn:hover { transform: scale(1.1); }
        .info-row { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #f1f5f9; }
        .info-row:last-child { border-bottom: none; }
        .section-divider { border-top: 2px solid #e2e8f0; margin: 2rem 0; }
    </style>
</head>
<body>

<?php view('layouts.navbar'); ?>

<div class="container py-5">
    <!-- Back Button -->
    <a href="<?php echo url('/dashboard'); ?>" class="btn btn-link text-decoration-none text-muted mb-4 p-0">
        <i class="bi bi-arrow-left me-2"></i>Kembali ke Dashboard
    </a>

    <div class="row g-4">
        <div class="col-lg-4">
            <!-- Profile Sidebar -->
            <div class="card shadow-sm p-4 text-center mb-4">
                <div class="position-relative d-inline-block mx-auto mb-3">
                    <?php if (!empty($siswa['foto']) && file_exists(ROOT_PATH . 'public/uploads/foto/' . $siswa['foto'])): ?>
                        <img src="<?php echo uploads('foto/' . $siswa['foto']); ?>" alt="Foto Profil" class="profile-photo">
                    <?php else: ?>
                        <div class="profile-photo-placeholder">
                            <?php echo strtoupper(substr($siswa['nama'] ?? $username, 0, 1)); ?>
                        </div>
                    <?php endif; ?>
                    <label for="photoUpload" class="photo-upload-btn">
                        <i class="bi bi-camera text-white small"></i>
                    </label>
                </div>
                <h4 class="fw-bold mb-1"><?php echo e($siswa['nama'] ?? $username); ?></h4>
                <p class="text-muted small mb-1"><?php echo e($siswa['nisn'] ?? '-'); ?></p>
                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 mb-4">Siswa Pendaftar PPDB</span>
                
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist">
                    <button class="nav-link active mb-2 text-start" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button" role="tab">
                        <i class="bi bi-person-fill me-2"></i>Data Diri
                    </button>
                    <button class="nav-link mb-2 text-start" id="v-pills-family-tab" data-bs-toggle="pill" data-bs-target="#v-pills-family" type="button" role="tab">
                        <i class="bi bi-people-fill me-2"></i>Data Keluarga
                    </button>
                    <button class="nav-link text-start" id="v-pills-security-tab" data-bs-toggle="pill" data-bs-target="#v-pills-security" type="button" role="tab">
                        <i class="bi bi-shield-lock-fill me-2"></i>Keamanan
                    </button>
                </div>
            </div>

            <!-- Stats Card -->
            <div class="card shadow-sm p-4 bg-light border-0">
                <h6 class="fw-bold mb-3">Informasi Akun</h6>
                <div class="info-row">
                    <span class="text-muted small">Status Akun</span>
                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">Aktif</span>
                </div>

                <div class="info-row">
                    <span class="text-muted small">Email</span>
                    <span class="fw-semibold small"><?php echo e($siswa['email'] ?? '-'); ?></span>
                </div>
                <div class="info-row">
                    <span class="text-muted small">No. HP</span>
                    <span class="fw-semibold small"><?php echo e($siswa['no_hp'] ?? '-'); ?></span>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <!-- Alerts -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center">
                    <i class="bi bi-check-circle-fill me-3 fs-4"></i>
                    <div><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill me-3 fs-4"></i>
                    <div><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                </div>
            <?php endif; ?>

            <div class="tab-content" id="v-pills-tabContent">
                <!-- Data Diri Tab -->
                <div class="tab-pane fade show active" id="v-pills-profile" role="tabpanel">
                    <div class="card shadow-sm p-4 p-md-5">
                        <!-- Photo Upload Form (Hidden) -->
                        <form action="<?php echo url('/siswa/foto/upload'); ?>" method="POST" enctype="multipart/form-data" id="photoForm">
                            <?php echo csrf_field(); ?>
                            <input type="file" name="foto" id="photoUpload" accept="image/*" class="d-none" onchange="document.getElementById('photoForm').submit();">
                        </form>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-bold mb-0">Informasi Pribadi</h5>
                            <span class="badge bg-info bg-opacity-10 text-info">
                                <i class="bi bi-info-circle me-1"></i> Lengkapi data Anda
                            </span>
                        </div>

                        <form action="<?php echo url('/siswa/profil/update'); ?>" method="POST" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <div class="row g-3">
                                <!-- Identitas -->
                                <div class="col-12">
                                    <h6 class="fw-semibold text-primary mb-3"><i class="bi bi-person-badge me-2"></i>Identitas</h6>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">NISN</label>
                                    <input type="text" class="form-control bg-light" value="<?php echo e($siswa['nisn'] ?? ''); ?>" disabled>
                                    <small class="text-muted">NISN tidak dapat diubah.</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">NIK <span class="text-danger">*</span></label>
                                    <input type="text" name="nik" class="form-control touch-target" value="<?php echo e($siswa['nik'] ?? ''); ?>" maxlength="16" placeholder="16 digit NIK">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" name="nama" class="form-control touch-target" value="<?php echo e($siswa['nama'] ?? $username); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">Jenis Kelamin <span class="text-danger">*</span></label>
                                    <select name="jk" class="form-select touch-target" required>
                                        <option value="">Pilih...</option>
                                        <option value="L" <?php echo ($siswa['jenis_kelamin'] ?? '') == 'L' ? 'selected' : ''; ?>>Laki-laki</option>
                                        <option value="P" <?php echo ($siswa['jenis_kelamin'] ?? '') == 'P' ? 'selected' : ''; ?>>Perempuan</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">Tempat Lahir <span class="text-danger">*</span></label>
                                    <input type="text" name="tempat_lahir" class="form-control touch-target" value="<?php echo e($siswa['tempat_lahir'] ?? ''); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">Tanggal Lahir <span class="text-danger">*</span></label>
                                    <input type="date" name="tanggal_lahir" class="form-control touch-target" value="<?php echo e($siswa['tanggal_lahir'] ?? ''); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">Agama</label>
                                    <select name="agama" class="form-select touch-target">
                                        <option value="">Pilih...</option>
                                        <option value="Islam" <?php echo ($siswa['agama'] ?? '') == 'Islam' ? 'selected' : ''; ?>>Islam</option>
                                        <option value="Kristen" <?php echo ($siswa['agama'] ?? '') == 'Kristen' ? 'selected' : ''; ?>>Kristen</option>
                                        <option value="Katolik" <?php echo ($siswa['agama'] ?? '') == 'Katolik' ? 'selected' : ''; ?>>Katolik</option>
                                        <option value="Hindu" <?php echo ($siswa['agama'] ?? '') == 'Hindu' ? 'selected' : ''; ?>>Hindu</option>
                                        <option value="Buddha" <?php echo ($siswa['agama'] ?? '') == 'Buddha' ? 'selected' : ''; ?>>Buddha</option>
                                        <option value="Konghucu" <?php echo ($siswa['agama'] ?? '') == 'Konghucu' ? 'selected' : ''; ?>>Konghucu</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">Sekolah Asal <span class="text-danger">*</span></label>
                                    <input type="text" name="sekolah_asal" class="form-control touch-target" value="<?php echo e($siswa['sekolah_asal'] ?? ''); ?>" placeholder="Nama SMP/MTs" required>
                                </div>

                                <!-- Kontak -->
                                <div class="col-12 mt-4">
                                    <h6 class="fw-semibold text-primary mb-3"><i class="bi bi-telephone me-2"></i>Kontak</h6>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">No. HP/WhatsApp</label>
                                    <input type="tel" name="no_hp" class="form-control touch-target" value="<?php echo e($siswa['no_hp'] ?? ''); ?>" placeholder="08xxxxxxxxxx">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">Email</label>
                                    <input type="email" name="email" class="form-control touch-target" value="<?php echo e($siswa['email'] ?? ''); ?>" placeholder="email@example.com">
                                </div>

                                <!-- Alamat -->
                                <div class="col-12 mt-4">
                                    <h6 class="fw-semibold text-primary mb-3"><i class="bi bi-geo-alt me-2"></i>Alamat</h6>
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-bold text-muted">Alamat Lengkap <span class="text-danger">*</span></label>
                                    <textarea name="alamat" class="form-control touch-target" rows="3" required placeholder="Jl. ..., RT/RW, Kelurahan"><?php echo e($siswa['alamat'] ?? ''); ?></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">Kecamatan <span class="text-danger">*</span></label>
                                    <select name="kecamatan" class="form-select touch-target" required>
                                        <option value="">Pilih...</option>
                                        <?php 
                                        $kecList = ['Bungus Teluk Kabung','Koto Tangah','Kuranji','Lubuk Begalung','Lubuk Kilangan','Nanggalo','Padang Barat','Padang Selatan','Padang Timur','Padang Utara','Pauh'];
                                        foreach ($kecList as $kec): ?>
                                        <option value="<?php echo $kec; ?>" <?php echo ($siswa['kecamatan'] ?? '') == $kec ? 'selected' : ''; ?>><?php echo $kec; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">Kode Pos</label>
                                    <input type="text" name="kode_pos" class="form-control touch-target" value="<?php echo e($siswa['kode_pos'] ?? ''); ?>" maxlength="5" placeholder="25xxx">
                                </div>

                                <!-- Nilai Rapor Section -->
                                <div class="col-12 mt-4">
                                    <h6 class="fw-semibold text-primary mb-3"><i class="bi bi-file-earmark-spreadsheet me-2"></i>Nilai Rapor Semester 1-5 (Rata-rata)</h6>
                                    <div class="p-3 rounded-4 bg-light border border-light-subtle">
                                        <div class="row g-3">
                                            <?php for($i=1; $i<=5; $i++): ?>
                                            <div class="col-6 col-md-2">
                                                <label class="form-label small fw-bold text-muted">Sem <?php echo $i; ?></label>
                                                <input type="number" step="0.01" name="nilai_sem<?php echo $i; ?>" class="form-control touch-target px-2 text-center" value="<?php echo e($siswa["nilai_sem$i"] ?? '0'); ?>" min="0" max="100">
                                            </div>
                                            <?php endfor; ?>
                                            <div class="col-12 col-md-2">
                                                <label class="form-label small fw-bold text-primary">Rata-rata</label>
                                                <div class="h-100 d-flex align-items-center">
                                                    <?php 
                                                    $total = 0;
                                                    for($i=1; $i<=5; $i++) $total += ($siswa["nilai_sem$i"] ?? 0);
                                                    $avg = $total > 0 ? round($total/5, 2) : 0;
                                                    ?>
                                                    <span class="fs-4 fw-bold text-primary"><?php echo $avg; ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Dokumen Section -->
                                <div class="col-12 mt-4">
                                    <h6 class="fw-semibold text-primary mb-3"><i class="bi bi-cloud-upload me-2"></i>Berkas Pendaftaran (Format PDF/JPG, Max 2MB)</h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold text-muted">Kartu Keluarga (KK)</label>
                                            <div class="input-group">
                                                <input type="file" name="file_kk" class="form-control touch-target" accept=".pdf,.jpg,.jpeg,.png">
                                                <?php if(!empty($siswa['file_kk'])): ?>
                                                    <a href="<?php echo asset('uploads/documents/'.$siswa['file_kk']); ?>" target="_blank" class="btn btn-outline-success border-start-0"><i class="bi bi-eye"></i></a>
                                                <?php endif; ?>
                                            </div>
                                            <?php if(!empty($siswa['file_kk'])): ?><small class="text-success fw-medium">Sudah diunggah</small><?php endif; ?>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold text-muted">Akta Kelahiran</label>
                                            <div class="input-group">
                                                <input type="file" name="file_akta" class="form-control touch-target" accept=".pdf,.jpg,.jpeg,.png">
                                                <?php if(!empty($siswa['file_akta'])): ?>
                                                    <a href="<?php echo asset('uploads/documents/'.$siswa['file_akta']); ?>" target="_blank" class="btn btn-outline-success border-start-0"><i class="bi bi-eye"></i></a>
                                                <?php endif; ?>
                                            </div>
                                            <?php if(!empty($siswa['file_akta'])): ?><small class="text-success fw-medium">Sudah diunggah</small><?php endif; ?>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold text-muted">Ijazah / SKL</label>
                                            <div class="input-group">
                                                <input type="file" name="file_ijazah" class="form-control touch-target" accept=".pdf,.jpg,.jpeg,.png">
                                                <?php if(!empty($siswa['file_ijazah'])): ?>
                                                    <a href="<?php echo asset('uploads/documents/'.$siswa['file_ijazah']); ?>" target="_blank" class="btn btn-outline-success border-start-0"><i class="bi bi-eye"></i></a>
                                                <?php endif; ?>
                                            </div>
                                            <?php if(!empty($siswa['file_ijazah'])): ?><small class="text-success fw-medium">Sudah diunggah</small><?php endif; ?>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold text-muted">Rapor Semester 1-5</label>
                                            <div class="input-group">
                                                <input type="file" name="file_rapor" class="form-control touch-target" accept=".pdf,.jpg,.jpeg,.png">
                                                <?php if(!empty($siswa['file_rapor'])): ?>
                                                    <a href="<?php echo asset('uploads/documents/'.$siswa['file_rapor']); ?>" target="_blank" class="btn btn-outline-success border-start-0"><i class="bi bi-eye"></i></a>
                                                <?php endif; ?>
                                            </div>
                                            <?php if(!empty($siswa['file_rapor'])): ?><small class="text-success fw-medium">Sudah diunggah</small><?php endif; ?>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold text-muted">Surat Keterangan Domisili (Opsional)</label>
                                            <div class="input-group">
                                                <input type="file" name="file_domisili" class="form-control touch-target" accept=".pdf,.jpg,.jpeg,.png">
                                                <?php if(!empty($siswa['file_domisili'])): ?>
                                                    <a href="<?php echo asset('uploads/documents/'.$siswa['file_domisili']); ?>" target="_blank" class="btn btn-outline-success border-start-0"><i class="bi bi-eye"></i></a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 mt-5">
                                    <button type="submit" class="btn btn-primary w-100 touch-target py-3 fs-5">
                                        <i class="bi bi-cloud-arrow-up-fill me-2"></i>Simpan Perubahan & Unggah Berkas
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Data Keluarga Tab -->
                <div class="tab-pane fade" id="v-pills-family" role="tabpanel">
                    <div class="card shadow-sm p-4 p-md-5">
                        <h5 class="fw-bold mb-4">Data Orang Tua/Wali</h5>
                        <form action="<?php echo url('/siswa/profil/update'); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="update_type" value="family">
                            
                            <!-- Ayah -->
                            <h6 class="fw-semibold text-primary mb-3"><i class="bi bi-person me-2"></i>Data Ayah</h6>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">Nama Ayah</label>
                                    <input type="text" name="nama_ayah" class="form-control touch-target" value="<?php echo e($siswa['nama_ayah'] ?? ''); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">Pekerjaan Ayah</label>
                                    <input type="text" name="pekerjaan_ayah" class="form-control touch-target" value="<?php echo e($siswa['pekerjaan_ayah'] ?? ''); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">No. HP Ayah</label>
                                    <input type="tel" name="no_hp_ayah" class="form-control touch-target" value="<?php echo e($siswa['no_hp_ayah'] ?? ''); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">Pendidikan Ayah</label>
                                    <select name="pendidikan_ayah" class="form-select touch-target">
                                        <option value="">Pilih...</option>
                                        <?php 
                                        $eduList = ['SD','SMP','SMA/SMK','D1','D2','D3','D4/S1','S2','S3'];
                                        foreach ($eduList as $edu): ?>
                                        <option value="<?php echo $edu; ?>" <?php echo ($siswa['pendidikan_ayah'] ?? '') == $edu ? 'selected' : ''; ?>><?php echo $edu; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Ibu -->
                            <h6 class="fw-semibold text-primary mb-3"><i class="bi bi-person me-2"></i>Data Ibu</h6>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">Nama Ibu</label>
                                    <input type="text" name="nama_ibu" class="form-control touch-target" value="<?php echo e($siswa['nama_ibu'] ?? ''); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">Pekerjaan Ibu</label>
                                    <input type="text" name="pekerjaan_ibu" class="form-control touch-target" value="<?php echo e($siswa['pekerjaan_ibu'] ?? ''); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">No. HP Ibu</label>
                                    <input type="tel" name="no_hp_ibu" class="form-control touch-target" value="<?php echo e($siswa['no_hp_ibu'] ?? ''); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">Pendidikan Ibu</label>
                                    <select name="pendidikan_ibu" class="form-select touch-target">
                                        <option value="">Pilih...</option>
                                        <?php foreach ($eduList as $edu): ?>
                                        <option value="<?php echo $edu; ?>" <?php echo ($siswa['pendidikan_ibu'] ?? '') == $edu ? 'selected' : ''; ?>><?php echo $edu; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Wali -->
                            <h6 class="fw-semibold text-primary mb-3"><i class="bi bi-people me-2"></i>Data Wali (Opsional)</h6>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">Nama Wali</label>
                                    <input type="text" name="nama_wali" class="form-control touch-target" value="<?php echo e($siswa['nama_wali'] ?? ''); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">Hubungan dengan Siswa</label>
                                    <input type="text" name="hubungan_wali" class="form-control touch-target" value="<?php echo e($siswa['hubungan_wali'] ?? ''); ?>" placeholder="Paman/Bibi/Kakek/dll">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">No. HP Wali</label>
                                    <input type="tel" name="no_hp_wali" class="form-control touch-target" value="<?php echo e($siswa['no_hp_wali'] ?? ''); ?>">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 touch-target">
                                <i class="bi bi-check-lg me-2"></i>Simpan Data Keluarga
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Keamanan Tab -->
                <div class="tab-pane fade" id="v-pills-security" role="tabpanel">
                    <div class="card shadow-sm p-4 p-md-5">
                        <h5 class="fw-bold mb-2">Keamanan Akun</h5>
                        <p class="text-muted small mb-4">Perbarui password Anda secara berkala untuk menjaga keamanan akun.</p>
                        
                        <form action="<?php echo url('/siswa/password/update'); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Password Saat Ini</label>
                                <input type="password" name="old_password" class="form-control touch-target" required>
                            </div>
                            <hr class="my-4 opacity-50">
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Password Baru</label>
                                <input type="password" name="new_password" class="form-control touch-target" required minlength="6">
                                <small class="text-muted">Minimal 6 karakter.</small>
                            </div>
                            <div class="mb-4">
                                <label class="form-label small fw-bold text-muted">Konfirmasi Password Baru</label>
                                <input type="password" name="confirm_password" class="form-control touch-target" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 touch-target">
                                <i class="bi bi-shield-check me-2"></i>Update Password
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
