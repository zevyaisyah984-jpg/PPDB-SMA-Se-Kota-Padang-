<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Prestasi - <?php echo APP_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo asset('css/style.css'); ?>">
    <style>
        :root { --theme-color: #f59e0b; --theme-light: #fef3c7; --theme-dark: #d97706; }
        .form-section { background: white; border-radius: 16px; padding: 2rem; margin-bottom: 1.5rem; box-shadow: 0 2px 10px rgba(0,0,0,0.05); display: none; }
        .form-section.active { display: block; animation: fadeIn 0.3s; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        .step-circle { width: 40px; height: 40px; border: 2px solid #e2e8f0; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; background: white; color: #94a3b8; z-index: 2; }
        .step-circle.active { border-color: var(--theme-color); color: var(--theme-dark); background: var(--theme-light); }
        .step-circle.completed { background: var(--theme-color); color: white; border-color: var(--theme-color); }
        .school-card { border: 2px solid #e2e8f0; border-radius: 12px; padding: 1rem; cursor: pointer; transition: all 0.2s; }
        .school-card:hover, .school-card.selected { border-color: var(--theme-color); background: #fffbeb; }
        .kategori-card { border: 2px solid #e2e8f0; border-radius: 12px; padding: 1.5rem; cursor: pointer; transition: all 0.2s; text-align: center; }
        .kategori-card:hover, .kategori-card.selected { border-color: var(--theme-color); background: var(--theme-light); }
        .kategori-card .icon { font-size: 2.5rem; margin-bottom: 0.5rem; }
        .info-box { background: var(--theme-light); border-left: 4px solid var(--theme-color); padding: 1rem; border-radius: 0 8px 8px 0; margin-bottom: 1.5rem; }
        .prestasi-item { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.5rem; margin-bottom: 1rem; position: relative; }
        .prestasi-item .remove-btn { position: absolute; top: 10px; right: 10px; }
        .btn-warning-custom { background: var(--theme-color); border-color: var(--theme-color); color: white; }
        .btn-warning-custom:hover { background: var(--theme-dark); border-color: var(--theme-dark); color: white; }
        .btn-outline-warning-custom { border-color: var(--theme-color); color: var(--theme-dark); }
        .btn-outline-warning-custom:hover { background: var(--theme-color); color: white; }
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
                <span class="badge px-3 py-2 rounded-pill mb-3" style="background: var(--theme-light); color: var(--theme-dark);">JALUR PRESTASI</span>
                <h2 class="fw-bold">Formulir Pendaftaran</h2>
                <p class="text-muted">Jalur untuk siswa berprestasi di bidang akademik dan non-akademik</p>
            </div>

            <!-- Info Box -->
            <div class="info-box">
                <h6 class="fw-bold mb-2"><i class="bi bi-trophy me-2"></i>Ketentuan Jalur Prestasi</h6>
                <ul class="mb-0 small">
                    <li><strong>Akademik:</strong> Nilai rapor, lomba sains, olimpiade, karya tulis ilmiah</li>
                    <li><strong>Non-Akademik:</strong> Olahraga, seni, tahfiz, pramuka, PMR, dll</li>
                    <li>Prestasi minimal tingkat <strong>Kota/Kabupaten</strong></li>
                    <li>Kuota jalur ini adalah <strong>30%</strong> dari total daya tampung</li>
                </ul>
            </div>

            <!-- Step Indicator -->
            <div class="d-flex justify-content-between mb-5 position-relative">
                <div class="position-absolute top-50 start-0 end-0" style="height: 2px; background: #e2e8f0; z-index: 1;"></div>
                <div class="d-flex flex-column align-items-center" style="z-index: 2;">
                    <div class="step-circle active" id="sc1">1</div>
                    <small class="mt-2 fw-semibold">Kategori</small>
                </div>
                <div class="d-flex flex-column align-items-center" style="z-index: 2;">
                    <div class="step-circle" id="sc2">2</div>
                    <small class="mt-2">Biodata</small>
                </div>
                <div class="d-flex flex-column align-items-center" style="z-index: 2;">
                    <div class="step-circle" id="sc3">3</div>
                    <small class="mt-2">Prestasi</small>
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

            <form action="<?php echo url('/daftar/prestasi'); ?>" method="POST" enctype="multipart/form-data" id="pendaftaranForm">

                <?php echo csrf_field(); ?>
                <input type="hidden" name="jalur" value="prestasi">
                
                <!-- Step 1: Kategori Prestasi -->
                <div class="form-section active" id="step1">
                    <h5 class="fw-bold mb-4"><i class="bi bi-trophy text-warning me-2"></i>Pilih Kategori Prestasi</h5>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="kategori-card d-block h-100">
                                <input type="radio" name="sub_jalur" value="akademik" class="d-none" required>
                                <div class="icon text-warning"><i class="bi bi-book"></i></div>
                                <h5 class="fw-bold">Prestasi Akademik</h5>
                                <p class="small text-muted mb-0">Olimpiade sains, lomba matematika, karya tulis ilmiah, debat bahasa, dll</p>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="kategori-card d-block h-100">
                                <input type="radio" name="sub_jalur" value="non_akademik" class="d-none" required>
                                <div class="icon text-warning"><i class="bi bi-stars"></i></div>
                                <h5 class="fw-bold">Prestasi Non-Akademik</h5>
                                <p class="small text-muted mb-0">Olahraga, seni, tahfiz Al-Quran, pramuka, PMR, paskibra, dll</p>
                            </label>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-4">
                        <button type="button" class="btn btn-warning-custom px-4" onclick="nextStep(1)">Lanjutkan <i class="bi bi-chevron-right"></i></button>
                    </div>
                </div>

                <!-- Step 2: Biodata -->
                <div class="form-section" id="step2">
                    <h5 class="fw-bold mb-4"><i class="bi bi-person-vcard text-warning me-2"></i>Data Identitas</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">NISN <span class="text-danger">*</span></label>
                            <input type="text" name="nisn" class="form-control" maxlength="10" required placeholder="Masukkan 10 digit NISN">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control" required placeholder="Sesuai ijazah">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">NIK <span class="text-danger">*</span></label>
                            <input type="text" name="nik" class="form-control" maxlength="16" required placeholder="Masukkan 16 digit NIK">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Sekolah Asal <span class="text-danger">*</span></label>
                            <input type="text" name="sekolah_asal" class="form-control" required placeholder="Nama SMP/MTs asal">
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
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Alamat Lengkap <span class="text-danger">*</span></label>
                            <textarea name="alamat" class="form-control" rows="2" required></textarea>
                        </div>
                        <div class="col-md-4">
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
                            <label class="form-label fw-semibold">No. HP Siswa <span class="text-danger">*</span></label>
                            <input type="tel" name="no_hp" class="form-control" required placeholder="08xxxxxxxxxx">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">No. HP Orang Tua <span class="text-danger">*</span></label>
                            <input type="tel" name="no_hp_ortu" class="form-control" required placeholder="08xxxxxxxxxx">
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn btn-outline-secondary px-4" onclick="prevStep(2)"><i class="bi bi-chevron-left"></i> Kembali</button>
                        <button type="button" class="btn btn-warning-custom px-4" onclick="nextStep(2)">Lanjutkan <i class="bi bi-chevron-right"></i></button>
                    </div>
                </div>

                <!-- Step 3: Data Prestasi -->
                <div class="form-section" id="step3">
                    <h5 class="fw-bold mb-4"><i class="bi bi-award text-warning me-2"></i>Detail Prestasi</h5>
                    <p class="text-muted mb-4">Tambahkan prestasi yang Anda miliki (minimal 1, maksimal 3)</p>
                    
                    <div id="prestasi-container">
                        <!-- Prestasi 1 -->
                        <div class="prestasi-item" id="prestasi-1">
                            <h6 class="fw-bold text-warning mb-3"><i class="bi bi-1-circle me-2"></i>Prestasi Utama</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Nama Kejuaraan/Lomba <span class="text-danger">*</span></label>
                                    <input type="text" name="prestasi_nama[]" class="form-control" required placeholder="Contoh: Olimpiade Matematika">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Bidang <span class="text-danger">*</span></label>
                                    <select name="prestasi_bidang[]" class="form-select" required>
                                        <option value="">Pilih bidang...</option>
                                        <optgroup label="Akademik">
                                            <option value="matematika">Matematika</option>
                                            <option value="fisika">Fisika</option>
                                            <option value="kimia">Kimia</option>
                                            <option value="biologi">Biologi</option>
                                            <option value="informatika">Informatika/Komputer</option>
                                            <option value="bahasa_inggris">Bahasa Inggris</option>
                                            <option value="bahasa_indonesia">Bahasa Indonesia</option>
                                            <option value="ekonomi">Ekonomi</option>
                                            <option value="kti">Karya Tulis Ilmiah</option>
                                        </optgroup>
                                        <optgroup label="Non-Akademik">
                                            <option value="sepak_bola">Sepak Bola</option>
                                            <option value="bola_voli">Bola Voli</option>
                                            <option value="basket">Basket</option>
                                            <option value="badminton">Badminton</option>
                                            <option value="renang">Renang</option>
                                            <option value="atletik">Atletik</option>
                                            <option value="silat">Pencak Silat</option>
                                            <option value="karate">Karate</option>
                                            <option value="taekwondo">Taekwondo</option>
                                            <option value="tari">Seni Tari</option>
                                            <option value="musik">Seni Musik</option>
                                            <option value="lukis">Seni Lukis</option>
                                            <option value="tahfiz">Tahfiz Al-Quran</option>
                                            <option value="pramuka">Pramuka</option>
                                            <option value="pmr">PMR</option>
                                            <option value="paskibra">Paskibra</option>
                                            <option value="lainnya">Lainnya</option>
                                        </optgroup>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Tingkat <span class="text-danger">*</span></label>
                                    <select name="prestasi_tingkat[]" class="form-select" required>
                                        <option value="">Pilih tingkat...</option>
                                        <option value="kecamatan">Kecamatan</option>
                                        <option value="kota">Kota/Kabupaten</option>
                                        <option value="provinsi">Provinsi</option>
                                        <option value="nasional">Nasional</option>
                                        <option value="internasional">Internasional</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Peringkat/Juara <span class="text-danger">*</span></label>
                                    <select name="prestasi_juara[]" class="form-select" required>
                                        <option value="">Pilih peringkat...</option>
                                        <option value="1">Juara 1 / Emas</option>
                                        <option value="2">Juara 2 / Perak</option>
                                        <option value="3">Juara 3 / Perunggu</option>
                                        <option value="harapan1">Juara Harapan 1</option>
                                        <option value="harapan2">Juara Harapan 2</option>
                                        <option value="harapan3">Juara Harapan 3</option>
                                        <option value="peserta">Peserta/Finalis</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Tahun <span class="text-danger">*</span></label>
                                    <select name="prestasi_tahun[]" class="form-select" required>
                                        <option value="">Pilih tahun...</option>
                                        <option value="2024">2024</option>
                                        <option value="2023">2023</option>
                                        <option value="2022">2022</option>
                                        <option value="2021">2021</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Penyelenggara</label>
                                    <input type="text" name="prestasi_penyelenggara[]" class="form-control" placeholder="Contoh: Dinas Pendidikan Kota Padang">
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-outline-warning-custom w-100 mb-3" onclick="addPrestasi()">
                        <i class="bi bi-plus-circle me-2"></i>Tambah Prestasi Lainnya
                    </button>

                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn btn-outline-secondary px-4" onclick="prevStep(3)"><i class="bi bi-chevron-left"></i> Kembali</button>
                        <button type="button" class="btn btn-warning-custom px-4" onclick="nextStep(3)">Lanjutkan <i class="bi bi-chevron-right"></i></button>
                    </div>
                </div>

                <!-- Step 4: Pilih Sekolah -->
                <div class="form-section" id="step4">
                    <h5 class="fw-bold mb-4"><i class="bi bi-building text-warning me-2"></i>Pilih Sekolah Tujuan</h5>
                    <p class="text-muted mb-4">Pilih satu sekolah yang ingin Anda tuju</p>
                    <div class="row g-3">
                        <?php foreach ($sekolah_list as $s): ?>
                        <div class="col-md-6">
                            <label class="school-card d-block">
                                <input type="radio" name="sekolah_id" value="<?php echo $s['id']; ?>" class="d-none" required>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-3 p-2 me-3" style="background: var(--theme-light);">
                                        <i class="bi bi-building text-warning"></i>
                                    </div>
                                    <div class="flex-fill">
                                        <div class="fw-bold"><?php echo e($s['nama']); ?></div>
                                        <small class="text-muted"><i class="bi bi-geo-alt"></i> <?php echo e($s['kecamatan']); ?></small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge" style="background: var(--theme-color);">A</span>
                                    </div>
                                </div>
                            </label>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn btn-outline-secondary px-4" onclick="prevStep(4)"><i class="bi bi-chevron-left"></i> Kembali</button>
                        <button type="button" class="btn btn-warning-custom px-4" onclick="nextStep(4)">Lanjutkan <i class="bi bi-chevron-right"></i></button>
                    </div>
                </div>

                <!-- Step 5: Upload Berkas -->
                <div class="form-section" id="step5">
                    <h5 class="fw-bold mb-4"><i class="bi bi-folder text-warning me-2"></i>Upload Berkas Pendukung</h5>
                    
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
                            <label class="form-label fw-semibold">Ijazah/SKL <span class="text-danger">*</span></label>
                            <input type="file" name="file_ijazah" class="form-control" accept=".pdf,.jpg,.png" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Pas Foto 3x4 <span class="text-danger">*</span></label>
                            <input type="file" name="file_foto" class="form-control" accept=".jpg,.png" required>
                            <small class="text-muted">Background merah/biru</small>
                        </div>
                    </div>

                    <!-- Berkas Prestasi -->
                    <div class="alert" style="background: var(--theme-light); border: none;">
                        <h6 class="fw-bold mb-2"><i class="bi bi-award me-2"></i>Berkas Khusus Jalur Prestasi</h6>
                        <p class="small mb-0">Upload sertifikat/piagam penghargaan prestasi Anda</p>
                    </div>
                    
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Sertifikat Prestasi 1 <span class="text-danger">*</span></label>
                            <input type="file" name="file_sertifikat_1" class="form-control" accept=".pdf,.jpg,.png" required>
                            <small class="text-muted">Sertifikat/Piagam prestasi utama</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Sertifikat Prestasi 2</label>
                            <input type="file" name="file_sertifikat_2" class="form-control" accept=".pdf,.jpg,.png">
                            <small class="text-muted">Opsional</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Sertifikat Prestasi 3</label>
                            <input type="file" name="file_sertifikat_3" class="form-control" accept=".pdf,.jpg,.png">
                            <small class="text-muted">Opsional</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Rapor Semester 1-5</label>
                            <input type="file" name="file_rapor" class="form-control" accept=".pdf,.jpg,.png">
                            <small class="text-muted">Untuk prestasi akademik (nilai rapor)</small>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn btn-outline-secondary px-4" onclick="prevStep(5)"><i class="bi bi-chevron-left"></i> Kembali</button>
                        <button type="submit" class="btn btn-warning-custom px-5"><i class="bi bi-check-circle me-2"></i>Kirim Pendaftaran</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let currentStep = 1;
const totalSteps = 5;
let prestasiCount = 1;

function nextStep(s) {
    if (s === 1) {
        const kategori = document.querySelector('input[name="sub_jalur"]:checked');
        if (!kategori) {
            alert('Silakan pilih kategori prestasi terlebih dahulu');
            return;
        }
    }
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

function addPrestasi() {
    if (prestasiCount >= 3) {
        alert('Maksimal 3 prestasi');
        return;
    }
    prestasiCount++;
    
    const container = document.getElementById('prestasi-container');
    const newItem = document.createElement('div');
    newItem.className = 'prestasi-item';
    newItem.id = 'prestasi-' + prestasiCount;
    newItem.innerHTML = `
        <button type="button" class="btn btn-sm btn-outline-danger remove-btn" onclick="removePrestasi(${prestasiCount})">
            <i class="bi bi-trash"></i>
        </button>
        <h6 class="fw-bold text-warning mb-3"><i class="bi bi-${prestasiCount}-circle me-2"></i>Prestasi ${prestasiCount}</h6>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">Nama Kejuaraan/Lomba</label>
                <input type="text" name="prestasi_nama[]" class="form-control" placeholder="Contoh: Olimpiade Matematika">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Bidang</label>
                <select name="prestasi_bidang[]" class="form-select">
                    <option value="">Pilih bidang...</option>
                    <optgroup label="Akademik">
                        <option value="matematika">Matematika</option>
                        <option value="fisika">Fisika</option>
                        <option value="kimia">Kimia</option>
                        <option value="biologi">Biologi</option>
                        <option value="informatika">Informatika/Komputer</option>
                        <option value="bahasa_inggris">Bahasa Inggris</option>
                        <option value="bahasa_indonesia">Bahasa Indonesia</option>
                    </optgroup>
                    <optgroup label="Non-Akademik">
                        <option value="sepak_bola">Sepak Bola</option>
                        <option value="bola_voli">Bola Voli</option>
                        <option value="basket">Basket</option>
                        <option value="badminton">Badminton</option>
                        <option value="silat">Pencak Silat</option>
                        <option value="tari">Seni Tari</option>
                        <option value="musik">Seni Musik</option>
                        <option value="tahfiz">Tahfiz Al-Quran</option>
                        <option value="pramuka">Pramuka</option>
                        <option value="lainnya">Lainnya</option>
                    </optgroup>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Tingkat</label>
                <select name="prestasi_tingkat[]" class="form-select">
                    <option value="">Pilih tingkat...</option>
                    <option value="kecamatan">Kecamatan</option>
                    <option value="kota">Kota/Kabupaten</option>
                    <option value="provinsi">Provinsi</option>
                    <option value="nasional">Nasional</option>
                    <option value="internasional">Internasional</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Peringkat/Juara</label>
                <select name="prestasi_juara[]" class="form-select">
                    <option value="">Pilih peringkat...</option>
                    <option value="1">Juara 1 / Emas</option>
                    <option value="2">Juara 2 / Perak</option>
                    <option value="3">Juara 3 / Perunggu</option>
                    <option value="harapan1">Juara Harapan 1</option>
                    <option value="peserta">Peserta/Finalis</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Tahun</label>
                <select name="prestasi_tahun[]" class="form-select">
                    <option value="">Pilih tahun...</option>
                    <option value="2024">2024</option>
                    <option value="2023">2023</option>
                    <option value="2022">2022</option>
                    <option value="2021">2021</option>
                </select>
            </div>
        </div>
    `;
    container.appendChild(newItem);
}

function removePrestasi(id) {
    const item = document.getElementById('prestasi-' + id);
    if (item) {
        item.remove();
        prestasiCount--;
    }
}

// Handle kategori selection
document.querySelectorAll('.kategori-card').forEach(card => {
    card.addEventListener('click', function() {
        document.querySelectorAll('.kategori-card').forEach(c => c.classList.remove('selected'));
        this.classList.add('selected');
    });
});

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
