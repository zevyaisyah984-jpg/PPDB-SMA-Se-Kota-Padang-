<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Zonasi - <?php echo APP_NAME; ?></title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="<?php echo asset('css/style.css'); ?>">
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; color: #334155; }
        
        /* Modern Card */
        .form-card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 40px -10px rgba(0,0,0,0.05); /* Soft drop shadow */
            border: 1px solid rgba(226, 232, 240, 0.8);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .form-section { display: none; padding: 2.5rem; }
        .form-section.active { display: block; animation: slideUpFade 0.5s cubic-bezier(0.16, 1, 0.3, 1); }
        
        @keyframes slideUpFade {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Progressive Stepper */
        .stepper-container {
            position: relative;
            display: flex;
            justify-content: space-between;
            margin-bottom: 3rem;
        }
        
        .stepper-progress-bar {
            position: absolute;
            top: 24px;
            left: 0;
            right: 0;
            height: 4px;
            background: #E2E8F0;
            z-index: 1;
            border-radius: 4px;
        }
        
        .stepper-progress-fill {
            position: absolute;
            top: 0; left: 0; height: 100%;
            background: #1A56DB;
            transition: width 0.4s ease;
            border-radius: 4px;
        }
        
        .step-item {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 80px;
        }
        
        .step-circle {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: white;
            border: 2px solid #E2E8F0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.1rem;
            color: #94A3B8;
            transition: all 0.3s ease;
            margin-bottom: 8px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        }
        
        .step-item.active .step-circle {
            border-color: #1A56DB;
            background: #EFF6FF;
            color: #1A56DB;
            box-shadow: 0 0 0 4px rgba(26, 86, 219, 0.15);
        }
        
        .step-item.completed .step-circle {
            background: #1A56DB;
            border-color: #1A56DB;
            color: white;
        }
        
        .step-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #94A3B8;
            transition: color 0.3s;
        }
        
        .step-item.active .step-label, 
        .step-item.completed .step-label {
            color: #1A56DB;
        }

        /* Form Inputs */
        .form-label { font-weight: 600; color: #475569; margin-bottom: 0.5rem; font-size: 0.95rem; }
        .form-control, .form-select {
            padding: 0.85rem 1rem;
            border-radius: 12px;
            border: 1px solid #CBD5E1;
            font-size: 1rem;
            transition: all 0.2s;
        }
        .form-control:focus, .form-select:focus {
            border-color: #1A56DB;
            box-shadow: 0 0 0 4px rgba(26, 86, 219, 0.1);
        }
        
        /* Selection Cards */
        .school-card { 
            border: 2px solid #F1F5F9; 
            border-radius: 16px; 
            padding: 1.25rem; 
            cursor: pointer; 
            transition: all 0.2s; 
            background: white;
        }
        .school-card:hover { border-color: #BFDBFE; background: #F8FAFC; }
        .school-card input:checked + div { }  /* Logic handled by class 'selected' below */
        .school-card.selected { 
            border-color: #1A56DB; 
            background: #EFF6FF; 
            box-shadow: 0 4px 12px rgba(26, 86, 219, 0.1);
        }

        /* Buttons */
        .btn-action {
            padding: 12px 32px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.2s;
        }
        .btn-primary-theme {
            background: #1A56DB; color: white; border: none;
            box-shadow: 0 4px 12px rgba(26, 86, 219, 0.25);
        }
        .btn-primary-theme:hover {
            background: #1e40af; transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(26, 86, 219, 0.3);
        }
        .btn-outline-theme {
            background: transparent; border: 2px solid #E2E8F0; color: #64748B;
        }
        .btn-outline-theme:hover {
            border-color: #CBD5E1; background: #F8FAFC; color: #1E293B;
        }
    </style>
</head>
<body>

<!-- Navbar Minimalist -->
<nav class="navbar bg-white border-bottom py-3 sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2 fw-bold text-dark" href="<?php echo url('/dashboard'); ?>">
            <div class="rounded-circle bg-light p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                <i class="bi bi-arrow-left"></i>
            </div>
            <span>Kembali ke Dashboard</span>
        </a>
        <div class="text-end d-none d-sm-block">
            <span class="text-secondary small d-block">Jalur Pendaftaran</span>
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 rounded-pill">ZONASI SMA</span>
        </div>
    </div>
</nav>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9 col-xl-8">
            
            <!-- Header title -->
            <div class="text-center mb-5">
                <h2 class="fw-bold mb-2 text-dark">Formulir Pendaftaran</h2>
                <p class="text-secondary">Lengkapi data diri dan dokumen dengan benar</p>
            </div>

            <!-- Modern Progressive Stepper -->
            <div class="stepper-container px-4">
                <div class="stepper-progress-bar">
                    <div class="stepper-progress-fill" id="stepperFill" style="width: 0%;"></div>
                </div>
                
                <!-- Steps -->
                <div class="step-item active" id="stepIndicator1">
                    <div class="step-circle">1</div>
                    <span class="step-label mt-2">Identitas</span>
                </div>
                <div class="step-item" id="stepIndicator2">
                    <div class="step-circle">2</div>
                    <span class="step-label mt-2">Nilai Rapor</span>
                </div>
                <div class="step-item" id="stepIndicator3">
                    <div class="step-circle">3</div>
                    <span class="step-label mt-2">Alamat</span>
                </div>
                <div class="step-item" id="stepIndicator4">
                    <div class="step-circle">4</div>
                    <span class="step-label mt-2">Sekolah</span>
                </div>
                <div class="step-item" id="stepIndicator5">
                    <div class="step-circle">5</div>
                    <span class="step-label mt-2">Berkas</span>
                </div>
            </div>

            <!-- Error Alerts -->
            <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger shadow-sm border-0 rounded-4 mb-4 d-flex align-items-center gap-3 p-3">
                <i class="bi bi-exclamation-octagon-fill text-danger fs-4"></i>
                <div>
                    <strong>Terjadi Kesalahan!</strong><br>
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <div id="js-error" class="alert alert-danger shadow-sm border-0 rounded-4 mb-4 d-none d-flex align-items-center gap-3 p-3">
                <i class="bi bi-exclamation-triangle-fill text-danger fs-4"></i>
                <div>
                    <strong>Perhatian!</strong> <span id="js-error-msg"></span>
                </div>
            </div>

            <!-- Main Form Card -->
            <div class="form-card">
                <form action="<?php echo url('/daftar/zonasi'); ?>" method="POST" enctype="multipart/form-data" id="pendaftaranForm">
                    <?php echo csrf_field(); ?>
                    
                    <!-- Step 1: Identitas -->
                    <div class="form-section active" id="step1">
                        <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                            <i class="bi bi-person-circle fs-3 text-primary"></i>
                            <div>
                                <h4 class="fw-bold mb-0 text-dark">Data Diri Siswa</h4>
                                <small class="text-secondary">Isi sesuai dengan Kartu Keluarga / Akta</small>
                            </div>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label">NISN</label>
                                <input type="text" name="nisn" class="form-control" maxlength="10" placeholder="Nomor Induk Siswa Nasional" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">NIK</label>
                                <input type="text" name="nik" class="form-control" maxlength="16" placeholder="Nomor Induk Kependudukan" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" name="nama" class="form-control" placeholder="Nama Lengkap Siswa" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Sekolah Asal</label>
                                <input type="text" name="sekolah_asal" class="form-control" placeholder="Nama SMP/MTs Asal" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Jenis Kelamin</label>
                                <select name="jk" class="form-select" required>
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tempat Lahir</label>
                                <input type="text" name="tempat_lahir" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control" required onchange="checkAge()">
                                <div id="ageWarning" class="alert alert-warning mt-2 py-2 px-3 small d-none rounded-3 border-0 bg-warning-subtle text-warning-emphasis">
                                    <i class="bi bi-exclamation-circle me-1"></i> <span id="ageWarningText"></span>
                                </div>
                                <div id="ageInfo" class="mt-2 text-primary small d-none fw-medium bg-primary-subtle px-3 py-2 rounded-3">
                                    <i class="bi bi-info-circle me-1"></i> Usia per 1 Juli 2026: <strong id="ageDisplay"></strong>
                                </div>
                            </div>
                            
                            <div class="col-12 border-top pt-3 mt-4">
                                <h6 class="fw-bold text-dark mb-3">Data Keluarga</h6>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">No. Kartu Keluarga</label>
                                <input type="text" name="no_kk" class="form-control" maxlength="16" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Terbit KK</label>
                                <input type="date" name="tgl_kk" class="form-control" required>
                                <small class="text-secondary d-block mt-1 fst-italic ms-1">Lihat di bagian bawah KK Anda</small>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-5 pt-3">
                            <button type="button" class="btn btn-action btn-primary-theme" onclick="nextStep(1)">
                                Lanjut Langkah Berikutnya <i class="bi bi-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Step 2: NEW - Nilai Rapor -->
                    <div class="form-section" id="step2">
                        <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                            <i class="bi bi-journal-check fs-3 text-primary"></i>
                            <div>
                                <h4 class="fw-bold mb-0 text-dark">Data Nilai Rapor</h4>
                                <small class="text-secondary">Seleksi Utama Domisili berdasarkan Rerata Rapor Sem 1-5</small>
                            </div>
                        </div>
                        
                        <div class="alert alert-primary border-0 bg-primary-subtle text-primary-emphasis rounded-3 mb-4">
                            <i class="bi bi-info-circle-fill me-2"></i> Masukkan rata-rata nilai pengetahuan (KI-3) untuk mata pelajaran kelompok A.
                        </div>

                        <div class="row g-4">
                            <div class="col-md-4 col-6">
                                <label class="form-label">Rata-rata Sem 1</label>
                                <input type="number" step="0.01" min="0" max="100" name="nilai_sem1" class="form-control" placeholder="00.00" required>
                            </div>
                            <div class="col-md-4 col-6">
                                <label class="form-label">Rata-rata Sem 2</label>
                                <input type="number" step="0.01" min="0" max="100" name="nilai_sem2" class="form-control" placeholder="00.00" required>
                            </div>
                            <div class="col-md-4 col-6">
                                <label class="form-label">Rata-rata Sem 3</label>
                                <input type="number" step="0.01" min="0" max="100" name="nilai_sem3" class="form-control" placeholder="00.00" required>
                            </div>
                            <div class="col-md-4 col-6">
                                <label class="form-label">Rata-rata Sem 4</label>
                                <input type="number" step="0.01" min="0" max="100" name="nilai_sem4" class="form-control" placeholder="00.00" required>
                            </div>
                            <div class="col-md-4 col-6">
                                <label class="form-label">Rata-rata Sem 5</label>
                                <input type="number" step="0.01" min="0" max="100" name="nilai_sem5" class="form-control" placeholder="00.00" required>
                            </div>
                             <div class="col-md-4 col-12 d-flex align-items-end">
                                <div class="w-100 p-3 bg-light rounded-3 text-center border">
                                    <small class="text-secondary fw-bold text-uppercase">Estimasi Skor Rapor</small>
                                    <div class="h4 fw-bold text-primary mb-0" id="avgPreview">-</div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-5 pt-3">
                            <button type="button" class="btn btn-action btn-outline-theme" onclick="prevStep(2)">
                                <i class="bi bi-arrow-left me-2"></i> Sebelumnya
                            </button>
                            <button type="button" class="btn btn-action btn-primary-theme" onclick="nextStep(2)">
                                Lanjut Langkah Berikutnya <i class="bi bi-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Step 3: Alamat -->
                    <div class="form-section" id="step3">
                        <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                            <i class="bi bi-geo-alt-fill fs-3 text-primary"></i>
                            <div>
                                <h4 class="fw-bold mb-0 text-dark">Alamat & Koordinat</h4>
                                <small class="text-secondary">Pastikan titik lokasi sesuai dengan tempat tinggal</small>
                            </div>
                        </div>

                        <div class="row g-4">
                            <div class="col-12">
                                <label class="form-label">Alamat Lengkap</label>
                                <textarea name="alamat" class="form-control" rows="3" placeholder="Nama Jalan, RT/RW, No. Rumah" required></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Kecamatan</label>
                                <select name="kecamatan" class="form-select" required>
                                    <option value="">Pilih Kecamatan...</option>
                                    <option>Koto Tangah</option>
                                    <option>Kuranji</option>
                                    <option>Padang Barat</option>
                                    <option>Padang Timur</option>
                                    <option>Padang Utara</option>
                                    <option>Lubuk Begalung</option>
                                    <option>Bungus Teluk Kabung</option>
                                    <option>Lubuk Kilangan</option>
                                    <option>Nanggalo</option>
                                    <option>Padang Selatan</option>
                                    <option>Pauh</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Kelurahan / Nagari</label>
                                <input type="text" name="kelurahan" class="form-control" placeholder="Nama Kelurahan atau Nagari" required>
                                <small class="text-secondary d-block mt-1">Sesuai yang tertera di Kartu Keluarga</small>
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label d-flex justify-content-between">
                                    <span>Titik Lokasi Rumah</span>
                                    <small class="text-primary cursor-pointer" onclick="initMap()"><i class="bi bi-arrow-clockwise"></i> Refresh Peta</small>
                                </label>
                                <div id="mapContainer" class="rounded-3 border" style="height: 350px; overflow: hidden;"></div>
                                <small class="text-secondary mt-2 d-block"><i class="bi bi-info-circle me-1"></i> Geser penanda (marker) merah ke lokasi rumah Anda yang tepat.</small>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Latitude</label>
                                <input type="text" id="lat" name="lat" class="form-control bg-light" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Longitude</label>
                                <input type="text" id="lng" name="lng" class="form-control bg-light" readonly>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-5 pt-3">
                            <button type="button" class="btn btn-action btn-outline-theme" onclick="prevStep(3)">
                                <i class="bi bi-arrow-left me-2"></i> Sebelumnya
                            </button>
                            <button type="button" class="btn btn-action btn-primary-theme" onclick="nextStep(3)">
                                Lanjut Langkah Berikutnya <i class="bi bi-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Step 4: Sekolah -->
                    <div class="form-section" id="step4">
                        <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                            <i class="bi bi-building-fill fs-3 text-primary"></i>
                            <div>
                                <h4 class="fw-bold mb-0 text-dark">Pilih Sekolah Tujuan</h4>
                                <small class="text-secondary">Pilih satu SMA Negeri tujuan pendaftaran</small>
                            </div>
                        </div>

                        <!-- Single Selection Warning -->
                        <div class="alert alert-warning border-0 bg-warning-subtle text-warning-emphasis rounded-4 mb-4 d-flex gap-3 p-3">
                            <i class="bi bi-shield-lock-fill fs-4 flex-shrink-0"></i>
                            <div>
                                <strong class="d-block mb-1">Aturan Seleksi Tunggal (Domisili)</strong>
                                Sesuai peraturan Juknis Sumbar 2025, Anda hanya dapat memilih <strong>1 (satu) sekolah</strong> di jalur Domisili. Pilihan akan otomatis terkunci saat dipilih. Pastikan pilihan Anda sudah benar sebelum mengunci data.
                            </div>
                        </div>

                        <div class="row g-3" id="schoolSelectionList">
                            <?php foreach ($sekolah_list as $s): ?>
                            <div class="col-md-6">
                                <label class="school-card d-flex align-items-start gap-3 h-100 position-relative">
                                    <input type="radio" name="sekolah_id" value="<?php echo $s['id']; ?>" class="d-none" required>
                                    
                                    <div class="rounded-circle bg-primary bg-opacity-10 p-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 50px; height: 50px;">
                                        <i class="bi bi-building text-primary fs-5"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold text-dark mb-1"><?php echo e($s['nama']); ?></h6>
                                        <div class="d-flex align-items-center gap-2 text-secondary small mb-2">
                                            <i class="bi bi-geo-alt"></i> <?php echo e($s['kecamatan']); ?>
                                        </div>
                                        <?php if(isset($s['kuota_zonasi'])): ?>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">
                                            Kuota: <?php echo $s['kuota_zonasi']; ?>
                                        </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="position-absolute top-0 end-0 m-3 opacity-0 transition-opacity check-indicator">
                                        <i class="bi bi-check-circle-fill text-primary fs-4"></i>
                                    </div>
                                    <style>.school-card.selected .check-indicator { opacity: 1; }</style>
                                </label>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Selection Control -->
                        <div id="selectionOverlay" class="d-none mt-4 p-3 bg-light rounded-4 border text-center">
                            <p class="mb-2 small text-secondary">Sekolah terpilih: <strong id="selectedSchoolName" class="text-dark"></strong></p>
                            <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-4" onclick="resetSchoolSelection()">
                                <i class="bi bi-arrow-counterclockwise"></i> Ganti Pilihan Sekolah
                            </button>
                        </div>

                        <div class="d-flex justify-content-between mt-5 pt-3">
                            <button type="button" class="btn btn-action btn-outline-theme" onclick="prevStep(4)">
                                <i class="bi bi-arrow-left me-2"></i> Sebelumnya
                            </button>
                            <button type="button" class="btn btn-action btn-primary-theme" onclick="nextStep(4)">
                                Lanjut Langkah Berikutnya <i class="bi bi-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Step 5: Berkas -->
                    <div class="form-section" id="step5">
                        <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                            <i class="bi bi-folder-fill fs-3 text-primary"></i>
                            <div>
                                <h4 class="fw-bold mb-0 text-dark">Upload Dokumen</h4>
                                <small class="text-secondary">Format: JPG/PNG/PDF (Max 2MB)</small>
                            </div>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label">Kartu Keluarga (Asli)</label>
                                <input type="file" name="file_kk" class="form-control" accept=".pdf,.jpg,.png,.jpeg">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Surat Keterangan Domisili</label>
                                <input type="file" name="file_domisili" class="form-control" accept=".pdf,.jpg,.png,.jpeg">
                                <small class="text-danger mt-1 d-block fw-medium small">* Wajib jika KK belum 1 tahun</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Akta Kelahiran</label>
                                <input type="file" name="file_akta" class="form-control" accept=".pdf,.jpg,.png,.jpeg">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Ijazah / SKL</label>
                                <input type="file" name="file_ijazah" class="form-control" accept=".pdf,.jpg,.png,.jpeg">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Scan Rapor (Semester 1-5)</label>
                                <input type="file" name="file_rapor" class="form-control" accept=".pdf" title="Gabungkan semua rapor menjadi satu file PDF">
                                <small class="text-secondary d-block mt-1 small">Format PDF (Gabungan semua semester)</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Pas Foto Terbaru (3x4)</label>
                                <input type="file" name="file_foto" class="form-control" accept=".jpg,.png,.jpeg">
                            </div>
                        </div>
                        
                        <div class="alert alert-info border-0 bg-info-subtle text-info-emphasis rounded-3 mt-4 d-flex gap-3">
                            <i class="bi bi-info-circle-fill fs-5 flex-shrink-0"></i>
                            <div>
                                <strong>Konfirmasi Data:</strong><br>
                                Dengan menekan tombol kirim, saya menyatakan bahwa data yang saya isikan adalah benar dan dapat dipertanggungjawabkan.
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-5 pt-3">
                            <button type="button" class="btn btn-action btn-outline-theme" onclick="prevStep(5)">
                                <i class="bi bi-arrow-left me-2"></i> Sebelumnya
                            </button>
                            <button type="submit" class="btn btn-action btn-primary-theme px-5">
                                <i class="bi bi-send-fill me-2"></i> Kirim Pendaftaran
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="<?php echo asset('js/file-uploader.js'); ?>"></script>
<script>
// Init File Uploaders
document.addEventListener('DOMContentLoaded', () => {
    new FileUploader('input[name="file_kk"]');
    new FileUploader('input[name="file_domisili"]');
    new FileUploader('input[name="file_akta"]');
    new FileUploader('input[name="file_ijazah"]');
    new FileUploader('input[name="file_rapor"]', { allowedTypes: ['application/pdf'] });
    new FileUploader('input[name="file_foto"]', { allowedTypes: ['image/jpeg', 'image/png', 'image/jpg'] });
    
    // Auto Calculate Rapor Avg
    const inputsNilai = document.querySelectorAll('input[name^="nilai_sem"]');
    inputsNilai.forEach(inp => {
        inp.addEventListener('input', () => {
            let sum = 0; let count = 0;
            inputsNilai.forEach(i => {
                const val = parseFloat(i.value);
                if(!isNaN(val)) { sum += val; count++; }
            });
            const avg = count === 5 ? (sum/5).toFixed(2) : '-';
            const prev = document.getElementById('avgPreview');
            if(prev) prev.textContent = avg;
        });
    });
});

let currentStep = 1;
let map, marker;
const totalSteps = 5;

function nextStep(s) { 
    const currentSection = document.getElementById('step' + s);
    const inputs = currentSection.querySelectorAll('input[required], select[required], textarea[required]');
    let valid = true;
    
    inputs.forEach(input => {
        if (!input.checkValidity()) {
            input.reportValidity();
            valid = false;
        }
    });

    // Step 2 Validation: Check if 5 grades are filled
    if (s === 2) {
        let count = 0;
        document.querySelectorAll('input[name^="nilai_sem"]').forEach(inp => { if(inp.value) count++; });
        if(count < 5) { alert("Lengkapi nilai rapor Sem 1-5!"); valid = false; }
    }
    
    if (valid) {
        currentStep = s + 1; 
        updateSteps(); 
        window.scrollTo({ top: 100, behavior: 'smooth' });
    }
}

function prevStep(s) { 
    currentStep = s - 1; 
    updateSteps(); 
    window.scrollTo({ top: 100, behavior: 'smooth' });
}

function updateSteps() {
    // Hide all sections
    for (let i = 1; i <= totalSteps; i++) {
        document.getElementById('step' + i).classList.remove('active');
        document.getElementById('stepIndicator' + i).classList.remove('active', 'completed');
    }
    
    // Show current section
    document.getElementById('step' + currentStep).classList.add('active');
    
    // Update Indicators
    document.getElementById('stepIndicator' + currentStep).classList.add('active');
    for (let i = 1; i < currentStep; i++) {
        document.getElementById('stepIndicator' + i).classList.add('completed');
    }
    
    // Update Progress Bar
    const progress = ((currentStep - 1) / (totalSteps - 1)) * 100;
    document.getElementById('stepperFill').style.width = progress + '%';
    
    // Initialize map if needed
    if (currentStep === 3 && !map) {
        setTimeout(initMap, 200); // Slight delay for animation
    }
    if (map) setTimeout(() => map.invalidateSize(), 300);
}

// Custom Marker Icon definition
const customIcon = L.divIcon({
    className: 'custom-map-pin',
    html: '<div style="background-color: #1A56DB; width: 24px; height: 24px; border: 3px solid white; border-radius: 50%; box-shadow: 0 4px 10px rgba(0,0,0,0.3);"></div>',
    iconSize: [24, 24],
    iconAnchor: [12, 12]
});



function initMap() {
    if(map) { map.invalidateSize(); return; }
    
    const mapContainer = document.getElementById('mapContainer');
    if (!mapContainer) {
        console.error('Map container not found');
        return;
    }
    
    try {
        map = L.map('mapContainer').setView([-0.9471, 100.4172], 13);
        
        // Primary tile layer with error handling
        const primaryTiles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        });
        
        // Fallback tile layer (Carto)
        const fallbackTiles = L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            maxZoom: 19,
            attribution: '© CARTO'
        });
        
        // Try primary first
        primaryTiles.on('tileerror', function(e) {
            console.warn('Primary tile failed, switching to fallback');
            map.removeLayer(primaryTiles);
            fallbackTiles.addTo(map);
        });
        
        primaryTiles.addTo(map);
        
        // Geolocation
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(pos => {
                map.setView([pos.coords.latitude, pos.coords.longitude], 16);
                setMarker(pos.coords.latitude, pos.coords.longitude);
            }, err => {
                console.warn('Geolocation failed:', err.message);
                showMapWarning('Tidak dapat mengakses lokasi. Silakan pilih lokasi secara manual.');
            });
        }
        
        map.on('click', e => setMarker(e.latlng.lat, e.latlng.lng));
        
    } catch (error) {
        console.error('Map initialization failed:', error);
        showMapWarning('Gagal memuat peta. Periksa koneksi internet Anda.');
    }
}

function showMapWarning(message) {
    const mapContainer = document.getElementById('mapContainer');
    if (mapContainer) {
        const existingWarning = mapContainer.querySelector('.map-warning');
        if (existingWarning) existingWarning.remove();
        
        const warning = document.createElement('div');
        warning.className = 'map-warning alert alert-warning m-3 position-absolute top-0 start-0 end-0 rounded-3 shadow-sm';
        warning.style.zIndex = '1000';
        warning.innerHTML = '<i class="bi bi-exclamation-triangle me-2"></i>' + message;
        mapContainer.style.position = 'relative';
        mapContainer.appendChild(warning);
    }
}

function setMarker(lat, lng) {
    if (marker) map.removeLayer(marker);
    marker = L.marker([lat, lng], {
        draggable: true,
        icon: customIcon
    }).addTo(map);
    
    document.getElementById('lat').value = lat.toFixed(8);
    document.getElementById('lng').value = lng.toFixed(8);
    marker.on('dragend', e => {
        document.getElementById('lat').value = e.target.getLatLng().lat.toFixed(8);
        document.getElementById('lng').value = e.target.getLatLng().lng.toFixed(8);
    });
}

// School Selection Logic
const schoolCards = document.querySelectorAll('.school-card');
const schoolSelectionList = document.getElementById('schoolSelectionList');
const selectionOverlay = document.getElementById('selectionOverlay');
const selectedSchoolNameDisplay = document.getElementById('selectedSchoolName');

schoolCards.forEach(c => {
    c.addEventListener('click', () => {
        if(c.classList.contains('locked')) return;

        // Select the school
        schoolCards.forEach(x => {
            x.classList.remove('selected');
            if(x !== c) x.classList.add('locked', 'opacity-50');
        });
        
        c.classList.add('selected');
        const radio = c.querySelector('input[type="radio"]');
        if(radio) radio.checked = true;

        // Show lock UI
        const schoolName = c.querySelector('h6').textContent;
        selectedSchoolNameDisplay.textContent = schoolName;
        selectionOverlay.classList.remove('d-none');
    });
});

function resetSchoolSelection() {
    schoolCards.forEach(x => {
        x.classList.remove('selected', 'locked', 'opacity-50');
        const radio = x.querySelector('input[type="radio"]');
        if(radio) radio.checked = false;
    });
    selectionOverlay.classList.add('d-none');
}

// SUBMISSION LOGIC (UNCHANGED)
// SUBMISSION LOGIC (STRICT JUKNIS 2025)
document.getElementById('pendaftaranForm').addEventListener('submit', function(e) {
    const tglLahirEl = document.getElementsByName('tanggal_lahir')[0];
    
    if (tglLahirEl && tglLahirEl.value) {
        const tglLahir = new Date(tglLahirEl.value);
        const targetDate = new Date('2025-07-01');
        let years = targetDate.getFullYear() - tglLahir.getFullYear();
        let months = targetDate.getMonth() - tglLahir.getMonth();
        let days = targetDate.getDate() - tglLahir.getDate();
        if (days < 0) { months--; }
        if (months < 0) { years--; }

        if (years > 21) {
            showError("Maaf, usia Anda melebihi batas maksimal (21 tahun per 1 Juli 2025) sesuai ketentuan PPDB.");
            e.preventDefault();
            return;
        }
    }
    
    console.log('Form submission proceeding...');
});

function DateTimeForm(dateStr) { return dateStr ? new Date(dateStr) : new Date(); }

function showError(msg) {
    const errDiv = document.getElementById('js-error');
    const errMsg = document.getElementById('js-error-msg');
    errMsg.innerText = msg;
    errDiv.classList.remove('d-none');
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Check Age Logic (Visual)
const MIN_AGE = 12; 
const MAX_AGE = 15; 

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
    const targetDate = new Date('2025-07-01');
    
    let years = targetDate.getFullYear() - tglLahir.getFullYear();
    let months = targetDate.getMonth() - tglLahir.getMonth();
    let days = targetDate.getDate() - tglLahir.getDate();
    
    if (days < 0) { months--; days += 30; }
    if (months < 0) { years--; months += 12; }
    
    const ageText = years + ' tahun ' + months + ' bulan';
    ageDisplay.textContent = ageText;
    ageInfo.classList.remove('d-none');
    
    if (years < MIN_AGE) {
        ageWarning.classList.remove('d-none', 'alert-warning');
        ageWarning.classList.add('alert-danger'); // Red
        ageWarningText.innerHTML = 'Usia Kurang! Minimal ' + MIN_AGE + ' tahun.';
        tglLahirInput.classList.add('is-invalid');
    } else if (years > MAX_AGE) {
        ageWarning.classList.remove('d-none', 'alert-warning');
        ageWarning.classList.add('alert-danger'); // Red
        ageWarningText.innerHTML = 'Usia Lebih! Maksimal ' + MAX_AGE + ' tahun.';
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
