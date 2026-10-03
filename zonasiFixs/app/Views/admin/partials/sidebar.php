<nav id="admin-sidebar">
    <div class="sidebar-header">
        <a href="#" class="sidebar-brand-wrapper">
            <div class="sidebar-brand-icon">
                <i class="bi bi-grid-1x2-fill"></i>
            </div>
            <div class="sidebar-brand-text">
                ADMIN PANEL<br>
                <span class="text-muted fw-normal" style="font-size: 0.75rem;">PPDB SMA Padang</span>
            </div>
        </a>
    </div>

    <!-- Scrollable Menu -->
    <div style="flex: 1; overflow-y: auto;">
        
        <!-- MAIN -->
        <div class="sidebar-menu-group">
            <a class="sidebar-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/admin') !== false && strpos($_SERVER['REQUEST_URI'], '/admin/') === false) ? 'active' : ''; ?>" href="<?php echo url('/admin'); ?>">
                <i class="bi bi-house-door-fill"></i> Dashboard
            </a>
        </div>

        <?php if ($_SESSION['admin_role'] === 'super_admin'): ?>
        <!-- MASTER DATA -->
        <div class="sidebar-menu-heading mt-4">Master Data</div>
        <div class="sidebar-menu-group">
            <a class="sidebar-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/admin/sekolah') !== false) ? 'active' : ''; ?>" href="<?php echo url('/admin/sekolah'); ?>">
                <i class="bi bi-buildings"></i> Data Sekolah
            </a>
            <a class="sidebar-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/admin/kuota') !== false) ? 'active' : ''; ?>" href="<?php echo url('/admin/kuota'); ?>">
                <i class="bi bi-pie-chart"></i> Pengaturan Kuota
            </a>
            <a class="sidebar-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/admin/jadwal') !== false) ? 'active' : ''; ?>" href="<?php echo url('/admin/jadwal'); ?>">
                <i class="bi bi-calendar-event"></i> Jadwal & Sistem
            </a>
            <a class="sidebar-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/admin/users') !== false) ? 'active' : ''; ?>" href="<?php echo url('/admin/users'); ?>">
                <i class="bi bi-people"></i> Manajemen User
            </a>
        </div>
        <?php endif; ?>

        <!-- PENDAFTARAN -->
        <div class="sidebar-menu-heading mt-4">Pendaftaran</div>
        <div class="sidebar-menu-group">
            <a class="sidebar-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/admin/pendaftar') !== false) ? 'active' : ''; ?>" href="<?php echo url('/admin/pendaftar'); ?>">
                <i class="bi bi-files"></i> Kelola Pendaftar
            </a>
        </div>

        <?php if ($_SESSION['admin_role'] === 'school_admin'): ?>
        <!-- SEKOLAH SAYA -->
        <div class="sidebar-menu-heading mt-4">Sekolah Saya</div>
        <div class="sidebar-menu-group">
            <a class="sidebar-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/admin/profil-sekolah') !== false) ? 'active' : ''; ?>" href="<?php echo url('/admin/profil-sekolah'); ?>">
                <i class="bi bi-building-gear"></i> Profil Sekolah
            </a>
            <a class="sidebar-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/admin/statistik') !== false) ? 'active' : ''; ?>" href="<?php echo url('/admin/statistik'); ?>">
                <i class="bi bi-bar-chart-line"></i> Statistik
            </a>
            <a class="sidebar-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/admin/cetak-bukti') !== false) ? 'active' : ''; ?>" href="<?php echo url('/admin/cetak-bukti'); ?>">
                <i class="bi bi-printer"></i> Cetak Bukti
            </a>
        </div>
        <?php endif; ?>

        <!-- SELEKSI -->
        <div class="sidebar-menu-heading mt-4">Seleksi & Hasil</div>
        <div class="sidebar-menu-group">
            
            <a class="sidebar-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/admin/seleksi/hasil') !== false) ? 'active' : ''; ?>" href="<?php echo url('/admin/seleksi/hasil'); ?>">
                <i class="bi bi-trophy"></i> Hasil Akhir
            </a>

            <?php if ($_SESSION['admin_role'] === 'super_admin'): ?>
            <a class="sidebar-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/admin/seleksi/publikasi') !== false) ? 'active' : ''; ?>" href="<?php echo url('/admin/seleksi/publikasi'); ?>">
                <i class="bi bi-megaphone"></i> Publikasi
            </a>
            <a class="sidebar-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/admin/seleksi/logs') !== false) ? 'active' : ''; ?>" href="<?php echo url('/admin/logs'); ?>">
                <i class="bi bi-activity"></i> Log Aktivitas
            </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- LOGOUT -->
    <div class="mt-auto pt-4 border-top">
        <a class="sidebar-link text-danger" href="<?php echo url('/admin/logout'); ?>">
            <i class="bi bi-box-arrow-right text-danger"></i> Keluar Sistem
        </a>
    </div>
</nav>
