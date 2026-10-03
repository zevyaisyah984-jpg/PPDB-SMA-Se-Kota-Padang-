<style>
    /* Announcement Hub Specific Styles */
    .hero-hub {
        background: linear-gradient(135deg, #0052CC 0%, #003380 100%);
        color: white;
        padding: 4rem 0 6rem;
        position: relative;
        overflow: hidden;
    }
    
    .hero-hub::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: url('<?php echo asset('images/pattern-dot.png'); ?>') repeat;
        opacity: 0.1;
    }

    /* Timeline Stepper */
    .timeline-steps {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        margin-top: -3rem;
        position: relative;
        z-index: 10;
        margin-bottom: 2rem;
    }
    
    .timeline-step {
        width: 15rem;
        text-align: center;
        position: relative;
        padding: 1rem;
    }
    
    .timeline-step::before {
        content: '';
        position: absolute;
        top: 2rem;
        left: 50%;
        width: 100%;
        height: 4px;
        background: #e9ecef;
        z-index: -1;
    }
    
    .timeline-step:last-child::before { display: none; }
    
    .step-circle {
        width: 3rem;
        height: 3rem;
        background: white;
        border: 4px solid #e9ecef;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-weight: bold;
        color: #adb5bd;
        transition: all 0.3s;
    }
    
    .timeline-step.active .step-circle {
        border-color: #0052CC;
        color: #0052CC;
        box-shadow: 0 0 0 4px rgba(0, 82, 204, 0.2);
    }
    
    .timeline-step.completed .step-circle {
        background: #0052CC;
        border-color: #0052CC;
        color: white;
    }
    
    .timeline-step.completed::before,
    .timeline-step.active::before {
        background: #0052CC;
    }

    /* Quick Check Box */
    .quick-check-box {
        background: white;
        border-radius: 24px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        padding: 2rem;
        margin-top: 2rem;
    }
    
    #searchResult {
        display: none;
        margin-top: 1.5rem;
        padding: 1rem;
        border-radius: 12px;
        text-align: center;
        animation: fadeIn 0.3s ease-in;
    }
    
    .result-success { background-color: #d1fae5; color: #065f46; border: 1px solid #10b981; }
    .result-fail { background-color: #fee2e2; color: #991b1b; border: 1px solid #ef4444; }
    .result-pending { background-color: #fef3c7; color: #92400e; border: 1px solid #f59e0b; }
    
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>

<!-- HERO SECTION -->
<section class="hero-hub text-center">
    <div class="container">
        
        <!-- Dynamic Header Message based on Stage -->
        <?php 
            // Logic to determine main message
            $headerTitle = "Pusat Informasi PPDB 2025";
            $headerDesc = "Dapatkan informasi resmi terkini seputar Penerimaan Peserta Didik Baru Provinsi Sumatera Barat.";
            $btnText = "Panduan Pendaftaran";
            $btnLink = url('/persyaratan');
            $btnClass = "btn-light text-primary";
            
            // Check Schedule Context
            $today = date('Y-m-d');
            $isRegistrationOpen = false;
            $isAnnouncementDay = false;

            if (!empty($jadwal)) {
                foreach($jadwal as $j) {
                    if (strpos(strtolower($j['nama_kegiatan']), 'pendaftaran') !== false && 
                        $today >= $j['tanggal_mulai'] && $today <= $j['tanggal_selesai']) {
                        $isRegistrationOpen = true;
                    }
                    if (strpos(strtolower($j['nama_kegiatan']), 'pengumuman') !== false && 
                        $today >= $j['tanggal_mulai']) {
                        $isAnnouncementDay = true;
                    }
                }
            }
            
            if ($isAnnouncementDay) {
                $headerTitle = "HASIL SELEKSI TELAH TERBIT!";
                $headerDesc = "Pengumuman hasil seleksi PPDB Jalur Zonasi sudah dapat diakses hari ini. Silakan cek status kelulusan Anda.";
                $btnText = "Cek Hasil Seleksi";
                $btnCheck = true; // Flag to scroll to search box
            } elseif ($isRegistrationOpen) {
                $headerTitle = "PENDAFTARAN RESMI DIBUKA!";
                $headerDesc = "Segera daftarkan diri Anda sebelum kuota terpenuhi. Pastikan seluruh berkas telah lengkap.";
                $btnText = "Daftar Sekarang";
                $btnLink = url('/login'); // Or dashboard if logged in
                $btnClass = "btn-warning text-dark fw-bold";
            }
        ?>

        <span class="badge bg-white bg-opacity-25 border border-white border-opacity-50 px-3 py-2 rounded-pill mb-3">
            <i class="bi bi-broadcast me-2"></i>Official Announcement
        </span>
        <h1 class="display-4 fw-extrabold mb-3"><?php echo $headerTitle; ?></h1>
        <p class="lead mb-4 mx-auto" style="max-width: 700px; opacity: 0.9;">
            <?php echo $headerDesc; ?>
        </p>
        
        <?php if(isset($btnCheck)): ?>
            <a href="#quickCheck" class="btn btn-light btn-lg rounded-pill px-5 fw-bold shadow-lg">
                <i class="bi bi-search me-2"></i> <?php echo $btnText; ?>
            </a>
        <?php else: ?>
            <a href="<?php echo $btnLink; ?>" class="<?php echo $btnClass; ?> btn-lg rounded-pill px-5 shadow-lg">
                <?php echo $btnText; ?> <i class="bi bi-arrow-right ms-2"></i>
            </a>
        <?php endif; ?>

    </div>
</section>

<!-- TIMELINE PROGRESS -->
<div class="container timeline-steps">
    <?php
    // Simplified Stages for visual clarity
    $stages = [
        ['label' => 'Pendaftaran', 'icon' => 'bi-pencil-square'],
        ['label' => 'Verifikasi', 'icon' => 'bi-file-earmark-check'],
        ['label' => 'Seleksi', 'icon' => 'bi-cpu'],
        ['label' => 'Pengumuman', 'icon' => 'bi-megaphone'],
        ['label' => 'Daftar Ulang', 'icon' => 'bi-building-check']
    ];
    
    // Map current_stage (1-based from DB urutan) to visual steps (0-4 index)
    // This assumes DB urutan roughly maps to these 5 broad phases. 
    // Logic: Map DB stage to nearest visual stage.
    // For now, simple mapping:
    $activeStep = 0;
    if (isset($current_stage)) {
        if ($current_stage >= 6) $activeStep = 4; // High ID = Daftar Ulang
        else if ($current_stage >= 5) $activeStep = 3; // Announcement
        else if ($current_stage >= 3) $activeStep = 2; // Selection
        else if ($current_stage >= 2) $activeStep = 1; // Verification
        else $activeStep = 0; // Registration
    }
    ?>

    <?php foreach($stages as $index => $step): ?>
        <div class="timeline-step <?php echo ($index == $activeStep) ? 'active' : (($index < $activeStep) ? 'completed' : ''); ?>">
            <div class="step-circle">
                <?php if ($index < $activeStep): ?>
                    <i class="bi bi-check-lg"></i>
                <?php else: ?>
                    <i class="bi <?php echo $step['icon']; ?>"></i>
                <?php endif; ?>
            </div>
            <p class="small fw-bold mb-0 <?php echo ($index == $activeStep) ? 'text-primary' : 'text-muted'; ?>">
                <?php echo $step['label']; ?>
            </p>
        </div>
    <?php endforeach; ?>
</div>

<div class="container pb-5">
    <div class="row g-4">
        
        <!-- MAIN CONTENT (LEFT) -->
        <div class="col-lg-12">
            
            <!-- Bento Grid Layout -->
            <div class="row g-4">
                
                <!-- Logged In User Status -->
                <?php if($isLoggedIn && isset($status)): ?>
                <div class="col-12">
                    <div class="bento-card p-4 border-0 shadow-sm <?php echo in_array($status, ['diterima', 'lulus', 'lolos']) ? 'bg-success bg-opacity-10' : 'bg-white'; ?>">
                        <h4 class="fw-bold mb-3">Status Seleksi Anda</h4>
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="mb-1 text-muted">Halo, <strong><?php echo $_SESSION['username']; ?></strong></p>
                                <?php if($isPublished): ?>
                                    <h2 class="fw-bold mb-0 text-uppercase <?php echo ($status == 'diterima') ? 'text-success' : 'text-danger'; ?>">
                                        <?php echo ($status == 'diterima') ? 'LULUS SELEKSI' : 'TIDAK LOLOS'; ?>
                                    </h2>
                                    <p class="mt-2 mb-0">
                                        <?php if($status == 'diterima'): ?>
                                            Selamat! Anda diterima di <strong><?php echo $sekolah_nama; ?></strong> jalur <?php echo ucfirst($jalur); ?>.
                                        <?php else: ?>
                                            Mohon maaf, Anda belum lulus seleksi di sekolah pilihan. Tetap semangat!
                                        <?php endif; ?>
                                    </p>
                                <?php else: ?>
                                    <h2 class="fw-bold mb-0 text-warning">MENUNGGU PENGUMUMAN</h2>
                                    <p class="mt-2 mb-0 text-muted">Hasil seleksi untuk sekolah <strong><?php echo $sekolah_nama; ?></strong> belum dipublikasikan.</p>
                                <?php endif; ?>
                            </div>
                            <?php if($isPublished && $status == 'diterima'): ?>
                            <div class="ms-3">
                                <a href="<?php echo url('/siswa/cetak-bukti'); ?>" class="btn btn-success rounded-pill fw-bold px-4">
                                    <i class="bi bi-printer me-2"></i> Cetak Bukti
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Quick Check & Search -->
                <div class="col-12" id="quickCheck">
                    <div class="bento-card p-5 text-center bg-white border-0 shadow-sm h-100">
                        <div class="icon-wrapper mb-3 mx-auto bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width:60px; height:60px;">
                            <i class="bi bi-search fs-3"></i>
                        </div>
                        <h3 class="fw-bold mb-2">Cek Status Penerimaan</h3>
                        <p class="text-muted mb-4">Masukkan NISN Anda untuk melihat status kelulusan secara cepat tanpa login.</p>
                        
                        <form id="checkStatusForm" class="mx-auto" style="max-width: 500px;">
                            <div class="input-group input-group-lg shadow-sm rounded-pill overflow-hidden border">
                                <input type="number" id="nisnInput" name="nisn" class="form-control border-0 ps-4" placeholder="Ketik 10 digit NISN..." required>
                                <button type="submit" class="btn btn-primary px-4 fw-bold">
                                    Cek Sekarang <i class="bi bi-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </form>

                        <!-- SEARCH RESULT AREA -->
                        <div id="searchResult"></div>
                    </div>
                </div>



                <!-- FAQ Accordion -->
                <div class="col-12">
                    <div class="bento-card p-4">
                        <h5 class="fw-bold mb-4"><i class="bi bi-question-circle-fill text-warning me-2"></i>Pertanyaan Sering Diajukan (FAQ)</h5>
                        
                        <div class="accordion accordion-flush" id="faqAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                        Bagaimana jika Koordinat/Jarak saya salah?
                                    </button>
                                </h2>
                                <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body text-muted small">
                                        Anda dapat melakukan perbaikan titik koordinat melalui menu <strong>Profil Siswa</strong> sebelum melakukan finalisasi pendaftaran. Jika sudah final, silakan hubungi operator sekolah tujuan untuk reset data.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                        Apa yang harus dibawa saat Daftar Ulang?
                                    </button>
                                </h2>
                                <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body text-muted small">
                                        Wajib membawa: Bukti Pendaftaran (Cetak), Bukti Kelulusan (Cetak SK), Kartu Keluarga Asli & Fotokopi, Rapor Asli/SKW Asli, dan Pas Foto terbaru.
                                    </div>
                                </div>
                            </div>
                             <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                        Kapan pengumuman Jalur Prestasi keluar?
                                    </button>
                                </h2>
                                <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body text-muted small">
                                        Sesuai jadwal, pengumuman Jalur Prestasi akan diterbitkan 2 hari setelah masa sanggah berakhir. Cek timeline di atas untuk tanggal pastinya.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- SIDEBAR (RIGHT) -->
        <div class="col-lg-12 mt-4">
            <h4 class="fw-bold mb-3">Daftar Pengumuman Terbaru</h4>
            <?php if (!empty($published_schools)): ?>
                <div class="row g-3">
                    <?php foreach($published_schools as $pub): ?>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm h-100 transition-hover">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span class="badge bg-success bg-opacity-10 text-success">
                                        <i class="bi bi-check-circle me-1"></i> Terbit
                                    </span>
                                    <small class="text-muted"><?php echo date('d M H:i', strtotime($pub['published_at'])); ?></small>
                                </div>
                                <h6 class="card-title fw-bold text-dark mb-1"><?php echo htmlspecialchars($pub['nama_sekolah']); ?></h6>
                                <p class="card-text small text-muted mb-3">Jalur: <?php echo ucfirst($pub['jalur']); ?></p>
                                <a href="<?php echo url('/monitoring/hasil-seleksi/' . $pub['sekolah_id']); ?>" class="btn btn-sm btn-primary w-100 rounded-pill">
                                    Lihat Hasil <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="alert alert-light border border-dashed text-center py-4">
                    <i class="bi bi-info-circle text-muted fs-4 mb-2 d-block"></i> 
                    <p class="mb-0 text-muted">Belum ada sekolah yang mempublikasikan hasil seleksi secara resmi.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkForm = document.getElementById('checkStatusForm');
    const resultDiv = document.getElementById('searchResult');
    
    checkForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const nisn = document.getElementById('nisnInput').value;
        const btn = checkForm.querySelector('button');
        const originalBtnText = btn.innerHTML;
        
        // Loading State
        btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Cek...';
        btn.disabled = true;
        resultDiv.style.display = 'none';
        resultDiv.className = ''; 

        // AJAX Request
        fetch('<?php echo url('/api/check-status'); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'nisn=' + encodeURIComponent(nisn)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.found) {
                    let statusClass = 'result-pending';
                    let icon = 'bi-exclamation-circle';
                    
                    if (data.status.includes('LULUS')) {
                        statusClass = 'result-success';
                        icon = 'bi-check-circle-fill';
                    } else if (data.status.includes('TIDAK')) {
                        statusClass = 'result-fail';
                        icon = 'bi-x-circle-fill';
                    }

                    resultDiv.className = statusClass;
                    resultDiv.innerHTML = `
                        <h4 class="fw-bold mb-2"><i class="bi ${icon} me-2"></i>${data.status}</h4>
                        ${data.sekolah ? `<p class="mb-1 fw-medium">${data.sekolah}</p>` : ''}
                        ${data.jalur ? `<span class="badge bg-dark bg-opacity-25">${data.jalur}</span>` : ''}
                    `;
                } else {
                    resultDiv.className = 'bg-light border text-muted';
                    resultDiv.innerHTML = '<p class="mb-0"><i class="bi bi-search me-2"></i>Data NISN tidak ditemukan.</p>';
                }
            } else {
                resultDiv.className = 'alert alert-danger';
                resultDiv.innerHTML = data.message;
            }
            resultDiv.style.display = 'block';
        })
        .catch(error => {
            console.error('Error:', error);
            resultDiv.className = 'alert alert-danger';
            resultDiv.innerHTML = 'Terjadi kesalahan jaringan. Coba lagi.';
            resultDiv.style.display = 'block';
        })
        .finally(() => {
            btn.innerHTML = originalBtnText;
            btn.disabled = false;
        });
    });
});
</script>
