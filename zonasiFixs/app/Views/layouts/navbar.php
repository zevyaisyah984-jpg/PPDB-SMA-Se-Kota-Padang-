<nav class="navbar navbar-expand-lg sticky-top py-3">
    <div class="container">
        <a class="navbar-brand fw-bold d-flex align-items-center fs-4" href="<?php echo url('/'); ?>" style="padding-top: 15px; padding-left: 20px; max-width: 80%;">
            <img src="<?php echo asset('images/logo_kemdikbud.png'); ?>" alt="Logo" width="55" height="55" class="me-3 fluid-logo">
            <span class="d-inline-block text-truncate responsive-brand">PPDB SMA Negeri Kota Padang</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <i class="bi bi-list fs-1 text-primary"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <?php 
            $currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '';
            $isHome = ($currentPath == '/' || rtrim($currentPath, '/') == rtrim(BASE_PATH, '/'));
            $isKuota = (strpos($currentPath, 'kuota') !== false);
            $isJadwal = (strpos($currentPath, 'jadwal') !== false);
            $isPersyaratan = (strpos($currentPath, 'persyaratan') !== false);
            ?>
            <ul class="navbar-nav ms-auto align-items-center" style="font-size: 15px;">
                <li class="nav-item">
                    <a class="nav-link <?php echo $isHome ? 'active-underline' : ''; ?>" href="<?php echo url('/'); ?>">Beranda</a>
                </li>
                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?php echo ($isJadwal || $isPersyaratan) ? 'active-underline' : ''; ?>" href="#" role="button" data-bs-toggle="dropdown">
                        Informasi
                    </a>
                    <ul class="dropdown-menu shadow-lg border-0 animate-fade-in">
                        <li><a class="dropdown-item" href="<?php echo url('pengumuman'); ?>">Pengumuman</a></li>
                        <li><a class="dropdown-item" href="<?php echo url('jadwal'); ?>">Jadwal</a></li>
                        <li><a class="dropdown-item" href="<?php echo url('persyaratan'); ?>">Jalur</a></li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?php echo $isKuota ? 'active-underline' : ''; ?>" href="<?php echo url('kuota'); ?>">Kuota Pendaftaran</a>
                </li>

                <li class="nav-item ms-lg-3 mt-3 mt-lg-0">
                    <a class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold js-btn-login" href="<?php echo url('login'); ?>" style="background: linear-gradient(135deg, #0d6efd 0%, #0056b3 100%); border: none;">
                        <i class="bi bi-person-circle me-1"></i> Login Siswa
                    </a>
                </li>


            </ul>
        </div>
    </div>
</nav>

<style>
    .responsive-brand {
        font-size: 1.25rem;
    }
    @media (max-width: 991px) {
        .navbar-nav {
            text-align: center;
            padding: 20px 0;
        }
        .nav-item {
            margin: 10px 0;
        }
    }
    @media (max-width: 768px) {
        .responsive-brand {
            font-size: 1rem;
            white-space: normal;
            line-height: 1.2;
        }
        .fluid-logo {
            width: 40px;
            height: 40px;
        }
        .navbar-brand {
            padding-left: 10px !important;
            padding-top: 5px !important;
        }
    }
    @media (max-width: 480px) {
        .responsive-brand {
            font-size: 0.9rem;
        }
        .navbar-brand img {
            width: 35px;
            height: 35px;
            margin-right: 10px !important;
        }
    }
</style>
