<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa - <?php echo APP_NAME; ?></title>
    <!-- Fonts & Icons -->
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo asset('css/style.css'); ?>">
    
    <style>
        :root {
            --sidebar-width: 280px;
            --dashboard-bg: #F9FAFB;
            
            /* Typography Variables */
            --font-primary: 'Plus Jakarta Sans', sans-serif;
            --font-body: 'Inter', sans-serif;
            --font-numbers: 'Outfit', sans-serif;

            /* Colors (Refined) */
            --text-heading: #1A202C;
            --text-body: #4A5568;
        }
        body {
            font-family: var(--font-primary);
            color: var(--text-body);
            background-color: var(--dashboard-bg);
            overflow-x: hidden;
        }
        
        /* Typography System */
        h1, h2, h3, h4, h5, h6, .fw-bold {
            font-family: var(--font-primary);
            color: var(--text-heading);
            font-weight: 700;
        }
        p, .small, .text-muted, li {
            font-family: var(--font-body);
            line-height: 1.6;
        }
        .small, small { letter-spacing: 0.02em; }
        
        /* Numbers & Stats */
        .display-1, .display-2, .display-3, .display-4, .display-5, .display-6, .h2.mb-0 {
            font-family: var(--font-numbers);
            font-weight: 700;
        }
        
        /* Status Labels */
        .badge {
            font-family: var(--font-primary);
            text-transform: uppercase;
            font-weight: 600;
            font-size: 11px !important;
            letter-spacing: 0.05em;
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: white;
            border-right: 1px solid rgba(0,0,0,0.05);
            z-index: 1000;
            transition: transform 0.3s ease;
        }
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 2rem;
            min-height: 100vh;
            transition: margin 0.3s ease;
        }
        
        .nav-link {
            color: var(--text-secondary);
            font-weight: 500;
            padding: 0.85rem 1.5rem;
            border-radius: 12px;
            margin: 0 1rem;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .nav-link:hover, .nav-link.active {
            background-color: var(--primary-soft);
            color: var(--primary);
        }
        .nav-link.active {
            font-weight: 600;
        }
        .nav-link i { font-size: 1.25rem; }

        /* Bento Cards */
        .bento-card {
            background: white;
            border-radius: 24px;
            border: 1px solid rgba(255,255,255,0.6);
            /* Glassmorphism subtle */
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03); 
            padding: 1.5rem;
            height: 100%;
            transition: transform 0.3s, box-shadow 0.3s;
            overflow: hidden;
            position: relative;
        }
        .bento-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(0, 71, 171, 0.08);
        }
        
        /* Stepper */
        .stepper-track {
            position: relative;
            display: flex;
            justify-content: space-between;
            margin-top: 2rem;
        }
        .stepper-line {
            position: absolute;
            top: 15px;
            left: 0;
            width: 100%;
            height: 2px;
            background: #E2E8F0;
            z-index: 0;
        }
        .stepper-progress {
            position: absolute;
            top: 15px;
            left: 0;
            height: 2px;
            background: var(--primary);
            z-index: 0;
            transition: width 0.5s ease;
        }
        .step-point {
            width: 32px;
            height: 32px;
            background: white;
            border: 2px solid #E2E8F0;
            border-radius: 50%;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            color: #94A3B8;
            font-weight: 700;
            position: relative;
        }
        .step-point.active {
            border-color: var(--primary);
            color: var(--primary);
            box-shadow: 0 0 0 4px var(--primary-soft);
        }
        .step-point.completed {
            background: var(--primary);
            border-color: var(--primary);
            color: white;
        }

        /* Mobile Responsive */
        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 1.5rem 1rem; }
            .overlay {
                display: none;
                position: fixed; top: 0; left: 0; width: 100%; height: 100%;
                background: rgba(0,0,0,0.3); z-index: 999;
            }
            .overlay.show { display: block; }
        }
        
        /* Custom Utilities */
        .text-gradient {
            background: linear-gradient(135deg, var(--primary) 0%, #3B82F6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .bg-glass {
            background: rgba(255,255,255,0.7);
            backdrop-filter: blur(10px);
        }
        .profile-avatar {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            overflow: hidden;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>

    <!-- Prepare Data -->
    <?php 
    // Data Preparation (Logic View)
    $pendaftaranModel = new Pendaftaran();
    $siswaModel = new Siswa();
    $dataPendaftaran = $pendaftaranModel->findByUserId(userId());
    $siswa = $siswaModel->findByUserId(userId());
    
    $status = $dataPendaftaran['status'] ?? 'belum_daftar';
    $sudahDaftar = !empty($dataPendaftaran);
    $namaSiswa = explode(' ', $username)[0]; // First name
    
    // Status Logic
    $statusLabel = 'Belum Terdaftar';
    $statusColor = 'bg-warning text-dark';
    $statusIcon = 'bi-exclamation-circle';
    
    if ($status === 'pending') {
        $statusLabel = 'Proses Verifikasi';
        $statusColor = 'bg-info text-dark';
        $statusIcon = 'bi-hourglass-split';
    } elseif ($status === 'terverifikasi') {
        $statusLabel = 'Dokumen Terverifikasi';
        $statusColor = 'bg-success text-white';
        $statusIcon = 'bi-check-circle-fill';
    } elseif ($status === 'diterima') {
        $statusLabel = 'Diterima';
        $statusColor = 'bg-primary text-white';
        $statusIcon = 'bi-award-fill';
    } elseif ($status === 'ditolak') {
        $statusLabel = 'DITOLAK (TIDAK LOLOS)';
        $statusColor = 'bg-danger text-white';
        $statusIcon = 'bi-x-circle-fill';
    }

    // Step Logic
    // Step 1: Registrasi (Akun OK)
    // Step 2: Unggah Berkas & Pilih Sekolah (Sudah Daftar)
    // Step 3: Verifikasi (Status != pending && != belum_daftar)
    // Step 4: Hasil (Diterima/Ditolak/Pengumuman Open)
    
    $currentStep = 1;
    if ($sudahDaftar) {
        // If already registered, they have completed Step 2 (Unggah Berkas)
        $currentStep = 3; // Default to Verification phase
        
        // If status is final (verified, accepted, or rejected), they are at the 'Hasil' stage
        if ($status === 'terverifikasi' || $status === 'diterima' || $status === 'ditolak') {
            $currentStep = 4;
        }
    }
    ?>

    <!-- Mobile Overlay -->
    <div class="overlay" id="overlay"></div>

    <!-- Sidebar -->
    <nav class="sidebar d-flex flex-column h-100 shadow-sm" id="sidebar">
        <!-- Logo -->
        <div class="px-4 py-4 d-flex align-items-center gap-3 border-bottom border-light">
            <div class="bg-primary text-white rounded-3 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                <i class="bi bi-mortarboard-fill fs-5"></i>
            </div>
            <div>
                <h6 class="fw-bold mb-0 text-dark">PPDB Sumbar</h6>
                <span class="text-secondary" style="font-size: 0.75rem;">Panel Siswa</span>
            </div>
        </div>

        <!-- Menu -->
        <div class="flex-grow-1 py-4 d-flex flex-column gap-1 overflow-y-auto">
            <span class="px-4 text-muted fw-bold mb-2 small text-uppercase" style="font-size: 0.7rem;">Menu Utama</span>
            
            <a href="<?php echo url('/dashboard'); ?>" class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/dashboard') !== false) ? 'active' : ''; ?>">
                <i class="bi bi-grid-fill"></i> Dashboard
            </a>
            <a href="<?php echo url('/siswa/profil'); ?>" class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/siswa/profil') !== false) ? 'active' : ''; ?>">
                <i class="bi bi-person-badge"></i> Data Pokok
            </a>
            <a href="<?php echo url('/siswa/dokumen'); ?>" class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/siswa/dokumen') !== false) ? 'active' : ''; ?>">
                <i class="bi bi-folder-check"></i> Dokumen Saya
            </a>

            
            <span class="px-4 text-muted fw-bold mt-4 mb-2 small text-uppercase" style="font-size: 0.7rem;">Pendaftaran</span>
            <a href="<?php echo url('/daftar/zonasi'); ?>" class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/daftar/zonasi') !== false) ? 'active' : ''; ?>">
                <i class="bi bi-geo-alt"></i> Jalur Zonasi
            </a>
            <a href="<?php echo url('/daftar/afirmasi'); ?>" class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/daftar/afirmasi') !== false) ? 'active' : ''; ?>">
                <i class="bi bi-heart-pulse"></i> Jalur Afirmasi
            </a>
            <a href="<?php echo url('/daftar/prestasi'); ?>" class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/daftar/prestasi') !== false) ? 'active' : ''; ?>">
                <i class="bi bi-trophy"></i> Jalur Prestasi
            </a>
            <a href="<?php echo url('/daftar/mutasi'); ?>" class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/daftar/mutasi') !== false) ? 'active' : ''; ?>">
                <i class="bi bi-truck"></i> Jalur Perpindahan
            </a>
        </div>

        <!-- User Profile (Bottom) -->
        <div class="p-4 border-top border-light bg-light mt-auto">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="profile-avatar border">
                    <?php if (!empty($siswa['foto'])): ?>
                        <img src="<?php echo uploads('foto/' . $siswa['foto']); ?>" alt="Avatar" class="w-100 h-100 object-fit-cover rounded-circle">
                    <?php else: ?>
                        <div class="w-100 h-100 bg-primary-soft text-primary d-flex align-items-center justify-content-center fw-bold">
                            <?php echo strtoupper(substr($username, 0, 1)); ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="overflow-hidden">
                    <h6 class="fw-bold mb-0 text-truncate text-dark" style="font-size: 0.9rem;"><?php echo e($username); ?></h6>
                    <small class="text-muted d-block">Siswa / Calon</small>
                </div>
            </div>
            <a href="<?php echo url('/logout/siswa'); ?>" class="btn btn-outline-danger btn-sm w-100 rounded-pill">
                <i class="bi bi-box-arrow-right me-1"></i> Keluar
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        
        <!-- Notification Banner for Published Results -->
        <?php include 'partials/notification_banner.php'; ?>
        <!-- Topbar Mobile Toggle -->
        <div class="d-lg-none d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold mb-0">Dashboard</h5>
            <button class="btn btn-light rounded-circle shadow-sm" id="sidebarToggle">
                <i class="bi bi-list fs-4"></i>
            </button>
        </div>

        <div class="container-fluid p-0">
            <!-- Hero Section -->
            <div class="row mb-5">
                <div class="col-12">
                    <div class="bento-card bg-white border-0 position-relative p-4 p-lg-5 overflow-hidden">
                        <!-- Decorative BG -->
                        <div class="position-absolute top-0 end-0 p-5 mt-3 me-3 d-none d-md-block opacity-10">
                            <i class="bi bi-mortarboard-fill text-primary" style="font-size: 10rem;"></i>
                        </div>
                        
                        <div class="position-relative z-1">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
                                <div>
                                    <h4 class="fw-bold text-dark mb-1">Halo, <?php echo e($namaSiswa); ?>! 👋</h4>
                                    <p class="text-secondary mb-0">Selamat datang di Panel Seleksi PPDB SMAN Sumbar 2025.</p>
                                </div>
                                <div class="mt-3 mt-md-0">
                                    <span class="badge py-2 px-3 rounded-pill shadow-sm <?php echo $statusColor; ?>">
                                        <i class="bi <?php echo $statusIcon; ?> me-1"></i> <?php echo $statusLabel; ?>
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Progressive Stepper -->
                            <div class="stepper-track mx-2 mx-md-5">
                                <div class="stepper-line"></div>
                                <div class="stepper-progress" style="width: <?php echo ($currentStep - 1) * 33.3; ?>%"></div>
                                
                                <?php 
                                $steps = ['Registrasi', 'Unggah Berkas', 'Verifikasi', 'Hasil'];
                                foreach ($steps as $idx => $label): 
                                    $num = $idx + 1;
                                    $state = ($currentStep > $num) ? 'completed' : (($currentStep == $num) ? 'active' : '');
                                    $checkIcon = ($currentStep > $num) ? '<i class="bi bi-check-lg"></i>' : $num;
                                ?>
                                <div class="d-flex flex-column align-items-center" style="position: relative; z-index: 2;">
                                    <div class="step-point <?php echo $state; ?> mb-2 shadow-sm">
                                        <?php echo $checkIcon; ?>
                                    </div>
                                    <span class="small fw-semibold text-secondary d-none d-md-block"><?php echo $label; ?></span>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bento Grid -->
            <div class="row g-4">
                
                <!-- School Choice Card -->
                <div class="col-md-8">
                    <div class="bento-card d-flex flex-column h-100">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h6 class="fw-bold mb-0"><i class="bi bi-building-fill text-primary me-2"></i>Sekolah Pilihan</h6>
                            <?php if ($sudahDaftar): ?>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill">Jalur <?php echo ucfirst($dataPendaftaran['jalur']); ?></span>
                            <?php endif; ?>
                        </div>
                        
                        <?php if ($sudahDaftar): ?>
                            <div class="d-flex align-items-center gap-4 mb-4">
                                <div class="rounded-4 bg-light p-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                    <i class="bi bi-bank fs-1 text-secondary"></i>
                                </div>
                                <div>
                                    <h4 class="fw-bold mb-1"><?php echo $dataPendaftaran['nama_sekolah']; ?></h4>
                                    <p class="text-secondary mb-0"><i class="bi bi-geo-alt me-1"></i> Jarak: <?php echo number_format($dataPendaftaran['jarak'], 2); ?> km</p>
                                </div>
                            </div>


                        <?php else: ?>
                            <!-- Registration Status Logic -->
                            <div class="text-center py-5">
                                <?php if (isset($is_registration_open) && $is_registration_open): ?>
                                    <div class="mb-3 bg-light rounded-circle d-inline-flex p-3">
                                        <i class="bi bi-search fs-3 text-muted"></i>
                                    </div>
                                    <h6 class="fw-bold">Belum Memilih Sekolah</h6>
                                    <p class="text-muted small mb-4">Silakan pilih jalur pendaftaran untuk memulai.</p>
                                    <a href="<?php echo url('/daftar/zonasi'); ?>" class="btn btn-primary rounded-pill px-4">Mulai Daftar</a>
                                <?php else: ?>
                                    <div class="mb-3 bg-warning bg-opacity-10 rounded-circle d-inline-flex p-3">
                                        <i class="bi bi-calendar-x fs-3 text-warning"></i>
                                    </div>
                                    <h6 class="fw-bold">Pendaftaran Belum Dibuka / Ditutup</h6>
                                    <p class="text-muted small mb-4">
                                        <?php if(isset($jadwal)): ?>
                                            Jadwal Pendaftaran: <br>
                                            <strong><?php echo date('d M Y', strtotime($jadwal['tanggal_mulai'])); ?> - <?php echo date('d M Y', strtotime($jadwal['tanggal_selesai'])); ?></strong>
                                        <?php else: ?>
                                            Mohon tunggu informasi lebih lanjut.
                                        <?php endif; ?>
                                    </p>
                                    <button class="btn btn-secondary rounded-pill px-4" disabled>Pendaftaran Ditutup</button>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Info & Countdown -->
                <div class="col-md-4">
                    <div class="row h-100 g-4">


                        <!-- Digital Documents Card -->
                        <!-- Digital Documents Card (New Universal Print Widget) -->
                        <div class="col-12 flex-grow-1">
                            <div class="bento-card">
                                <h6 class="fw-bold mb-3"><i class="bi bi-printer-fill text-primary me-2"></i>Cetak Bukti</h6>
                                <p class="text-muted small mb-3">Cetak dokumen sesuai status pendaftaran Anda.</p>
                                
                                <div class="d-flex flex-column gap-2">
                                    
                                    <!-- 1. Bukti Pendaftaran (Blue Button) -->
                                    <?php if ($sudahDaftar): ?>
                                        <a href="<?php echo url('/siswa/cetak-pendaftaran'); ?>" class="btn btn-primary w-100 text-start d-flex align-items-center p-3 rounded-pill shadow-sm">
                                            <i class="bi bi-file-earmark-text fs-4 me-3"></i>
                                            <div class="lh-1">
                                                <span class="d-block fw-bold">Bukti Pendaftaran</span>
                                                <small class="opacity-75" style="font-size: 0.75rem;">Siap untuk dicetak</small>
                                            </div>
                                            <i class="bi bi-chevron-right ms-auto"></i>
                                        </a>
                                    <?php else: ?>
                                        <button disabled class="btn btn-outline-secondary w-100 text-start d-flex align-items-center p-3 rounded-pill opacity-50">
                                            <i class="bi bi-file-earmark-text fs-4 me-3"></i>
                                            <div class="lh-1">
                                                <span class="d-block fw-bold">Bukti Pendaftaran</span>
                                                <small style="font-size: 0.75rem;">Belum tersedia</small>
                                            </div>
                                        </button>
                                    <?php endif; ?>

                                    <!-- 2. Bukti Diterima (Conditional based on Publication) -->
                                    <?php 
                                        // Use the data passed from controller if available
                                        $userReg = $pendaftaran ?? null;
                                        
                                        // If not available (edge case), fetch correctly using JOIN
                                        if (!$userReg && isset($_SESSION['user_id'])) {
                                            $db = getConnection();
                                            $stmt = $db->prepare("SELECT p.* FROM pendaftaran p JOIN siswa s ON p.siswa_id = s.id WHERE s.user_id = ?");
                                            $stmt->execute([$_SESSION['user_id']]);
                                            $userReg = $stmt->fetch();
                                        }
                                        
                                        // Normalization for robust status checking
                                        $statusRaw = $userReg['status'] ?? '';
                                        $cleanStatus = strtolower(trim($statusRaw));
                                        
                                        // Check for accepted status (broad check)
                                        $isLulus = in_array($cleanStatus, ['diterima', 'lulus', 'lolos', 'terverifikasi']); 

                                        // Check if results are published
                                        $isPublished = false;
                                        if ($userReg) {
                                            // Ensure function exists (helper)
                                            if (function_exists('isResultPublished')) {
                                                $isPublished = isResultPublished($userReg['sekolah_id'], $userReg['jalur']);
                                            } else {
                                                // Fallback if helper missing, check db directly
                                                $db = getConnection();
                                                $pubStmt = $db->prepare("SELECT status FROM publikasi_hasil WHERE sekolah_id = ? AND jalur = ? AND status = 'published'");
                                                $pubStmt->execute([$userReg['sekolah_id'], $userReg['jalur']]);
                                                $isPublished = ($pubStmt->fetch() !== false);
                                            }
                                        }
                                        
                                        // Can print only if: 1) Lulus AND 2) Published
                                        $canPrint = ($isLulus && $isPublished);
                                    ?>
                                    
                                    <?php if ($canPrint): ?>
                                        <!-- ENABLED: Lulus + Published -->
                                        <a href="<?php echo url('/siswa/cetak-bukti'); ?>" class="btn btn-outline-success w-100 text-start d-flex align-items-center p-3 rounded-pill border-2">
                                            <i class="bi bi-check-circle fs-4 me-3"></i>
                                            <div class="lh-1">
                                                <span class="d-block fw-bold text-dark">Bukti Diterima</span>
                                                <small class="text-muted" style="font-size: 0.75rem;">Surat Keputusan (SK)</small>
                                            </div>
                                            <i class="bi bi-printer ms-auto text-success"></i>
                                        </a>
                                    <?php elseif ($isLulus && !$isPublished): ?>
                                        <!-- WAITING: Lulus but NOT Published -->
                                        <button disabled class="btn btn-outline-warning w-100 text-start d-flex align-items-center p-3 rounded-pill border-2 opacity-75">
                                            <i class="bi bi-hourglass-split fs-4 me-3"></i>
                                            <div class="lh-1">
                                                <span class="d-block fw-bold">Bukti Diterima</span>
                                                <small style="font-size: 0.75rem;">Menunggu publikasi resmi</small>
                                            </div>
                                            <i class="bi bi-lock-fill ms-auto"></i>
                                        </button>
                                    <?php else: ?>
                                        <!-- DISABLED: Not Lulus -->
                                        <button disabled class="btn btn-outline-secondary w-100 text-start d-flex align-items-center p-3 rounded-pill opacity-50">
                                            <i class="bi bi-check-circle fs-4 me-3"></i>
                                            <div class="lh-1">
                                                <span class="d-block fw-bold">Bukti Diterima</span>
                                                <small style="font-size: 0.75rem;">Menunggu hasil seleksi</small>
                                            </div>
                                        </button>
                                    <?php endif; ?>

                                    <!-- 3. Bukti Ditolak (Only if rejected AND published) -->
                                    <?php if ($cleanStatus === 'ditolak' && $isPublished): ?>
                                        <a href="<?php echo url('/siswa/cetak-bukti-ditolak'); ?>" class="btn btn-outline-danger w-100 text-start d-flex align-items-center p-3 rounded-pill border-2 mt-1">
                                            <i class="bi bi-x-circle fs-4 me-3"></i>
                                            <div class="lh-1">
                                                <span class="d-block fw-bold text-dark">Bukti Hasil Seleksi</span>
                                                <small class="text-danger" style="font-size: 0.75rem;">Surat Keterangan Tidak Lulus</small>
                                            </div>
                                            <i class="bi bi-printer ms-auto text-danger"></i>
                                        </a>
                                    <?php endif; ?>

                                    <!-- 3. Bukti Daftar Ulang (Outline Button if Active, else Disabled) -->
                                    <?php if ($isLulus): ?>
                                        <a href="<?php echo url('/siswa/cetak-daftar-ulang'); ?>" class="btn btn-outline-primary w-100 text-start d-flex align-items-center p-3 rounded-pill border-2 mt-1">
                                            <i class="bi bi-patch-check fs-4 me-3"></i>
                                            <div class="lh-1">
                                                <span class="d-block fw-bold text-dark">Bukti Daftar Ulang</span>
                                                <small class="text-muted" style="font-size: 0.75rem;">Formulir registrasi ulang</small>
                                            </div>
                                            <i class="bi bi-printer ms-auto text-primary"></i>
                                        </a>
                                    <?php else: ?>
                                        <button disabled class="btn btn-outline-secondary w-100 text-start d-flex align-items-center p-3 rounded-pill opacity-50 mt-1">
                                            <i class="bi bi-patch-check fs-4 me-3"></i>
                                            <div class="lh-1">
                                                <span class="d-block fw-bold">Bukti Daftar Ulang</span>
                                                <small style="font-size: 0.75rem;">Tersedia setelah diterima</small>
                                            </div>
                                        </button>
                                    <?php endif; ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Announcement / Action -->
                <div class="col-12">
                    <?php if ($status === 'ditolak'): ?>
                        <div class="bento-card bg-danger bg-opacity-10 border-danger border-opacity-25">
                             <div class="d-flex gap-3 align-items-start">
                                <i class="bi bi-exclamation-octagon-fill text-danger fs-4 mt-1"></i>
                                <div>
                                    <?php if ($isPublished): ?>
                                        <h6 class="fw-bold text-dark mb-1">HASIL SELEKSI: TIDAK DITERIMA</h6>
                                        <p class="text-secondary small mb-2">
                                            Mohon maaf, berdasarkan hasil seleksi Anda dinyatakan <strong>TIDAK DITERIMA</strong>.
                                            <?php echo isset($catatan) ? '<br>Alasan: <strong>' . e($catatan) . '</strong>' : ''; ?>
                                        </p>
                                        <a href="<?php echo url('/siswa/cetak-bukti-ditolak'); ?>" class="btn btn-sm btn-danger rounded-pill px-3">
                                            <i class="bi bi-printer me-1"></i> Cetak Bukti Hasil
                                        </a>
                                    <?php else: ?>
                                        <h6 class="fw-bold text-dark mb-1">Pendaftaran Perlu Perbaikan</h6>
                                        <p class="text-secondary small mb-2">
                                            Mohon maaf, berkas Anda ditolak saat verifikasi dan perlu perbaikan.
                                            <?php echo isset($catatan) ? '<br>Catatan: <strong>' . e($catatan) . '</strong>' : '<br>Silakan cek kelengkapan dokumen.'; ?>
                                        </p>
                                        <a href="<?php echo url('/siswa/profil'); ?>" class="btn btn-sm btn-danger rounded-pill px-3">Perbaiki Data</a>
                                    <?php endif; ?>
                                </div>
                             </div>
                        </div>
                    <?php elseif ($status === 'diterima' || $status === 'terverifikasi'): ?>
                         <div class="bento-card bg-success bg-opacity-10 border-success border-opacity-25">
                            <div class="d-flex gap-3 align-items-start">
                                <i class="bi bi-check-circle-fill text-success fs-4 mt-1"></i>
                                <div>
                                    <h6 class="fw-bold text-dark mb-1">Status: <?php echo ucfirst($status); ?></h6>
                                    <p class="text-secondary small mb-2">Selamat! Tahapan Anda sedang berjalan lancar. Pantau terus jadwal selanjutnya.</p>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Default Announcement -->
                        <div class="bento-card bg-warning bg-opacity-10 border-warning border-opacity-25">
                            <div class="d-flex gap-3 align-items-start">
                                <i class="bi bi-megaphone-fill text-warning fs-4 mt-1"></i>
                                <div>
                                    <h6 class="fw-bold text-dark mb-1">Pengumuman Penting</h6>
                                    <p class="text-secondary small mb-2">Verifikasi berkas fisik untuk jalur Zonasi akan dilaksanakan mulai tanggal <strong>25 Juni 2025</strong> di sekolah tujuan masing-masing. Harap membawa dokumen asli.</p>
                                    <a href="#" class="text-primary fw-bold small text-decoration-none">Selengkapnya &rarr;</a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

            </div> <!-- End Row -->
        </div>
    </main>

    <!-- Sticky Help Button -->
    <a href="https://wa.me/6281234567890" target="_blank" class="position-fixed bottom-0 end-0 m-4 btn btn-success rounded-pill shadow-lg d-flex align-items-center gap-2 px-4 py-3" style="z-index: 1050; transition: transform 0.2s;">
        <i class="bi bi-whatsapp fs-4"></i>
        <span class="fw-bold d-none d-md-inline">Bantuan</span>
    </a>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar Toggle
        const toggleBtn = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');

        if(toggleBtn) {
            toggleBtn.addEventListener('click', () => {
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
            });
        }
        if(overlay) {
            overlay.addEventListener('click', () => {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
            });
        }

        // Countdown Logic
        const targetDate = new Date("<?php echo get_setting('tgl_selesai_pendaftaran', '2025-07-01'); ?>").getTime();
        
        function updateCountdown() {
            const now = new Date().getTime();
            const distance = targetDate - now;
            
            if (distance < 0) {
                document.getElementById("cd-days").innerText = "00";
                document.getElementById("cd-hours").innerText = "00";
                document.getElementById("cd-mins").innerText = "00";
                return;
            }
            
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            
            document.getElementById("cd-days").innerText = days.toString().padStart(2, '0');
            document.getElementById("cd-hours").innerText = hours.toString().padStart(2, '0');
            document.getElementById("cd-mins").innerText = minutes.toString().padStart(2, '0');
        }

        setInterval(updateCountdown, 60000); // Update every minute
        updateCountdown(); // Initial call
    </script>
</body>
</html>
