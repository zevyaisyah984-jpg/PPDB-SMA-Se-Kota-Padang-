<?php include ROOT_PATH . 'app/Views/admin/layouts/header.php'; ?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Audit Log Aktivitas</h4>
            <p class="text-muted small mb-0">Melacak setiap perubahan status pendaftaran untuk transparansi sistem.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3 text-secondary small text-uppercase">Waktu</th>
                            <th class="px-4 py-3 text-secondary small text-uppercase">Admin / Verifikator</th>
                            <th class="px-4 py-3 text-secondary small text-uppercase">Siswa (Target)</th>
                            <th class="px-4 py-3 text-secondary small text-uppercase">Aksi</th>
                            <th class="px-4 py-3 text-secondary small text-uppercase">Status (Lama → Baru)</th>
                            <th class="px-4 py-3 text-secondary small text-uppercase">IP Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($logs)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-journal-x fs-1 d-block mb-3 opacity-25"></i>
                                Belum ada log aktivitas tercatat.
                            </td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($logs as $log): ?>
                            <tr>
                                <td class="px-4 py-3 small text-muted">
                                    <?php echo date('d/m/Y H:i:s', strtotime($log['created_at'])); ?>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="fw-bold text-dark"><?php echo e($log['admin_name']); ?></div>
                                    <div class="small text-muted"><?php echo e($log['school_name']); ?></div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="fw-bold"><?php echo e($log['student_name']); ?></div>
                                    <div class="small text-muted">NISN: <?php echo e($log['nisn']); ?></div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="badge bg-secondary rounded-pill"><?php echo e(ucwords(str_replace('_', ' ', $log['action']))); ?></span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-light text-dark border small"><?php echo ucfirst($log['old_status'] ?? '-'); ?></span>
                                        <i class="bi bi-arrow-right mx-2 text-muted"></i>
                                        <span class="badge <?php 
                                            $badges = ['pending' => 'bg-warning', 'verifikasi' => 'bg-info', 'diterima' => 'bg-success', 'ditolak' => 'bg-danger', 'completed' => 'bg-success'];
                                            echo $badges[$log['new_status'] ?? ''] ?? 'bg-primary';
                                        ?> rounded-pill px-3"><?php echo ucfirst($log['new_status'] ?? '-'); ?></span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 small font-monospace text-muted">
                                    <?php echo e($log['ip_address']); ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include ROOT_PATH . 'app/Views/admin/layouts/footer.php'; ?>
