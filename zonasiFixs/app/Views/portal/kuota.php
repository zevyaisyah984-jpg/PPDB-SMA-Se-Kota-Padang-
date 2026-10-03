<div class="bg-gov-light py-5">
    <div class="container py-4">
        <!-- Search -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-3">
                <div class="row g-3 align-items-center">
                    <div class="col-md-8">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="bi bi-search text-primary"></i></span>
                            <input type="text" id="searchSekolah" class="form-control border-0 bg-light shadow-none" placeholder="Cari nama sekolah...">
                        </div>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <span class="badge bg-primary px-3 py-2 rounded-pill">
                            Total: <?php echo number_format($stats['total_kuota'] ?? 0); ?> Siswa
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="card border-0 rounded-4 overflow-hidden shadow">
            <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-primary text-white" style="position: sticky; top: 0; z-index: 10;">
                        <tr>
                            <th class="py-3 px-4">No</th>
                            <th class="py-3">Nama Sekolah</th>
                            <th class="py-3 text-center">Total</th>
                            <th class="py-3 text-center">Domisili<br><small class="fw-normal opacity-75">≥35%</small></th>
                            <th class="py-3 text-center">Afirmasi<br><small class="fw-normal opacity-75">≥30%</small></th>
                            <th class="py-3 text-center">Prestasi<br><small class="fw-normal opacity-75">≥30%</small></th>
                            <th class="py-3 text-center">Mutasi<br><small class="fw-normal opacity-75">≤5%</small></th>
                            <th class="py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($sekolah_list as $sekolah): ?>
                        <tr class="sekolah-row">
                            <td class="py-3 px-4 fw-bold"><?php echo $no++; ?></td>
                            <td class="py-3">
                                <div class="fw-bold text-dark"><?php echo e($sekolah['nama']); ?></div>
                                <small class="text-muted"><i class="bi bi-geo-alt me-1"></i><?php echo e($sekolah['kecamatan']); ?></small>
                            </td>
                            <td class="py-3 text-center">
                                <span class="badge bg-primary text-white rounded-pill px-3 py-2" style="min-width: 60px;">
                                    <?php echo $sekolah['kuota'] ? number_format($sekolah['kuota']) : '0'; ?>
                                </span>
                            </td>
                            <!-- Kuota Jalur Badges - Fixed Styling -->
                            <td class="py-3 text-center bg-light">
                                <span class="badge-quota badge-domisili">
                                    <?php echo ($sekolah['kuota_domisili'] > 0) ? number_format($sekolah['kuota_domisili']) : '0'; ?>
                                </span>
                            </td>
                            <td class="py-3 text-center">
                                <span class="badge-quota badge-afirmasi">
                                    <?php echo ($sekolah['kuota_afirmasi'] > 0) ? number_format($sekolah['kuota_afirmasi']) : '0'; ?>
                                </span>
                            </td>
                            <td class="py-3 text-center bg-light">
                                <span class="badge-quota badge-prestasi">
                                    <?php 
                                    $k_prestasi = ($sekolah['kuota_prestasi_akademik'] ?? 0) + ($sekolah['kuota_prestasi_nonakademik'] ?? 0);
                                    echo ($k_prestasi > 0) ? number_format($k_prestasi) : '0'; 
                                    ?>
                                </span>
                            </td>
                            <td class="py-3 text-center">
                                <span class="badge-quota badge-mutasi">
                                    <?php echo ($sekolah['kuota_mutasi'] > 0) ? number_format($sekolah['kuota_mutasi']) : '0'; ?>
                                </span>
                            </td>
                            <td class="py-3 text-center">
                                <a href="<?php echo url('/kuota/' . $sekolah['id']); ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    Detail <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <style>
            /* Custom Badge Styles for Kuota Table */
            .badge-quota {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-width: 50px;
                padding: 6px 12px;
                border-radius: 50px;
                font-weight: 600;
                font-size: 0.875rem;
            }
            .badge-domisili { background-color: #DBEAFE; color: #1e40af; }
            .badge-afirmasi { background-color: #e0e7ff; color: #3730a3; }
            .badge-prestasi { background-color: #dbeafe; color: #1e40af; }
            .badge-mutasi { background-color: #f3f4f6; color: #374151; }
            
            .table-hover tbody tr:hover {
                background-color: rgba(26, 86, 219, 0.02);
            }
            </style>
        </div>

        <!-- Legend -->
        <div class="mt-4 p-3 bg-white rounded-4 shadow-sm">
            <p class="small text-muted mb-0">
                <i class="bi bi-info-circle me-1"></i>
                <strong>Catatan:</strong> Kuota mutasi maksimal 5%. Sisa kuota jalur afirmasi/prestasi/mutasi yang tidak terisi akan dialihkan ke jalur Domisili.
            </p>
        </div>
    </div>
</div>

<script>
document.getElementById('searchSekolah').addEventListener('input', function(e) {
    const search = e.target.value.toLowerCase();
    document.querySelectorAll('.sekolah-row').forEach(row => {
        const nama = row.textContent.toLowerCase();
        row.style.display = nama.includes(search) ? '' : 'none';
    });
});
</script>
