<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Sekolah - <?php echo APP_NAME; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        :root { --sidebar-width: 260px; --primary-color: #0052CC; --primary-light: #E9F0FF; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; color: #1e293b; }
        #sidebar { width: var(--sidebar-width); height: 100vh; position: fixed; top: 0; left: 0; background: #fff; border-right: 1px solid rgba(0,0,0,0.05); z-index: 1000; padding: 20px; }
        .sidebar-brand { display: flex; align-items: center; padding: 10px 15px; margin-bottom: 30px; color: var(--primary-color); font-weight: 700; font-size: 1.25rem; text-decoration: none; }
        .nav-link { color: #64748b; padding: 12px 15px; border-radius: 10px; font-weight: 500; margin-bottom: 5px; display: flex; align-items: center; text-decoration: none; }
        .nav-link i { font-size: 1.25rem; margin-right: 12px; }
        .nav-link:hover { color: var(--primary-color); background: var(--primary-light); }
        .nav-link.active { color: #fff; background: var(--primary-color); }
        #main-content { margin-left: var(--sidebar-width); padding: 40px; min-height: 100vh; }
        
        .section-card { background: #fff; border-radius: 20px; padding: 28px; margin-bottom: 28px; border: 1px solid rgba(0,0,0,0.05); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); }
        .form-section-title { font-weight: 700; color: #0f172a; margin-bottom: 24px; display: flex; align-items: center; gap: 10px; font-size: 1.1rem; }
        .form-section-title i { color: var(--primary-color); }
        
        #map { height: 350px; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .preview-img { max-width: 100%; height: 160px; object-fit: cover; border-radius: 14px; border: 1px solid #e2e8f0; }
        .form-label { font-weight: 600; color: #475569; font-size: 0.9rem; margin-bottom: 8px; }
        .form-control, .form-select { border-radius: 10px; padding: 10px 15px; border: 1px solid #e2e8f0; font-size: 0.95rem; }
        .form-control:focus, .form-select:focus { border-color: var(--primary-color); box-shadow: 0 0 0 4px rgba(0, 82, 204, 0.1); }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <nav id="sidebar">
        <a href="#" class="sidebar-brand"><i class="bi bi-hexagon-fill me-2"></i> Admin Panel</a>
        <ul class="nav flex-column">
            <li class="nav-item"><a class="nav-link" href="<?php echo url('/admin'); ?>"><i class="bi bi-grid"></i> Dashboard</a></li>
            <li class="nav-item"><span class="text-uppercase small text-muted fw-bold px-3 mt-4 mb-2 d-block">Master Data</span></li>
            <li class="nav-item"><a class="nav-link active" href="<?php echo url('/admin/sekolah'); ?>"><i class="bi bi-building"></i> Data Sekolah</a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo url('/admin/pendaftar'); ?>"><i class="bi bi-people"></i> Pendaftar</a></li>
        </ul>
    </nav>

    <!-- Main Content -->
    <main id="main-content">
        <div class="mb-4 d-flex align-items-center justify-content-between">
            <a href="<?php echo url('/admin/sekolah'); ?>" class="btn btn-link text-muted text-decoration-none p-0">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Data Sekolah
            </a>
            <div class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">Mode Edit</div>
        </div>
        
        <header class="mb-4 pb-2 border-bottom">
            <h3 class="fw-bold mb-1">Edit Data Sekolah</h3>
            <p class="text-muted small mb-0"><?php echo htmlspecialchars($sekolah['nama']); ?> (NPSN: <?php echo $sekolah['npsn']; ?>)</p>
        </header>

        <form action="<?php echo url('/admin/sekolah/update/' . $sekolah['id']); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            
            <div class="row">
                <div class="col-lg-8">
                    <!-- Basic Info -->
                    <div class="section-card">
                        <div class="form-section-title d-flex justify-content-between align-items-center">
                            <div><i class="bi bi-info-circle-fill"></i> Informasi Dasar</div>
                            <button type="button" id="btnAutoSync" class="btn btn-sm btn-outline-primary rounded-pill border-2 fw-bold icon-link hover-shadow">
                                <i class="bi bi-magic me-1"></i> Auto-Fill Data (AI)
                            </button>
                        </div>
                        
                        <div class="mb-4 p-3 bg-light rounded-4">
                            <label class="form-label">Foto Profil Sekolah</label>
                            <div class="row align-items-center g-3">
                                <div class="col-auto">
                                    <?php if (!empty($sekolah['foto']) && file_exists(ROOT_PATH . 'public/uploads/sekolah/' . $sekolah['foto'])): ?>
                                        <img src="<?php echo url('uploads/sekolah/' . $sekolah['foto']); ?>" class="preview-img" id="previewImg" style="width:180px;">
                                    <?php else: ?>
                                        <div class="preview-img bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center" id="previewPlaceholder" style="width:180px;height:120px;">
                                            <i class="bi bi-image fs-1 text-muted"></i>
                                        </div>
                                        <img src="" class="preview-img d-none" id="previewImg" style="width:180px;">
                                    <?php endif; ?>
                                </div>
                                <div class="col">
                                    <input type="file" name="foto" id="fotoInput" class="form-control" accept="image/*">
                                    <p class="small text-muted mt-2 mb-0">Format: JPG, PNG. Rekomendasi 4:3. Max 2MB.</p>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label">Nama Sekolah <span class="text-danger">*</span></label>
                                <input type="text" name="nama" class="form-control" value="<?php echo htmlspecialchars($sekolah['nama']); ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">NPSN <span class="text-danger">*</span></label>
                                <input type="text" name="npsn" class="form-control" value="<?php echo $sekolah['npsn']; ?>" required>
                            </div>
                            
                            <!-- New Data Fields from Enrichment Engine -->
                            <div class="col-md-6">
                                <label class="form-label">Kepala Sekolah</label>
                                <input type="text" name="kepala_sekolah" class="form-control" value="<?php echo htmlspecialchars($sekolah['kepala_sekolah'] ?? ''); ?>" placeholder="Nama Kepala Sekolah">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">NIP Kepala Sekolah</label>
                                <input type="text" name="nip_kepala_sekolah" class="form-control" value="<?php echo htmlspecialchars($sekolah['nip_kepala_sekolah'] ?? ''); ?>" placeholder="NIP">
                            </div>
                             <div class="col-md-6">
                                <label class="form-label">Email Sekolah</label>
                                <input type="email" name="email_sekolah" class="form-control" value="<?php echo htmlspecialchars($sekolah['email_sekolah'] ?? ''); ?>" placeholder="sekolah@sch.id">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Website</label>
                                <input type="text" name="website" class="form-control" value="<?php echo htmlspecialchars($sekolah['website'] ?? ''); ?>" placeholder="www.sekolah.sch.id">
                            </div>
                             <div class="col-md-6">
                                <label class="form-label">Telepon</label>
                                <input type="text" name="telepon" class="form-control" value="<?php echo htmlspecialchars($sekolah['telepon'] ?? ''); ?>" placeholder="021-xxxxxx">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Akreditasi</label>
                                <select name="akreditasi" class="form-select">
                                    <option value="A" <?php echo $sekolah['akreditasi'] == 'A' ? 'selected' : ''; ?>>A (Unggul)</option>
                                    <option value="B" <?php echo $sekolah['akreditasi'] == 'B' ? 'selected' : ''; ?>>B (Baik)</option>
                                    <option value="C" <?php echo $sekolah['akreditasi'] == 'C' ? 'selected' : ''; ?>>C (Cukup)</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Alamat Lengkap</label>
                                <textarea name="alamat" class="form-control" rows="2"><?php echo htmlspecialchars($sekolah['alamat'] ?? ''); ?></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Kecamatan</label>
                                <input type="text" name="kecamatan" class="form-control" value="<?php echo htmlspecialchars($sekolah['kecamatan']); ?>">
                            </div>
                        </div>
                    </div>

                    <!-- Map & Coordinates -->
                    <div class="section-card">
                        <div class="form-section-title">
                            <i class="bi bi-geo-alt-fill"></i> Titik Koordinat
                        </div>
                        <div id="map" class="mb-3"></div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Latitude</label>
                                <input type="text" name="latitude" id="latitude" class="form-control" value="<?php echo $sekolah['latitude'] ?? ''; ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Longitude</label>
                                <input type="text" name="longitude" id="longitude" class="form-control" value="<?php echo $sekolah['longitude'] ?? ''; ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Quota Logic -->
                    <div class="section-card">
                        <div class="form-section-title">
                            <i class="bi bi-pie-chart-fill"></i> Manajemen Kuota
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Total Kuota Daya Tampung</label>
                            <input type="number" name="kuota" id="totalKuota" class="form-control form-control-lg fw-bold text-center border-primary" value="<?php echo $sekolah['kuota']; ?>">
                        </div>
                        
                        <div class="d-grid mb-4">
                            <button type="button" id="autoCalculate" class="btn btn-primary-subtle text-primary border-primary border-opacity-25 fw-bold">
                                <i class="bi bi-calculator me-2"></i>Distribusi Otomatis (Juknis)
                            </button>
                        </div>

                        <div class="space-y-3">
                            <div class="mb-3">
                                <label class="form-label small text-success">Domisili (min 35%)</label>
                                <input type="number" name="kuota_domisili" id="kuotaDomisili" class="form-control" value="<?php echo $sekolah['kuota_domisili'] ?? 0; ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label small text-info">Afirmasi (min 30%)</label>
                                <input type="number" name="kuota_afirmasi" id="kuotaAfirmasi" class="form-control" value="<?php echo $sekolah['kuota_afirmasi'] ?? 0; ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label small text-warning">Prestasi Akademik (15%)</label>
                                <input type="number" name="kuota_prestasi_akademik" id="kuotaPA" class="form-control" value="<?php echo $sekolah['kuota_prestasi_akademik'] ?? 0; ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label small" style="color:#8B5CF6;">Prestasi Non-Akademik (15%)</label>
                                <input type="number" name="kuota_prestasi_nonakademik" id="kuotaPN" class="form-control" value="<?php echo $sekolah['kuota_prestasi_nonakademik'] ?? 0; ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label small text-danger">Mutasi (max 5%)</label>
                                <input type="number" name="kuota_mutasi" id="kuotaMutasi" class="form-control" value="<?php echo $sekolah['kuota_mutasi'] ?? 0; ?>">
                            </div>
                        </div>

                        <div class="mt-4 p-3 bg-light rounded-4 text-center">
                            <div class="small text-muted mb-1">Total Terdistribusi:</div>
                            <div class="h4 fw-bold text-primary mb-0" id="totalJalur">0</div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="section-card bg-primary bg-opacity-10 border-primary border-opacity-10">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg rounded-3 fw-bold">
                                <i class="bi bi-cloud-arrow-up-fill me-2"></i>Simpan Data
                            </button>
                            <a href="<?php echo url('/admin/sekolah'); ?>" class="btn btn-outline-secondary">Batalkan</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Map Logic
        const curLat = <?php echo $sekolah['latitude'] ?: -0.9471; ?>;
        const curLng = <?php echo $sekolah['longitude'] ?: 100.4172; ?>;
        
        const map = L.map('map').setView([curLat, curLng], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
        
        let marker = L.marker([curLat, curLng], {draggable: true}).addTo(map);
        
        marker.on('dragend', function(e) {
            const pos = e.target.getLatLng();
            document.getElementById('latitude').value = pos.lat.toFixed(6);
            document.getElementById('longitude').value = pos.lng.toFixed(6);
        });

        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            document.getElementById('latitude').value = e.latlng.lat.toFixed(6);
            document.getElementById('longitude').value = e.latlng.lng.toFixed(6);
        });

        // Photo Preview
        document.getElementById('fotoInput').addEventListener('change', function(e) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImg').src = e.target.result;
                document.getElementById('previewImg').classList.remove('d-none');
                if(document.getElementById('previewPlaceholder')) document.getElementById('previewPlaceholder').classList.add('d-none');
            };
            reader.readAsDataURL(e.target.files[0]);
        });

        // Auto Calculate
        document.getElementById('autoCalculate').addEventListener('click', () => {
            const t = parseInt(document.getElementById('totalKuota').value) || 0;
            const d = Math.floor(t * 0.35);
            const a = Math.floor(t * 0.30);
            const pa = Math.floor(t * 0.15);
            const pn = Math.floor(t * 0.15);
            const m = Math.floor(t * 0.05);
            
            // Fix rounding diff by adding to domisili (the largest portion)
            const sum = d + a + pa + pn + m;
            const diff = t - sum;
            
            document.getElementById('kuotaDomisili').value = d + diff;
            document.getElementById('kuotaAfirmasi').value = a;
            document.getElementById('kuotaPA').value = pa;
            document.getElementById('kuotaPN').value = pn;
            document.getElementById('kuotaMutasi').value = m;
            updateTotal();
        });

        function updateTotal() {
            const d = parseInt(document.getElementById('kuotaDomisili').value) || 0;
            const a = parseInt(document.getElementById('kuotaAfirmasi').value) || 0;
            const pa = parseInt(document.getElementById('kuotaPA').value) || 0;
            const pn = parseInt(document.getElementById('kuotaPN').value) || 0;
            const m = parseInt(document.getElementById('kuotaMutasi').value) || 0;
            document.getElementById('totalJalur').textContent = d + a + pa + pn + m;
        }

        ['kuotaDomisili', 'kuotaAfirmasi', 'kuotaPA', 'kuotaPN', 'kuotaMutasi'].forEach(id => {
            document.getElementById(id).addEventListener('input', updateTotal);
        });
        
        updateTotal();

        // --- AUTO-FILL (DATA ENRICHMENT) LOGIC ---
        document.getElementById('btnAutoSync').addEventListener('click', function() {
            const btn = this;
            const originalText = btn.innerHTML;
            
            // UI Loading State
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Anti-Gravity Search...';
            
            fetch('<?php echo url("/admin/sekolah/auto-sync/" . $sekolah["id"]); ?>')
                .then(response => response.json())
                .then(res => {
                    setTimeout(() => { // Artificial delay for effect
                        if (res.success) {
                            const data = res.data;
                            
                            // Helper to update field and adding visual cue
                            const updateField = (name, value) => {
                                const input = document.querySelector(`[name="${name}"]`);
                                if (input) {
                                    input.value = value;
                                    input.classList.add('is-valid');
                                    input.style.borderColor = '#10B981';
                                    input.style.backgroundColor = '#ECFDF5';
                                }
                            };

                            updateField('kepala_sekolah', data.kepala_sekolah);
                            updateField('nip_kepala_sekolah', data.nip_kepala_sekolah);
                            updateField('email_sekolah', data.email_sekolah);
                            updateField('website', data.website);
                            updateField('telepon', data.telepon);
                            updateField('akreditasi', data.akreditasi);
                            
                            // Update Map & Coordinates
                            if (data.latitude && data.longitude) {
                                document.getElementById('latitude').value = data.latitude;
                                document.getElementById('longitude').value = data.longitude;
                                marker.setLatLng([data.latitude, data.longitude]);
                                map.setView([data.latitude, data.longitude], 15);
                            }

                            alert('✅ ' + res.message);
                        } else {
                            alert('❌ Gagal: ' + res.message);
                        }
                        
                        btn.disabled = false;
                        btn.innerHTML = originalText;
                    }, 1500); 
                })
                .catch(err => {
                    console.error(err);
                    alert('❌ Terjadi kesalahan jaringan.');
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                });
        });
    </script>
</body>
</html>
