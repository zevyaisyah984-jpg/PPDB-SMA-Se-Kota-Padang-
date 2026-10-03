<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?> - <?php echo APP_NAME; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo asset('css/style.css'); ?>">
    
    <style>
        :root {
            --sidebar-width: 280px;
            --dashboard-bg: #F9FAFB;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--dashboard-bg);
        }
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 2rem;
            min-height: 100vh;
        }
        .bento-card {
            background: white;
            border-radius: 24px;
            border: 1px solid rgba(0,0,0,0.05);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
            padding: 1.5rem;
            transition: transform 0.3s;
        }
        .bento-card:hover { transform: translateY(-5px); }
        
        .doc-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        
        @media (max-width: 991px) {
            .main-content { margin-left: 0; padding: 1rem; }
        }
    </style>
</head>
<body>

    <?php 
    // Manual mapping for sidebar (usually included via layout, but we're in unified dashboard style)
    $pendaftaranModel = new Pendaftaran();
    $dataPendaftaran = $pendaftaranModel->findByUserId(userId());
    ?>

    <!-- Re-use the same Sidebar from dashboard.php -->
    <nav class="sidebar d-flex flex-column h-100 shadow-sm position-fixed top-0 start-0 bg-white" id="sidebar" style="width: var(--sidebar-width); z-index: 1000;">
        <div class="px-4 py-4 d-flex align-items-center gap-3 border-bottom border-light">
            <div class="bg-primary text-white rounded-3 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                <i class="bi bi-mortarboard-fill fs-5"></i>
            </div>
            <div>
                <h6 class="fw-bold mb-0 text-dark">PPDB Sumbar</h6>
                <span class="text-secondary" style="font-size: 0.75rem;">Panel Siswa</span>
            </div>
        </div>

        <div class="flex-grow-1 py-4 d-flex flex-column gap-1 overflow-y-auto">
            <span class="px-4 text-muted fw-bold mb-2 small text-uppercase" style="font-size: 0.7rem;">Menu Utama</span>
            <a href="<?php echo url('/dashboard'); ?>" class="nav-link px-4 py-2 text-decoration-none text-secondary d-flex align-items-center gap-2">
                <i class="bi bi-grid-fill"></i> Dashboard
            </a>
            <a href="<?php echo url('/siswa/profil'); ?>" class="nav-link px-4 py-2 text-decoration-none text-secondary d-flex align-items-center gap-2">
                <i class="bi bi-person-badge"></i> Data Pokok
            </a>
            <a href="<?php echo url('/siswa/dokumen'); ?>" class="nav-link px-4 py-2 text-decoration-none text-primary fw-bold bg-primary-soft d-flex align-items-center gap-2">
                <i class="bi bi-folder-check text-primary"></i> Dokumen Saya
            </a>
            <a href="<?php echo url('/pengumuman'); ?>" class="nav-link px-4 py-2 text-decoration-none text-secondary d-flex align-items-center gap-2">
                <i class="bi bi-megaphone"></i> Pengumuman
            </a>
        </div>
    </nav>

    <main class="main-content">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0">Dokumen Saya</h4>
                <a href="<?php echo url('/siswa/profil'); ?>" class="btn btn-outline-primary rounded-pill btn-sm px-3">
                    <i class="bi bi-pencil-square me-1"></i> Perbarui Data & File
                </a>
            </div>

            <div class="row g-4">
                <?php 
                $docs = [
                    ['key' => 'file_kk', 'label' => 'Kartu Keluarga', 'icon' => 'bi-people', 'color' => 'bg-info'],
                    ['key' => 'file_akta', 'label' => 'Akta Kelahiran', 'icon' => 'bi-file-person', 'color' => 'bg-primary'],
                    ['key' => 'file_ijazah', 'label' => 'Ijazah / SKL', 'icon' => 'bi-mortarboard', 'color' => 'bg-success'],
                    ['key' => 'file_rapor', 'label' => 'Rapor Semester 1-5', 'icon' => 'bi-file-earmark-spreadsheet', 'color' => 'bg-warning'],
                    ['key' => 'file_domisili', 'label' => 'Ket. Domisili', 'icon' => 'bi-geo-alt', 'color' => 'bg-danger'],
                    ['key' => 'foto', 'label' => 'Pas Foto', 'icon' => 'bi-person-bounding-box', 'color' => 'bg-secondary'],
                ];

                foreach ($docs as $doc):
                    $filename = $siswa[$doc['key']] ?? null;
                    $folder = ($doc['key'] == 'foto') ? 'foto' : 'documents';
                ?>
                <div class="col-md-4 col-sm-6">
                    <div class="bento-card h-100">
                        <div class="doc-icon <?php echo $doc['color']; ?> text-white">
                            <i class="bi <?php echo $doc['icon']; ?>"></i>
                        </div>
                        <h6 class="fw-bold mb-1"><?php echo $doc['label']; ?></h6>
                        <?php if ($filename): ?>
                            <p class="text-success small mb-3"><i class="bi bi-check-circle-fill me-1"></i> Sudah Diunggah</p>
                            <div class="d-flex gap-2">
                                <a href="<?php echo uploads($folder.'/'.$filename); ?>" target="_blank" class="btn btn-light btn-sm flex-grow-1 rounded-pill">
                                    <i class="bi bi-eye me-1"></i> Lihat
                                </a>
                                <a href="<?php echo uploads($folder.'/'.$filename); ?>" download class="btn btn-primary btn-sm rounded-circle">
                                    <i class="bi bi-download"></i>
                                </a>
                            </div>
                        <?php else: ?>
                            <p class="text-muted small mb-3"><i class="bi bi-dash-circle me-1"></i> Belum Ada File</p>
                            <a href="<?php echo url('/siswa/profil'); ?>" class="btn btn-outline-secondary btn-sm w-100 rounded-pill">Unggah Sekarang</a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>

</body>
</html>
