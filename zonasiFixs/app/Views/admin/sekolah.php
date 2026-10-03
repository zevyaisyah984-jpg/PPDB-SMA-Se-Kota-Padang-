<?php include ROOT_PATH . 'app/Views/admin/layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Data Sekolah</h4>
        <p class="text-muted small mb-0">Kelola data SMA Negeri Kota Padang</p>
    </div>
    <a href="<?php echo url('/admin/sekolah/tambah'); ?>" class="btn btn-primary rounded-3">
        <i class="bi bi-plus-lg me-2"></i>Tambah Sekolah
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
    <i class="bi bi-exclamation-octagon-fill me-2"></i>
    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
</div>
<?php endif; ?>

<div class="card table-card border-0 rounded-4 overflow-hidden h-100">
    <div class="card-header bg-white border-bottom py-4 px-4">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0">Daftar Sekolah</h6>
            <div class="input-group" style="width: 250px;">
                <span class="input-group-text bg-light border-0"><i class="bi bi-search"></i></span>
                <input type="text" id="searchSchool" class="form-control bg-light border-0" placeholder="Cari sekolah...">
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-high-end align-middle mb-0">
            <thead>
                <tr>
                    <th class="ps-4">Foto</th>
                    <th>Nama Sekolah</th>
                    <th>NPSN</th>
                    <th>Kecamatan</th>
                    <th>Kuota</th>
                    <th>Status</th>
                    <th>Akreditasi</th>
                    <th class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sekolah_list as $s): ?>
                <tr>
                    <td class="ps-4">
                        <?php if (!empty($s['foto']) && file_exists(ROOT_PATH . 'public/uploads/sekolah/' . $s['foto'])): ?>
                            <img src="<?php echo url('uploads/sekolah/' . $s['foto']); ?>" class="rounded-3 shadow-sm" style="width: 48px; height: 48px; object-fit: cover;">
                        <?php else: ?>
                            <div class="rounded-3 bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center text-secondary" style="width: 48px; height: 48px;">
                                <i class="bi bi-building"></i>
                            </div>
                        <?php endif; ?>
                    </td>
                    <td class="fw-bold text-dark"><?php echo htmlspecialchars($s['nama']); ?></td>
                    <td class="text-muted font-monospace small"><?php echo $s['npsn']; ?></td>
                    <td><?php echo htmlspecialchars($s['kecamatan']); ?></td>
                    <td><span class="badge bg-light text-dark border rounded-pill px-3"><?php echo $s['kuota']; ?></span></td>
                    <td>
                        <?php if($s['is_active']): ?>
                            <span class="badge-soft badge-soft-success">Aktif</span>
                        <?php else: ?>
                            <span class="badge-soft badge-soft-danger">Nonaktif</span>
                        <?php endif; ?>
                    </td>
                    <td><span class="badge-soft badge-soft-warning"><?php echo $s['akreditasi']; ?></span></td>
                    <td class="text-end pe-4">
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="<?php echo url('/admin/sekolah/toggle/' . $s['id']); ?>" 
                               class="btn btn-sm <?php echo $s['is_active'] ? 'btn-outline-warning' : 'btn-outline-success'; ?> rounded-circle"
                               style="width: 32px; height: 32px;"
                               title="<?php echo $s['is_active'] ? 'Nonaktifkan' : 'Aktifkan'; ?>">
                                <i class="bi <?php echo $s['is_active'] ? 'bi-slash-circle' : 'bi-check-circle'; ?>"></i>
                            </a>
                            <a href="<?php echo url('/admin/sekolah/edit/' . $s['id']); ?>" class="btn btn-sm btn-outline-primary rounded-circle" style="width: 32px; height: 32px;" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-danger rounded-circle" 
                                    style="width: 32px; height: 32px;"
                                    data-bs-toggle="modal" data-bs-target="#deleteModal" 
                                    data-id="<?php echo $s['id']; ?>" 
                                    data-nama="<?php echo htmlspecialchars($s['nama']); ?>"
                                    title="Hapus">
                                <i class="bi bi-trash"></i>
                            </button>
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
                <p class="mb-0">Apakah Anda yakin ingin menghapus <strong id="deleteSchoolName"></strong>?</p>
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
        // Search functionality
        var searchInput = document.getElementById('searchSchool');
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                var filter = this.value.toLowerCase();
                var rows = document.querySelectorAll('.table tbody tr');
                
                rows.forEach(function(row) {
                    var schoolName = row.cells[1].textContent.toLowerCase();
                    var npsn = row.cells[2].textContent.toLowerCase();
                    var kecamatan = row.cells[3].textContent.toLowerCase();
                    
                    if (schoolName.includes(filter) || npsn.includes(filter) || kecamatan.includes(filter)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }
        
        // Delete modal functionality
        var deleteModal = document.getElementById('deleteModal');
        if (deleteModal) {
            deleteModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var id = button.getAttribute('data-id');
                var nama = button.getAttribute('data-nama');
                document.getElementById('deleteSchoolName').textContent = nama;
                document.getElementById('deleteLink').href = '<?php echo url('/admin/sekolah/hapus/'); ?>' + id;
            });
        }
    });
</script>

<?php include ROOT_PATH . 'app/Views/admin/layouts/footer.php'; ?>

