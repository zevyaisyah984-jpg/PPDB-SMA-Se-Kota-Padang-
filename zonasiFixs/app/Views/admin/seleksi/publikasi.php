<?php 
$title = 'Publikasi Pengumuman';
include ROOT_PATH . 'app/Views/admin/layouts/header.php'; 
?>

<!-- Info Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body text-center">
                <h2 class="fw-bold text-primary mb-0"><?php echo number_format($stats['total'] ?? 0); ?></h2>
                <div class="text-muted small">Total Hasil</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body text-center">
                <h2 class="fw-bold text-success mb-0"><?php echo number_format($stats['lulus'] ?? 0); ?></h2>
                <div class="text-muted small">Lulus</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body text-center">
                <h2 class="fw-bold text-danger mb-0"><?php echo number_format($stats['tidak_lulus'] ?? 0); ?></h2>
                <div class="text-muted small">Tidak Lulus</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body text-center">
                <h2 class="fw-bold text-info mb-0"><?php echo number_format($stats['published'] ?? 0); ?></h2>
                <div class="text-muted small">Dipublikasikan</div>
            </div>
        </div>
    </div>
</div>

<!-- Main Publication Area -->
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-header bg-success text-white p-3 rounded-top-4">
        <h6 class="mb-0 fw-bold"><i class="bi bi-megaphone-fill me-2"></i> Publikasi Pengumuman</h6>
    </div>
    <div class="card-body p-4">
        <div class="alert alert-warning border-0 rounded-3 small">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> 
            <strong>Perhatian:</strong> Setelah pengumuman dipublikasikan, siswa dapat melihat hasil seleksi mereka. Pastikan proses seleksi sudah selesai dan hasil sudah diverifikasi.
        </div>

        <form action="<?php echo url('/admin/seleksi/publikasi'); ?>" method="GET" id="filterForm">
            <div class="mb-3">
                <label class="fw-bold small mb-1">1. Pilih Jalur *</label>
                <select name="jalur" class="form-select" onchange="document.getElementById('filterForm').submit()">
                    <option value="">-- Pilih Jalur --</option>
                    <option value="zonasi" <?php echo $filter_jalur == 'zonasi' ? 'selected' : ''; ?>>Zonasi</option>
                    <option value="afirmasi" <?php echo $filter_jalur == 'afirmasi' ? 'selected' : ''; ?>>Afirmasi</option>
                    <option value="prestasi" <?php echo $filter_jalur == 'prestasi' ? 'selected' : ''; ?>>Prestasi</option>
                    <option value="mutasi" <?php echo $filter_jalur == 'mutasi' ? 'selected' : ''; ?>>Mutasi</option>
                </select>
                <?php if ($filter_jalur): ?>
                     <div class="small text-success mt-1"><i class="bi bi-check-circle-fill"></i> Jalur terpilih.</div>
                <?php endif; ?>
            </div>
            
            <div class="mb-4">
                <label class="fw-bold small mb-1">2. Pilih Sekolah *</label>
                <select name="sekolah_id" class="form-select" onchange="document.getElementById('filterForm').submit()">
                    <option value="">-- Pilih Sekolah --</option>
                    <?php foreach ($all_sekolah as $s): ?>
                        <option value="<?php echo $s['id']; ?>" <?php echo $filter_sekolah == $s['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($s['nama']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>

        <?php if ($filter_jalur && $filter_sekolah): ?>
            <form action="<?php echo url('/admin/seleksi/publish'); ?>" method="POST" class="d-inline">
                <input type="hidden" name="sekolah_id" value="<?php echo $filter_sekolah; ?>">
                <input type="hidden" name="jalur" value="<?php echo $filter_jalur; ?>">
                <!-- Determine current status for button -->
                <?php 
                    $currentStatus = 'draft';
                    foreach ($sekolah_list as $sl) {
                        if ($sl['id'] == $filter_sekolah) {
                            $currentStatus = $sl['status_publikasi'] ?? 'draft';
                            break;
                        }
                    }
                ?>
                
                <?php if ($currentStatus == 'published'): ?>
                    <button type="submit" name="action" value="unpublish" class="btn btn-danger btn-lg rounded-pill fw-bold px-4 shadow-sm">
                        <i class="bi bi-eye-slash-fill me-2"></i> Batalkan Publikasi
                    </button>
                <?php else: ?>
                    <button type="submit" name="action" value="publish" class="btn btn-success btn-lg rounded-pill fw-bold px-4 shadow-sm">
                        <i class="bi bi-megaphone-fill me-2"></i> Publikasikan Pengumuman
                    </button>
                <?php endif; ?>
            </form>
            
            <a href="<?php echo url('/admin/seleksi/hasil?sekolah_id=' . $filter_sekolah); ?>" class="btn btn-outline-primary btn-lg rounded-pill fw-bold px-4 ms-2">
                <i class="bi bi-table me-2"></i> Lihat Hasil Seleksi
            </a>
        <?php else: ?>
             <button disabled class="btn btn-success btn-lg rounded-pill fw-bold px-4 opacity-50">
                <i class="bi bi-megaphone-fill me-2"></i> Publikasikan Pengumuman
            </button>
            <button disabled class="btn btn-outline-primary btn-lg rounded-pill fw-bold px-4 ms-2">
                 <i class="bi bi-table me-2"></i> Lihat Hasil Seleksi
            </button>
        <?php endif; ?>

    </div>
</div>

<!-- Table List -->
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-header bg-white p-3 border-bottom-0">
        <h6 class="mb-0 fw-bold"><i class="bi bi-list-columns-reverse me-2"></i> Daftar Sekolah dengan Hasil Seleksi</h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4" style="width: 5%;">No</th>
                    <th>Nama Sekolah</th>
                    <th class="text-center">Total Hasil</th>
                    <th class="text-center">Lulus</th>
                    <th class="text-center">Tidak Lulus</th>
                    <th class="text-center">Status Publikasi</th>
                    <th class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($sekolah_list)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted small">
                            <?php echo $filter_jalur ? 'Belum ada data hasil seleksi untuk jalur ini.' : 'Silakan pilih jalur terlebih dahulu.'; ?>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $no=1; foreach ($sekolah_list as $row): ?>
                    <tr>
                        <td class="ps-4"><?php echo $no++; ?></td>
                        <td class="fw-bold"><?php echo htmlspecialchars($row['nama']); ?></td>
                        <td class="text-center"><?php echo number_format($row['total_hasil']); ?></td>
                        <td class="text-center text-success fw-bold"><?php echo number_format($row['lulus']); ?></td>
                        <td class="text-center text-danger"><?php echo number_format($row['tidak_lulus']); ?></td>
                        <td class="text-center">
                            <?php if (($row['status_publikasi'] ?? 'draft') == 'published'): ?>
                                <span class="badge bg-success rounded-pill px-3">
                                    <i class="bi bi-check-circle-fill me-1"></i> Sudah Dipublikasi
                                </span>
                                <div class="text-muted small mt-1" style="font-size: 0.7rem;">
                                    <?php echo date('d M H:i', strtotime($row['published_at'])); ?>
                                </div>
                            <?php else: ?>
                                <span class="badge bg-secondary rounded-pill px-3">
                                    <i class="bi bi-x-circle me-1"></i> Belum
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end pe-4">
                            <a href="<?php echo url('/admin/seleksi/hasil?sekolah_id=' . $row['id']); ?>" class="btn btn-info btn-sm text-white rounded-pill px-3">
                                <i class="bi bi-eye-fill me-1"></i> Detail
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include ROOT_PATH . 'app/Views/admin/layouts/footer.php'; ?>
