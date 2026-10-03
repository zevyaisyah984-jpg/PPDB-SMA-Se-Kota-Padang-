<?php 
// Include Admin Header (contains Sidebar, Body, Main container, Top Header)
include ROOT_PATH . 'app/Views/admin/layouts/header.php'; 
?>

<!-- Additional Breadcrumb for this page -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="<?php echo url('/admin/pendaftar'); ?>" class="text-decoration-none">Pendaftar</a></li>
        <li class="breadcrumb-item active" aria-current="page">Detail</li>
    </ol>
</nav>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row g-4">
            <!-- Kolom Kiri: Biodata Siswa -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    <div class="card-header">
                        <h6 class="fw-bold mb-0"><i class="bi bi-person-lines-fill me-2 text-primary"></i> Data Diri Siswa</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="text-muted small text-uppercase fw-semibold">Nama Lengkap</label>
                                <p class="fw-bold mb-0"><?php echo e($pendaftar['nama_siswa']); ?></p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small text-uppercase fw-semibold">NISN</label>
                                <p class="fw-bold mb-0"><?php echo e($pendaftar['nisn']); ?></p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small text-uppercase fw-semibold">NIK</label>
                                <p class="fw-bold mb-0"><?php echo e($pendaftar['nik']); ?></p>
                            </div>
                           <div class="col-md-6">
                                <label class="text-muted small text-uppercase fw-semibold">Asal Sekolah</label>
                                <p class="fw-bold mb-0"><?php echo e($pendaftar['sekolah_asal'] ?? '-'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Data Pendaftaran -->
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    <div class="card-header">
                         <h6 class="fw-bold mb-0"><i class="bi bi-file-earmark-text me-2 text-primary"></i> Data Pendaftaran</h6>
                    </div>
                    <div class="card-body p-4">
                         <div class="row g-4">
                            <div class="col-md-6">
                                <label class="text-muted small text-uppercase fw-semibold">No. Pendaftaran</label>
                                <p class="fw-bold mb-0 text-primary"><?php echo e($pendaftar['no_pendaftaran']); ?></p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small text-uppercase fw-semibold">Tanggal Daftar</label>
                                <p class="fw-bold mb-0"><?php echo date('d F Y H:i', strtotime($pendaftar['tanggal_daftar'])); ?></p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small text-uppercase fw-semibold">Jalur Pendaftaran</label>
                                <span class="badge bg-light text-dark border px-3"><?php echo strtoupper($pendaftar['jalur']); ?></span>
                            </div>
                             <div class="col-md-6">
                                <label class="text-muted small text-uppercase fw-semibold">Jarak ke Sekolah</label>
                                <p class="fw-bold mb-0"><?php echo $pendaftar['jarak'] ? $pendaftar['jarak'] . ' KM' : '-'; ?></p>
                            </div>
                        </div>

                        <!-- Data Tambahan (Prestasi/Mutasi) -->
                        <?php if ($pendaftar['jalur'] == 'prestasi' && !empty($pendaftar['data_prestasi'])): ?>
                            <hr class="my-4">
                            <h6 class="fw-bold mb-3">Data Prestasi</h6>
                            <?php $prestasi = json_decode($pendaftar['data_prestasi'], true); ?>
                            <?php if ($prestasi): ?>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm small mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Nama Prestasi</th>
                                            <th>Tingkat</th>
                                            <th>Juara</th>
                                            <th>Tahun</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($prestasi as $p): ?>
                                        <tr>
                                            <td><?php echo e($p['nama']); ?></td>
                                            <td><?php echo e($p['tingkat']); ?></td>
                                            <td><?php echo e($p['juara']); ?></td>
                                            <td><?php echo e($p['tahun']); ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php if ($pendaftar['jalur'] == 'mutasi' && !empty($pendaftar['data_perpindahan'])): ?>
                            <hr class="my-4">
                            <h6 class="fw-bold mb-3">Data Perpindahan</h6>
                            <?php $mutasi = json_decode($pendaftar['data_perpindahan'], true); ?>
                            <?php if ($mutasi): ?>
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="text-muted small text-uppercase fw-semibold">Alasan Pindah</label>
                                    <p class="mb-0"><?php echo e($mutasi['alasan_pindah']); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small text-uppercase fw-semibold">Asal</label>
                                    <p class="mb-0"><?php echo e($mutasi['kota_asal']); ?>, <?php echo e($mutasi['provinsi_asal']); ?></p>
                                </div>
                            </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Dokumen Pendukung -->
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    <div class="card-header">
                         <h6 class="fw-bold mb-0"><i class="bi bi-folder2-open me-2 text-primary"></i> Dokumen Pendukung</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <!-- Dokumen Wajib / Umum -->
                            <?php 
                            $docs = [
                                'Kartu Keluarga' => $pendaftar['file_kk'] ?? null,
                                'Akte Kelahiran' => $pendaftar['file_akta'] ?? null,
                                'Ijazah / SKL' => $pendaftar['file_ijazah'] ?? null
                            ];
                            
                            // Dokumen Tambahan dari JSON
                            $extraDocs = !empty($pendaftar['data_dokumen']) ? json_decode($pendaftar['data_dokumen'], true) : [];
                            if ($extraDocs) {
                                foreach ($extraDocs as $key => $file) {
                                    $label = ucwords(str_replace('_', ' ', str_replace('file_', '', $key)));
                                    $docs[$label] = $file;
                                }
                            }
                            ?>

                            <?php $hasDocs = false; ?>
                            <?php foreach ($docs as $label => $file): ?>
                                <?php if ($file): $hasDocs = true; ?>
                                <div class="col-md-4 col-sm-6">
                                    <div class="border rounded p-3 text-center h-100 position-relative hover-shadow transition">
                                        <i class="bi bi-file-earmark-pdf fs-1 text-danger"></i>
                                        <div class="small fw-bold mt-2 text-truncate" title="<?php echo $label; ?>"><?php echo $label; ?></div>
                                        <a href="<?php echo url('/uploads/documents/' . $file); ?>" target="_blank" class="stretched-link"></a>
                                    </div>
                                </div>
                                <?php endif; ?>
                            <?php endforeach; ?>

                            <?php if (!$hasDocs): ?>
                                <div class="col-12">
                                    <div class="alert alert-secondary text-center">
                                        <i class="bi bi-info-circle me-2"></i> Belum ada dokumen yang diunggah.
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Status & Verifikasi -->
            <div class="col-lg-4">
                <!-- Status Utama -->
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    <div class="card-header bg-primary text-white py-3">
                        <h6 class="fw-bold mb-0 d-flex align-items-center">
                            <i class="bi bi-shield-check me-2"></i> Verifikasi Sistem
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <form action="<?php echo url('/admin/pendaftar/' . $pendaftar['id'] . '/verify'); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            
                            <div class="mb-4">
                                <label class="form-label small text-muted fw-bold">Update Status Pendaftaran</label>
                                <select name="status" id="statusSelect" class="form-select border-primary border-opacity-25 bg-primary bg-opacity-10 fw-bold text-primary">
                                    <option value="pending" <?php echo $pendaftar['status'] == 'pending' ? 'selected' : ''; ?>>🕒 Pending (Menunggu)</option>
                                    <option value="verifikasi" <?php echo $pendaftar['status'] == 'verifikasi' ? 'selected' : ''; ?>>🔍 Verifikasi Berkas</option>
                                    <option value="terverifikasi" <?php echo $pendaftar['status'] == 'terverifikasi' ? 'selected' : ''; ?>>✅ Terverifikasi (Valid)</option>
                                    <option value="ditolak" <?php echo $pendaftar['status'] == 'ditolak' ? 'selected' : ''; ?>>❌ Ditolak (Berkas Bermasalah)</option>
                                </select>
                            </div>
                            
                            <div class="mb-4" id="rejectionReason" style="<?php echo $pendaftar['status'] == 'ditolak' ? '' : 'display:none;'; ?>">
                                <label class="form-label small text-danger fw-bold">Alasan Penolakan <span class="text-danger">*</span></label>
                                <textarea name="catatan" class="form-control border-danger border-opacity-25 bg-danger bg-opacity-10" rows="3" placeholder="Contoh: KK tidak terbaca atau Pas Foto tidak sesuai..."><?php echo e($pendaftar['catatan_verifikasi']); ?></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 fw-bold rounded-pill py-2">
                                <i class="bi bi-save me-2"></i>Simpan Perubahan
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Verifikasi Fisik -->
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 border-start border-4 <?php echo ($pendaftar['verifikasi_fisik'] == 'sudah') ? 'border-success' : 'border-warning'; ?>">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold mb-0">Verifikasi Fisik</h6>
                            <?php if ($pendaftar['verifikasi_fisik'] == 'sudah' && $pendaftar['status'] != 'ditolak'): ?>
                                <span class="badge bg-success">Sudah Valid</span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark">Wajib Hadir</span>
                            <?php endif; ?>
                        </div>
                        <p class="small text-muted mb-4">Siswa harus membawa dokumen asli ke sekolah untuk divalidasi oleh panitia.</p>
                        
                        <div class="d-grid gap-2">
                            <form action="<?php echo url('/admin/verifikasi-fisik/' . $pendaftar['id']); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="status" value="<?php echo ($pendaftar['verifikasi_fisik'] == 'sudah') ? '0' : '1'; ?>">
                                <button type="submit" class="btn <?php echo ($pendaftar['verifikasi_fisik'] == 'sudah') ? 'btn-outline-danger' : 'btn-success'; ?> w-100 fw-bold rounded-3">
                                    <?php if ($pendaftar['verifikasi_fisik'] == 'sudah'): ?>
                                        <i class="bi bi-x-circle me-2"></i>Batalkan Verifikasi Fisik
                                    <?php else: ?>
                                        <i class="bi bi-patch-check-fill me-2"></i>Tandai Sudah Datang
                                    <?php endif; ?>
                                </button>
                            </form>
                            
                            <?php if ($pendaftar['verifikasi_fisik'] == 'sudah'): ?>
                                <a href="<?php echo url('/admin/cetak-bukti/' . $pendaftar['id']); ?>" target="_blank" class="btn btn-primary-subtle text-primary w-100 fw-bold rounded-3 mt-2 font-monospace">
                                    <i class="bi bi-printer-fill me-2"></i>CETAK BUKTI VERIFIKASI
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <script>
                    document.getElementById('statusSelect').addEventListener('change', function() {
                        const reasonDiv = document.getElementById('rejectionReason');
                        if (this.value === 'ditolak') {
                            reasonDiv.style.display = 'block';
                            reasonDiv.querySelector('textarea').setAttribute('required', 'required');
                        } else {
                            reasonDiv.style.display = 'none';
                            reasonDiv.querySelector('textarea').removeAttribute('required');
                        }
                    });
                </script>

                </div>
            </div>

             <!-- Riwayat Aktivitas -->
             <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    <div class="card-header bg-light">
                        <h6 class="fw-bold mb-0"><i class="bi bi-clock-history me-2"></i> Riwayat Aktivitas</h6>
                    </div>
                    <div class="card-body p-0">
                        <?php if (empty($logs)): ?>
                            <div class="p-4 text-center text-muted small">
                                Belum ada aktivitas tercatat.
                            </div>
                        <?php else: ?>
                            <ul class="list-group list-group-flush">
                                <?php foreach ($logs as $log): ?>
                                <li class="list-group-item p-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="fw-bold small"><?php echo e($log['admin_name']); ?></span>
                                        <span class="text-muted" style="font-size: 0.75rem;"><?php echo date('d M H:i', strtotime($log['created_at'])); ?></span>
                                    </div>
                                    <div class="small mb-1">
                                        <?php if ($log['action'] == 'update_status'): ?>
                                            Mengubah status dari <span class="badge bg-secondary"><?php echo $log['old_status']; ?></span> ke <span class="badge bg-primary"><?php echo $log['new_status']; ?></span>
                                        <?php else: ?>
                                            <?php echo ucwords(str_replace('_', ' ', $log['action'])); ?>
                                        <?php endif; ?>
                                    </div>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>

<?php include ROOT_PATH . 'app/Views/admin/layouts/footer.php'; ?>
