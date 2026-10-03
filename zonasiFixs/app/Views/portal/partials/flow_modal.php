<!-- Registration Flow Modal -->
<div class="modal fade" id="flowModal" tabindex="-1" aria-labelledby="flowModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 24px; overflow: hidden;">
            <!-- Modal Header -->
            <div class="modal-header border-0 bg-gradient-primary text-white position-relative" style="padding: 2rem;">
                <div>
                    <h4 class="modal-title fw-bold mb-1" id="flowModalLabel">
                        <i class="bi bi-signpost-split-fill me-2"></i>
                        Alur Pendaftaran PPDB
                    </h4>
                    <p class="small mb-0 opacity-75">Ikuti 5 langkah mudah untuk menyelesaikan pendaftaran</p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <!-- Modal Body -->
            <div class="modal-body p-0">
                <!-- Progress Bar -->
                <div class="px-4 pt-4 pb-2">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <small class="text-muted fw-bold">Langkah <span id="currentStep">1</span> dari 5</small>
                        <small class="text-primary fw-bold"><span id="progressPercent">20</span>% Selesai</small>
                    </div>
                    <div class="progress" style="height: 8px; border-radius: 10px;">
                        <div class="progress-bar bg-primary" role="progressbar" id="progressBar" style="width: 20%; transition: width 0.3s ease;"></div>
                    </div>
                </div>
                
                <!-- Steps Container -->
                <div class="steps-container p-4" id="stepsContainer">
                    <!-- Step 1 -->
                    <div class="step-card active" data-step="1">
                        <div class="d-flex gap-4">
                            <div class="step-number-wrapper">
                                <div class="step-number">
                                    <span>1</span>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="fw-bold text-dark mb-2">
                                    <i class="bi bi-person-fill-add text-primary me-2"></i>
                                    Daftar Akun
                                </h5>
                                <p class="text-secondary mb-3">
                                    Buat akun dengan mengisi data diri lengkap. Pastikan email dan nomor HP aktif untuk verifikasi.
                                </p>
                                <div class="alert alert-info border-0 bg-info bg-opacity-10 rounded-3 mb-3">
                                    <div class="d-flex align-items-start gap-2">
                                        <i class="bi bi-info-circle text-info mt-1"></i>
                                        <div class="small">
                                            <strong>Tips:</strong> Gunakan email yang aktif karena akan digunakan untuk notifikasi penting.
                                        </div>
                                    </div>
                                </div>
                                <a href="<?php echo url('/register'); ?>" class="btn btn-primary btn-sm rounded-pill px-4">
                                    <i class="bi bi-arrow-right me-1"></i> Daftar Sekarang
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="step-card" data-step="2">
                        <div class="d-flex gap-4">
                            <div class="step-number-wrapper">
                                <div class="step-number">
                                    <span>2</span>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="fw-bold text-dark mb-2">
                                    <i class="bi bi-box-arrow-in-right text-success me-2"></i>
                                    Login ke Dashboard
                                </h5>
                                <p class="text-secondary mb-3">
                                    Masuk menggunakan email dan password yang telah didaftarkan untuk mengakses dashboard siswa.
                                </p>
                                <div class="card border-primary border-opacity-25 bg-primary  bg-opacity-5 mb-3">
                                    <div class="card-body p-3">
                                        <div class="small text-dark">
                                            <i class="bi bi-shield-check text-success me-2"></i>
                                            <strong>Keamanan terjamin:</strong> Semua data terenkripsi dan terlindungi
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="step-card" data-step="3">
                        <div class="d-flex gap-4">
                            <div class="step-number-wrapper">
                                <div class="step-number">
                                    <span>3</span>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="fw-bold text-dark mb-2">
                                    <i class="bi bi-patch-check text-warning me-2"></i>
                                    Pilih Jalur Pendaftaran
                                </h5>
                                <p class="text-secondary mb-3">
                                    Pilih jalur sesuai kriteria kamu. Setiap jalur memiliki persyaratan berbeda.
                                </p>
                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <div class="card border-0 bg-light h-100">
                                            <div class="card-body p-2 text-center">
                                                <i class="bi bi-geo-alt-fill text-primary fs-5"></i>
                                                <div class="small fw-bold mt-1">Zonasi</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="card border-0 bg-light h-100">
                                            <div class="card-body p-2 text-center">
                                                <i class="bi bi-heart-fill text-danger fs-5"></i>
                                                <div class="small fw-bold mt-1">Afirmasi</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="card border-0 bg-light h-100">
                                            <div class="card-body p-2 text-center">
                                                <i class="bi bi-trophy-fill text-warning fs-5"></i>
                                                <div class="small fw-bold mt-1">Prestasi</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="card border-0 bg-light h-100">
                                            <div class="card-body p-2 text-center">
                                                <i class="bi bi-arrow-left-right text-info fs-5"></i>
                                                <div class="small fw-bold mt-1">Mutasi</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 4 -->
                    <div class="step-card" data-step="4">
                        <div class="d-flex gap-4">
                            <div class="step-number-wrapper">
                                <div class="step-number">
                                    <span>4</span>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="fw-bold text-dark mb-2">
                                    <i class="bi bi-file-earmark-text text-info me-2"></i>
                                    Lengkapi Dokumen
                                </h5>
                                <p class="text-secondary mb-3">
                                    Upload semua dokumen yang diperlukan sesuai jalur yang dipilih.
                                </p>
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        <span class="small">Foto 3x4 (maks. 200KB)</span>
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        <span class="small">Kartu Keluarga (PDF)</span>
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        <span class="small">Raport Semester 1-5 (PDF)</span>
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        <span class="small">Dokumen pendukung lainnya</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Step 5 -->
                    <div class="step-card" data-step="5">
                        <div class="d-flex gap-4"  >
                            <div class="step-number-wrapper">
                                <div class="step-number">
                                    <span>5</span>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="fw-bold text-dark mb-2">
                                    <i class="bi bi-send-check text-success me-2"></i>
                                    Submit & Pantau Status
                                </h5>
                                <p class="text-secondary mb-3">
                                    Setelah semua lengkap, submit pendaftaran dan pantau status seleksi secara real-time.
                                </p>
                                <div class="alert alert-success border-0 bg-success bg-opacity-10 rounded-3">
                                    <div class="d-flex align-items-start gap-2">
                                        <i class="bi bi-trophy text-success mt-1"></i>
                                        <div class="small">
                                            <strong>Selamat!</strong> Kamu sudah menyelesaikan semua langkah pendaftaran.
                                        </div>
                                    </div>
                                </div>
                                <a href="<?php echo url('/pengumuman'); ?>" class="btn btn-success btn-sm rounded-pill px-4">
                                    <i class="bi bi-search me-1"></i> Cek Status Saya
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Modal Footer -->
            <div class="modal-footer border-0 bg-light p-4">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4" id="prevBtn" disabled>
                    <i class="bi bi-arrow-left me-1"></i> Sebelumnya
                </button>
                <button type="button" class="btn btn-primary rounded-pill px-4" id="nextBtn">
                    Selanjutnya <i class="bi bi-arrow-right ms-1"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Modal Styles */
.bg-gradient-primary {
    background: linear-gradient(135deg, #0052CC 0%, #0041A3 100%);
}

.steps-container {
    max-height: 60vh;
    overflow-y: auto;
}

.step-card {
    padding: 1.5rem;
    border-radius: 16px;
    background: #f8f9fa;
    margin-bottom: 1rem;
    opacity: 0.6;
    transform: scale(0.98);
    transition: all 0.3s ease;
}

.step-card.active {
    opacity: 1;
    transform: scale(1);
    background: white;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
}

.step-number-wrapper {
    flex-shrink: 0;
}

.step-number {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 1.25rem;
    color: #6c757d;
    transition: all 0.3s ease;
}

.step-card.active .step-number {
    background: linear-gradient(135deg, #0052CC, #0041A3);
    color: white;
    box-shadow: 0 4px 12px rgba(0, 82, 204, 0.3);
    transform: scale(1.1);
}

.step-card.completed .step-number {
    background: #28a745;
    color: white;
}

.step-card.completed .step-number span::before {
    content: '\f26b';
    font-family: 'bootstrap-icons';
}

.step-card.completed .step-number span {
    display: none;
}

/* Scrollbar */
.steps-container::-webkit-scrollbar {
    width: 6px;
}

.steps-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.steps-container::-webkit-scrollbar-thumb {
    background: #0052CC;
    border-radius: 10px;
}

.steps-container::-webkit-scrollbar-thumb:hover {
    background: #0041A3;
}
</style>

<script>
// Flow Modal Navigation
document.addEventListener('DOMContentLoaded', function() {
    let currentStep = 1;
    const totalSteps = 5;
    
    const nextBtn = document.getElementById('nextBtn');
    const prevBtn = document.getElementById('prevBtn');
    const progressBar = document.getElementById('progressBar');
    const progressPercent = document.getElementById('progressPercent');
    const currentStepSpan = document.getElementById('currentStep');
    
    function updateStep(step) {
        // Update all step cards
        document.querySelectorAll('.step-card').forEach(card => {
            const cardStep = parseInt(card.getAttribute('data-step'));
            card.classList.remove('active', 'completed');
            
            if (cardStep === step) {
                card.classList.add('active');
                // Scroll to active step
                card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            } else if (cardStep < step) {
                card.classList.add('completed');
            }
        });
        
        // Update progress
        const progress = (step / totalSteps) * 100;
        progressBar.style.width = progress + '%';
        progressPercent.textContent = Math.round(progress);
        currentStepSpan.textContent = step;
        
        // Update navigation buttons
        prevBtn.disabled = step === 1;
        
        if (step === totalSteps) {
            nextBtn.innerHTML = '<i class="bi bi-check-circle me-1"></i> Selesai';
            nextBtn.classList.remove('btn-primary');
            nextBtn.classList.add('btn-success');
        } else {
            nextBtn.innerHTML = 'Selanjutnya <i class="bi bi-arrow-right ms-1"></i>';
            nextBtn.classList.add('btn-primary');
            nextBtn.classList.remove('btn-success');
        }
    }
    
    if (nextBtn) {
        nextBtn.addEventListener('click', function() {
            if (currentStep < totalSteps) {
                currentStep++;
                updateStep(currentStep);
            } else {
                // Close modal on finish
                const modal = bootstrap.Modal.getInstance(document.getElementById('flowModal'));
                modal.hide();
            }
        });
    }
    
    if (prevBtn) {
        prevBtn.addEventListener('click', function() {
            if (currentStep > 1) {
                currentStep--;
                updateStep(currentStep);
            }
        });
    }
    
    // Reset on modal close
    const flowModal = document.getElementById('flowModal');
    if (flowModal) {
        flowModal.addEventListener('hidden.bs.modal', function() {
            currentStep = 1;
            updateStep(currentStep);
        });
    }
});
</script>
