<div class="col-md-6 col-lg-4 directory-item" 
     data-school-name="<?php echo strtolower($sekolah['nama']); ?>" 
     data-school-kec="<?php echo strtolower($sekolah['kecamatan']); ?>">
    <div class="card h-100 directory-card border-0 shadow-sm overflow-hidden animate-fade-in hover-scale-sm transition-all">
        <!-- School Image -->
        <div class="position-relative overflow-hidden group">
            <?php if (!empty($sekolah['foto']) && file_exists(ROOT_PATH . 'public/uploads/sekolah/' . $sekolah['foto'])): ?>
                <img src="<?php echo url('uploads/sekolah/' . htmlspecialchars($sekolah['foto'])); ?>" 
                     alt="<?php echo htmlspecialchars($sekolah['nama']); ?>"
                     class="directory-img w-100 transition-transform-slow" style="height: 200px; object-fit: cover;">
            <?php else: ?>
                <div class="bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="height: 200px;">
                    <i class="bi bi-building fs-1 text-primary opacity-50"></i>
                </div>
            <?php endif; ?>
            
            <!-- Badges -->
            <div class="position-absolute top-0 end-0 p-3 d-flex flex-column gap-2">
                <span class="badge bg-white text-primary shadow-sm rounded-pill px-3 py-1 fw-bold">
                    <i class="bi bi-award-fill me-1 text-warning"></i> Akreditasi <?php echo $sekolah['akreditasi']; ?>
                </span>
            </div>
            
            <!-- Overlay Gradient -->
            <div class="position-absolute bottom-0 start-0 w-100 h-50 bg-gradient-to-t from-black opacity-50"></div>
        </div>

        <div class="card-body p-4 d-flex flex-column">
            <h5 class="fw-bold text-dark mb-1"><?php echo htmlspecialchars($sekolah['nama']); ?></h5>
            <small class="text-muted mb-3 d-block"><i class="bi bi-upc-scan me-1"></i> NPSN: <?php echo $sekolah['npsn']; ?></small>
            
            <div class="mb-3">
                <div class="d-flex align-items-start mb-2">
                    <i class="bi bi-geo-alt-fill text-danger me-2 mt-1"></i>
                    <span class="text-secondary small lh-sm">
                        <?php echo !empty($sekolah['alamat']) ? htmlspecialchars($sekolah['alamat']) : 'Alamat belum tersedia'; ?>
                    </span>
                </div>
                <?php if(!empty($sekolah['telepon'])): ?>
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-telephone-fill text-success me-2"></i>
                    <span class="text-secondary small"><?php echo htmlspecialchars($sekolah['telepon']); ?></span>
                </div>
                <?php endif; ?>
            </div>

            <div class="d-flex flex-wrap gap-2 mb-4 mt-auto">
                <span class="badge bg-light text-dark border"><i class="bi bi-people me-1 text-primary"></i> Kuota: <?php echo $sekolah['kuota']; ?></span>
                <span class="badge bg-light text-dark border"><i class="bi bi-map me-1 text-info"></i> <?php echo htmlspecialchars($sekolah['kecamatan']); ?></span>
            </div>

            <div class="d-grid gap-2">
                <a href="<?php echo url('kuota/' . $sekolah['id']); ?>" class="btn btn-primary rounded-pill fw-bold py-2 shadow-sm">
                    Lihat Detail Sekolah
                </a>
                <?php if(!empty($sekolah['website'])): ?>
                <a href="<?php echo htmlspecialchars($sekolah['website']); ?>" target="_blank" class="btn btn-outline-light text-secondary border-0 btn-sm small">
                    <i class="bi bi-globe me-1"></i> Kunjungi Website
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
.hover-scale-sm:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important; }
.transition-all { transition: all 0.3s ease; }
.bg-gradient-to-t { background: linear-gradient(to top, rgba(0,0,0,0.6), transparent); }
</style>
