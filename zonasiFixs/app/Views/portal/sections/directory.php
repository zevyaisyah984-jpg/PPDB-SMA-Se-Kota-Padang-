<!-- Section Direktori Sekolah -->
<section id="schoolDirectory" class="py-5 bg-gov-light shadow-sm">
    <div class="container py-4">
        <div class="row mb-5 justify-content-center">
            <div class="col-lg-8 text-center mt-5 mt-lg-0">
                <div class="badge-ppdb bg-primary bg-opacity-10 text-primary mb-3">Direktori Sekolah</div>
                <h2 class="fw-bold text-dark">Daftar Lengkap SMA Negeri Kota Padang</h2>
                <div class="bg-primary mx-auto mb-4" style="width: 80px; height: 5px; border-radius: 2px;"></div>
                <p class="text-muted lead">Telusuri seluruh sekolah negeri dengan informasi akreditasi, daya tampung, dan lokasi yang akurat.</p>
            </div>
        </div>

        <!-- Filter Bar Direktori -->
        <div class="directory-filter-bar mb-5 animate-fade-in">
            <div class="row g-3 align-items-center">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-0 ps-0"><i class="bi bi-search text-primary"></i></span>
                        <input type="text" id="dirSearch" class="form-control border-0 shadow-none ps-1" placeholder="Cari nama sekolah (contoh: SMA 1)...">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-0 ps-0"><i class="bi bi-filter text-primary"></i></span>
                        <select id="dirFilterKec" class="form-select border-0 shadow-none ps-1">
                            <option value="">Semua Kecamatan</option>
                            <option value="lubuk begalung">Lubuk Begalung</option>
                            <option value="kuranji">Kuranji</option>
                            <option value="padang barat">Padang Barat</option>
                            <option value="padang timur">Padang Timur</option>
                            <option value="padang selatan">Padang Selatan</option>
                            <option value="padang utara">Padang Utara</option>
                            <option value="nanggalo">Nanggalo</option>
                            <option value="koto tangah">Koto Tangah</option>
                            <option value="pauh">Pauh</option>
                            <option value="lubuk kilangan">Lubuk Kilangan</option>
                            <option value="bungus tlk.kabung">Bungus Teluk Kabung</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-md-end text-muted small">
                        Menampilkan <span id="dirCount" class="fw-bold text-primary"><?php echo count($sekolah_list); ?></span> Sekolah
                    </div>
                </div>
            </div>
        </div>

        <!-- Grid Sekolah -->
        <div class="row g-4" id="directoryGrid">
            <?php foreach ($sekolah_list as $sekolah): ?>
                <?php view('components.directory_card', ['sekolah' => $sekolah]); ?>
            <?php endforeach; ?>
        </div>
        
        <!-- Empty Search Result -->
        <div id="dirEmpty" class="text-center py-5 d-none animate-fade-in">
            <i class="bi bi-search display-1 text-muted opacity-25 mb-4"></i>
            <h4 class="fw-bold text-muted">Sekolah tidak ditemukan</h4>
            <p class="text-muted">Coba gunakan kata kunci pencarian atau filter kecamatan lain.</p>
            <button onclick="resetDirectoryFilters()" class="btn btn-outline-primary rounded-pill px-4 mt-2">Reset Pencarian</button>
        </div>
    </div>
</section>
