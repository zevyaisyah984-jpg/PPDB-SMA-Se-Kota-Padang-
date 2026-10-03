<?php 
$tglSelesai = get_setting('tgl_selesai_pendaftaran');
$tglPengumuman = get_setting('tgl_pengumuman');
?>

<!-- PPDB Status Data -->
<script>
window.PPDB_STATUS = {
    endDate: "<?php echo $tglSelesai; ?>",
    announceDate: "<?php echo $tglPengumuman; ?>"
};
</script>


<!-- Registration Closed Modal -->
<div class="modal fade" id="registrationClosedModal" tabindex="-1" aria-labelledby="registrationClosedModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden position-relative">
            <!-- Background Arc Decoration -->
            <div style="position: absolute; bottom: -50px; left: 50%; transform: translateX(-50%); width: 150%; height: 50%; background: #3b82f6; opacity: 0.1; border-radius: 50% 50% 0 0; z-index: 0;"></div>
            
            <div class="modal-body p-0 position-relative" style="z-index: 1;">
                <div class="row g-0">
                    <!-- Left Illustration (Desktop) -->
                    <div class="col-lg-3 d-none d-lg-flex align-items-end p-3">
                        <img src="https://img.freepik.com/free-vector/hand-drawn-back-school-illustration_23-2149028014.jpg" alt="Student" style="width: 100%; opacity: 0.8;">
                    </div>
                    
                    <!-- Main Content -->
                    <div class="col-lg-6 py-5 px-4 text-center">
                        <div class="bg-white rounded-4 p-4 shadow-sm border mb-4">
                            <h4 class="fw-bold mb-0 text-dark" style="line-height: 1.5;">
                                Pendaftaran / Pembuatan Akun PPDB Online<br>
                                <span class="text-primary"><?php echo APP_NAME; ?></span><br>
                                sudah ditutup
                            </h4>
                        </div>
                        
                        <div class="d-grid gap-2 mb-3">
                            <button type="button" class="btn btn-primary rounded-pill py-3 fw-bold shadow-sm" data-bs-dismiss="modal">
                                Mengerti
                            </button>
                            <a href="#heroSection" onclick="smoothScrollTo('heroSection');" class="btn btn-link text-decoration-none text-muted small" data-bs-dismiss="modal">
                                Kembali ke Beranda
                            </a>
                        </div>
                        <small class="text-muted">Sudah punya akun? <a href="<?php echo url('/login'); ?>" class="text-primary text-decoration-none fw-bold">Login di sini</a></small>
                    </div>

                    <!-- Right Illustration (Desktop) -->
                    <div class="col-lg-3 d-none d-lg-flex align-items-end p-3">
                        <img src="https://img.freepik.com/free-vector/back-school-background_23-2147847761.jpg" alt="Student" style="width: 100%; opacity: 0.8;">
                    </div>
                </div>
                
                <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        </div>
    </div>
</div>


<style>
#registrationClosedModal .modal-content {
    border-radius: 20px !important;
}
#registrationClosedModal .btn-primary {
    background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
    border: none;
    transition: all 0.3s ease;
}
#registrationClosedModal .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(59, 130, 246, 0.4);
}
</style>


