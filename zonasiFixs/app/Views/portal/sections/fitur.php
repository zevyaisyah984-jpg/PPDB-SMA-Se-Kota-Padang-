<!-- Fitur Utama - 3 Pilar Informasi -->
<section class="section" style="background: var(--surface-bg);">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge badge-primary mb-3">
                <i class="bi bi-stars me-1"></i>Layanan Utama
            </span>
            <h2 class="fw-bold mb-3">Apa yang Bisa Kamu Lakukan?</h2>
            <p class="text-secondary mx-auto" style="max-width: 500px;">
                Tiga fitur utama untuk membantu perjalanan pendaftaranmu
            </p>
        </div>

        <div class="row g-4 justify-content-center">
            <!-- Pilar 1: Alur Pendaftaran -->
            <div class="col-md-6 col-lg-4">
                <div class="card feature-card h-100 border-0">
                    <div class="card-body p-4 text-center">
                        <div class="feature-icon mx-auto">
                            <i class="bi bi-signpost-split-fill"></i>
                        </div>
                        <h4 class="feature-title">Alur Pendaftaran</h4>
                        <p class="feature-desc mb-4">
                            Panduan lengkap dari awal hingga akhir. Ikuti langkah-langkahnya dengan mudah.
                        </p>
                        <button type="button" class="btn btn-sm btn-primary px-4" data-bs-toggle="modal" data-bs-target="#flowModal">
                            <i class="bi bi-arrow-right me-1"></i>Lihat Panduan
                        </button>
                    </div>
                </div>
            </div>

            <!-- Pilar 2: Cek Status -->
            <div class="col-md-6 col-lg-4">
                <div class="card feature-card h-100 border-0">
                    <div class="card-body p-4 text-center">
                        <div class="feature-icon mx-auto" style="background: var(--accent-emerald-light); color: var(--accent-emerald);">
                            <i class="bi bi-search"></i>
                        </div>
                        <h4 class="feature-title">Cek Status</h4>
                        <p class="feature-desc mb-4">
                            Lihat status pendaftaran dan hasil seleksi. Transparan dan real-time.
                        </p>
                        <a href="<?php echo url('/pengumuman'); ?>" class="btn btn-sm btn-success px-4">
                            <i class="bi bi-search me-1"></i>Cek Sekarang
                        </a>
                    </div>
                </div>
            </div>

            <!-- Pilar 3: Bantuan -->
            <div class="col-md-6 col-lg-4">
                <div class="bento-card h-100 p-4 border-0">
                    <div class="text-center">
                        <div class="feature-icon mx-auto mb-3" style="background: var(--accent-orange-light); color: var(--accent-orange);">
                            <i class="bi bi-headset"></i>
                        </div>
                        <h4 class="feature-title fw-bold">Butuh Bantuan?</h4>
                        <p class="text-muted small mb-4">
                            Tim kami siap membantu. Hubungi helpdesk untuk pertanyaan apapun.
                        </p>
                        <?php 
                        // Dynamic Helpdesk Number with cleaning
                        $waNum = preg_replace('/[^0-9]/', '', $wa_helpdesk ?? '6285147985180');
                        if (substr($waNum, 0, 1) === '0') $waNum = '62' . substr($waNum, 1);
                        ?>
                        <a href="https://wa.me/<?php echo $waNum; ?>?text=Halo,%20saya%20butuh%20bantuan%20terkait%20PPDB%20SMA." target="_blank" class="btn btn-primary rounded-pill px-4 w-100">
                            <i class="bi bi-whatsapp me-2"></i>Hubungi Helpdesk
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Info Cards -->
        <div class="row g-3 mt-5">
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-3 d-flex align-items-center">
                        <div class="bg-primary-subtle rounded-3 p-2 me-3">
                            <i class="bi bi-calendar-check text-primary fs-5"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Pendaftaran</small>
                            <span class="fw-bold text-primary">Dibuka</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-3 d-flex align-items-center">
                        <div class="bg-primary-subtle rounded-3 p-2 me-3">
                            <i class="bi bi-clock text-primary fs-5"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Durasi</small>
                            <span class="fw-bold">30 Hari</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-3 d-flex align-items-center">
                        <div class="bg-primary-subtle rounded-3 p-2 me-3">
                            <i class="bi bi-cash-coin text-primary fs-5"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Biaya</small>
                            <span class="fw-bold text-success">GRATIS</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-3 d-flex align-items-center">
                        <div class="bg-primary-subtle rounded-3 p-2 me-3">
                            <i class="bi bi-phone text-primary fs-5"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Akses</small>
                            <span class="fw-bold">HP/Laptop</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
