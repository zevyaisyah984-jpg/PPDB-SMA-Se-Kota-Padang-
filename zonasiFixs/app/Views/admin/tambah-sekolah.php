<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Sekolah - <?php echo APP_NAME; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        :root { --sidebar-width: 260px; --primary-color: #0052CC; --primary-light: #E9F0FF; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f3f4f6; }
        #sidebar { width: var(--sidebar-width); height: 100vh; position: fixed; top: 0; left: 0; background: #fff; border-right: 1px solid rgba(0,0,0,0.05); z-index: 1000; padding: 20px; }
        .sidebar-brand { display: flex; align-items: center; padding: 10px 15px; margin-bottom: 30px; color: var(--primary-color); font-weight: 700; font-size: 1.25rem; text-decoration: none; }
        .nav-link { color: #64748b; padding: 12px 15px; border-radius: 10px; font-weight: 500; margin-bottom: 5px; display: flex; align-items: center; }
        .nav-link i { font-size: 1.25rem; margin-right: 12px; }
        .nav-link:hover { color: var(--primary-color); background: rgba(79, 70, 229, 0.05); }
        .nav-link.active { color: #fff; background: var(--primary-color); }
        #main-content { margin-left: var(--sidebar-width); padding: 30px; min-height: 100vh; }
        #map { height: 350px; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .map-section { background: linear-gradient(135deg, #0052CC 0%, #003D99 100%); color: white; }
        .preview-img { max-width: 200px; max-height: 150px; object-fit: cover; border-radius: 12px; border: 2px solid #e2e8f0; }
        .section-card { background: #fff; border-radius: 16px; padding: 24px; margin-bottom: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .form-section-title { font-weight: 600; color: var(--primary-color); margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }
    </style>
</head>
<body>
    <!-- Sidebar Replacement (Use standard sidebar partial if available, but for now just fix colors here) -->
    <style>
        #sidebar .sidebar-brand { color: var(--primary-color); }
        #sidebar .nav-link.active { background: var(--primary-color); }
    </style>

    <!-- Main Content -->
    <main id="main-content">
        <div class="mb-4">
            <a href="<?php echo url('/admin/sekolah'); ?>" class="text-muted text-decoration-none small">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Data Sekolah
            </a>
        </div>
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1"><i class="bi bi-plus-circle me-2 text-primary"></i>Tambah Sekolah Baru</h4>
                <p class="text-muted mb-0 small">Lengkapi data sekolah dengan informasi yang akurat</p>
            </div>
        </div>

        <form action="<?php echo url('/admin/sekolah/tambah'); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            
            <div class="row">
                <div class="col-lg-8">
                    <!-- Section 1: Informasi Dasar -->
                    <div class="section-card">
                        <div class="form-section-title">
                            <i class="bi bi-building"></i> Informasi Dasar
                        </div>
                        
                        <!-- School Photo -->
                        <div class="mb-4 p-3 bg-light rounded-3">
                            <label class="form-label fw-semibold">Foto Sekolah</label>
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <div class="preview-img bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center" id="previewPlaceholder" style="width:150px;height:100px;">
                                        <i class="bi bi-image fs-2 text-muted"></i>
                                    </div>
                                    <img src="" class="preview-img d-none" id="previewImg">
                                </div>
                                <div class="col">
                                    <input type="file" name="foto" id="fotoInput" class="form-control" accept="image/*">
                                    <small class="text-muted">Format: JPG, PNG. Max 2MB (Opsional)</small>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Nama Sekolah <span class="text-danger">*</span></label>
                                <input type="text" name="nama" class="form-control" placeholder="SMAN 1 Padang" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">NPSN <span class="text-danger">*</span></label>
                                <input type="text" name="npsn" class="form-control" placeholder="10303496" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Alamat Lengkap</label>
                                <textarea name="alamat" class="form-control" rows="2" placeholder="Jl. Belanti Raya No. 1, Padang"></textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Kecamatan</label>
                                <input type="text" name="kecamatan" class="form-control" placeholder="Padang Utara">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Kelurahan</label>
                                <input type="text" name="kelurahan" class="form-control" placeholder="Ulak Karang">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Kode Pos</label>
                                <input type="text" name="kode_pos" class="form-control" placeholder="25173">
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Kontak & Profil -->
                    <div class="section-card">
                        <div class="form-section-title">
                            <i class="bi bi-telephone"></i> Kontak & Profil
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nomor Telepon</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                    <input type="text" name="telepon" class="form-control" placeholder="0751-123456">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Email Sekolah</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input type="email" name="email" class="form-control" placeholder="info@sman1padang.sch.id">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Website</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-globe"></i></span>
                                    <input type="url" name="website" class="form-control" placeholder="https://sman1padang.sch.id">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Akreditasi</label>
                                <select name="akreditasi" class="form-select">
                                    <option value="A">A (Unggul)</option>
                                    <option value="B">B (Baik)</option>
                                    <option value="C">C (Cukup)</option>
                                    <option value="Belum">Belum Terakreditasi</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nama Kepala Sekolah</label>
                                <input type="text" name="kepala_sekolah" class="form-control" placeholder="Dr. Ahmad Sudirman, M.Pd">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">NIP Kepala Sekolah</label>
                                <input type="text" name="nip_kepala_sekolah" class="form-control" placeholder="196501011990031001">
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Lokasi & Peta -->
                    <div class="section-card">
                        <div class="form-section-title">
                            <i class="bi bi-geo-alt"></i> Lokasi Sekolah
                        </div>
                        <div class="alert alert-info border-0 rounded-3 small mb-3">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Tips:</strong> Klik pada peta untuk menentukan lokasi sekolah, atau masukkan koordinat secara manual.
                        </div>
                        <div id="map" class="mb-3"></div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Latitude</label>
                                <input type="text" name="latitude" id="latitude" class="form-control" placeholder="-0.923412">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Longitude</label>
                                <input type="text" name="longitude" id="longitude" class="form-control" placeholder="100.355678">
                            </div>
                            <div class="col-12">
                                <button type="button" id="getLocation" class="btn btn-outline-primary btn-sm rounded-pill">
                                    <i class="bi bi-crosshair me-1"></i> Gunakan Lokasi Saya
                                </button>
                                <button type="button" id="searchLocation" class="btn btn-outline-secondary btn-sm rounded-pill ms-2">
                                    <i class="bi bi-search me-1"></i> Cari dari Alamat
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Section 4: Kuota -->
                    <div class="section-card">
                        <div class="form-section-title">
                            <i class="bi bi-pie-chart"></i> Pengaturan Kuota
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Total Kuota Penerimaan</label>
                            <input type="number" name="kuota" id="totalKuota" class="form-control form-control-lg text-center fw-bold" placeholder="300" value="0">
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <small class="text-muted fw-semibold">Distribusi Per Jalur</small>
                            <button type="button" id="autoCalculate" class="btn btn-sm btn-outline-primary rounded-pill">
                                <i class="bi bi-calculator me-1"></i>Auto
                            </button>
                        </div>
                        <div class="mb-2">
                            <label class="form-label small text-success fw-semibold mb-1">Zonasi/Domisili (≥35%)</label>
                            <input type="number" name="kuota_domisili" id="kuotaDomisili" class="form-control" value="0">
                        </div>
                        <div class="mb-2">
                            <label class="form-label small text-info fw-semibold mb-1">Afirmasi (≥30%)</label>
                            <input type="number" name="kuota_afirmasi" id="kuotaAfirmasi" class="form-control" value="0">
                        </div>
                        <div class="mb-2">
                            <label class="form-label small text-warning fw-semibold mb-1">Prestasi Akademik (≥15%)</label>
                            <input type="number" name="kuota_prestasi_akademik" id="kuotaPA" class="form-control" value="0">
                        </div>
                        <div class="mb-2">
                            <label class="form-label small fw-semibold mb-1" style="color:#8B5CF6;">Prestasi Non-Akademik (≥15%)</label>
                            <input type="number" name="kuota_prestasi_nonakademik" id="kuotaPN" class="form-control" value="0">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small text-danger fw-semibold mb-1">Mutasi (≤5%)</label>
                            <input type="number" name="kuota_mutasi" id="kuotaMutasi" class="form-control" value="0">
                        </div>
                        <div class="bg-light rounded-3 p-2 text-center">
                            <small class="text-muted">Total Jalur:</small>
                            <strong id="totalJalur" class="ms-1 text-primary fs-5">0</strong>
                        </div>
                    </div>

                    <!-- Status Sekolah -->
                    <div class="section-card">
                        <div class="form-section-title">
                            <i class="bi bi-toggle-on"></i> Status
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="isActive" checked>
                            <label class="form-check-label fw-semibold" for="isActive">Sekolah Aktif</label>
                        </div>
                        <small class="text-muted">Sekolah aktif akan tampil di portal pendaftaran</small>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="section-card bg-primary bg-opacity-10 border border-primary border-opacity-25">
                        <button type="submit" class="btn btn-primary w-100 py-2 rounded-3 fw-semibold mb-2">
                            <i class="bi bi-plus-lg me-2"></i>Tambah Sekolah
                        </button>
                        <a href="<?php echo url('/admin/sekolah'); ?>" class="btn btn-outline-secondary w-100 rounded-3">Batal</a>
                    </div>
                </div>
            </div>
        </form>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Initialize Map - Default to Padang city center
        const defaultLat = -0.9471;
        const defaultLng = 100.4172;
        
        const map = L.map('map').setView([defaultLat, defaultLng], 13);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);
        
        let marker = null;
        
        // Click on map to set location
        map.on('click', function(e) {
            setMarker(e.latlng.lat, e.latlng.lng);
        });
        
        function setMarker(lat, lng) {
            if (marker) {
                map.removeLayer(marker);
            }
            marker = L.marker([lat, lng], {draggable: true}).addTo(map);
            document.getElementById('latitude').value = lat.toFixed(6);
            document.getElementById('longitude').value = lng.toFixed(6);
            
            marker.on('dragend', function(e) {
                const pos = e.target.getLatLng();
                document.getElementById('latitude').value = pos.lat.toFixed(6);
                document.getElementById('longitude').value = pos.lng.toFixed(6);
            });
        }
        
        // Get current location
        document.getElementById('getLocation').addEventListener('click', function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    map.setView([lat, lng], 16);
                    setMarker(lat, lng);
                }, function(error) {
                    alert('Gagal mendapatkan lokasi: ' + error.message);
                });
            } else {
                alert('Geolocation tidak didukung browser ini.');
            }
        });
        
        // Update map when coordinates are manually entered
        document.getElementById('latitude').addEventListener('change', updateMapFromInputs);
        document.getElementById('longitude').addEventListener('change', updateMapFromInputs);
        
        function updateMapFromInputs() {
            const lat = parseFloat(document.getElementById('latitude').value);
            const lng = parseFloat(document.getElementById('longitude').value);
            if (!isNaN(lat) && !isNaN(lng)) {
                map.setView([lat, lng], 16);
                setMarker(lat, lng);
            }
        }

        // Photo preview
        document.getElementById('fotoInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.getElementById('previewImg');
                    const placeholder = document.getElementById('previewPlaceholder');
                    img.src = e.target.result;
                    img.classList.remove('d-none');
                    if (placeholder) placeholder.classList.add('d-none');
                }
                reader.readAsDataURL(file);
            }
        });

        // Auto-calculate quota per jalur
        document.getElementById('autoCalculate').addEventListener('click', function() {
            const total = parseInt(document.getElementById('totalKuota').value) || 0;
            const domisili = Math.floor(total * 0.35);
            const afirmasi = Math.floor(total * 0.30);
            const akademik = Math.floor(total * 0.15);
            const nonakademik = Math.floor(total * 0.15);
            const mutasi = Math.floor(total * 0.05);
            
            const allocated = domisili + afirmasi + akademik + nonakademik + mutasi;
            const adjusted_domisili = domisili + (total - allocated);
            
            document.getElementById('kuotaDomisili').value = adjusted_domisili;
            document.getElementById('kuotaAfirmasi').value = afirmasi;
            document.getElementById('kuotaPA').value = akademik;
            document.getElementById('kuotaPN').value = nonakademik;
            document.getElementById('kuotaMutasi').value = mutasi;
            
            updateTotal();
        });

        // Update total jalur
        function updateTotal() {
            const d = parseInt(document.getElementById('kuotaDomisili').value) || 0;
            const a = parseInt(document.getElementById('kuotaAfirmasi').value) || 0;
            const pa = parseInt(document.getElementById('kuotaPA').value) || 0;
            const pn = parseInt(document.getElementById('kuotaPN').value) || 0;
            const m = parseInt(document.getElementById('kuotaMutasi').value) || 0;
            document.getElementById('totalJalur').textContent = d + a + pa + pn + m;
        }

        // Update total on input change
        ['kuotaDomisili', 'kuotaAfirmasi', 'kuotaPA', 'kuotaPN', 'kuotaMutasi'].forEach(id => {
            document.getElementById(id).addEventListener('input', updateTotal);
        });

        // Initial calculation
        updateTotal();
    </script>
</body>
</html>
