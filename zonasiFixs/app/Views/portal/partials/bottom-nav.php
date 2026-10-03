<!-- Bottom Navigation - Mobile Only -->
<nav class="bottom-nav" id="bottomNav">
    <a href="<?php echo url('/'); ?>" class="bottom-nav-item <?php echo (strpos($_SERVER['REQUEST_URI'], '/siswa') === false && strpos($_SERVER['REQUEST_URI'], '/pengumuman') === false) ? 'active' : ''; ?>">
        <i class="bi bi-house-fill"></i>
        <span>Home</span>
    </a>
    <a href="<?php echo url('/siswa/login'); ?>" class="bottom-nav-item <?php echo (strpos($_SERVER['REQUEST_URI'], '/siswa') !== false) ? 'active' : ''; ?>">
        <i class="bi bi-person-plus-fill"></i>
        <span>Daftar</span>
    </a>
    <a href="<?php echo url('/pengumuman'); ?>" class="bottom-nav-item <?php echo (strpos($_SERVER['REQUEST_URI'], '/pengumuman') !== false) ? 'active' : ''; ?>">
        <i class="bi bi-megaphone-fill"></i>
        <span>Hasil</span>
    </a>
    <a href="<?php echo url('/siswa/login'); ?>" class="bottom-nav-item">
        <i class="bi bi-person-circle"></i>
        <span>Akun</span>
    </a>
</nav>
