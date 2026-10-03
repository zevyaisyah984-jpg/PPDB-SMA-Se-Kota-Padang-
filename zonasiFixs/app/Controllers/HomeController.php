<?php
// Home Controller - Public pages
require_once ROOT_PATH . 'app/Models/Sekolah.php';

class HomeController {
    
    // Homepage
    public function index() {
        $sekolah = new Sekolah();
        
        // Dynamic Schedule Logic
        $db = getConnection();
        $is_registration_open = false;
        $pendaftaran_jadwal = [];
        
        try {
            // Fetch 'Pendaftaran' related schedule (assuming it contains 'Pendaftaran' in name)
            // Or use specific ID/Code if available. For now, name matching is safer with current schema knowledge.
            $stmt = $db->query("SELECT * FROM jadwal WHERE nama_kegiatan LIKE '%Pendaftaran%' ORDER BY id ASC LIMIT 1");
            $pendaftaran_jadwal = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($pendaftaran_jadwal) {
                $today = date('Y-m-d');
                $start = $pendaftaran_jadwal['tanggal_mulai'];
                $end = $pendaftaran_jadwal['tanggal_selesai'];
                
                // Logic: Open if today is within range OR status is explicitly 'berlangsung' (if admin overrides)
                // However, strictly adhering to dates + manual override check is best.
                // If status is 'selesai', force closed.
                // If status is 'akan_datang', force closed.
                
                if ($today >= $start && $today <= $end) {
                    $is_registration_open = true;
                }
                
                // Override based on explicit status if needed (optional, but good for "emergency close")
                if ($pendaftaran_jadwal['status'] == 'selesai') {
                    $is_registration_open = false;
                }
            }
        } catch (PDOException $e) {
            // Fallback default
        }

        $data = [
            'title' => 'Beranda',
            'sekolah_list' => $sekolah->all(),
            'stats' => $sekolah->getStatistics(),
            'is_registration_open' => $is_registration_open,
            'pendaftaran_jadwal' => $pendaftaran_jadwal
        ];
        
        view('layouts.header', $data);
        view('portal.home', $data);
        view('layouts.footer');
    }

    // Kuota Page
    public function kuota() {
        $sekolah = new Sekolah();
        $data = [
            'title' => 'Kuota Pendaftaran',
            'sekolah_list' => $sekolah->allWithKuota(),
            'stats' => $sekolah->getStatistics()
        ];
        
        view('layouts.header', $data);
        view('portal.kuota', $data);
        view('layouts.footer');
    }

    // Detail Sekolah
    public function detail($id) {
        require_once ROOT_PATH . 'app/Models/Pendaftaran.php';
        $sekolah = new Sekolah();
        $pendaftaran = new Pendaftaran();
        
        $data = [
            'title' => 'Detail Sekolah',
            'sekolah' => $sekolah->find($id),
            'pendaftar_counts' => $pendaftaran->getApplicantCounts($id)
        ];
        
        if (!$data['sekolah']) {
            redirect('/kuota');
        }
        
        view('layouts.header', $data);
        view('portal.detail', $data);
        view('layouts.footer');
    }

    // Jadwal Page
    public function jadwal() {
        $db = getConnection();
        try {
            $stmt = $db->query("SELECT * FROM jadwal ORDER BY urutan ASC");
            $jadwal = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $jadwal = [];
        }

        $data = [
            'title' => 'Jadwal PPDB',
            'jadwal' => $jadwal
        ];
        
        view('layouts.header', $data);
        view('portal.jadwal', $data);
        view('layouts.footer');
    }

    // monitoring Page
    public function monitoring() {
        $sekolah = new Sekolah();
        $data = [
            'title' => 'Monitoring Data PPDB',
            'sekolah_list' => $sekolah->allWithKuota(),
            'stats' => $sekolah->getStatistics()
        ];
        
        view('layouts.header', $data);
        view('portal.monitoring', $data);
        view('layouts.footer');
    }

    // Persyaratan Page
    public function persyaratan() {
        $data = ['title' => 'Persyaratan PPDB'];
        
        view('layouts.header', $data);
        view('portal.persyaratan');
        view('layouts.footer');
    }

    // Pengumuman Page (Public/Private Hybrid)
    // Pengumuman Page (Official Announcement Hub)
    public function pengumuman() {
        require_once ROOT_PATH . 'app/Models/Pendaftaran.php';
        $db = getConnection();
        $sekolah = new Sekolah();
        $pendaftaranModel = new Pendaftaran();

        $data = [
            'title' => 'Pusat Pengumuman PPDB', // Changed Title
            'username' => $_SESSION['username'] ?? null,    
            'isLoggedIn' => isset($_SESSION['user_id']),
            'status' => null,
            'isPublished' => false,
            'rankingPublished' => false,
            'sekolah_list' => $sekolah->all(),
            'tgl_pengumuman' => get_setting('tgl_pengumuman'),
            'jadwal' => [],
            'stats' => [],
            'current_stage' => 1
        ];

        // 1. Fetch Timeline/Jadwal
        try {
            $stmt = $db->query("SELECT * FROM jadwal ORDER BY urutan ASC");
            $data['jadwal'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $data['jadwal'] = [];
        }

        // 2. Calculate Current Stage
        $today = date('Y-m-d');
        foreach ($data['jadwal'] as $j) {
            if ($today >= $j['tanggal_mulai'] && $today <= $j['tanggal_selesai']) {
                $data['current_stage'] = $j['urutan'];
                break;
            } elseif ($today > $j['tanggal_selesai']) {
                $data['current_stage'] = $j['urutan'] + 1; // Move to next if finished
            }
        }
        // Cap at max stage if everything is finished
        if (!empty($data['jadwal'])) {
            $maxStage = max(array_column($data['jadwal'], 'urutan'));
            if ($data['current_stage'] > $maxStage) $data['current_stage'] = $maxStage; // Or specific "Finished" state
        }

        // 3. Aggregate Stats (Cached-like query)
        $data['stats'] = [
            'total_registrants' => $pendaftaranModel->countByStatus(),
            'zonasi' => $pendaftaranModel->countByStatus(), // Placeholder, ideally refine countByJalur
            'verified' => $pendaftaranModel->countByStatus('verifikasi') + $pendaftaranModel->countByStatus('terverifikasi') + $pendaftaranModel->countByStatus('diterima')
        ];
        
        // Refine count by Jalur manually for now
        $stmt_stats = $db->query("SELECT jalur, COUNT(*) as total FROM pendaftaran GROUP BY jalur");
        while($row = $stmt_stats->fetch(PDO::FETCH_ASSOC)) {
            $data['stats'][$row['jalur']] = $row['total'];
        }

        // NEW: Fetch list of schools that have published results
        $data['published_schools'] = [];
        try {
            $stmt_pub = $db->query("
                SELECT ph.*, s.nama as nama_sekolah 
                FROM publikasi_hasil ph
                JOIN sekolah s ON ph.sekolah_id = s.id
                WHERE ph.status = 'published'
                ORDER BY ph.published_at DESC
            ");
            $data['published_schools'] = $stmt_pub->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {}

        // 4. User Specific Data (if logged in)
        if (isset($_SESSION['user_id'])) {
            $dataPendaftaran = $pendaftaranModel->findByUserId($_SESSION['user_id']);
            
            if ($dataPendaftaran) {
                $data['status'] = $dataPendaftaran['status'] ?? null;
                $data['sekolah_id'] = $dataPendaftaran['sekolah_id'];
                $data['jalur'] = $dataPendaftaran['jalur'];
                $data['no_pendaftaran'] = $dataPendaftaran['no_pendaftaran'] ?? null;
                
                // Get names
                $stmt = $db->prepare("SELECT nama FROM siswa WHERE id = (SELECT siswa_id FROM pendaftaran WHERE id = ?)");
                $stmt->execute([$dataPendaftaran['id']]);
                $siswa = $stmt->fetch();
                $data['siswa_nama'] = $siswa['nama'] ?? null;
                
                $sekolahData = $sekolah->find($dataPendaftaran['sekolah_id']);
                $data['sekolah_nama'] = $sekolahData['nama'] ?? null;
                
                // PUBLICATION CHECK
                $stmt = $db->prepare("SELECT status FROM publikasi_hasil WHERE sekolah_id = ? AND jalur = ?");
                $stmt->execute([$dataPendaftaran['sekolah_id'], $dataPendaftaran['jalur']]);
                $pub = $stmt->fetch();
                
                $data['isPublished'] = ($pub && $pub['status'] == 'published');
                
                if (!$data['isPublished'] && in_array($data['status'], ['diterima', 'cadangan', 'tidak_diterima'])) {
                    $data['status'] = 'pending';
                }
            }
        }
        
        view('layouts.header', $data);
        view('portal.pengumuman', $data);
        view('layouts.footer');
    }

    // API: Check Status (Quick Search)
    public function checkStatus() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid method']);
            return;
        }
        
        $nisn = trim($_POST['nisn'] ?? '');
        if (empty($nisn)) {
            echo json_encode(['success' => false, 'message' => 'NISN wajib diisi']);
            return;
        }

        require_once ROOT_PATH . 'app/Models/Pendaftaran.php';
        $db = getConnection();

        try {
            // Find student by NISN then get registration
            $stmt = $db->prepare("SELECT p.status, p.sekolah_id, p.jalur, s.nama as nama_sekolah 
                                  FROM pendaftaran p
                                  JOIN siswa sis ON p.siswa_id = sis.id
                                  JOIN sekolah s ON p.sekolah_id = s.id
                                  WHERE sis.nisn = ? LIMIT 1");
            $stmt->execute([$nisn]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                // Check Publication
                $pubStmt = $db->prepare("SELECT status FROM publikasi_hasil WHERE sekolah_id = ? AND jalur = ?");
                $pubStmt->execute([$result['sekolah_id'], $result['jalur']]);
                $pub = $pubStmt->fetch(PDO::FETCH_ASSOC);
                $isPublished = ($pub && $pub['status'] == 'published');

                if ($isPublished) {
                    $statusMapping = [
                        'diterima' => 'LULUS SELEKSI',
                        'tidak_diterima' => 'TIDAK LOLOS',
                        'cadangan' => 'CADANGAN',
                        'default' => 'DALAM PROSES'
                    ];
                    
                    $finalStatus = $statusMapping[$result['status']] ?? $statusMapping['default'];
                    if (in_array($result['status'], ['diterima', 'tidak_diterima', 'cadangan'])) {
                        echo json_encode([
                            'success' => true, 
                            'found' => true,
                            'status' => $finalStatus,
                            'sekolah' => $result['nama_sekolah'],
                            'jalur' => ucfirst($result['jalur'])
                        ]);
                    } else {
                        echo json_encode(['success' => true, 'found' => true, 'status' => 'DALAM PROSES']);    
                    }
                } else {
                    echo json_encode(['success' => true, 'found' => true, 'status' => 'MENUNGGU PENGUMUMAN']);
                }
            } else {
                echo json_encode(['success' => true, 'found' => false]);
            }

        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan sistem']);
        }
        exit;
    }

    // New Monitoring Detail Page (Real-Time Ranking)
    public function monitoringDetail($id) {
        require_once ROOT_PATH . 'app/Models/Pendaftaran.php';
        $pendaftaran = new Pendaftaran();
        $sekolahModel = new Sekolah();
        
        $sekolah = $sekolahModel->find($id);
        
        if (!$sekolah) {
            redirect('/monitoring');
        }
        
        // Fetch rankings for ALL paths regardless of publication status (Transparency Feature)
        // Transparency: Show current live ranking.
        
        $data = [
            'title' => 'Pantau Hasil Seleksi - ' . $sekolah['nama'],
            'sekolah' => $sekolah,
            'ranking_zonasi' => $pendaftaran->getRankedList(500, 'zonasi', $id),
            'ranking_afirmasi' => $pendaftaran->getRankedList(200, 'afirmasi', $id),
            'ranking_prestasi' => $pendaftaran->getRankedList(200, 'prestasi', $id),
            'ranking_mutasi' => $pendaftaran->getRankedList(50, 'mutasi', $id),
            'last_update' => date('d M Y H:i:s')
        ];
        
        view('layouts.header', $data);
        view('portal.monitoring_detail', $data);
        view('layouts.footer');
    }
    
    /**
     * API: Check for data updates
     * Returns last update timestamp for client-side polling
     */
    public function checkUpdates() {
        header('Content-Type: application/json');
        
        $cacheFile = ROOT_PATH . 'writable/cache/last_update.txt';
        $lastUpdate = 0;
        
        if (file_exists($cacheFile)) {
            $lastUpdate = (int) file_get_contents($cacheFile);
        } else {
            // Create initial timestamp
            $lastUpdate = time();
            @mkdir(dirname($cacheFile), 0755, true);
            file_put_contents($cacheFile, $lastUpdate);
        }
        
        echo json_encode([
            'success' => true,
            'lastUpdate' => $lastUpdate,
            'currentTime' => time()
        ]);
        exit;
    }
}
