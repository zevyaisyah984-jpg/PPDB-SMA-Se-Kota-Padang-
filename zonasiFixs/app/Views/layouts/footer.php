<!-- Footer - Premium Modern Design -->
<footer class="footer">
    <div class="container">
        <div class="row g-5">
            <!-- Brand Column -->
            <div class="col-lg-4">
                <div class="d-flex align-items-center mb-4">
                    <img src="<?php echo asset('images/logo_kemdikbud.png'); ?>" alt="Logo" width="52" height="52" class="me-3 footer-logo-bg">
                    <div>
                        <h5 class="mb-0 text-white">PPDB SMA Padang</h5>
                        <small class="text-primary-light fw-medium">Dinas Pendidikan Kota Padang</small>
                    </div>
                </div>
                <p class="mb-4 opacity-75 lh-lg">
                    Portal resmi Penerimaan Peserta Didik Baru (PPDB) SMA Negeri Kota Padang. Kami berkomitmen menyelenggarakan seleksi yang transparan, objektif, dan akuntabel bagi seluruh calon siswa.
                </p>
                <div class="d-flex gap-3">
                    <a href="#" class="social-btn"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="social-btn"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="social-btn"><i class="bi bi-youtube"></i></a>
                    <a href="#" class="social-btn"><i class="bi bi-whatsapp"></i></a>
                </div>
            </div>
            
            <!-- Quick Links -->
            <div class="col-6 col-md-3 col-lg-2">
                <h6 class="mb-4 text-white">Layanan</h6>
                <div class="footer-link-group">
                    <a href="<?php echo url('/jadwal'); ?>">Jadwal PPDB</a>
                    <a href="<?php echo url('/'); ?>#mekanisme">Alur Pendaftaran</a>
                    <a href="<?php echo url('/persyaratan'); ?>">Persyaratan</a>
                    <a href="<?php echo url('/kuota'); ?>">Daya Tampung</a>
                </div>
            </div>
            
            <!-- Contact Info -->
            <div class="col-md-5 col-lg-3">
                <h6 class="mb-4 text-white">Hubungi Kami</h6>
                <div class="footer-contact-item">
                    <div class="footer-contact-icon">
                        <i class="bi bi-geo-alt"></i>
                    </div>
                    <div>
                        <small class="d-block text-muted mb-1">Alamat Kantor</small>
                        <span class="small">Jl. Jenderal Sudirman No.1, Kota Padang</span>
                    </div>
                </div>
                <div class="footer-contact-item">
                    <div class="footer-contact-icon">
                        <i class="bi bi-envelope"></i>
                    </div>
                    <div>
                        <small class="d-block text-muted mb-1">Email Dukungan</small>
                        <span class="small">ppdb@padang.go.id</span>
                    </div>
                </div>
            </div>
            
            <!-- Related Sites -->
            <div class="col-6 col-md-4 col-lg-3">
                <h6 class="mb-4 text-white">Tautan Terkait</h6>
                <div class="footer-link-group">
                    <a href="https://kemdikbud.go.id" target="_blank"><i class="bi bi-link-45deg"></i> Kemdikbudristek</a>
                    <a href="https://sumbarprov.go.id" target="_blank"><i class="bi bi-link-45deg"></i> Pemprov Sumbar</a>
                    <a href="#"><i class="bi bi-link-45deg"></i> Dinas Pendidikan</a>
                    <a href="#"><i class="bi bi-link-45deg"></i> Portal Padang</a>
                </div>
            </div>
        </div>
        
        <!-- Bottom Bar -->
        <div class="footer-bottom">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    <p class="small mb-0 opacity-50 text-white">
                        &copy; <?php echo date('Y'); ?> PPDB SMA Kota Padang. All Rights Reserved.
                    </p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <a href="<?php echo url('/admin/login'); ?>" class="small text-decoration-none opacity-50 text-white hover-opacity-100 transition-base">
                        <i class="bi bi-shield-lock me-1"></i> Panel Admin
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Bottom Navigation (Mobile) -->
<?php if (file_exists(ROOT_PATH . 'app/Views/portal/partials/bottom-nav.php')) view('portal.partials.bottom-nav'); ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<!-- Scripts -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const navbar = document.getElementById('mainNavbar');
    if (navbar) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) navbar.classList.add('scrolled');
            else navbar.classList.remove('scrolled');
        });
    }
});
</script>

<!-- Confetti Animation -->
<script>
function showConfetti() {
    const colors = ['#0047AB', '#10B981', '#F59E0B', '#EF4444'];
    for (let i = 0; i < 50; i++) {
        const confetti = document.createElement('div');
        confetti.className = 'position-fixed pointer-events-none';
        confetti.style.width = '10px';
        confetti.style.height = '10px';
        confetti.style.zIndex = '9999';
        confetti.style.left = Math.random() * 100 + 'vw';
        confetti.style.top = '-10px';
        confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
        confetti.style.borderRadius = '2px';
        confetti.style.transition = 'transform 3s linear, opacity 3s';
        document.body.appendChild(confetti);
        
        setTimeout(() => {
            confetti.style.transform = `translateY(100vh) rotate(${Math.random() * 360}deg)`;
            confetti.style.opacity = '0';
        }, 100);
        
        setTimeout(() => confetti.remove(), 3100);
    }
}
</script>

<!-- Auto-Refresh Notification System -->
<script src="<?php echo url('assets/js/auto-refresh.js'); ?>"></script>

<!-- Directory Search & Filter -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('dirSearch');
    const filterKec = document.getElementById('dirFilterKec');
    const directoryGrid = document.getElementById('directoryGrid');
    const dirCount = document.getElementById('dirCount');
    const dirEmpty = document.getElementById('dirEmpty');
    
    if (!searchInput || !filterKec || !directoryGrid) return;
    
    const allCards = Array.from(directoryGrid.querySelectorAll('[data-school-name]'));
    
    function filterDirectory() {
         const searchQuery = searchInput.value.toLowerCase().trim();
        const selectedKec = filterKec.value.toLowerCase().trim();
        
        let visibleCount = 0;
        
        allCards.forEach(card => {
            const schoolName = (card.getAttribute('data-school-name') || '').toLowerCase();
            const schoolKec = (card.getAttribute('data-school-kec') || '').toLowerCase();
            
            const matchesSearch = searchQuery === '' || schoolName.includes(searchQuery);
            const matchesKec = selectedKec === '' || schoolKec.includes(selectedKec);
            
            if (matchesSearch && matchesKec) {
                card.classList.remove('d-none');
                visibleCount++;
            } else {
                card.classList.add('d-none');
            }
        });
        
        // Update count
        if (dirCount) {
            dirCount.textContent = visibleCount;
        }
        
        // Show/hide empty message
        if (dirEmpty) {
            if (visibleCount === 0) {
                dirEmpty.classList.remove('d-none');
            } else {
                dirEmpty.classList.add('d-none');
            }
        }
    }
    
    // Event listeners
    if (searchInput) {
        searchInput.addEventListener('input', filterDirectory);
    }
    
    if (filterKec) {
        filterKec.addEventListener('change', filterDirectory);
    }
});

function resetDirectoryFilters() {
    const searchInput = document.getElementById('dirSearch');
    const filterKec = document.getElementById('dirFilterKec');
    
    if (searchInput) searchInput.value = '';
    if (filterKec) filterKec.value = '';
    
    // Trigger filter to show all
    const directoryGrid = document.getElementById('directoryGrid');
    if (directoryGrid) {
        const allCards = directoryGrid.querySelectorAll('[data-school-name]');
        allCards.forEach(card => card.classList.remove('d-none'));
        
        const dirCount = document.getElementById('dirCount');
        if (dirCount) dirCount.textContent = allCards.length;
        
        const dirEmpty = document.getElementById('dirEmpty');
        if (dirEmpty) dirEmpty.classList.add('d-none');
    }
}
</script>

<!-- Search Map Initialization -->
<script>
let searchMap, userMarker, zonaCircle;
const schoolMarkers = [];
const zoneCircles = [];
const PADANG_CENTER = [-0.9471, 100.4172];
const ZONE_RADIUS = 3000; // 3km radius

document.addEventListener('DOMContentLoaded', function() {
    const mapContainer = document.getElementById('map');
    if (!mapContainer) return; // Map not on this page
    
    try {
        // Initialize map
        searchMap = L.map('map').setView(PADANG_CENTER, 13);
        
        // Primary tile layer with error handling
        const primaryTiles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        });
        
        // Fallback tile layer (Carto)
        const fallbackTiles = L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            maxZoom: 19,
            attribution: '© CARTO'
        });
        
        primaryTiles.on('tileerror', function(e) {
            console.warn('Primary tile failed, switching to fallback');
            searchMap.removeLayer(primaryTiles);
            fallbackTiles.addTo(searchMap);
        });
        
        primaryTiles.addTo(searchMap);
    } catch (error) {
        console.error('Failed to init map:', error);
        mapContainer.innerHTML = `
            <div class="d-flex flex-column align-items-center justify-content-center h-100 bg-light rounded-4">
                <i class="bi bi-exclamation-triangle-fill text-warning display-4 mb-3"></i>
                <h5 class="fw-bold">Gagal memuat peta</h5>
                <p class="text-muted text-center px-4">
                    Terjadi kesalahan saat menginisialisasi peta.<br>
                    Silakan cek koneksi internet Anda atau coba muat ulang halaman.
                </p>
                <button onclick="location.reload()" class="btn btn-primary rounded-pill btn-sm">
                    <i class="bi bi-arrow-clockwise me-2"></i>Muat Ulang
                </button>
            </div>
        `;
        return;
    }
    
    try {
        // Add school markers from PHP data
        if (window.sekolahData && Array.isArray(window.sekolahData)) {
            window.sekolahData.forEach(function(sekolah) {
                if (sekolah.latitude && sekolah.longitude) {
                    const marker = L.marker([sekolah.latitude, sekolah.longitude], {
                        icon: L.divIcon({
                            className: 'school-marker',
                            html: '<div style="background-color: #0047AB; width: 32px; height: 32px; border: 3px solid white; border-radius: 50%; box-shadow: 0 4px 10px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center;"><i class="bi bi-building text-white" style="font-size: 14px;"></i></div>',
                            iconSize: [32, 32],
                            iconAnchor: [16, 16]
                        })
                    }).addTo(searchMap);
                    
                    marker.bindPopup(`
                        <div style="min-width: 200px;">
                            <strong>${sekolah.nama}</strong><br>
                            <small class="text-muted">${sekolah.alamat || sekolah.kecamatan || ''}</small><br>
                            <div class="mt-2 d-flex gap-2">
                                <span class="badge bg-primary">Kuota: ${sekolah.kuota || 0}</span>
                                <span class="badge bg-success">${sekolah.akreditasi || 'A'}</span>
                            </div>
                            <a href="sekolah/${sekolah.id}" class="btn btn-sm btn-primary w-100 mt-2">Lihat Detail</a>
                        </div>
                    `);
                    
                    // Create zone circle for this school (hidden by default)
                    const zoneCircle = L.circle([sekolah.latitude, sekolah.longitude], {
                        radius: ZONE_RADIUS,
                        color: '#0047AB',
                        fillColor: '#0047AB',
                        fillOpacity: 0.08,
                        weight: 1,
                        dashArray: '5, 5'
                    });
                    
                    schoolMarkers.push({ marker, data: sekolah, zoneCircle });
                }
            });
        }
        
        // Fix map size issue - multiple attempts
        setTimeout(function() { searchMap.invalidateSize(); }, 100);
        setTimeout(function() { searchMap.invalidateSize(); }, 500);
        setTimeout(function() { searchMap.invalidateSize(); }, 1000);
        
        // Also fix on window resize
        window.addEventListener('resize', function() {
            if (searchMap) searchMap.invalidateSize();
        });
        
        // Initialize zone circles visibility
        const showZonesCheckbox = document.getElementById('showZones');
        if (showZonesCheckbox) {
            showZonesCheckbox.addEventListener('change', toggleZoneCircles);
            // Show zones by default if checked
            if (showZonesCheckbox.checked) {
                toggleZoneCircles();
            }
        }
        
        // Initialize Kecamatan filter
        const filterKecamatan = document.getElementById('filterKecamatan');
        if (filterKecamatan) {
            filterKecamatan.addEventListener('change', filterByKecamatan);
        }
        
    } catch (error) {
        console.error('Map initialization failed:', error);
        if (mapContainer) {
            mapContainer.innerHTML = '<div class="alert alert-warning m-4 text-center"><i class="bi bi-exclamation-triangle me-2"></i>Gagal memuat peta. Periksa koneksi internet Anda.</div>';
        }
    }
});

function toggleZoneCircles() {
    const showZones = document.getElementById('showZones');
    if (!showZones || !searchMap) return;
    
    schoolMarkers.forEach(item => {
        if (showZones.checked) {
            item.zoneCircle.addTo(searchMap);
        } else {
            searchMap.removeLayer(item.zoneCircle);
        }
    });
}

// Jalan Lalu Lintas Layer (Google Maps Traffic)
let trafficLayer;

function toggleTrafficLayer() {
    const showTraffic = document.getElementById('showTraffic');
    if (!showTraffic || !searchMap) return;
    
    if (showTraffic.checked) {
        if (!trafficLayer) {
            // Use Google Traffic Tiles
            trafficLayer = L.tileLayer('https://mt0.google.com/vt/lyrs=m,traffic&x={x}&y={y}&z={z}', {
                attribution: '© Google Maps',
                maxZoom: 20
            });
        }
        trafficLayer.addTo(searchMap);
    } else {
        if (trafficLayer) {
            searchMap.removeLayer(trafficLayer);
        }
    }
}

function filterByKecamatan() {
    const filterKecamatan = document.getElementById('filterKecamatan');
    if (!filterKecamatan || !searchMap) return;
    
    const selectedKecamatan = filterKecamatan.value.toLowerCase();
    
    schoolMarkers.forEach(item => {
        const schoolKecamatan = (item.data.kecamatan || '').toLowerCase();
        
        if (selectedKecamatan === '' || schoolKecamatan.includes(selectedKecamatan)) {
            item.marker.addTo(searchMap);
            if (document.getElementById('showZones')?.checked) {
                item.zoneCircle.addTo(searchMap);
            }
        } else {
            searchMap.removeLayer(item.marker);
            searchMap.removeLayer(item.zoneCircle);
        }
    });
    
    // Auto-focus if only one result
    const visibleSchools = schoolMarkers.filter(item => {
        const schoolKecamatan = (item.data.kecamatan || '').toLowerCase();
        return selectedKecamatan === '' || schoolKecamatan.includes(selectedKecamatan);
    });
    
    if (visibleSchools.length === 1 && visibleSchools[0].data.latitude) {
        searchMap.setView([visibleSchools[0].data.latitude, visibleSchools[0].data.longitude], 15);
    } else if (selectedKecamatan === '') {
        searchMap.setView(PADANG_CENTER, 13);
    }
}

function getCurrentLocation() {
    if (!searchMap) return;
    
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            
            searchMap.setView([lat, lng], 15);
            
            // Add user marker
            if (userMarker) searchMap.removeLayer(userMarker);
            userMarker = L.marker([lat, lng], {
                icon: L.divIcon({
                    className: 'user-marker',
                    html: '<div style="background-color: #F59E0B; width: 20px; height: 20px; border: 3px solid white; border-radius: 50%; box-shadow: 0 0 0 4px rgba(245,158,11,0.3);"></div>',
                    iconSize: [20, 20],
                    iconAnchor: [10, 10]
                })
            }).addTo(searchMap);
            
            userMarker.bindPopup('<strong>Lokasi Anda</strong>').openPopup();
            
            // Show user zone radius
            if (zonaCircle) searchMap.removeLayer(zonaCircle);
            zonaCircle = L.circle([lat, lng], {
                radius: ZONE_RADIUS,
                color: '#F59E0B',
                fillColor: '#F59E0B',
                fillOpacity: 0.15,
                weight: 2
            }).addTo(searchMap);
            
        }, function(error) {
            alert('Gagal mendapatkan lokasi: ' + error.message);
        });
    } else {
        alert('Geolocation tidak didukung browser ini.');
    }
}

function resetAllFilters() {
    // Reset search input
    const searchInput = document.getElementById('searchSchool');
    if (searchInput) searchInput.value = '';
    
    // Reset kecamatan filter
    const filterKecamatan = document.getElementById('filterKecamatan');
    if (filterKecamatan) filterKecamatan.value = '';
    
    // Show all markers
    schoolMarkers.forEach(item => {
        item.marker.addTo(searchMap);
    });
    
    // Remove user marker and zone circle
    if (userMarker && searchMap) {
        searchMap.removeLayer(userMarker);
        userMarker = null;
    }
    if (zonaCircle && searchMap) {
        searchMap.removeLayer(zonaCircle);
        zonaCircle = null;
    }
    
    // Reset map view
    if (searchMap) {
        searchMap.setView(PADANG_CENTER, 13);
    }
    
    // Refresh zone visibility
    toggleZoneCircles();
}

// Search functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchSchool');
    const suggestionBox = document.getElementById('schoolSuggestions');
    
    // Simple Fuzzy Match Function
    function fuzzyMatch(str, pattern) {
        pattern = pattern.toLowerCase().split('').reduce((a,b) => a + '[^' + b + ']*' + b, '');
        return new RegExp(pattern).test(str.toLowerCase());
    }
    
    // Highlight matching text
    function highlightMatch(text, query) {
        if (!query) return text;
        const regex = new RegExp(`(${query.split('').join('.*?')})`, 'gi');
        return text.replace(regex, '<span class="text-primary fw-bold">$1</span>');
    }

    if (searchInput && suggestionBox && window.sekolahData) {
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            if (query.length < 1) {
                suggestionBox.classList.add('d-none');
                return;
            }
            
            // Fuzzy filter
            const matches = window.sekolahData.filter(s => {
                const name = s.nama.toLowerCase();
                // Prioritize exact/contains match
                if (name.includes(query)) return true;
                // Then fuzzy match
                return fuzzyMatch(name, query);
            }).slice(0, 8); // Show up to 8 results
            
            if (matches.length > 0) {
                suggestionBox.innerHTML = matches.map(s => 
                    `<div class="search-suggestion-item" onclick="focusSchool(${s.latitude}, ${s.longitude}, '${s.nama.replace(/\'/g, "\\\'")}')">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-geo-alt me-2 text-muted"></i>
                            <div>
                                <div class="small fw-bold">${highlightMatch(s.nama, query)}</div>
                                <div class="text-muted" style="font-size: 10px;">${s.kecamatan || 'Kota Padang'}</div>
                            </div>
                        </div>
                    </div>`
                ).join('');
                suggestionBox.classList.remove('d-none');
            } else {
                suggestionBox.innerHTML = '<div class="p-3 text-center text-muted small">Sekolah tidak ditemukan</div>';
                suggestionBox.classList.remove('d-none');
            }
        });
        
        // Hide on outside click
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !suggestionBox.contains(e.target)) {
                suggestionBox.classList.add('d-none');
            }
        });
    }
});

function focusSchool(lat, lng, name) {
    if (searchMap && lat && lng) {
        searchMap.setView([lat, lng], 16);
        document.getElementById('schoolSuggestions').classList.add('d-none');
        document.getElementById('searchSchool').value = name;
        
        // Find and open popup for the school
        schoolMarkers.forEach(item => {
            if (item.data.latitude == lat && item.data.longitude == lng) {
                item.marker.openPopup();
            }
        });
    }
}
</script>

<?php if (function_exists('view')) @view('portal.partials.closed_modal'); ?>

</body>
</html>

