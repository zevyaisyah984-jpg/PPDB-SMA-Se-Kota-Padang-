<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Perpindahan - <?php echo APP_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo asset('css/style.css'); ?>">
    <style>
        :root { --theme-color: #dc3545; --theme-light: #f8d7da; --theme-dark: #b02a37; }
        .form-section { background: white; border-radius: 16px; padding: 2rem; margin-bottom: 1.5rem; box-shadow: 0 2px 10px rgba(0,0,0,0.05); display: none; }
        .form-section.active { display: block; animation: fadeIn 0.3s; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        .step-circle { width: 40px; height: 40px; border: 2px solid #e2e8f0; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; background: white; color: #94a3b8; z-index: 2; }
        .step-circle.active { border-color: var(--theme-color); color: var(--theme-dark); background: var(--theme-light); }
        .step-circle.completed { background: var(--theme-color); color: white; border-color: var(--theme-color); }
        .school-card { border: 2px solid #e2e8f0; border-radius: 12px; padding: 1rem; cursor: pointer; transition: all 0.2s; }
        .school-card:hover, .school-card.selected { border-color: var(--theme-color); background: #fff5f5; }
        .kategori-card { border: 2px solid #e2e8f0; border-radius: 12px; padding: 1.5rem; cursor: pointer; transition: all 0.2s; text-align: center; }
        .kategori-card:hover, .kategori-card.selected { border-color: var(--theme-color); background: var(--theme-light); }
        .kategori-card .icon { font-size: 2.5rem; margin-bottom: 0.5rem; }
        .info-box { background: var(--theme-light); border-left: 4px solid var(--theme-color); padding: 1rem; border-radius: 0 8px 8px 0; margin-bottom: 1.5rem; }
        .btn-danger-custom { background: var(--theme-color); border-color: var(--theme-color); color: white; }
        .btn-danger-custom:hover { background: var(--theme-dark); border-color: var(--theme-dark); color: white; }
        .btn-outline-danger-custom { border-color: var(--theme-color); color: var(--theme-dark); }
        .btn-outline-danger-custom:hover { background: var(--theme-color); color: white; }
    </style>
</head>
<body class="bg-light">

<nav class="navbar sticky-top bg-white border-bottom">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?php echo url('/dashboard'); ?>">
            <i class="bi bi-arrow-left me-2"></i>Kembali ke Dashboard
        </a>
    </div>
</nav>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="text-center mb-5">
                <span class="badge px-3 py-2 rounded-pill mb-3" style="background: var(--theme-light); color: var(--theme-dark);">JALUR PERPINDAHAN</span>
                <h2 class="fw-bold">Formulir Pendaftaran</h2>
                <p class="text-muted">Jalur untuk siswa yang orang tua/walinya mengalami perpindahan tugas</p>
            </div>

            <!-- Info Box -->
            <div class="info-box">
                <h6 class="fw-bold mb-2"><i class="bi bi-arrow-left-right me-2"></i>Ketentuan Jalur Perpindahan</h6>
                <ul class="mb-0 small">
                    <li>Orang tua/wali <strong>dipindahtugaskan</strong> ke wilayah Kota Padang</li>
                    <li>Memiliki <strong>Surat Keputusan (SK) Mutasi</strong> dari instansi terkait</li>
                    <li>Siswa berasal dari <strong>luar Kota Padang</strong></li>
                    <li>Kuota jalur ini adalah <strong>5%</strong> dari total daya tampung</li>
                </ul>
            </div>

            <!-- Step Indicator -->
            <div class="d-flex justify-content-between mb-5 position-relative">
                <div class="position-absolute top-50 start-0 end-0" style="height: 2px; background: #e2e8f0; z-index: 1;"></div>
                <div class="d-flex flex-column align-items-center" style="z-index: 2;">
                    <div class="step-circle active" id="sc1">1</div>
                    <small class="mt-2 fw-semibold">Biodata</small>
                </div>
                <div class="d-flex flex-column align-items-center" style="z-index: 2;">
                    <div class="step-circle" id="sc2">2</div>
                    <small class="mt-2">Orang Tua</small>
                </div>
                <div class="d-flex flex-column align-items-center" style="z-index: 2;">
                    <div class="step-circle" id="sc3">3</div>
                    <small class="mt-2">Perpindahan</small>
                </div>
                <div class="d-flex flex-column align-items-center" style="z-index: 2;">
                    <div class="step-circle" id="sc4">4</div>
                    <small class="mt-2">Sekolah</small>
                </div>
                <div class="d-flex flex-column align-items-center" style="z-index: 2;">
                    <div class="step-circle" id="sc5">5</div>
                    <small class="mt-2">Berkas</small>
                </div>
            </div>

            <!-- Error Message -->
            <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show rounded-4 mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i><strong>Kesalahan!</strong> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>

            <div id="js-error" class="alert alert-danger rounded-4 mb-4 d-none" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i><strong>Perhatian!</strong> <span id="js-error-msg"></span>
            </div>

            <form action="<?php echo url('/daftar/mutasi'); ?>" method="POST" enctype="multipart/form-data" id="pendaftaranForm">

                <?php echo csrf_field(); ?>
                <input type="hidden" name="jalur" value="mutasi">
                
                <!-- Step 1: Biodata Siswa -->
                <div class="form-section active" id="step1">
                    <h5 class="fw-bold mb-4"><i class="bi bi-person-vcard text-danger me-2"></i>Data Identitas Siswa</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">NISN <span class="text-danger">*</span></label>
                            <input type="text" name="nisn" class="form-control" maxlength="10" required placeholder="Masukkan 10 digit NISN">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control" required placeholder="Sesuai ijazah/rapor">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">NIK <span class="text-danger">*</span></label>
                            <input type="text" name="nik" class="form-control" maxlength="16" required placeholder="Masukkan 16 digit NIK">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Sekolah Asal <span class="text-danger">*</span></label>
                            <input type="text" name="sekolah_asal" class="form-control" required placeholder="Nama SMP/MTs asal (luar Kota Padang)">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Tempat Lahir <span class="text-danger">*</span></label>
                            <input type="text" name="tempat_lahir" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Tanggal Lahir <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control" required onchange="checkAge()">
                            <div id="ageWarning" class="alert alert-warning mt-2 py-2 px-3 small d-none">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <span id="ageWarningText"></span>
                            </div>
                            <div id="ageInfo" class="text-muted small mt-1 d-none">
                                <i class="bi bi-info-circle me-1"></i> Usia: <strong id="ageDisplay"></strong>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select name="jk" class="form-select" required>
                                <option value="">Pilih...</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">No. Kartu Keluarga <span class="text-danger">*</span></label>
                            <input type="text" name="no_kk" class="form-control" maxlength="16" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tanggal Terbit KK <span class="text-danger">*</span></label>
                            <input type="date" name="tgl_kk" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">No. HP Siswa</label>
                            <input type="tel" name="no_hp" class="form-control" placeholder="08xxxxxxxxxx">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="email@example.com">
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-4">
                        <button type="button" class="btn btn-danger-custom px-4" onclick="nextStep(1)">Lanjutkan <i class="bi bi-chevron-right"></i></button>
                    </div>
                </div>

                <!-- Step 2: Data Orang Tua -->
                <div class="form-section" id="step2">
                    <h5 class="fw-bold mb-4"><i class="bi bi-people text-danger me-2"></i>Data Orang Tua/Wali</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Ayah <span class="text-danger">*</span></label>
                            <input type="text" name="nama_ayah" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">NIK Ayah <span class="text-danger">*</span></label>
                            <input type="text" name="nik_ayah" class="form-control" maxlength="16" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Pekerjaan Ayah <span class="text-danger">*</span></label>
                            <select name="pekerjaan_ayah" class="form-select" required>
                                <option value="">Pilih...</option>
                                <option value="pns">PNS</option>
                                <option value="tni">TNI</option>
                                <option value="polri">POLRI</option>
                                <option value="bumn">Pegawai BUMN</option>
                                <option value="swasta">Pegawai Swasta</option>
                                <option value="wiraswasta">Wiraswasta</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Instansi/Perusahaan <span class="text-danger">*</span></label>
                            <input type="text" name="instansi_ayah" class="form-control" required placeholder="Nama instansi tempat bekerja">
                        </div>
                        <div class="col-12"><hr class="my-2"></div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Ibu <span class="text-danger">*</span></label>
                            <input type="text" name="nama_ibu" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">NIK Ibu</label>
                            <input type="text" name="nik_ibu" class="form-control" maxlength="16">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">No. HP Orang Tua <span class="text-danger">*</span></label>
                            <input type="tel" name="no_hp_ortu" class="form-control" required placeholder="08xxxxxxxxxx">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">No. HP Alternatif</label>
                            <input type="tel" name="no_hp_alternatif" class="form-control" placeholder="08xxxxxxxxxx">
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn btn-outline-secondary px-4" onclick="prevStep(2)"><i class="bi bi-chevron-left"></i> Kembali</button>
                        <button type="button" class="btn btn-danger-custom px-4" onclick="nextStep(2)">Lanjutkan <i class="bi bi-chevron-right"></i></button>
                    </div>
                </div>

                <!-- Step 3: Data Perpindahan -->
                <div class="form-section" id="step3">
                    <h5 class="fw-bold mb-4"><i class="bi bi-signpost-2 text-danger me-2"></i>Detail Perpindahan</h5>
                    
                    <div class="alert alert-warning mb-4">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Penting:</strong> Pastikan data perpindahan sesuai dengan Surat Keputusan (SK) Mutasi
                    </div>

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Alasan Perpindahan <span class="text-danger">*</span></label>
                            <select name="alasan_pindah" class="form-select" required>
                                <option value="">Pilih alasan...</option>
                                <option value="mutasi_kerja">Mutasi/Pindah Tugas Orang Tua</option>
                                <option value="promosi">Promosi Jabatan</option>
                                <option value="penugasan_khusus">Penugasan Khusus</option>
                                <option value="ikut_ortu">Mengikuti Orang Tua yang Pindah Domisili</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kota/Kabupaten Asal <span class="text-danger">*</span></label>
                            <input type="text" name="kota_asal" class="form-control" required placeholder="Contoh: Jakarta Selatan">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Provinsi Asal <span class="text-danger">*</span></label>
                            <input type="text" name="provinsi_asal" class="form-control" required placeholder="Contoh: DKI Jakarta">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Alamat Asal (Sebelum Pindah)</label>
                            <textarea name="alamat_asal" class="form-control" rows="2" placeholder="Alamat lengkap sebelum pindah"></textarea>
                        </div>
                        <div class="col-12"><hr class="my-2"></div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Alamat Baru di Padang <span class="text-danger">*</span></label>
                            <textarea name="alamat" class="form-control" rows="2" required placeholder="Alamat lengkap di Kota Padang"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kecamatan <span class="text-danger">*</span></label>
                            <select name="kecamatan" class="form-select" required>
                                <option value="">Pilih...</option>
                                <option>Bungus Teluk Kabung</option>
                                <option>Koto Tangah</option>
                                <option>Kuranji</option>
                                <option>Lubuk Begalung</option>
                                <option>Lubuk Kilangan</option>
                                <option>Nanggalo</option>
                                <option>Padang Barat</option>
                                <option>Padang Selatan</option>
                                <option>Padang Timur</option>
                                <option>Padang Utara</option>
                                <option>Pauh</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tanggal Pindah <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_pindah" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nomor SK Mutasi <span class="text-danger">*</span></label>
                            <input type="text" name="no_sk_mutasi" class="form-control" required placeholder="Nomor Surat Keputusan">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tanggal SK Mutasi <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_sk_mutasi" class="form-control" required>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn btn-outline-secondary px-4" onclick="prevStep(3)"><i class="bi bi-chevron-left"></i> Kembali</button>
                        <button type="button" class="btn btn-danger-custom px-4" onclick="nextStep(3)">Lanjutkan <i class="bi bi-chevron-right"></i></button>
                    </div>
                </div>

                <!-- Step 4: Pilih Sekolah -->
                <div class="form-section" id="step4">
                    <h5 class="fw-bold mb-4"><i class="bi bi-building text-danger me-2"></i>Pilih Sekolah Tujuan</h5>
                    <p class="text-muted mb-4">Pilih satu sekolah yang ingin Anda tuju di Kota Padang</p>
                    <div class="row g-3">
                        <?php foreach ($sekolah_list as $s): ?>
                        <div class="col-md-6">
                            <label class="school-card d-block">
                                <input type="radio" name="sekolah_id" value="<?php echo $s['id']; ?>" class="d-none" required>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-3 p-2 me-3" style="background: var(--theme-light);">
                                        <i class="bi bi-building text-danger"></i>
                                    </div>
                                    <div class="flex-fill">
                                        <div class="fw-bold"><?php echo e($s['nama']); ?></div>
                                        <small class="text-muted"><i class="bi bi-geo-alt"></i> <?php echo e($s['kecamatan']); ?></small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-danger">A</span>
                                    </div>
                                </div>
                            </label>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn btn-outline-secondary px-4" onclick="prevStep(4)"><i class="bi bi-chevron-left"></i> Kembali</button>
                        <button type="button" class="btn btn-danger-custom px-4" onclick="nextStep(4)">Lanjutkan <i class="bi bi-chevron-right"></i></button>
                    </div>
                </div>

                <!-- Step 5: Upload Berkas -->
                <div class="form-section" id="step5">
                    <h5 class="fw-bold mb-4"><i class="bi bi-folder text-danger me-2"></i>Upload Berkas Pendukung</h5>
                    
                    <!-- Berkas Umum -->
                    <h6 class="fw-semibold text-muted mb-3">Berkas Umum</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kartu Keluarga <span class="text-danger">*</span></label>
                            <input type="file" name="file_kk" class="form-control" accept=".pdf,.jpg,.png" required>
                            <small class="text-muted">Format: PDF, JPG, PNG (Max 2MB)</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Surat Keterangan Domisili</label>
                            <input type="file" name="file_domisili" class="form-control" accept=".pdf,.jpg,.png">
                            <small class="text-muted">Wajib jika KK belum 1 tahun</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Akta Kelahiran <span class="text-danger">*</span></label>
                            <input type="file" name="file_akta" class="form-control" accept=".pdf,.jpg,.png" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Rapor Terakhir <span class="text-danger">*</span></label>
                            <input type="file" name="file_rapor" class="form-control" accept=".pdf,.jpg,.png" required>
                            <small class="text-muted">Rapor semester terakhir dari sekolah asal</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Pas Foto 3x4 <span class="text-danger">*</span></label>
                            <input type="file" name="file_foto" class="form-control" accept=".jpg,.png" required>
                            <small class="text-muted">Background merah/biru</small>
                        </div>
                    </div>

                    <!-- Berkas Khusus Perpindahan -->
                    <div class="alert" style="background: var(--theme-light); border: none;">
                        <h6 class="fw-bold mb-2"><i class="bi bi-file-earmark-text me-2"></i>Berkas Khusus Jalur Perpindahan</h6>
                        <p class="small mb-0">Dokumen wajib untuk jalur perpindahan tugas orang tua</p>
                    </div>
                    
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">SK Mutasi/Pindah Tugas <span class="text-danger">*</span></label>
                            <input type="file" name="file_sk_mutasi" class="form-control" accept=".pdf,.jpg,.png" required>
                            <small class="text-muted">Surat Keputusan dari instansi</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Surat Pindah dari Sekolah Asal <span class="text-danger">*</span></label>
                            <input type="file" name="file_surat_pindah" class="form-control" accept=".pdf,.jpg,.png" required>
                            <small class="text-muted">Surat keterangan pindah</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Surat Keterangan Domisili Baru</label>
                            <input type="file" name="file_domisili" class="form-control" accept=".pdf,.jpg,.png">
                            <small class="text-muted">Dari kelurahan/RT setempat</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">KTP Orang Tua <span class="text-danger">*</span></label>
                            <input type="file" name="file_ktp_ortu" class="form-control" accept=".pdf,.jpg,.png" required>
                            <small class="text-muted">KTP ayah atau ibu</small>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn btn-outline-secondary px-4" onclick="prevStep(5)"><i class="bi bi-chevron-left"></i> Kembali</button>
                        <button type="submit" class="btn btn-danger-custom px-5"><i class="bi bi-check-circle me-2"></i>Kirim Pendaftaran</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let currentStep = 1;
const totalSteps = 5;

function nextStep(s) {
    currentStep = s + 1;
    updateSteps();
}

function prevStep(s) {
    currentStep = s - 1;
    updateSteps();
}

function updateSteps() {
    for (let i = 1; i <= totalSteps; i++) {
        document.getElementById('step' + i).classList.remove('active');
        document.getElementById('sc' + i).classList.remove('active', 'completed');
    }
    document.getElementById('step' + currentStep).classList.add('active');
    document.getElementById('sc' + currentStep).classList.add('active');
    for (let i = 1; i < currentStep; i++) {
        document.getElementById('sc' + i).classList.add('completed');
    }
    window.scrollTo({ top: 200, behavior: 'smooth' });
}

// Handle school card selection
document.querySelectorAll('.school-card').forEach(card => {
    card.addEventListener('click', function() {
        document.querySelectorAll('.school-card').forEach(c => c.classList.remove('selected'));
        this.classList.add('selected');
    });
});

document.getElementById('pendaftaranForm').addEventListener('submit', function(e) {
    const maxUmur = <?php echo get_setting('max_umur', 15); ?>;
    const tglLahir = new Date(document.getElementsByName('tanggal_lahir')[0].value);
    const targetDate = new Date('2026-07-01'); // Dihitung per 1 Juli 2026
    let age = targetDate.getFullYear() - tglLahir.getFullYear();
    const m = targetDate.getMonth() - tglLahir.getMonth();
    if (m < 0 || (m === 0 && targetDate.getDate() < tglLahir.getDate())) {
        age--;
    }
    
    if (age > maxUmur) {
        showError("Maaf, usia Anda melebihi batas maksimal (" + maxUmur + " tahun) sesuai ketentuan PPDB.");
        e.preventDefault();
        return;
    }
    
    if (age < 12) {
        showError("Maaf, usia Anda belum mencukupi (minimal 12 tahun).");
        e.preventDefault();
        return;
    }


    const tglKK = new Date(document.getElementsByName('tgl_kk')[0].value);
    const now = new Date();
    let kkAge = now.getFullYear() - tglKK.getFullYear();
    const mKK = now.getMonth() - tglKK.getMonth();
    if (mKK < 0 || (mKK === 0 && now.getDate() < tglKK.getDate())) {
        kkAge--;
    }
    
    const hasDomisili = document.getElementsByName('file_domisili')[0].files.length > 0;

    if (kkAge < 1 && !hasDomisili) {
        showError("Kartu Keluarga harus diterbitkan minimal 1 tahun sebelum pendaftaran. Jika kurang dari 1 tahun, wajib mengunggah Surat Keterangan Domisili.");
        e.preventDefault();
        return;
    }
});

function showError(msg) {
    const errDiv = document.getElementById('js-error');
    const errMsg = document.getElementById('js-error-msg');
    errMsg.innerText = msg;
    errDiv.classList.remove('d-none');
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Real-time age validation
const MIN_AGE = 12;
const MAX_AGE = 15; // Maksimal usia per 1 Juli 2026

function checkAge() {
    const tglLahirInput = document.getElementById('tanggal_lahir');
    const ageWarning = document.getElementById('ageWarning');
    const ageWarningText = document.getElementById('ageWarningText');
    const ageInfo = document.getElementById('ageInfo');
    const ageDisplay = document.getElementById('ageDisplay');
    
    if (!tglLahirInput.value) {
        ageWarning.classList.add('d-none');
        ageInfo.classList.add('d-none');
        return;
    }
    
    const tglLahir = new Date(tglLahirInput.value);
    const targetDate = new Date('2026-07-01'); // Per 1 Juli 2026
    
    let years = targetDate.getFullYear() - tglLahir.getFullYear();
    let months = targetDate.getMonth() - tglLahir.getMonth();
    if (months < 0) { years--; months += 12; }
    
    const ageText = years + ' tahun ' + months + ' bulan';
    ageDisplay.textContent = ageText;
    ageInfo.classList.remove('d-none');
    
    if (years < MIN_AGE) {
        ageWarning.classList.remove('d-none', 'alert-warning');
        ageWarning.classList.add('alert-danger');
        ageWarningText.innerHTML = '<strong>Usia Kurang!</strong> Minimal ' + MIN_AGE + ' tahun. Usia: ' + ageText;
        tglLahirInput.classList.add('is-invalid');
    } else if (years > MAX_AGE) {
        ageWarning.classList.remove('d-none', 'alert-warning');
        ageWarning.classList.add('alert-danger');
        ageWarningText.innerHTML = '<strong>Usia Melebihi Batas!</strong> Maksimal ' + MAX_AGE + ' tahun. Usia: ' + ageText;
        tglLahirInput.classList.add('is-invalid');
    } else {
        ageWarning.classList.add('d-none');
        tglLahirInput.classList.remove('is-invalid');
        tglLahirInput.classList.add('is-valid');
    }
}
</script>

</body>
</html>
