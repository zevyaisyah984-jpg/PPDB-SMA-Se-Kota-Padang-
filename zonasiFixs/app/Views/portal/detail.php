<?php
// Helper for Progress calculation
function calculateProgress($current, $total) {
    if ($total <= 0) return 0;
    $percent = ($current / $total) * 100;
    return min($percent, 100); // Cap at 100%
}
?>

<div class="bg-surface-secondary min-vh-100 pb-5 font-sans">
    
    <!-- 1. DYNAMIC HERO SECTION -->
    <?php 
    $fotoPath = !empty($sekolah['foto']) ? 'uploads/sekolah/' . $sekolah['foto'] : 'assets/images/hero_bg.jpg';
    $fotoUrl = url($fotoPath); // Ensure 'url' helper handles the path correctly
    ?>
    <section class="position-relative w-100 overflow-hidden" style="height: 500px;">
        <!-- Background Image with Zoom Effect -->
        <div class="position-absolute top-0 start-0 w-100 h-100" style="
            background: url('<?php echo $fotoUrl; ?>') no-repeat center center;
            background-size: cover;
            animation: slowZoom 30s infinite alternate;
        "></div>
        
        <!-- Gradient Overlay (Dark & Premium) -->
        <div class="position-absolute top-0 start-0 w-100 h-100" style="
            background: linear-gradient(180deg, rgba(15, 23, 42, 0.3) 0%, rgba(15, 23, 42, 0.8) 60%, #0F172A 100%);
        "></div>
        
        <!-- Content -->
        <div class="container position-relative h-100 py-5 d-flex flex-column justify-content-between z-2">
            <!-- Header Nav -->
            <div class="d-flex justify-content-between align-items-center mt-2">
                <a href="<?php echo url('/kuota'); ?>" class="btn btn-glass btn-icon rounded-circle text-white backdrop-blur hover-scale">
                    <i class="bi bi-arrow-left fs-5"></i>
                </a>
                <!-- Floating Accreditation Badge -->
                <div class="badge-glass px-4 py-2 rounded-pill d-flex align-items-center gap-2 backdrop-blur shadow-lg animate-fade-in-down">
                    <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">
                        <i class="bi bi-star-fill small"></i>
                    </div>
                    <span class="text-white fw-bold letter-spacing-wide">Akreditasi <?php echo $sekolah['akreditasi']; ?></span>
                </div>
            </div>
            
            <!-- Hero Text -->
            <div class="text-white mb-5 animate-fade-in-up">
                <div class="d-inline-flex align-items-center gap-2 mb-3 bg-white bg-opacity-10 px-4 py-1 rounded-pill backdrop-blur border border-white border-opacity-10">
                    <span class="w-2 h-2 rounded-circle bg-green-400 breathing-dot"></span>
                    <span class="small fw-bold text-uppercase tracking-wider">Pendaftaran Zonasi 2024</span>
                </div>
                
                <h1 class="display-3 fw-bold mb-2 text-shadow-lg lh-sm"><?php echo htmlspecialchars($sekolah['nama']); ?></h1>
                
                <div class="d-flex flex-wrap align-items-center gap-4 mt-3 opacity-90 text-shadow-sm">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-geo-alt-fill text-danger"></i>
                        <span class="fs-5"><?php echo htmlspecialchars($sekolah['alamat']); ?></span>
                    </div>
                    <?php if(!empty($sekolah['telepon'])): ?>
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-telephone-fill text-success"></i>
                        <span><?php echo htmlspecialchars($sekolah['telepon']); ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content Container overlap -->
    <div class="container position-relative z-3" style="margin-top: -100px;">
        
        <div class="row g-4 mb-5">
            <!-- 2. LEFT COLUMN: INFO & MAP -->
            <div class="col-lg-8">
                <!-- Bento Grid Container -->
                <div class="d-flex flex-column gap-4">
                    
                    <!-- Profile Card -->
                    <div class="card border-0 shadow-apple p-4 p-md-5 bg-white rounded-3xl animate-fade-in-up" style="animation-delay: 0.1s;">
                         <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom border-light">
                            <h4 class="fw-bold text-slate-800 mb-0">
                                <i class="bi bi-building-check me-2 text-primary"></i>Profil Sekolah
                            </h4>
                         </div>
                         
                         <div class="row g-4">
                             <!-- Principal -->
                             <div class="col-md-12">
                                 <div class="p-4 rounded-4 bg-slate-50 border-micro d-flex align-items-center gap-4 hover-bg-white transition-all">
                                     <div class="icon-sq-lg bg-white shadow-sm text-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0">
                                         <i class="bi bi-person-workspace fs-3"></i>
                                     </div>
                                     <div>
                                         <p class="text-muted small mb-1 fw-bold text-uppercase tracking-wide">Kepala Sekolah</p>
                                         <h5 class="fw-bold text-slate-800 mb-0"><?php echo !empty($sekolah['kepala_sekolah']) ? htmlspecialchars($sekolah['kepala_sekolah']) : '-'; ?></h5>
                                         <?php if(!empty($sekolah['nip_kepala_sekolah'])): ?>
                                            <small class="text-muted font-monospace bg-white px-2 py-1 rounded border small mt-1 d-inline-block">
                                                NIP. <?php echo htmlspecialchars($sekolah['nip_kepala_sekolah']); ?>
                                            </small>
                                         <?php endif; ?>
                                     </div>
                                 </div>
                             </div>

                             <!-- Stats Grid -->
                             <div class="col-md-6">
                                 <div class="p-4 rounded-4 h-100 bg-blue-50 border-blue border-opacity-10 position-relative overflow-hidden group">
                                     <div class="d-position-absolute top-0 end-0 p-3 opacity-10 group-hover-scale transition-transform">
                                        <i class="bi bi-card-heading fs-1 text-blue-600"></i>
                                     </div>
                                     <div class="text-blue-600 fw-bold small text-uppercase mb-2">NPSN</div>
                                     <h2 class="fw-bold text-slate-800 mb-0 tracking-tight"><?php echo htmlspecialchars($sekolah['npsn']); ?></h2>
                                     <p class="text-blue-600 small opacity-75 mb-0 mt-1">Nomor Pokok Sekolah Nasional</p>
                                 </div>
                             </div>
                             
                             <div class="col-md-6">
                                 <div class="p-4 rounded-4 h-100 bg-amber-50 border-amber border-opacity-10 position-relative overflow-hidden group">
                                     <div class="d-position-absolute top-0 end-0 p-3 opacity-10 group-hover-scale transition-transform">
                                        <i class="bi bi-star-fill fs-1 text-amber-600"></i>
                                     </div>
                                     <div class="text-amber-600 fw-bold small text-uppercase mb-2">Akreditasi</div>
                                     <h2 class="fw-bold text-slate-800 mb-0 tracking-tight">Grade <?php echo htmlspecialchars($sekolah['akreditasi']); ?></h2>
                                     <p class="text-amber-600 small opacity-75 mb-0 mt-1">Standar Nasional Pendidikan</p>
                                 </div>
                             </div>
                         </div>
                    </div>

                    <!-- Modern Quota Section -->
                    <div class="card border-0 shadow-apple p-4 p-md-5 bg-white rounded-3xl animate-fade-in-up" style="animation-delay: 0.2s;">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div>
                                <h4 class="fw-bold text-slate-800 mb-1">
                                    <i class="bi bi-pie-chart-fill me-2 text-primary"></i>Kuota & Pendaftar
                                </h4>
                                <p class="text-muted small mb-0">Update real-time penerimaan siswa baru</p>
                            </div>
                            <span class="badge bg-green-100 text-green-700 rounded-pill px-3 py-2 border-green small">
                                <span class="w-2 h-2 rounded-circle bg-green-500 d-inline-block me-1 blinking-dot"></span> Live Data
                            </span>
                        </div>
                        
                        <div class="row g-3">
                            <?php 
                            $paths = [
                                'domisili' => ['color' => 'blue', 'icon' => 'house-door', 'label' => 'Jalur Zonasi', 'desc' => 'Prioritas jarak tempat tinggal', 'quota' => $sekolah['kuota_domisili'] ?? 0, 'filled' => $pendaftar_counts['zonasi'] ?? 0],
                                'afirmasi' => ['color' => 'purple', 'icon' => 'heart', 'label' => 'Jalur Afirmasi', 'desc' => 'Keluarga kurang mampu', 'quota' => $sekolah['kuota_afirmasi'] ?? 0, 'filled' => $pendaftar_counts['afirmasi'] ?? 0], 
                                'prestasi' => ['color' => 'rose', 'icon' => 'trophy', 'label' => 'Jalur Prestasi', 'desc' => 'Akademik & Non-Akademik', 'quota' => ($sekolah['kuota_prestasi_akademik'] ?? 0) + ($sekolah['kuota_prestasi_nonakademik'] ?? 0), 'filled' => $pendaftar_counts['prestasi'] ?? 0],
                                'mutasi'   => ['color' => 'orange', 'icon' => 'arrow-left-right', 'label' => 'Jalur Mutasi', 'desc' => 'Perpindahan tugas orang tua', 'quota' => $sekolah['kuota_mutasi'] ?? 0, 'filled' => $pendaftar_counts['mutasi'] ?? 0]
                            ];
                            
                            foreach($paths as $key => $path): 
                                $progress = calculateProgress($path['filled'], $path['quota']);
                                $isFull = $path['filled'] >= $path['quota'];
                                $color = $path['color'];
                            ?>
                            <div class="col-md-6">
                                <div class="p-3 border rounded-4 hover-shadow transition-all bg-white h-100 position-relative overflow-hidden">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="rounded-3 bg-<?php echo $color; ?>-50 p-3 text-<?php echo $color; ?>-600 me-3">
                                            <i class="bi bi-<?php echo $path['icon']; ?>-fill fs-5"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold text-dark mb-0"><?php echo $path['label']; ?></h6>
                                            <span class="text-muted small"><?php echo $path['desc']; ?></span>
                                        </div>
                                        <div class="ms-auto text-end">
                                            <div class="fw-bold fs-4 text-<?php echo $color; ?>-600 lh-1"><?php echo $path['quota']; ?></div>
                                            <small class="text-uppercase text-muted" style="font-size: 0.65rem; letter-spacing: 0.5px;">Kuota</small>
                                        </div>
                                    </div>
                                    
                                    <!-- Progress -->
                                    <div class="d-flex justify-content-between small fw-bold mb-1 mt-auto">
                                        <span class="text-<?php echo $color; ?>-700">
                                            <i class="bi bi-people-fill me-1"></i><?php echo $path['filled']; ?> Pendaftar
                                        </span>
                                        <span class="text-secondary"><?php echo round($progress); ?>%</span>
                                    </div>
                                    <div class="progress" style="height: 6px; border-radius: 10px; background: #f1f5f9;">
                                        <div class="progress-bar bg-<?php echo $color; ?>-500 shadow-sm" role="progressbar" style="width: <?php echo $progress; ?>%; border-radius: 10px;"></div>
                                    </div>
                                    
                                    <?php if($isFull): ?>
                                    <div class="position-absolute top-0 end-0 m-2">
                                        <span class="badge bg-danger rounded-pill shadow-sm small">Penuh</span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- 3. RIGHT COLUMN: DISTANCE & MAP -->
            <div class="col-lg-4">
                <div class="sticky-lg-top" style="top: 20px; z-index: 10;">
                    
                    <!-- Distance Calculator -->
                    <div class="card border-0 shadow-apple overflow-hidden mb-4 bg-primary text-white rounded-3xl animate-fade-in-up" style="animation-delay: 0.3s; background: linear-gradient(135deg, #2563EB 0%, #1E40AF 100%);">
                        <div class="card-body p-4 position-relative overflow-hidden">
                            <!-- Decor circles -->
                            <div class="position-absolute top-0 end-0 translate-middle-y me-n3 mt-n3 rounded-circle bg-white opacity-10" style="width: 150px; height: 150px;"></div>
                            
                            <div class="position-relative z-2">
                                <div class="d-flex justify-content-between align-items-start mb-4">
                                    <div class="icon-sq bg-white bg-opacity-20 rounded-3 d-flex align-items-center justify-content-center border border-white border-opacity-20 navbar-blur">
                                        <i class="bi bi-geo-alt-fill fs-4 text-white"></i>
                                    </div>
                                    <span class="badge bg-black bg-opacity-20 rounded-pill px-3 py-1 border border-white border-opacity-10 backdrop-blur">
                                        <i class="bi bi-crosshair me-1"></i> GPS Tracker
                                    </span>
                                </div>
                                
                                <h5 class="fw-bold text-white mb-1 opacity-90">Cek Jarak Zonasi</h5>
                                <p class="text-blue-100 small mb-4">Hitung jarak rumah ke sekolah secara akurat.</p>
                                
                                <!-- Distance Result -->
                                <div id="distanceDisplay" class="text-center py-3 rounded-4 bg-black bg-opacity-10 border border-white border-opacity-10 mb-4 backdrop-blur">
                                    <h1 class="display-3 fw-bold mb-0 lh-1"><span id="distanceValue">--</span></h1>
                                    <p class="small text-white opacity-75 mb-0" id="distanceStatus">Klik tombol hitung</p>
                                </div>
                                
                                <!-- Zone Status -->
                                <div id="zoneIndicator" class="d-none mb-3 slide-up-fade">
                                    <div class="d-flex align-items-center gap-3 bg-white text-dark rounded-4 p-3 shadow-md">
                                        <div class="rounded-circle p-2 flex-shrink-0" id="zoneBadge">
                                            <i class="bi bi-check-lg"></i>
                                        </div>
                                        <div class="flex-grow-1 lh-sm">
                                            <div class="fw-bold" id="zoneText">Dalam Zona</div>
                                            <div class="text-xs text-muted">Radius aman</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <button onclick="calculateDistance(<?php echo $sekolah['latitude']; ?>, <?php echo $sekolah['longitude']; ?>)" 
                                        class="btn btn-white w-100 rounded-pill py-3 fw-bold shadow-lg hover-scale text-primary" 
                                        id="calculateBtn">
                                    <i class="bi bi-crosshair me-2"></i>
                                    <span id="btnText">Hitung Jarak Saya</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Integrated Map -->
                     <div class="card border-0 shadow-apple overflow-hidden position-relative p-1 bg-white rounded-3xl animate-fade-in-up" style="animation-delay: 0.4s; height: 350px;">
                        <div class="w-100 h-100 rounded-4 overflow-hidden position-relative">
                            <?php if ($sekolah['latitude'] && $sekolah['longitude']): ?>
                                <div id="school-detail-map" style="width: 100%; height: 100%; z-index: 1;" class="saturation-low"></div>
                                <!-- Floating Button -->
                                <div class="position-absolute bottom-0 start-0 w-100 p-3 bg-gradient-to-t-white" style="z-index: 2;">
                                    <a href="https://maps.google.com/?q=<?php echo $sekolah['latitude']; ?>,<?php echo $sekolah['longitude']; ?>" target="_blank" class="btn btn-dark w-100 rounded-pill shadow-lg py-3 fw-bold tracking-wide hover-scale">
                                        <i class="bi bi-map-fill me-2"></i>Buka di Google Maps
                                    </a>
                                </div>
                            <?php else: ?>
                                 <div class="d-flex flex-column align-items-center justify-content-center h-100 bg-slate-50">
                                    <i class="bi bi-map-fill fs-1 text-slate-300 mb-2"></i>
                                    <span class="small fw-bold text-muted">Lokasi belum diset</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* --- PREMIUM UI VARIABLES --- */
:root {
    --text-shadow-lg: 0 4px 20px rgba(0,0,0,0.5);
    --shadow-apple: 0 20px 40px -10px rgba(0,0,0,0.06);
    --primary-gradient: linear-gradient(135deg, #2563EB 0%, #1E40AF 100%);
}

/* --- TYPOGRAPHY --- */
.font-sans { font-family: 'Plus Jakarta Sans', sans-serif; }
.tracking-tight { letter-spacing: -0.025em; }
.tracking-wide { letter-spacing: 0.05em; }
.text-shadow-lg { text-shadow: var(--text-shadow-lg); }

/* --- COLORS --- */
.bg-slate-50 { background-color: #F8FAFC; }
.text-slate-800 { color: #1E293B; }
.bg-surface-secondary { background-color: #F1F5F9; }

/* Palette Colors */
.bg-blue-50 { background-color: #EFF6FF; } .text-blue-600 { color: #2563EB; } .bg-blue-500 { background-color: #3B82F6; }
.bg-amber-50 { background-color: #FFFBEB; } .text-amber-600 { color: #D97706; } .bg-amber-500 { background-color: #F59E0B; }
.bg-rose-50  { background-color: #FFF1F2; } .text-rose-600  { color: #E11D48; } .bg-rose-500  { background-color: #F43F5E; }
.bg-purple-50 { background-color: #FAF5FF; } .text-purple-600 { color: #9333EA; } .bg-purple-500 { background-color: #A855F7; }
.bg-orange-50 { background-color: #FFF7ED; } .text-orange-600 { color: #EA580C; } .bg-orange-500 { background-color: #F97316; }
.bg-green-100 { background-color: #DCFCE7; } .text-green-700 { color: #15803D; } .bg-green-500 { background-color: #22C55E; }

/* --- COMPONENTS --- */
.rounded-3xl { border-radius: 24px !important; }

.btn-glass { 
    background: rgba(255, 255, 255, 0.15); 
    border: 1px solid rgba(255,255,255,0.2); 
    color: white;
}
.btn-glass:hover { background: rgba(255, 255, 255, 0.25); color: white; }

.badge-glass { background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); }
.backdrop-blur { backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); }

.border-micro { border: 1px solid rgba(0,0,0,0.04); }
.shadow-apple { box-shadow: var(--shadow-apple); }

.icon-sq-lg { width: 64px; height: 64px; }
.icon-sq { width: 48px; height: 48px; }

.hover-scale { transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1); }
.hover-scale:hover { transform: scale(1.05); }

.hover-bg-white:hover { background: white !important; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
.hover-shadow:hover { box-shadow: 0 10px 20px -5px rgba(0,0,0,0.1) !important; transform: translateY(-2px); }

.bg-gradient-to-t-white {
    background: linear-gradient(to top, rgba(255,255,255,1) 0%, rgba(255,255,255,0.8) 50%, rgba(255,255,255,0) 100%);
}

.w-2 { width: 0.5rem; }
.h-2 { height: 0.5rem; }

/* --- ANIMATIONS --- */
@keyframes slowZoom {
    from { transform: scale(1); }
    to { transform: scale(1.15); }
}

@keyframes fadeInDown {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in-down { animation: fadeInDown 0.8s ease-out forwards; }

@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in-up { opacity: 0; animation: fadeInUp 0.8s ease-out forwards; }

@keyframes breathe {
    0% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.2); opacity: 0.7; }
    100% { transform: scale(1); opacity: 1; }
}
.breathing-dot { animation: breathe 2s infinite ease-in-out; }

/* Custom Scrollbar for map if needed */
.saturation-low { filter: saturate(0.8); }

</style>

<!-- Scripts -->
<?php if ($sekolah['latitude'] && $sekolah['longitude']): ?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mapEl = document.getElementById('school-detail-map');
        if (mapEl) {
             var detailMap = L.map('school-detail-map', {
                zoomControl: false, 
                attributionControl: false, 
                dragging: false, 
                scrollWheelZoom: false,
                doubleClickZoom: false
            }).setView([<?php echo $sekolah['latitude']; ?>, <?php echo $sekolah['longitude']; ?>], 15);
            
            L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                maxZoom: 20
            }).addTo(detailMap);
            
            L.marker([<?php echo $sekolah['latitude']; ?>, <?php echo $sekolah['longitude']; ?>], {
                icon: L.divIcon({
                    className: 'school-marker-detail',
                    html: '<div style="background-color: #E11D48; width: 40px; height: 40px; border: 4px solid white; border-radius: 50%; box-shadow: 0 8px 20px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center; position:relative;"><i class="bi bi-mortarboard-fill text-white fs-5"></i></div>',
                    iconSize: [40, 40],
                    iconAnchor: [20, 20]
                })
            }).addTo(detailMap);
            
            setTimeout(() => detailMap.invalidateSize(), 500);
        }
    });

    function calculateDistance(schoolLat, schoolLng) {
        const btn = document.getElementById('calculateBtn');
        const btnText = document.getElementById('btnText');
        const distanceValue = document.getElementById('distanceValue');
        const distanceStatus = document.getElementById('distanceStatus');
        const zoneIndicator = document.getElementById('zoneIndicator');
        const zoneBadge = document.getElementById('zoneBadge');
        const zoneText = document.getElementById('zoneText');
        
        btn.disabled = true;
        btnText.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mendeteksi Lokasi...';
        
        if (!navigator.geolocation) {
            alert('❌ Geolocation tidak didukung browser Anda');
            btn.disabled = false;
            btnText.textContent = 'Hitung Jarak Saya';
            return;
        }
        
        navigator.geolocation.getCurrentPosition(function(position) {
            const userLat = position.coords.latitude;
            const userLng = position.coords.longitude;
            const distance = getDistanceFromLatLonInKm(userLat, userLng, schoolLat, schoolLng);
            
            distanceValue.style.opacity = '0';
            setTimeout(() => {
                distanceValue.innerHTML = `${distance.toFixed(2)} <span class="fs-4 fw-normal">km</span>`;
                distanceValue.style.opacity = '1';
                distanceValue.parentElement.classList.add('bg-success', 'bg-opacity-25');
                distanceValue.parentElement.classList.remove('bg-black');
            }, 300);
            
            distanceStatus.textContent = 'Akurasi GPS Tinggi';
            zoneIndicator.classList.remove('d-none');
            
            if (distance <= 3) {
                zoneBadge.className = 'rounded-circle p-2 bg-success text-white';
                zoneBadge.innerHTML = '<i class="bi bi-check-lg"></i>';
                zoneText.textContent = 'Dalam Zona Domisili';
                zoneText.classList.add('text-success');
            } else {
                zoneBadge.className = 'rounded-circle p-2 bg-warning text-dark';
                zoneBadge.innerHTML = '<i class="bi bi-exclamation-lg"></i>';
                zoneText.textContent = 'Luar Zona Domisili';
                zoneText.classList.add('text-warning');
            }
            
            btn.disabled = false;
            btn.className = 'btn btn-success w-100 rounded-pill py-3 fw-bold shadow-lg';
            btnText.innerHTML = '<i class="bi bi-check-circle me-2"></i>Selesai';
            
        }, function(error) {
            alert('Gagal: ' + error.message);
            btn.disabled = false;
            btnText.textContent = 'Coba Lagi';
        }, { enableHighAccuracy: true });
    }
    
    function getDistanceFromLatLonInKm(lat1, lon1, lat2, lon2) {
        const R = 6371; 
        const dLat = deg2rad(lat2 - lat1);
        const dLon = deg2rad(lon2 - lon1);
        const a = Math.sin(dLat/2) * Math.sin(dLat/2) + Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) * Math.sin(dLon/2) * Math.sin(dLon/2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        return R * c;
    }
    function deg2rad(deg) { return deg * (Math.PI/180); }
</script>
<?php endif; ?>
