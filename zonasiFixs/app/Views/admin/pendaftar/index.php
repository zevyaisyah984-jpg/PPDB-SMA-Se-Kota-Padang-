<?php 
// Include Admin Header (contains Sidebar, Body start, Main container start, Top Header)
include ROOT_PATH . 'app/Views/admin/layouts/header.php'; 
?>

<div class="card table-card border-0 h-100 mb-4">
    <div class="card-header bg-white border-bottom py-4 px-4 d-flex justify-content-between align-items-center">
        <div>
            <h6 class="fw-bold mb-1">Daftar Pendaftar</h6>
            <p class="text-muted small mb-0">Kelola dan verifikasi data calon siswa baru.</p>
        </div>
        <div class="admin-search-wrapper">
            <i class="bi bi-search admin-search-icon"></i>
            <input type="text" class="admin-search-input" id="tableSearchInput" placeholder="Cari nama atau NISN...">
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-high-end mb-0 align-middle">
            <thead>
                <tr>
                    <th class="ps-4">No</th>
                    <th>Nama Siswa</th>
                    <th>Jalur</th>
                    <th>Jarak</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach ($pendaftar as $p): ?>
                <tr>
                    <td class="ps-4 text-muted fw-semibold" style="width: 50px;"><?php echo $no++; ?></td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="profile-avatar me-3" style="width: 40px; height: 40px; font-size: 1rem; background-color: var(--primary-color);">
                                <?php echo substr($p['nama_siswa'], 0, 1); ?>
                            </div>
                            <div>
                                <div class="fw-bold text-dark"><?php echo e($p['nama_siswa']); ?></div>
                                <div class="text-muted small">NISN: <?php echo e($p['nisn']); ?></div>
                                <div class="d-flex align-items-center gap-2 mt-1">
                                    <span class="text-muted small fst-italic"><?php echo e($p['sekolah_asal']); ?></span>
                                    <?php 
                                    $tgl_kk = $p['tgl_kk'] ?? null;
                                    $warning_kk = false;
                                    if ($tgl_kk && strtotime($tgl_kk) > strtotime('2024-06-24')):
                                        $warning_kk = true;
                                    ?>
                                        <span class="badge bg-warning text-dark border-warning border-opacity-25" style="font-size: 0.65rem;" title="KK terbit < 1 tahun (<?php echo date('d/m/Y', strtotime($tgl_kk)); ?>)">
                                            <i class="bi bi-exclamation-triangle-fill me-1"></i>KK Baru
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-10 fw-semibold px-3 py-2 rounded-pill">
                            <?php echo ucfirst($p['jalur']); ?>
                        </span>
                    </td>
                    <td>
                        <?php 
                        $jarak = $p['jarak'] ?? null;
                        if (!$jarak && isset($p['siswa_lat'], $p['sekolah_lat'])) {
                            // Calculate on the fly if missing (Haversine simplified for display)
                            $theta = $p['siswa_lng'] - $p['sekolah_lng'];
                            $dist = sin(deg2rad($p['siswa_lat'])) * sin(deg2rad($p['sekolah_lat'])) +  cos(deg2rad($p['siswa_lat'])) * cos(deg2rad($p['sekolah_lat'])) * cos(deg2rad($theta));
                            $dist = acos($dist);
                            $dist = rad2deg($dist);
                            $jarak = $dist * 60 * 1.1515 * 1.609344;
                        }
                        ?>
                        <?php if($jarak): ?>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-geo-alt-fill text-danger me-2 opacity-75"></i>
                                <span class="fw-bold text-dark"><?php echo number_format($jarak, 2); ?> km</span>
                            </div>
                        <?php else: ?>
                            <span class="text-muted">-</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-muted font-monospace small">
                        <?php echo date('d/m/y H:i', strtotime($p['tanggal_daftar'])); ?>
                    </td>
                    <td>
                        <?php 
                        $statusClass = [
                            'pending' => 'bg-warning bg-opacity-10 text-warning border-warning',
                            'verifikasi' => 'bg-info bg-opacity-10 text-info border-info',
                            'terverifikasi' => 'bg-info bg-opacity-10 text-info border-info',
                            'diterima' => 'bg-success bg-opacity-10 text-success border-success',
                            'tidak_diterima' => 'bg-danger bg-opacity-10 text-danger border-danger',
                            'ditolak' => 'bg-danger bg-opacity-10 text-danger border-danger',
                            'daftar_ulang' => 'bg-success bg-opacity-10 text-success border-success'
                        ];
                        $bg = $statusClass[$p['status']] ?? 'bg-secondary bg-opacity-10 text-secondary border-secondary';
                        $label = ucfirst(str_replace('_', ' ', $p['status']));
                        if ($p['status'] == 'tidak_diterima') $label = 'Tidak Lolos';
                        if ($p['status'] == 'diterima') $label = 'Lolos Seleksi';
                        ?>
                        <span class="badge border border-opacity-25 <?php echo $bg; ?> px-3 py-2 rounded-pill fw-bold" style="font-size: 0.75rem;">
                            <?php echo $label; ?>
                        </span>
                    </td>
                    <td class="text-end pe-4">
                        <div class="btn-group" role="group">
                            <!-- Detail Button (Eye Icon) -->
                            <button type="button" 
                                    class="btn btn-sm btn-outline-primary rounded-circle p-0" 
                                    style="width: 36px; height: 36px;"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#detailModal<?php echo $p['id']; ?>"
                                    title="Lihat Detail">
                                <i class="bi bi-eye"></i>
                            </button>
                            
                            <!-- Accept Button (Check Icon) - Only if pending/terverifikasi -->
                            <?php if (in_array($p['status'], ['pending', 'terverifikasi'])): ?>
                            <button type="button" 
                                    class="btn btn-sm btn-outline-success rounded-circle p-0 ms-1" 
                                    style="width: 36px; height: 36px;"
                                    onclick="acceptStudent(<?php echo $p['id']; ?>, '<?php echo htmlspecialchars($p['nama_siswa'], ENT_QUOTES); ?>')"
                                    title="Terima">
                                <i class="bi bi-check-lg"></i>
                            </button>
                            <?php endif; ?>
                            
                            <!-- Reject Button (X Icon) - Only if not rejected -->
                            <?php if ($p['status'] != 'ditolak'): ?>
                            <button type="button" 
                                    class="btn btn-sm btn-outline-danger rounded-circle p-0 ms-1" 
                                    style="width: 36px; height: 36px;"
                                    onclick="openRejectModal(<?php echo $p['id']; ?>, '<?php echo htmlspecialchars($p['nama_siswa'], ENT_QUOTES); ?>')"
                                    title="Tolak">
                                <i class="bi bi-x-lg"></i>
                            </button>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                
                <!-- Modals removed from here to fix invalid HTML (div inside tbody) -->
                
                <?php endforeach; ?>
                
                <?php if(empty($pendaftar)): ?>
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <div class="d-flex flex-column align-items-center justify-content-center opacity-50">
                            <i class="bi bi-inbox fs-1 mb-3"></i>
                            <h6 class="fw-bold">Belum ada data pendaftar</h6>
                            <p class="small text-muted">Data pendaftaran akan muncul di sini.</p>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white border-top py-3">
        <!-- Pagination Placeholder -->
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center mb-0 pagination-sm">
                <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">Next</a></li>
            </ul>
        </nav>
    </div>
</div>

<!-- SweetAlert2 for Better Alerts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function acceptStudent(id, name) {
    Swal.fire({
        title: 'Terima Pendaftaran?',
        html: `Anda akan menerima pendaftaran:<br><strong>${name}</strong>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="bi bi-check-lg me-2"></i>Ya, Terima',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Memproses...',
                text: 'Mohon tunggu',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Submit form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `<?php echo url('/admin/pendaftar/'); ?>${id}/accept`;
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('tableSearchInput');
    const tableBody = document.querySelector('tbody');
    const tableRows = tableBody.querySelectorAll('tr');

    if (searchInput) {
        searchInput.addEventListener('keyup', function(e) {
            const term = e.target.value.toLowerCase().trim();
            let hasVisibleRow = false;

            tableRows.forEach(row => {
                // Skip if it is the "empty data" placeholder row (detected by colspan or specific content)
                if (row.querySelector('td[colspan]')) return;

                // Get searchable content: Name (col 2) and NISN (inside col 2)
                // We use innerText to capture all visible text in the row for broader search match
                const textContent = row.innerText.toLowerCase();

                if (textContent.includes(term)) {
                    row.style.display = '';
                    hasVisibleRow = true;
                } else {
                    row.style.display = 'none';
                }
            });

            // Handle "No results found" message
            const existingNoResult = document.getElementById('no-search-results');
            if (!hasVisibleRow && term !== '') {
                if (!existingNoResult) {
                    const noResultRow = document.createElement('tr');
                    noResultRow.id = 'no-search-results';
                    noResultRow.innerHTML = `
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="bi bi-search me-2"></i>
                            Tidak ditemukan data untuk "<strong>${e.target.value}</strong>"
                        </td>
                    `;
                    tableBody.appendChild(noResultRow);
                } else {
                    existingNoResult.style.display = '';
                    existingNoResult.querySelector('strong').textContent = e.target.value;
                }
            } else if (existingNoResult) {
                existingNoResult.style.display = 'none';
            }
        });
    }
});
</script>


<!-- MODALS SECTION (Moved outside table) -->
<?php if(!empty($pendaftar)): foreach($pendaftar as $p): ?>
<!-- Detail Modal for Student ID: <?php echo $p['id']; ?> -->
<div class="modal fade" id="detailModal<?php echo $p['id']; ?>" tabindex="-1" aria-labelledby="detailModalLabel<?php echo $p['id']; ?>" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title fw-bold" id="detailModalLabel<?php echo $p['id']; ?>">Detail Pendaftar</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6"><label class="text-muted small">Nama</label><p class="fw-bold"><?php echo htmlspecialchars($p['nama_siswa']); ?></p></div>
                    <div class="col-md-6"><label class="text-muted small">NISN</label><p class="fw-bold"><?php echo htmlspecialchars($p['nisn']); ?></p></div>
                    <div class="col-md-6"><label class="text-muted small">Jalur</label><p><span class="badge bg-primary"><?php echo strtoupper($p['jalur']); ?></span></p></div>
                    <div class="col-md-6"><label class="text-muted small">Sekolah Asal</label><p class="fw-bold"><?php echo htmlspecialchars($p['sekolah_asal'] ?? '-'); ?></p></div>
                </div>
                <hr>
                <a href="<?php echo url('/admin/pendaftar/' . $p['id']); ?>" class="btn btn-primary w-100">Lihat Detail Lengkap</a>
            </div>
        </div>
    </div>
</div>
<?php endforeach; endif; ?>

<!-- Reject Modal removed in favor of simple SweetAlert confirmation -->

<script>
function openRejectModal(id, name) {
    Swal.fire({
        title: 'Tolak Pendaftaran?',
        html: `Anda akan menolak pendaftaran:<br><strong>${name}</strong>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="bi bi-x-circle me-2"></i>Ya, Tolak',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Memproses...',
                text: 'Mohon tunggu',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Submit form with default reason
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `<?php echo url('/admin/pendaftar/'); ?>${id}/reject`;
            
            // Add default hidden input for reason to satisfy backend validation
            const reasonInput = document.createElement('input');
            reasonInput.type = 'hidden';
            reasonInput.name = 'reject_reason';
            reasonInput.value = 'Ditolak oleh Admin (Quick Action)';
            form.appendChild(reasonInput);

            const notesInput = document.createElement('input');
            notesInput.type = 'hidden';
            notesInput.name = 'reject_notes';
            notesInput.value = '-';
            form.appendChild(notesInput);
            
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>

<?php include ROOT_PATH . 'app/Views/admin/layouts/footer.php'; ?>
