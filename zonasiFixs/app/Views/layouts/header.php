<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="description" content="Sistem Penerimaan Peserta Didik Baru (PPDB) SMA Negeri Kota Padang Online - Daftar dengan mudah, cepat, dan transparan">
    <meta name="keywords" content="PPDB, SMA, Padang, Zonasi, Online, Pendaftaran, 2026">
    <meta name="theme-color" content="#0047AB">
    <title><?php echo APP_NAME; ?> - Daftar SMA Impianmu</title>
    
    <!-- Preconnect for Performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Fonts - Modern FinTech/EdTech Selection -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Leaflet Map -->
    <link href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" rel="stylesheet">
    
    <!-- Custom Styles -->
    <link rel="stylesheet" href="<?php echo asset('css/style.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('css/design-tokens.css'); ?>">
    
    <!-- Base URL for JS -->
    <script>const BASE_URL = "<?php echo url('/'); ?>";</script>
    
    <!-- ACCESSIBILITY: Base font size and touch targets -->
    <style>
        :root {
            /* Typography Variables */
            --font-primary: 'Plus Jakarta Sans', sans-serif;
            --font-body: 'Inter', sans-serif;
            --font-numbers: 'Outfit', sans-serif;
            
            /* Colors (Refined) */
            --text-heading: #1A202C;
            --text-body: #4A5568;
            --text-muted: #718096;
        }

        html { font-size: 16px; } /* Minimum 16px base */
        
        body { 
            font-family: var(--font-primary); /* Default for UI headers etc */
            color: var(--text-body);
            padding-top: 0; 
        }

        /* Typography Hierarchy */
        h1, h2, h3, h4, h5, h6, .fw-bold {
            font-family: var(--font-primary);
            color: var(--text-heading);
            font-weight: 700;
        }

        p, .small, .text-muted, li, .desc-text {
            font-family: var(--font-body);
            line-height: 1.6;
        }
        
        .small, small {
            letter-spacing: 0.02em; /* Better readability for small text */
        }

        /* Stats & Numbers */
        .display-1, .display-2, .display-3, .display-4, .display-5, .display-6, .stat-value, .counter {
            font-family: var(--font-numbers);
            font-weight: 700;
            letter-spacing: -0.02em; /* Tighter for large numbers */
        }

        /* Status Labels */
        .badge, .status-label, .text-uppercase {
            font-family: var(--font-primary);
            text-transform: uppercase;
            font-weight: 600;
            font-size: 11px !important;
            letter-spacing: 0.05em;
        }
        
        /* Error Text */
        .alert-danger, .text-danger, .invalid-feedback {
            font-family: var(--font-body);
        }
        /* Minimum Touch Target */
        .btn, .nav-link, button, a { min-height: 48px; display: inline-flex; align-items: center; }
        
        /* Floating Glass Navbar */
        .navbar-glass {
            background: transparent; /* Initially transparent */
            backdrop-filter: none;
            border-bottom: 1px solid transparent;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            padding: 1.5rem 0;
        }
        
        .navbar-glass.scrolled {
            background: var(--glass-bg);
            backdrop-filter: var(--glass-blur);
            -webkit-backdrop-filter: var(--glass-blur);
            border-bottom: 1px solid var(--glass-border);
            padding: 1rem 0;
            box-shadow: var(--shadow-sm);
        }
        
        .navbar-brand img { filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1)); }
        
        /* Active Nav State */
        .nav-link { 
            color: var(--text-primary) !important;
            font-weight: 500;
            position: relative;
            padding: 0.5rem 1rem !important;
            border-radius: var(--radius-sm);
            transition: all 0.2s;
        }
        
        .nav-link:hover {
            color: var(--primary) !important;
            background: rgba(0, 71, 171, 0.05);
        }
        
        .nav-link.active {
            color: var(--primary) !important;
            font-weight: 600;
            background: rgba(0, 71, 171, 0.08);
        }
        
        /* High-Contrast Login Button */
        .btn-login {
            background: var(--primary);
            color: white !important;
            font-weight: 600;
            padding: 10px 24px;
            border-radius: var(--radius-md);
            border: none;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-md);
        }
        .btn-login:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: var(--shadow-glow);
        }
        .btn-login:active {
            transform: scale(0.98);
        }
        
        /* Loading Spinner */
        .btn-loading .spinner-border {
            width: 1rem;
            height: 1rem;
            border-width: 2px;
        }
        .btn-loading .btn-text { display: none; }
        .btn-loading .btn-spinner { display: inline-block !important; }
        .btn-spinner { display: none; }
    </style>
</head>
<body>

<!-- Glassmorphism Navbar -->
<nav class="navbar navbar-expand-lg navbar-glass fixed-top" id="mainNavbar">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-3" href="<?php echo url('/'); ?>">
            <img src="<?php echo asset('images/logo_kemdikbud.png'); ?>" alt="Logo" height="48">
            <div class="d-none d-md-block lh-1">
                <span class="d-block fw-bold text-dark fs-6">PPDB ONLINE</span>
                <small class="text-secondary fw-medium" style="font-size: 0.75rem;">Provinsi Sumatera Barat</small>
            </div>
        </a>
        
        <button class="navbar-toggler border-0 p-2 glass" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <i class="bi bi-list fs-4 text-dark"></i>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center gap-lg-2">
                <?php 
                $currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '';
                function isActive($needle) {
                    $currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '';
                    return strpos($currentPath, $needle) !== false ? 'active' : '';
                }
                ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($currentPath === '/' || rtrim($currentPath, '/') === rtrim(BASE_PATH, '/')) ? 'active' : ''; ?>" href="<?php echo url('/'); ?>">
                        Beranda
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        Informasi
                    </a>
                    <ul class="dropdown-menu border-0 shadow-xl rounded-4 p-2 mt-2">
                        <li><a class="dropdown-item rounded-3 py-2 px-3 mb-1" href="<?php echo url('/jadwal'); ?>">Jadwal Pelaksanaan</a></li>
                        <li><a class="dropdown-item rounded-3 py-2 px-3 mb-1" href="<?php echo url('/persyaratan'); ?>">Persyaratan & Aturan</a></li>
                        <li><a class="dropdown-item rounded-3 py-2 px-3" href="<?php echo url('/kuota'); ?>">Daya Tampung</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo isActive('/pengumuman'); ?>" href="<?php echo url('/pengumuman'); ?>">
                        Pengumuman
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo isActive('/monitoring'); ?>" href="<?php echo url('/monitoring'); ?>">
                        Pantau Hasil
                    </a>
                </li>
                <li class="nav-item ms-lg-4 mt-3 mt-lg-0">
                    <a class="btn btn-login d-flex align-items-center gap-2" href="<?php echo url('/login'); ?>" id="btnMasuk" onclick="handleLoginClick(this)">
                        <span class="btn-text">Masuk / Daftar</span>
                        <i class="bi bi-arrow-right"></i>
                        <span class="btn-spinner spinner-border spinner-border-sm text-white" role="status"></span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Scripts -->
<script>
window.addEventListener('scroll', function() {
    const navbar = document.getElementById('mainNavbar');
    if (window.scrollY > 20) {
        navbar.classList.add('scrolled');
    } else {
        navbar.classList.remove('scrolled');
    }
});

function handleLoginClick(btn) {
    btn.classList.add('btn-loading');
}
</script>
