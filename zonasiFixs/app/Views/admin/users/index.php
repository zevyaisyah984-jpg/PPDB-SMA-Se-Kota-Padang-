<?php include ROOT_PATH . 'app/Views/admin/layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Manajemen Admin</h4>
        <p class="text-muted small mb-0">Kelola pengguna dan hak akses admin</p>
    </div>
    <a href="<?php echo url('/admin/users/tambah'); ?>" class="btn btn-primary rounded-3">
        <i class="bi bi-plus-lg me-2"></i>Tambah Admin
    </a>
</div>

<?php if (isset($_SESSION['success'])): ?>
<div class="alert alert-success border-0 bg-success bg-opacity-10 text-success d-flex align-items-center mb-4">
    <i class="bi bi-check-circle-fill me-2"></i>
    <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
</div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
<div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger d-flex align-items-center mb-4">
    <i class="bi bi-exclamation-circle-fill me-2"></i>
    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
</div>
<?php endif; ?>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="px-4 py-3 text-secondary small text-uppercase">Admin</th>
                    <th class="px-4 py-3 text-secondary small text-uppercase">Username</th>
                    <th class="px-4 py-3 text-secondary small text-uppercase">Role</th>
                    <th class="px-4 py-3 text-secondary small text-uppercase">Sekolah</th>
                    <th class="px-4 py-3 text-secondary small text-uppercase text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($admins as $admin): ?>
                <tr>
                    <td class="px-4">
                        <div class="d-flex align-items-center">
                            <div class="avatar-circle me-3" style="width: 40px; height: 40px; background: var(--primary-color); color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                                <?php echo strtoupper(substr($admin['nama'], 0, 1)); ?>
                            </div>
                            <div class="fw-semibold"><?php echo htmlspecialchars($admin['nama']); ?></div>
                        </div>
                    </td>
                    <td class="px-4 text-muted"><?php echo htmlspecialchars($admin['username']); ?></td>
                    <td class="px-4">
                        <?php if($admin['role'] == 'super_admin'): ?>
                            <span class="badge bg-primary bg-opacity-10 text-primary fw-normal">Super Admin</span>
                        <?php else: ?>
                            <span class="badge bg-secondary bg-opacity-10 text-secondary fw-normal">Admin Sekolah</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4">
                        <?php if($admin['role'] == 'school_admin'): ?>
                            <?php echo htmlspecialchars($admin['nama_sekolah'] ?? '-'); ?>
                        <?php else: ?>
                            <span class="text-muted small">-</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 text-end">
                        <div class="d-flex gap-2 justify-content-end">
                            <?php if($admin['id'] != $_SESSION['admin_id']): ?>
                            <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3" 
                                    data-bs-toggle="modal" data-bs-target="#deleteModal" 
                                    data-id="<?php echo $admin['id']; ?>" 
                                    data-nama="<?php echo htmlspecialchars($admin['nama']); ?>">
                                <i class="bi bi-trash me-1"></i>Hapus
                            </button>
                            <?php else: ?>
                            <span class="text-muted small fst-italic">Akun Anda</span>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-danger"><i class="bi bi-exclamation-triangle me-2"></i>Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">Apakah Anda yakin ingin menghapus admin <strong id="deleteAdminName"></strong>?</p>
                <p class="text-muted small mb-0">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Batal</button>
                <a href="#" id="deleteLink" class="btn btn-danger rounded-3">
                    <i class="bi bi-trash me-1"></i>Ya, Hapus
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var deleteModal = document.getElementById('deleteModal');
        if (deleteModal) {
            deleteModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var id = button.getAttribute('data-id');
                var nama = button.getAttribute('data-nama');
                document.getElementById('deleteAdminName').textContent = nama;
                document.getElementById('deleteLink').href = '<?php echo url('/admin/users/hapus/'); ?>' + id;
            });
        }
    });
</script>

<?php include ROOT_PATH . 'app/Views/admin/layouts/footer.php'; ?>

