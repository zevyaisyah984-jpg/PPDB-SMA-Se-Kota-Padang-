<?php
// app/Views/portal/sections/mekanisme.php
// NEW DESIGN: Hybrid Dashboard (Stats + Mechanism)
// Responsive, Premium, & High Value
?>
<section id="mekanisme" class="py-5 bg-surface-primary position-relative overflow-hidden" style="background: #F8FAFC;">
    
    <!-- Background Gradient Orbs -->
    <div class="position-absolute top-0 start-0 w-100 h-100 overflow-hidden pointer-events-none" style="z-index: 0;">
        <div class="position-absolute top-0 start-0 bg-primary opacity-5 rounded-circle blur-3xl" style="width: 600px; height: 600px; filter: blur(100px); transform: translate(-30%, -30%);"></div>
        <div class="position-absolute bottom-0 end-0 bg-info opacity-5 rounded-circle blur-3xl" style="width: 500px; height: 500px; filter: blur(80px); transform: translate(20%, 20%);"></div>
    </div>

    <div class="container position-relative py-4" style="z-index: 1;">
        
        <!-- Header -->
        <div class="row mb-5 align-items-end">
            <div class="col-lg-8">
                <span class="badge bg-white text-primary border shadow-sm rounded-pill px-3 py-2 mb-3">
                    <i class="bi bi-rocket-takeoff-fill me-2"></i>Panduan & Data Realtime
                </span>
                <h2 class="display-5 fw-extrabold text-slate-800 mb-3" style="letter-spacing: -1px; color: #1e293b;">
                    Alur & Informasi <span class="text-primary">PPDB 2026</span>
                </h2>
                <p class="text-muted fs-5 fw-light" style="max-width: 650px;">
                    Ikuti <strong>4 Langkah Mudah</strong> mendaftar sekolah impianmu dan pantau ketersediaan kuota secara transparan.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end d-none d-lg-block">
                <a href="<?php echo url('/persyaratan'); ?>" class="btn btn-outline-primary rounded-pill px-4 fw-bold">
                    Pelajari Persyaratan <i class="bi bi-arrow-right ms-2"></i>
                </a>
            </div>
        </div>

        <!-- HYBRID BENTO GRID -->
        <div class="bento-grid-wrapper">
            
            <!-- 1. LEFT COLUMN: MECHANISM STEPS (Vertical Flow) -->
            <div class="bento-card bg-white p-0 overflow-hidden d-flex flex-column" style="grid-area: steps;">
                <div class="p-4 border-bottom bg-light bg-opacity-50">
                    <h5 class="fw-bold mb-1"><i class="bi bi-list-task me-2 text-primary"></i>Alur Pendaftaran</h5>
                    <small class="text-muted">Proses dari awal hingga pengumuman.</small>
                </div>
                <div class="p-4 d-flex flex-column justify-content-between h-100 gap-4">
                    <!-- Step 1 -->
                    <div class="d-flex gap-3 position-relative step-item">
                        <div class="step-line"></div>
                        <div class="step-icon bg-primary text-white shadow-primary">1</div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1">Buat Akun</h6>
                            <p class="text-secondary small mb-0 lh-sm">Gunakan NISN & NIK untuk aktivasi akun siswa.</p>
                        </div>
                    </div>
                    <!-- Step 2 -->
                    <div class="d-flex gap-3 position-relative step-item">
                        <div class="step-line"></div>
                        <div class="step-icon bg-white border border-2 border-primary text-primary">2</div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1">Pilih Sekolah & Jalur</h6>
                            <p class="text-secondary small mb-0 lh-sm">Tentukan jalur (Zonasi/Prestasi) dan unggah berkas.</p>
                        </div>
                    </div>
                    <!-- Step 3 -->
                    <div class="d-flex gap-3 position-relative step-item">
                        <div class="step-line"></div>
                        <div class="step-icon bg-white border border-2 border-primary text-primary">3</div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1">Verifikasi Berkas</h6>
                            <p class="text-secondary small mb-0 lh-sm">Admin sekolah memverifikasi data Anda.</p>
                        </div>
                    </div>
                    <!-- Step 4 -->
                    <div class="d-flex gap-3 step-item">
                        <div class="step-icon bg-success text-white shadow-success"><i class="bi bi-check-lg"></i></div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1">Pengumuman & Lapor Diri</h6>
                            <p class="text-secondary small mb-0 lh-sm">Cek kelulusan dan lakukan daftar ulang online.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. CENTER TOP: TOTAL QUOTA CHART (Visual) -->
            <div class="bento-card bg-primary text-white p-4 position-relative overflow-hidden" style="grid-area: quota;">
                <div class="d-flex justify-content-between align-items-start position-relative z-2">
                    <div>
                        <div class="badge bg-white bg-opacity-20 backdrop-blur text-white mb-3">
                            <i class="bi bi-pie-chart-fill me-1"></i> Data Kuota
                        </div>
                        <h2 class="display-4 fw-bold mb-0 font-numeric">
                            <?php echo isset($stats['total_kuota']) ? number_format($stats['total_kuota']) : 0; ?>
                        </h2>
                        <span class="text-white-50 small text-uppercase fw-semibold ls-1">Total Daya Tampung</span>
                    </div>
                    <!-- CSS Pie Chart Representation -->
                    <div class="pie-chart-mockup shadow-lg">
                        <svg viewBox="0 0 32 32" width="80" height="80">
                            <circle r="16" cx="16" cy="16" fill="rgba(255,255,255,0.1)" />
                            <circle r="16" cx="16" cy="16" fill="transparent" stroke="white" stroke-width="32" stroke-dasharray="75 100" class="chart-segment" />
                        </svg>
                        <div class="position-absolute top-50 start-50 translate-middle text-center small fw-bold" style="font-size: 10px;">
                            <?php echo isset($stats['total_kuota']) && $stats['total_kuota'] > 0 ? 'ON' : '0%'; ?>
                        </div>
                    </div>
                </div>
                
                <?php
                // Calculate percentages based on actual data
                $total_q = isset($stats['total_kuota']) && $stats['total_kuota'] > 0 ? $stats['total_kuota'] : 1;
                $pct_zonasi = isset($stats['total_zonasi']) ? round(($stats['total_zonasi'] / $total_q) * 100) : 0;
                $pct_prestasi = isset($stats['total_prestasi']) ? round(($stats['total_prestasi'] / $total_q) * 100) : 0;
                $pct_afirmasi = isset($stats['total_afirmasi']) ? round(($stats['total_afirmasi'] / $total_q) * 100) : 0;
                
                // Ensure at least some default if no data (e.g. 50/30/15)
                if ($total_q === 1) { 
                    $pct_zonasi = 50; $pct_prestasi = 30; $pct_afirmasi = 15; 
                }
                ?>
                <div class="mt-4 pt-3 border-top border-white border-opacity-10 position-relative z-2">
                    <div class="row g-2 text-center text-white-50 small">
                        <div class="col">
                            <strong class="d-block text-white"><?php echo $pct_zonasi; ?>%</strong> Zonasi
                        </div>
                        <div class="col border-start border-white border-opacity-10">
                            <strong class="d-block text-white"><?php echo $pct_prestasi; ?>%</strong> Prestasi
                        </div>
                        <div class="col border-start border-white border-opacity-10">
                            <strong class="d-block text-white"><?php echo $pct_afirmasi; ?>%</strong> Afirmasi
                        </div>
                    </div>
                </div>

                <!-- Patterns -->
                 <div class="position-absolute bottom-0 end-0 opacity-10 p-3">
                    <i class="bi bi-grid-3x3-gap-fill fs-1"></i>
                 </div>
            </div>

            <!-- 3. CENTER BOTTOM: STATISTICS ROW -->
            <div class="bento-card bg-white p-3 d-flex align-items-center gap-3" style="grid-area: stat1;">
                <div class="rounded-circle bg-indigo-50 text-indigo p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                    <i class="bi bi-building fs-4 text-primary"></i>
                </div>
                <div>
                    <h4 class="fw-bold text-dark mb-0 font-numeric">
                        <?php echo isset($stats['total_sekolah']) ? $stats['total_sekolah'] : count($sekolah_list ?? []); ?>
                    </h4>
                    <span class="text-muted small">Sekolah Negeri</span>
                </div>
            </div>

            <div class="bento-card bg-white p-3 d-flex align-items-center gap-3" style="grid-area: stat2;">
                <div class="rounded-circle bg-orange-50 text-orange p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                    <i class="bi bi-people fs-4 text-warning"></i>
                </div>
                <div>
                    <h4 class="fw-bold text-dark mb-0 font-numeric">
                        <?php echo isset($stats['total_pendaftar']) ? number_format($stats['total_pendaftar']) : '0'; ?>
                    </h4>
                    <span class="text-muted small">Siswa Mendaftar</span>
                </div>
            </div>

            <!-- 4. RIGHT COLUMN: LIVE UPDATE & CTA -->
            <div class="bento-card p-4 d-flex flex-column justify-content-between position-relative overflow-hidden" style="grid-area: cta; background: linear-gradient(145deg, #1e293b 0%, #0f172a 100%); border: 1px solid rgba(255,255,255,0.1);">
                
                <!-- Live Indicator -->
                <div class="position-relative z-2 mb-4">
                    <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill" style="background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.3);">
                        <span class="pulsing-red-dot"></span>
                        <span class="text-uppercase fw-bold" style="color: #ef4444; font-size: 0.75rem; letter-spacing: 1px;">Live Update</span>
                    </div>
                </div>

                <div class="position-relative z-2">
                    <h3 class="fw-bold mb-2 text-white" style="text-shadow: 0 2px 4px rgba(0,0,0,0.3);">Pantau Hasil Seleksi</h3>
                    <p class="text-slate-300 small mb-4" style="color: #cbd5e1; line-height: 1.6;">
                        Lihat perankingkan siswa secara real-time. Data diperbarui otomatis setiap detik.
                    </p>
                </div>

                <div class="position-relative z-2 mt-auto">
                    <a href="<?php echo url('/monitoring'); ?>" class="btn w-100 rounded-pill fw-bold py-2 mb-2 d-flex align-items-center justify-content-center gap-2" style="background: #3b82f6; color: white; border: none; box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.5);">
                        <i class="bi bi-bar-chart-fill"></i> Lihat Monitoring
                    </a>
                    <a href="<?php echo url('/register'); ?>" class="btn w-100 rounded-pill fw-bold py-2" style="background: rgba(255,255,255,0.05); color: white; border: 1px solid rgba(255,255,255,0.2); backdrop-filter: blur(4px);">
                        Daftar Sekarang
                    </a>
                </div>

                <!-- Decorative Gradients -->
                <div class="position-absolute top-0 end-0 bg-primary opacity-20 rounded-circle blur-3xl" style="width: 150px; height: 150px; transform: translate(30%, -30%); filter: blur(50px);"></div>
                <div class="position-absolute bottom-0 start-0 bg-purple opacity-20 rounded-circle blur-3xl" style="width: 150px; height: 150px; transform: translate(-30%, 30%); filter: blur(50px); background: #8b5cf6;"></div>
            </div>

        </div>
    </div>
</section>

<style>
/* FONT SETUP */
.font-numeric { font-family: 'Outfit', sans-serif; }
.ls-1 { letter-spacing: 1px; }

/* BENTO GRID LAYOUT */
.bento-grid-wrapper {
    display: grid;
    gap: 24px;
    grid-template-columns: repeat(4, 1fr);
    grid-template-rows: auto auto; 
    grid-template-areas: 
        "steps quota quota cta"
        "steps stat1 stat2 cta";
}

/* CARDS */
.bento-card {
    border-radius: 24px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
    border: 1px solid rgba(0,0,0,0.04);
    transition: all 0.3s ease;
}
.bento-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.01);
}

/* STEPPER */
.step-item { min-height: 80px; }
.step-icon {
    width: 32px; height: 32px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-weight: bold;
    font-size: 14px;
    z-index: 2;
    flex-shrink: 0;
}
.step-line {
    position: absolute;
    top: 32px; left: 15px;
    width: 2px; height: 100%;
    background: #e2e8f0;
    z-index: 1;
}
.step-item:last-child .step-line { display: none; }
.shadow-primary { box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.2); }
.shadow-success { box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.2); }

/* PIE CHART MOCKUP */
.pie-chart-mockup circle { transition: stroke-dasharray 1s ease; }

/* PULSING DOT */
.pulsing-red-dot {
    width: 10px; height: 10px;
    background: #ef4444;
    border-radius: 50%;
    box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7);
    animation: pulse-red 1.5s infinite;
}
@keyframes pulse-red {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
    70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(239, 68, 68, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
}

/* RESPONSIVE */
@media (max-width: 992px) {
    .bento-grid-wrapper {
        grid-template-columns: 1fr 1fr;
        grid-template-areas: 
            "quota quota"
            "stat1 stat2"
            "steps steps"
            "cta cta";
    }
}
@media (max-width: 576px) {
    .bento-grid-wrapper {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }
    .step-item { min-height: auto; padding-bottom: 20px; }
}
</style>
