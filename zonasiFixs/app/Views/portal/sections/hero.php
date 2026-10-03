<!-- Hero Section - Gov-Tech Modernism -->
<section id="heroSection" class="hero-section-cobalt">
    <div class="container hero-content">
        <div class="row align-items-center g-5">
            <!-- Left Side - 60% Text & CTA -->
            <div class="col-lg-7 position-relative z-2">
                <!-- Badge -->
                <div class="hero-badge-cobalt animate-fade-in-up">
                    <span class="badge-icon"><i class="bi bi-patch-check-fill"></i></span>
                    <span class="badge-text">Portal Resmi PPDB 2026</span>
                </div>
                
                <!-- Title - High Contrast & Bold -->
                <h1 class="hero-title-cobalt animate-fade-in-up" style="animation-delay: 0.1s">
                    Mulai Masa Depan Hebat<br>
                    <span class="text-gradient-cyan">di SMA Pilihan</span>
                </h1>
                
                <!-- Subtitle -->
                <p class="hero-subtitle-cobalt animate-fade-in-up" style="animation-delay: 0.15s">
                    Nikmati kemudahan pendaftaran sekolah dengan sistem yang <strong>transparan</strong>, <strong>cepat</strong>, dan <strong>terintegrasi</strong>. Pantau hasil seleksi secara real-time dari mana saja.
                </p>
                
                <!-- CTA Buttons -->
                <div class="d-flex flex-column flex-sm-row gap-3 animate-fade-in-up" style="animation-delay: 0.2s">
                    <?php if (isset($is_registration_open) && $is_registration_open): ?>
                        <a href="<?php echo url('/register'); ?>" class="btn-hero-primary pulse-effect">
                            <span>Daftar Sekarang</span>
                            <i class="bi bi-arrow-right-short fs-4"></i>
                        </a>
                    <?php else: ?>
                        <!-- Disabled / Status Button -->
                         <button type="button" class="btn btn-secondary border-0 opacity-75 pe-none" style="background: rgba(255,255,255,0.2); color: #e2e8f0; font-weight: 600; padding: 16px 36px; border-radius: 14px;">
                            <i class="bi bi-lock-fill me-2"></i>
                            <?php 
                            // Determine label text
                            $label = "Pendaftaran Ditutup";
                            if (isset($pendaftaran_jadwal) && !empty($pendaftaran_jadwal)) {
                                $today = date('Y-m-d');
                                if ($today < $pendaftaran_jadwal['tanggal_mulai']) {
                                    $label = "Segera Dibuka: " . date('d M Y', strtotime($pendaftaran_jadwal['tanggal_mulai']));
                                }
                            }
                            echo $label; 
                            ?>
                        </button>
                    <?php endif; ?>
                    <a href="#searchSection" class="btn-hero-glass">
                        <i class="bi bi-search"></i>
                        <span>Cari Sekolah</span>
                    </a>
                </div>
                
                <!-- Stats Row (Mobile Only) -->
                <div class="d-lg-none mt-5 animate-fade-in-up" style="animation-delay: 0.3s">
                    <div class="row g-2">
                        <div class="col-4">
                            <div class="stat-card-mobile">
                                <span class="h3 fw-bold mb-0 text-white"><?php echo isset($stats['total_sekolah']) ? $stats['total_sekolah'] : count($sekolah_list ?? []); ?></span>
                                <small class="text-white-50">Sekolah</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stat-card-mobile">
                                <span class="h3 fw-bold mb-0 text-white"><?php echo isset($stats['total_kuota']) ? number_format($stats['total_kuota']) : 0; ?></span>
                                <small class="text-white-50">Kuota</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stat-card-mobile">
                                <span class="h3 fw-bold mb-0 text-white">4</span>
                                <small class="text-white-50">Jalur</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Side - 40% Visual -->
            <div class="col-lg-5 d-none d-lg-block text-center position-relative">
                <div class="hero-image-wrapper animate-fade-in-up" style="animation-delay: 0.3s">
                    <div class="image-backdrop-glow"></div>
                    <img src="<?php echo asset('images/hero-student-group.png'); ?>" alt="Siswa SMA Belajar" class="hero-main-image">
                    
                    <!-- Floating Glass Cards (Bento Style) -->
                    <div class="float-card card-top-right">
                        <div class="icon-box bg-success-soft text-success">
                            <i class="bi bi-shield-check-fill"></i>
                        </div>
                        <div>
                            <span class="d-block fw-bold text-dark lh-1">Terverifikasi</span>
                            <small class="text-secondary" style="font-size: 0.7rem;">Kemdikbud Ristek</small>
                        </div>
                    </div>
                    
                    <div class="float-card card-bottom-left">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-group">
                                <span class="avatar bg-primary text-white">4K+</span>
                            </div>
                            <div>
                                <span class="d-block fw-bold text-dark lh-1">Siswa Mendaftar</span>
                                <small class="text-secondary" style="font-size: 0.7rem;">Hari ini</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Background Elements -->
    <div class="hero-bg-overlay"></div>
    <div class="hero-shape-bottom">
        <svg viewBox="0 0 1440 100" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
            <path d="M0 40L48 45C96 50 192 60 288 65C384 70 480 70 576 60C672 50 768 30 864 25C960 20 1056 30 1152 45C1248 60 1344 80 1392 90L1440 100V101H1392C1344 101 1248 101 1152 101C1056 101 960 101 864 101C768 101 672 101 576 101C480 101 384 101 288 101C192 101 96 101 48 101H0V40Z" fill="#F8FAFC"/>
        </svg>
    </div>
</section>

<style>
/* Hero Section Cobalt Theme */
.hero-section-cobalt {
    position: relative;
    background-color: #0047AB; /* Fallback */
    background: radial-gradient(circle at top right, #1E3A8A 0%, #0047AB 40%, #002868 100%);
    padding: 9rem 0 6rem;
    overflow: hidden;
    color: #fff;
    min-height: 90vh; /* Full impactful height */
    display: flex;
    align-items: center;
}

/* Background Overlay Pattern */
.hero-bg-overlay {
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background-image: 
        linear-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
    background-size: 40px 40px;
    opacity: 0.3;
    z-index: 1;
}

.hero-content {
    position: relative;
    z-index: 5;
}

/* Badge */
.hero-badge-cobalt {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 8px 16px;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 100px;
    margin-bottom: 24px;
    color: #EBF2FF;
    font-size: 0.85rem;
    font-weight: 600;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.badge-icon { color: #00D4FF; }

/* Typography */
.hero-title-cobalt {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-weight: 800;
    font-size: 3.5rem;
    line-height: 1.1;
    margin-bottom: 1.5rem;
    letter-spacing: -0.03em;
    color: #ffffff; /* Force white for contrast */
    text-shadow: 0 2px 4px rgba(0,0,0,0.1); /* Subtle shadow for legibility */
}

.text-gradient-cyan {
    background: linear-gradient(90deg, #FFFFFF 0%, #00D4FF 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.hero-subtitle-cobalt {
    font-size: 1.15rem;
    color: #E2E8F0; /* Brighter slate for better readability */
    max-width: 540px;
    line-height: 1.7;
    margin-bottom: 2.5rem;
    font-weight: 400;
}

.hero-subtitle-cobalt strong {
    color: #fff;
    font-weight: 600;
}

/* Buttons */
.btn-hero-primary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    padding: 16px 36px;
    background: #FFFFFF;
    color: #0047AB;
    font-weight: 700;
    font-size: 1rem;
    border-radius: 14px;
    text-decoration: none;
    transition: all 0.3s cubic-bezier(0.2, 0.8, 0.2, 1);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

.btn-hero-primary:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
    color: #003380;
}

.btn-hero-glass {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    padding: 16px 36px;
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: #FFFFFF;
    font-weight: 600;
    font-size: 1rem;
    border-radius: 14px;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-hero-glass:hover {
    background: rgba(255, 255, 255, 0.15);
    border-color: rgba(255, 255, 255, 0.4);
    color: #FFFFFF;
}

/* Image & Cards */
.hero-image-wrapper {
    position: relative;
    padding: 20px;
}

.hero-main-image {
    width: 100%;
    max-width: 480px;
    border-radius: 32px;
    /* Fancy shape masking or just rounded rect */
    mask-image: radial-gradient(white 98%, transparent 100%);
    box-shadow: 0 25px 80px -20px rgba(0, 0, 0, 0.5);
    transform: rotate(-2deg);
    transition: transform 0.5s ease;
    border: 4px solid rgba(255, 255, 255, 0.1);
}

.hero-image-wrapper:hover .hero-main-image {
    transform: rotate(0deg) scale(1.02);
}

.image-backdrop-glow {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 120%;
    height: 120%;
    background: radial-gradient(circle, rgba(0, 212, 255, 0.2) 0%, transparent 70%);
    filter: blur(60px);
    z-index: -1;
}

/* Floating Cards */
.float-card {
    position: absolute;
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(16px);
    padding: 14px 20px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    gap: 12px;
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    animation: float-y 4s ease-in-out infinite;
    z-index: 10;
    min-width: 180px;
    text-align: left;
    border: 1px solid rgba(255, 255, 255, 0.8);
}

.card-top-right {
    top: 40px;
    right: 0px;
    animation-delay: 0s;
}

.card-bottom-left {
    bottom: 60px;
    left: 20px;
    animation-delay: 2s;
}

.icon-box {
    width: 40px; height: 40px;
    display: flex; align-items: center; justify-content: center;
    border-radius: 12px;
    font-size: 1.2rem;
}

.bg-success-soft { background: #D1FAE5; }

@keyframes float-y {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

/* Mobile Stats */
.stat-card-mobile {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 16px;
    padding: 15px;
    text-align: center;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

/* Shape */
.hero-shape-bottom {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    z-index: 2;
    line-height: 0;
}

.hero-shape-bottom svg {
    width: 100%;
    height: auto;
    display: block;
}

/* Pulse Effect */
.pulse-effect {
    animation: pulse-border 2s infinite;
}
@keyframes pulse-border {
    0% { box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.4); }
    70% { box-shadow: 0 0 0 10px rgba(255, 255, 255, 0); }
    100% { box-shadow: 0 0 0 0 rgba(255, 255, 255, 0); }
}

/* Responsive */
@media (max-width: 991px) {
    .hero-title-cobalt { font-size: 2.5rem; }
    .hero-image-wrapper { margin-top: 3rem; transform: scale(0.9); }
    .hero-section-cobalt { padding-top: 8rem; text-align: center; } 
    .d-flex.gap-3 { justify-content: center; } /* Center buttons on mobile */
    .hero-subtitle-cobalt { margin-left: auto; margin-right: auto; }
}
</style>
