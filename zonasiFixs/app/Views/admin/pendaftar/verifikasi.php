<?php 
$title = 'Verifikasi Fisik';
include ROOT_PATH . 'app/Views/admin/layouts/header.php'; 
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Verifikasi Dokumen Fisik</h4>
        <p class="text-muted small mb-0">Pastikan siswa membawa dokumen asli (KK, Akta, Ijazah) sebelum melakukan verifikasi.</p>
    </div>
</div>

<?php if (isset($_SESSION['success'])): ?>
<div class="alert alert-success rounded-4 border-0 mb-4">
    <i class="bi bi-check-circle me-2"></i><?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
</div>
<?php endif; ?>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr class="text-secondary small text-uppercase">
                    <th class="border-0 ps-4">Pendaftar</th>
                    <th class="border-0">Asal Sekolah</th>
                    <th class="border-0">Jalur</th>
                    <th class="border-0 text-center">Status</th>
                    <th class="border-0 pe-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($pendaftar)): ?>
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted">Belum ada pendaftar yang perlu diverifikasi.</td>
                </tr>
                <?php else: ?>
                    <?php foreach ($pendaftar as $p): ?>
                    <tr>
                        <td class="ps-4">
                            <div class="fw-semibold"><?php echo htmlspecialchars($p['nama_siswa'] ?? $p['nama'] ?? 'N/A'); ?></div>
                            <div class="text-muted small">NISN: <?php echo $p['nisn'] ?? 'N/A'; ?></div>
                        </td>
                        <td><?php echo htmlspecialchars($p['sekolah_asal'] ?? 'N/A'); ?></td>
                        <td><span class="badge bg-light text-dark rounded-pill"><?php echo ucfirst($p['jalur'] ?? 'zonasi'); ?></span></td>
                        <td class="text-center">
                            <!-- Physical Verification Badge -->
                            <div class="mb-1">
                                <?php if (isset($p['verifikasi_fisik']) && $p['verifikasi_fisik'] === 'sudah'): ?>
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 border border-success border-opacity-25" title="Dokumen Fisik Lengkap">
                                        <i class="bi bi-file-earmark-check me-1"></i> Fisik Oke
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 border border-warning border-opacity-25" title="Dokumen Fisik Belum">
                                        <i class="bi bi-file-earmark me-1"></i> Fisik Belum
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <!-- System Status Badge -->
                            <div>
                                <?php 
                                $statusClass = 'bg-secondary';
                                $statusLabel = $p['status'];
                                if ($p['status'] == 'terverifikasi') { $statusClass = 'bg-primary'; $statusLabel = 'Terverifikasi'; }
                                elseif ($p['status'] == 'ditolak') { $statusClass = 'bg-danger'; }
                                elseif ($p['status'] == 'pending') { $statusClass = 'bg-warning text-dark'; }
                                ?>
                                <span class="badge <?php echo $statusClass; ?> rounded-pill px-2" style="font-size: 0.7rem;">
                                    <?php echo ucfirst($statusLabel); ?>
                                </span>
                            </div>
                        </td>
                        <td class="pe-4 text-center">
                            <div class="d-flex justify-content-center gap-1">
                                <!-- 1. Toggle Physical Verification -->
                                <form action="<?php echo url('/admin/pendaftar/set-verifikasi-fisik/' . $p['id']); ?>" method="POST" title="Toggle Verifikasi Fisik">
                                    <input type="hidden" name="status" value="<?php echo (isset($p['verifikasi_fisik']) && $p['verifikasi_fisik'] === 'sudah') ? 0 : 1; ?>">
                                    <button type="submit" class="btn <?php echo (isset($p['verifikasi_fisik']) && $p['verifikasi_fisik'] === 'sudah') ? 'btn-outline-secondary' : 'btn-outline-primary'; ?> btn-sm rounded-circle" style="width: 32px; height: 32px; padding: 0;">
                                        <i class="bi <?php echo (isset($p['verifikasi_fisik']) && $p['verifikasi_fisik'] === 'sudah') ? 'bi-arrow-counterclockwise' : 'bi-file-check'; ?>"></i>
                                    </button>
                                </form>

                                <!-- 2. Approve (Set to Terverifikasi) -->
                                <?php if ($p['status'] !== 'terverifikasi'): ?>
                                <form action="<?php echo url('/admin/pendaftar/' . $p['id'] . '/verify'); ?>" method="POST" onsubmit="return confirm('Terima siswa ini? Status akan menjadi Terverifikasi.');">
                                    <input type="hidden" name="status" value="terverifikasi">
                                    <input type="hidden" name="redirect_to" value="/admin/verifikasi">
                                    <button type="submit" class="btn btn-success btn-sm rounded-circle text-white" style="width: 32px; height: 32px; padding: 0;" title="Terima / Verifikasi">
                                        <i class="bi bi-check-lg"></i>
                                    </button>
                                </form>
                                <?php endif; ?>

                                <!-- 3. Reject (Set to Ditolak) -->
                                <?php if ($p['status'] !== 'ditolak'): ?>
                                <form action="<?php echo url('/admin/pendaftar/' . $p['id'] . '/verify'); ?>" method="POST" onsubmit="return confirm('Tolak pendaftaran ini?');">
                                    <input type="hidden" name="status" value="ditolak">
                                    <input type="hidden" name="redirect_to" value="/admin/verifikasi">
                                    <button type="submit" class="btn btn-danger btn-sm rounded-circle text-white" style="width: 32px; height: 32px; padding: 0;" title="Tolak">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </form>
                                <?php endif; ?>
                                
                                <a href="<?php echo url('/admin/pendaftar/' . $p['id']); ?>" class="btn btn-light btn-sm rounded-circle" style="width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center;" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include ROOT_PATH . 'app/Views/admin/layouts/footer.php'; ?>
