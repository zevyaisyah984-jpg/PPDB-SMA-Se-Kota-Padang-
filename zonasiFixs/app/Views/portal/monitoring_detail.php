<?php
// Helper for masking names
function maskName($name) {
    if (strlen($name) < 3) return $name;
    $parts = explode(' ', $name);
    $maskedParts = [];
    foreach ($parts as $index => $part) {
        if ($index === 0) {
            $maskedParts[] = $part; // Keep first name
        } else {
            $maskedParts[] = substr($part, 0, 1) . str_repeat('*', strlen($part) - 1);
        }
    }
    return implode(' ', $maskedParts);
}

// Helper to determine badge status
function getStatusBadge($status) {
    if ($status === 'diterima') return '<span class="badge bg-success bg-opacity-10 text-success rounded-pill fw-bold">Lolos Sementara</span>';
    if ($status === 'cadangan') return '<span class="badge bg-warning bg-opacity-10 text-warning rounded-pill fw-bold text-dark">Cadangan</span>';
    if ($status === 'pending') return '<span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill">Verifikasi</span>';
    return '<span class="badge bg-danger bg-opacity-10 text-danger rounded-pill">Tidak Lolos</span>';
}
?>

<div class="bg-gov-light min-vh-100 py-5">
    <div class="container py-4">
        
        <!-- HEADER SECTION -->
        <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
            <div class="card-body p-4 p-lg-5 position-relative">
                <!-- Background Decoration -->
                <div class="position-absolute top-0 end-0 translate-middle-y me-n5 mt-n5 rounded-circle bg-primary bg-opacity-10" style="width: 250px; height: 250px;"></div>
                
                <div class="row align-items-center position-relative z-1">
                    <div class="col-lg-8">
                        <div class="d-flex align-items-center gap-3 mb-2">
                             <a href="<?php echo url('/monitoring'); ?>" class="btn btn-light btn-icon rounded-circle shadow-sm" data-bs-toggle="tooltip" title="Kembali">
                                <i class="bi bi-arrow-left"></i>
                            </a>
                            <span class="badge bg-green-500 text-white rounded-pill px-3 py-1 animate-pulse">
                                <i class="bi bi-broadcast me-1"></i> Live Real-time
                            </span>
                            <span class="text-muted small">Update: <?php echo $last_update; ?></span>
                        </div>
                        <h2 class="fw-bold mb-1 text-dark"><?php echo e($sekolah['nama']); ?></h2>
                        <div class="d-flex align-items-center gap-2 text-muted">
                            <i class="bi bi-patch-check-fill text-primary"></i>
                            <span>Akreditasi <?php echo $sekolah['akreditasi']; ?></span>
                            <span class="mx-2">•</span>
                            <i class="bi bi-geo-alt"></i>
                            <span><?php echo $sekolah['kecamatan']; ?></span>
                        </div>
                    </div>
                    <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                         <div class="bg-primary bg-opacity-10 p-3 rounded-4 d-inline-block text-start">
                            <small class="text-primary fw-bold text-uppercase d-block mb-1">Total Kuota</small>
                            <span class="display-6 fw-bold text-primary"><?php echo $sekolah['kuota']; ?></span>
                            <small class="text-muted ms-1">Siswa</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- MAIN CONTENT (TABS & TABLE) -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
            <div class="card-header bg-white border-bottom-0 p-4 pb-0">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <!-- Tabs -->
                    <ul class="nav nav-pills custom-pills gap-2" id="rankingTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active rounded-pill fw-bold px-4" id="zonasi-tab" data-bs-toggle="pill" data-bs-target="#zonasi" type="button">
                                <i class="bi bi-geo-alt me-1"></i>Zonasi
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill fw-bold px-4" id="afirmasi-tab" data-bs-toggle="pill" data-bs-target="#afirmasi" type="button">
                                <i class="bi bi-heart me-1"></i>Afirmasi
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill fw-bold px-4" id="prestasi-tab" data-bs-toggle="pill" data-bs-target="#prestasi" type="button">
                                <i class="bi bi-trophy me-1"></i>Prestasi
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill fw-bold px-4" id="mutasi-tab" data-bs-toggle="pill" data-bs-target="#mutasi" type="button">
                                <i class="bi bi-arrow-left-right me-1"></i>Mutasi
                            </button>
                        </li>
                    </ul>
                    
                    <!-- Search Box -->
                    <div class="input-group" style="max-width: 300px;">
                        <span class="input-group-text bg-light border-0 ps-3 rounded-start-pill">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control bg-light border-0 rounded-end-pill py-2" id="filterInput" placeholder="Cari Nama / NISN...">
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="tab-content" id="rankingTabContent">
                    
                    <!-- Zonasi Tab -->
                    <div class="tab-pane fade show active" id="zonasi" role="tabpanel">
                        <?php echo renderLiveTable($ranking_zonasi, 'zonasi'); ?>
                    </div>
                    
                    <!-- Afirmasi Tab -->
                    <div class="tab-pane fade" id="afirmasi" role="tabpanel">
                        <?php echo renderLiveTable($ranking_afirmasi, 'afirmasi'); ?>
                    </div>
                    
                    <!-- Prestasi Tab -->
                    <div class="tab-pane fade" id="prestasi" role="tabpanel">
                        <?php echo renderLiveTable($ranking_prestasi, 'prestasi'); ?>
                    </div>
                    
                    <!-- Mutasi Tab -->
                    <div class="tab-pane fade" id="mutasi" role="tabpanel">
                        <?php echo renderLiveTable($ranking_mutasi, 'mutasi'); ?>
                    </div>
                    
                </div>
            </div>
            
            <!-- Loading Skeleton (Hidden by default, shown via JS) -->
            <div id="loadingSkeleton" class="p-4 d-none">
                <div class="placeholder-glow">
                    <span class="placeholder col-12 mb-2 rounded bg-secondary p-4"></span>
                    <span class="placeholder col-12 mb-2 rounded bg-secondary p-4"></span>
                    <span class="placeholder col-12 mb-2 rounded bg-secondary p-4"></span>
                    <span class="placeholder col-12 mb-2 rounded bg-secondary p-4"></span>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-4 text-muted small">
            <i class="bi bi-info-circle me-1"></i> Data diperbarui secara otomatis setiap ada perubahan status verifikasi.
        </div>
    </div>
</div>

<?php
// Function to render table
function renderLiveTable($data, $jalur) {
    if (empty($data)) {
        return '<div class="text-center py-5">
            <div class="mb-3"><i class="bi bi-inbox fs-1 text-muted opacity-25"></i></div>
            <h6 class="text-muted fw-bold">Belum ada data pendaftar</h6>
            <p class="text-muted small">Data akan muncul setelah proses pendaftaran dimulai.</p>
        </div>';
    }
    
    $html = '<div class="table-responsive"><table class="table table-hover align-middle mb-0 data-table">';
    $html .= '<thead class="bg-light text-secondary">
        <tr class="text-uppercase small fw-bold" style="letter-spacing: 0.5px;">
            <th class="px-4 py-3 border-0" width="60">No</th>
            <th class="px-4 py-3 border-0">Nama Calon Siswa</th>
            <th class="px-4 py-3 border-0">Asal Sekolah</th>
            <th class="px-4 py-3 border-0 text-center">' . ($jalur == 'zonasi' ? 'Jarak' : ($jalur == 'prestasi' ? 'Skor' : 'Usia')) . '</th>
            <th class="px-4 py-3 border-0 text-center">Status</th>
        </tr>
    </thead><tbody class="border-top-0">';
    
    foreach ($data as $index => $row) {
        $metric = '';
        if ($jalur == 'zonasi') $metric = number_format($row['jarak_meter'] ?? 0) . ' m';
        elseif ($jalur == 'prestasi') $metric = $row['skor'] ?? 0;
        else {
             $dob = new DateTime($row['tanggal_lahir'] ?? 'now');
             $now = new DateTime(date('Y') . '-07-01');
             $diff = $dob->diff($now);
             $metric = $diff->y . ' Th ' . $diff->m . ' Bln';
        }
        
        $html .= '<tr class="searchable-row">
            <td class="px-4 py-3 fw-bold text-muted">' . ($index + 1) . '</td>
            <td class="px-4 py-3">
                <div class="fw-bold text-dark">' . maskName($row['nama_siswa']) . '</div>
                <small class="text-muted font-monospace nisn-data">' . substr($row['nisn'] ?? '', 0, 4) . '******</small>
            </td>
            <td class="px-4 py-3 text-muted">' . ($row['sekolah_asal'] ?? '-') . '</td>
            <td class="px-4 py-3 text-center fw-bold text-primary">' . $metric . '</td>
            <td class="px-4 py-3 text-center">' . getStatusBadge($row['status'] ?? 'pending') . '</td>
        </tr>';
    }
    
    $html .= '</tbody></table></div>';
    return $html;
}
?>

<style>
/* Custom Styles for Monitoring Page */
.bg-green-500 { background-color: #10B981; }
.text-green-500 { color: #10B981; }

.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.custom-pills .nav-link {
    color: #64748b;
    background: #F1F5F9;
    border: 1px solid transparent;
    transition: all 0.2s;
}

.custom-pills .nav-link.active {
    background: #1A56DB;
    color: #fff;
    box-shadow: 0 4px 12px rgba(26, 86, 219, 0.3);
}

.custom-pills .nav-link:hover:not(.active) {
    background: #E2E8F0;
}

.table-hover tbody tr:nth-of-type(odd) {
    background-color: rgba(249, 250, 251, 0.5); /* Zebra subtle */
}
.table-hover tbody tr:hover {
    background-color: rgba(26, 86, 219, 0.05);
}
</style>

<script>
// Real-time Search Logic
document.getElementById('filterInput').addEventListener('keyup', function(e) {
    const term = e.target.value.toLowerCase();
    const activeTab = document.querySelector('.tab-pane.active');
    
    if(!activeTab) return;
    
    const rows = activeTab.querySelectorAll('.searchable-row');
    let hasResult = false;
    
    // Simple Debounce / UI Feedback could be added here
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        if(text.includes(term)) {
            row.style.display = '';
            hasResult = true;
        } else {
            row.style.display = 'none';
        }
    });
    
    // Optional: Show "No results" message if !hasResult
});

// Re-apply search when switching tabs
const triggerTabList = document.querySelectorAll('#rankingTabs button')
triggerTabList.forEach(triggerEl => {
  triggerEl.addEventListener('shown.bs.tab', event => {
      document.getElementById('filterInput').dispatchEvent(new Event('keyup'));
  })
})
</script>
