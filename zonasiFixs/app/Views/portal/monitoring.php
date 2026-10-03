<?php
// app/Views/portal/monitoring.php 
// RESTORED VERSION: Modern Clean UI
?>
<div class="bg-gov-light py-5" style="min-height: 100vh; background-color: #f8fafc;">
    <div class="container py-4">
        <!-- Title & Intro -->
        <div class="text-center mb-5">
            <h2 class="display-6 fw-extrabold text-dark mb-2 ls-tight">Monitoring & Hasil Seleksi</h2>
            <p class="text-secondary fs-5" style="max-width: 600px; margin: 0 auto;">
                Pantau data pendaftar dan hasil seleksi sementara secara real-time. Transparan dan akuntabel.
            </p>
        </div>

        <!-- Search Bar -->
        <div class="position-relative mb-4 z-2">
            <div class="card border-0 shadow-sm rounded-pill overflow-hidden">
                <div class="card-body p-2 ps-4">
                    <div class="row align-items-center">
                        <div class="col-md-9">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-search text-primary fs-5 me-3"></i>
                                <input type="text" id="searchSekolah" class="form-control border-0 shadow-none fs-5 text-dark fw-medium" placeholder="Cari nama sekolah (Contoh: SMAN 1 Padang)..." style="background: transparent;">
                            </div>
                        </div>
                        <div class="col-md-3 text-end d-none d-md-block">
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2 fw-bold">
                                <span class="spinner-grow spinner-grow-sm me-2" role="status" aria-hidden="true" style="width: 0.5rem; height: 0.5rem;"></span>
                                Live Data
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modern Table Card -->
        <div class="card border-0 rounded-4 shadow-lg overflow-hidden mb-4 bg-white">
            <div class="table-responsive custom-scrollbar" style="max-height: 650px; overflow-y: auto;">
                <table class="table table-hover mb-0 align-middle w-100" style="border-collapse: separate; border-spacing: 0;">
                    <thead class="bg-light sticky-top" style="z-index: 10;">
                        <tr>
                            <th class="py-4 px-4 text-uppercase text-secondary fw-bold fs-xs border-bottom-0" style="width: 80px; letter-spacing: 1px;">No</th>
                            <th class="py-4 text-uppercase text-secondary fw-bold fs-xs border-bottom-0" style="letter-spacing: 1px;">Nama Sekolah</th>
                            <th class="py-4 text-center text-uppercase text-secondary fw-bold fs-xs border-bottom-0" style="letter-spacing: 1px;">Daya Tampung</th>
                            <th class="py-4 text-end px-5 text-uppercase text-secondary fw-bold fs-xs border-bottom-0" style="letter-spacing: 1px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($sekolah_list)): ?>
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary opacity-25"></i>
                                        Tidak ada data sekolah ditemukan.
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php $no = 1; foreach ($sekolah_list as $sekolah): ?>
                            <tr class="sekolah-row transition-hover border-bottom border-light">
                                <td class="py-4 px-4">
                                    <span class="fw-bold text-secondary font-monospace"><?php echo str_pad($no++, 2, '0', STR_PAD_LEFT); ?></span>
                                </td>
                                <td class="py-4">
                                    <h6 class="fw-bold text-dark mb-1 d-block text-decoration-none">
                                        <?php echo e($sekolah['nama']); ?>
                                    </h6>
                                    <div class="d-flex align-items-center text-muted small">
                                        <i class="bi bi-geo-alt-fill me-1 text-primary opacity-50"></i>
                                        <?php echo e($sekolah['kecamatan'] ?? 'Kota Padang'); ?>
                                    </div>
                                </td>
                                <td class="py-4 text-center">
                                    <div class="d-inline-flex align-items-center px-3 py-1 rounded-pill bg-blue-50 text-primary border border-primary-subtle fs-sm fw-bold">
                                        <i class="bi bi-mortarboard me-2"></i>
                                        <?php echo $sekolah['kuota'] ?? 0; ?> Kursi
                                    </div>
                                </td>
                                <td class="py-4 text-end px-4">
                                    <a href="<?php echo url('/monitoring/hasil-seleksi/' . $sekolah['id']); ?>" class="btn btn-outline-primary rounded-pill px-4 fw-bold btn-sm-hover-solid stretched-link-custom">
                                        Pantau Hasil <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="d-flex justify-content-center">
            <p class="small text-muted mb-0 d-flex align-items-center bg-white px-3 py-2 rounded-pill shadow-sm border">
                <i class="bi bi-info-circle-fill text-info me-2"></i>
                <span>Data diperbarui secara <strong>real-time</strong> sesuai input pendaftaran.</span>
            </p>
        </div>
    </div>
</div>

<style>
/* Custom Scrollbar */
.custom-scrollbar::-webkit-scrollbar { width: 6px; }
.custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
.custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

/* Table Styling */
.transition-hover { transition: all 0.2s ease; }
.transition-hover:hover { background-color: #f8fafc; transform: translateY(-1px); }

.fs-xs { font-size: 0.75rem; }
.bg-blue-50 { background-color: #eff6ff; }

/* Search focus effect */
#searchSekolah:focus {
    outline: none;
}

.stretched-link-custom {
    position: relative;
    z-index: 2;
}
</style>

<script>
document.getElementById('searchSekolah').addEventListener('input', function(e) {
    const search = e.target.value.toLowerCase().trim();
    const rows = document.querySelectorAll('.sekolah-row');
    let hasResult = false;
    
    rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        if (text.includes(search)) {
            row.style.display = '';
            hasResult = true;
        } else {
            row.style.display = 'none';
        }
    });

    // Optional: Show empty state if filtering hides all
    // Logic can be added here if needed
});
</script>
