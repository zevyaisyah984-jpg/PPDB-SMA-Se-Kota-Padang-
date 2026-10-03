<!-- Floating Search Map Section -->
<div id="searchSection" class="container-fluid px-0 px-lg-5 my-5">
    
    <!-- Map Container with Floating UI -->
    <div class="map-outer-wrapper position-relative shadow-2xl overflow-hidden" style="border-radius: 32px; height: 80vh; min-height: 600px;">
        
        <!-- The Map -->
        <div id="map" class="h-100 w-100 z-0"></div>
        
        <!-- Floating Interaction Layer -->
        <div class="position-absolute top-0 start-0 w-100 h-100 z-1 pointer-events-none p-4">
            
            <!-- 1. Floating Search Bar (Top Center) -->
            <div class="row justify-content-center pointer-events-auto">
                <div class="col-lg-10 col-xl-8">
                    <div class="glass-search-bar d-flex flex-column flex-md-row gap-2 p-2 rounded-4 shadow-lg animate-slide-down">
                        
                        <!-- Search Input -->
                        <div class="flex-grow-1 position-relative">
                            <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary"></i>
                            <input type="text" 
                                   class="form-control border-0 bg-transparent py-3 ps-5 text-dark fw-medium" 
                                   id="searchSchool" 
                                   placeholder="Cari nama sekolah (contoh: SMA 1)..."
                                   autocomplete="off"
                                   style="box-shadow: none;">
                            <div id="schoolSuggestions" class="position-absolute top-100 start-0 w-100 mt-2 bg-white rounded-4 shadow-lg overflow-hidden d-none"></div>
                        </div>
                        
                        <!-- Divider (Desktop) -->
                        <div class="vr d-none d-md-block my-2 text-secondary opacity-25"></div>
                        
                        <!-- Dropdown Kecamatan -->
                        <div class="position-relative" style="min-width: 200px;">
                            <i class="bi bi-geo-alt position-absolute top-50 start-0 translate-middle-y ms-3 text-primary"></i>
                            <select class="form-select border-0 bg-transparent py-3 ps-5 text-dark fw-medium" 
                                    id="filterKecamatan" 
                                    style="box-shadow: none; cursor: pointer;">
                                <option value="">Semua Kecamatan</option>
                                <option value="Bungus Tlk.Kabung">Bungus Tlk. Kabung</option>
                                <option value="Lubuk Begalung">Lubuk Begalung</option>
                                <option value="Kuranji">Kuranji</option>
                                <option value="Pauh">Pauh</option>
                                <option value="Lubuk Kilangan">Lubuk Kilangan</option>
                                <option value="Koto Tangah">Koto Tangah</option>
                                <option value="Nanggalo">Nanggalo</option>
                                <option value="Padang Selatan">Padang Selatan</option>
                                <option value="Padang Timur">Padang Timur</option>
                                <option value="Padang Utara">Padang Utara</option>
                                <option value="Padang Barat">Padang Barat</option>
                            </select>
                        </div>
                        
                        <!-- Actions -->
                        <div class="d-flex gap-2">
                            <button type="button" onclick="getCurrentLocation()" class="btn btn-warning rounded-3 px-3 d-flex align-items-center justify-content-center" title="Lokasi Saya">
                                <i class="bi bi-crosshair text-white fs-5"></i>
                            </button>
                            <button type="button" onclick="resetAllFilters()" class="btn btn-light rounded-3 px-3 d-flex align-items-center justify-content-center" title="Reset Filter">
                                <i class="bi bi-arrow-counterclockwise text-danger fs-5"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- 2. Floating Filter Toggles (Bottom Left) -->
            <div class="position-absolute bottom-0 start-0 p-4 pointer-events-auto d-none d-md-block">
                <div class="glass-panel p-3 rounded-4 shadow-lg animate-slide-up">
                    <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
                        <i class="bi bi-layers-fill text-primary"></i> Layer Peta
                    </h6>
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" id="showZones" checked>
                        <label class="form-check-label fw-medium" for="showZones">Radius Zonasi</label>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="showTraffic" onchange="toggleTrafficLayer()">
                        <label class="form-check-label fw-medium" for="showTraffic">Lalu Lintas (Preview)</label>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

<style>
/* Floating UI Styles */
.pointer-events-none { pointer-events: none; }
.pointer-events-auto { pointer-events: auto; }

.glass-search-bar {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(16px);
    border: 1px solid rgba(255, 255, 255, 0.4);
}

.glass-panel {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    min-width: 220px;
}

/* Animations */
.animate-slide-down { animation: slideDown 0.6s cubic-bezier(0.16, 1, 0.3, 1); }
.animate-slide-up { animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1); }

@keyframes slideDown {
    from { transform: translateY(-50px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

@keyframes slideUp {
    from { transform: translateY(50px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

/* Custom Select & Input */
.form-select:focus, .form-control:focus {
    box-shadow: none;
    background-color: #F8FAFC;
}

.shadow-2xl {
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

.search-suggestion-item {
    padding: 10px 16px;
    cursor: pointer;
    transition: background 0.2s;
}
.search-suggestion-item:hover { background: #eff6ff; }
</style>
