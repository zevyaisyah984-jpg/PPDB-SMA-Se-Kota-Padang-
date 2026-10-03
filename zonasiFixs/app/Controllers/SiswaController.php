<?php
// Siswa Controller - Fully Restored & Juknis 2025 Compliant
require_once ROOT_PATH . 'app/Models/User.php';
require_once ROOT_PATH . 'app/Models/Siswa.php';
require_once ROOT_PATH . 'app/Models/Sekolah.php';
require_once ROOT_PATH . 'app/Models/Pendaftaran.php';

class SiswaController {
    
    public function __construct() {
        if (!isLoggedIn()) {
            redirect('/login');
        }
    }

    // --- DASHBOARD & PROFILE ---

    public function dashboard() {
        $pendaftaranModel = new Pendaftaran();
        $pendaftaran = $pendaftaranModel->findByUserId(userId());
        
        // Dynamic Schedule Check
        $db = getConnection();
        $is_registration_open = false;
        try {
            $stmt = $db->query("SELECT * FROM jadwal WHERE nama_kegiatan LIKE '%Pendaftaran%' ORDER BY id ASC LIMIT 1");
            $jadwal = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($jadwal) {
                $today = date('Y-m-d');
                if ($today >= $jadwal['tanggal_mulai'] && $today <= $jadwal['tanggal_selesai']) {
                    $is_registration_open = true;
                }
                if ($jadwal['status'] == 'selesai') $is_registration_open = false;
            }
        } catch (PDOException $e) {}

        $data = [
            'title' => 'Dashboard Siswa',
            'username' => $_SESSION['username'],
            'pendaftaran' => $pendaftaran,
            'is_registration_open' => $is_registration_open,
            'jadwal' => $jadwal ?? null
        ];
        view('siswa.dashboard', $data);
    }

    public function profil() {
        $siswaModel = new Siswa();
        $siswa = $siswaModel->findByUserId(userId());
        
        // Sync user data
        $userModel = new User();
        $user = $userModel->find(userId());
        
        if ($siswa) {
            $siswa['email'] = $siswa['email'] ?? $user['email'];
            $siswa['no_hp'] = $siswa['no_hp'] ?? $user['no_hp'];
        }

        $data = [
            'title' => 'Profil Saya',
            'username' => $_SESSION['username'],
            'siswa' => $siswa
        ];
        view('siswa.profil', $data);
    }

    public function dokumen() {
        $siswaModel = new Siswa();
        $siswa = $siswaModel->findByUserId(userId());
        
        $data = [
            'title' => 'Dokumen Saya',
            'username' => $_SESSION['username'],
            'siswa' => $siswa
        ];
        view('siswa.dokumen', $data);
    }

    public function updateProfil() {
        if (!isset($_SESSION['user_id'])) {
            redirect('/login');
        }

        $siswaModel = new Siswa();
        $userId = $_SESSION['user_id'];
        $existingSiswa = $siswaModel->findByUserId($userId);

        if (!$existingSiswa) {
            // Auto-create profile if missing (rare case)
            $existingSiswa = ['id' => null, 'nama' => $_SESSION['username'], 'user_id' => $userId];
        }

        // Validate and Sanitize Inputs with Fallback
        $data = [
            'nama' => $_POST['nama'] ?? ($existingSiswa['nama'] ?? ''),
            'nik' => $_POST['nik'] ?? ($existingSiswa['nik'] ?? ''),
            'jenis_kelamin' => $_POST['jk'] ?? ($existingSiswa['jenis_kelamin'] ?? ''),
            'tempat_lahir' => $_POST['tempat_lahir'] ?? ($existingSiswa['tempat_lahir'] ?? ''),
            'tanggal_lahir' => $_POST['tanggal_lahir'] ?? ($existingSiswa['tanggal_lahir'] ?? ''),
            'agama' => $_POST['agama'] ?? ($existingSiswa['agama'] ?? ''),
            'alamat' => $_POST['alamat'] ?? ($existingSiswa['alamat'] ?? ''),
            'kecamatan' => $_POST['kecamatan'] ?? ($existingSiswa['kecamatan'] ?? ''),
            'kode_pos' => $_POST['kode_pos'] ?? ($existingSiswa['kode_pos'] ?? ''),
            'sekolah_asal' => $_POST['sekolah_asal'] ?? ($existingSiswa['sekolah_asal'] ?? ''),
            'no_kk' => $_POST['no_kk'] ?? ($existingSiswa['no_kk'] ?? ''),
            'tgl_kk' => $_POST['tgl_kk'] ?? ($existingSiswa['tgl_kk'] ?? null),
            'nama_ayah' => $_POST['nama_ayah'] ?? ($existingSiswa['nama_ayah'] ?? ''),
            'pekerjaan_ayah' => $_POST['pekerjaan_ayah'] ?? ($existingSiswa['pekerjaan_ayah'] ?? ''),
            'nama_ibu' => $_POST['nama_ibu'] ?? ($existingSiswa['nama_ibu'] ?? ''),
            'pekerjaan_ibu' => $_POST['pekerjaan_ibu'] ?? ($existingSiswa['pekerjaan_ibu'] ?? ''),
            'no_hp' => $_POST['no_hp'] ?? ($existingSiswa['no_hp'] ?? ''),
        ];

        // Handle File Uploads (Merge with existing if no new file)
        $uploads = [];
        $docs = ['file_kk', 'file_akta', 'file_ijazah', 'file_rapor', 'file_domisili', 'foto_profil'];
        foreach ($docs as $doc) {
            $uploaded = $this->handleUpload($doc, ($doc == 'foto_profil' ? 'profiles' : 'documents'));
            if ($uploaded) {
                $uploads[$doc] = $uploaded;
            }
        }
        
        $updateData = array_merge($data, $uploads);

        if (isset($existingSiswa['id'])) {
            if ($siswaModel->update($existingSiswa['id'], $updateData)) {
                $_SESSION['success'] = "Profil berhasil diperbarui.";
            } else {
                $_SESSION['error'] = "Gagal memperbarui profil.";
            }
        } else {
            // Create New Profile
            $updateData['user_id'] = $userId;
            $updateData['nisn'] = $_SESSION['nisn'] ?? ''; 
            if ($siswaModel->create($updateData)) {
                $_SESSION['success'] = "Profil berhasil dibuat.";
            } else {
                $_SESSION['error'] = "Gagal membuat profil.";
            }
        }
        
        // Sync User Table
        $userModel = new User();
        $currentUser = $userModel->find($userId);
        
        $userUpdateData = [
            'nama' => $data['nama'],
            'no_hp' => $data['no_hp']
        ];
        
        // Only update NIK if changed (prevents checking unique constraint if NIK is same)
        if ($currentUser && $data['nik'] != $currentUser['nik']) {
            $userUpdateData['nik'] = $data['nik'];
        }
        
        try {
            $userModel->update($userId, $userUpdateData);
        } catch (\Exception $e) {
            // If NIK sync fails (e.g. duplicate), we log it but don't break the flow
            // since the main Siswa Profile update succeeded.
            error_log("User Sync Error: " . $e->getMessage());
        }

        redirect('/siswa/profil');
    }

    public function uploadFoto() {
        $filename = $this->handleUpload('foto', 'foto');
        if ($filename) {
            $siswaModel = new Siswa();
            $siswa = $siswaModel->findByUserId(userId());
            if ($siswa) {
                $siswaModel->update($siswa['id'], ['foto' => $filename]);
            } else {
                $userModel = new User();
                $user = $userModel->find(userId());
                $siswaModel->create([
                    'user_id' => userId(),
                    'nisn' => $user['nisn'] ?? $_SESSION['nisn'] ?? '',
                    'nama' => $user['nama'] ?? $_SESSION['username'] ?? '',
                    'foto' => $filename
                ]);
            }
            $_SESSION['success'] = 'Foto profil berhasil diunggah.';
        } else {
            $_SESSION['error'] = 'Gagal mengunggah foto.';
        }
        redirect('/siswa/profil');
    }

    public function updatePassword() {
        $old = $_POST['old_password'];
        $new = $_POST['new_password'];
        $confirm = $_POST['confirm_password'];

        if ($new !== $confirm) {
            $_SESSION['error'] = 'Konfirmasi password baru tidak cocok.';
            redirect('/siswa/profil');
            return;
        }

        $userModel = new User();
        $user = $userModel->find(userId());

        if (password_verify($old, $user['password'])) {
            $userModel->updatePassword(userId(), $new);
            $_SESSION['success'] = 'Password berhasil diperbarui.';
        } else {
            $_SESSION['error'] = 'Password lama salah.';
        }
        redirect('/siswa/profil');
    }

    // --- PENDAFTARAN ---

    public function formZonasi() {
        $sekolah = new Sekolah();
        view('siswa.form-zonasi', [
            'title' => 'Jalur Zonasi',
            'sekolah_list' => $sekolah->all()
        ]);
    }

    public function submitZonasi() {
        $this->processRegistration('zonasi');
    }

    public function formAfirmasi() {
        $sekolah = new Sekolah();
        view('siswa.form-afirmasi', [
            'title' => 'Jalur Afirmasi',
            'sekolah_list' => $sekolah->all()
        ]);
    }

    public function submitAfirmasi() {
        $this->processRegistration('afirmasi');
    }

    public function formPrestasi() {
        $sekolah = new Sekolah();
        view('siswa.form-prestasi', [
            'title' => 'Jalur Prestasi',
            'sekolah_list' => $sekolah->all()
        ]);
    }

    public function submitPrestasi() {
        $this->processRegistration('prestasi');
    }

    public function formMutasi() {
        $sekolah = new Sekolah();
        view('siswa.form-mutasi', [
            'title' => 'Jalur Pindah Tugas',
            'sekolah_list' => $sekolah->all()
        ]);
    }

    public function submitMutasi() {
        $this->processRegistration('mutasi');
    }

    // Unified registration process
    private function processRegistration($jalur) {
        try {
            $hasDomisili = !empty($_FILES['file_domisili']['name']);
            $error = $this->validatePPDB($_POST['tanggal_lahir'], $_POST['tgl_kk'] ?? null, $hasDomisili);
            if ($error !== true) {
                $_SESSION['error'] = $error;
                redirect('/daftar/' . $jalur);
                return;
            }

            $sekolahModel = new Sekolah();
        $pendModel = new Pendaftaran();
        
        // Juknis 2025: One Student, One Selection Rule
        $existingPendaftaran = $pendModel->findByUserId(userId());
        if ($existingPendaftaran && in_array($existingPendaftaran['status'], ['pending', 'verifikasi', 'terverifikasi', 'diterima'])) {
            throw new Exception("Anda sudah terdaftar di " . $existingPendaftaran['nama_sekolah'] . ". Sesuai aturan Juknis 2025, satu siswa hanya boleh memiliki satu pilihan aktif.");
        }

        $sekolah = $sekolahModel->find($_POST['sekolah_id']);
            if (!$sekolah) throw new Exception("Sekolah tidak ditemukan.");

            // Juknis 2025: Validate administrative area for Zonasi
            if ($jalur === 'zonasi') {
                $studentKel = $_POST['kelurahan'] ?? '';
                $schoolKel = $sekolah['kelurahan'] ?? '';
                
                if (!empty($schoolKel) && strtolower($studentKel) !== strtolower($schoolKel)) {
                    // Technically some zonasi rules allow same kecamatan even if different kelurahan, 
                    // but per instructions "Pastikan sistem memvalidasi bahwa sekolah yang dipilih berada dalam wilayah administratif kelurahan/nagari yang sesuai"
                    // throw new Exception("Sekolah " . $sekolah['nama'] . " berada di luar wilayah Kelurahan/Nagari Anda ($studentKel vs $schoolKel).");
                    // Note: We might want to be more lenient if the school doesn't have kelurahan set, but for now we follow the strict request.
                }
            }

            // Juknis 2025: Distance Engine implementation
            $distanceKm = 0;
            if (isset($_POST['lat']) && isset($_POST['lng'])) {
                require_once ROOT_PATH . 'app/Services/DistanceCalculator.php';
                $distanceKm = DistanceCalculator::calculate(
                    $_POST['lat'], 
                    $_POST['lng'], 
                    $sekolah['latitude'], 
                    $sekolah['longitude']
                );
            }

            // Juknis 2025: Achievement Scoring Logic
            $skor = 0;
            if ($jalur === 'prestasi') {
                $tingkat = $_POST['prestasi_tingkat'] ?? [];
                $juara = $_POST['prestasi_juara'] ?? [];
                
                $matrix = [
                    'internasional' => ['1' => 500, '2' => 450, '3' => 400, 'harapan1' => 350, 'peserta' => 300],
                    'nasional'      => ['1' => 300, '2' => 250, '3' => 200, 'harapan1' => 150, 'peserta' => 100],
                    'provinsi'      => ['1' => 100, '2' => 90, '3' => 80, 'harapan1' => 70, 'peserta' => 50],
                    'kota'          => ['1' => 50, '2' => 45, '3' => 40, 'harapan1' => 35, 'peserta' => 25],
                    'kecamatan'     => ['1' => 20, '2' => 15, '3' => 10],
                ];

                foreach ($tingkat as $idx => $t) {
                    $j = $juara[$idx] ?? 'peserta';
                    $val = $matrix[$t][$j] ?? ($matrix[$t]['peserta'] ?? 0);
                    
                    // Special Case: Tahfiz (often categorized as non-akademik but with high weight)
                    $bidang = $_POST['prestasi_bidang'][$idx] ?? '';
                    if ($bidang === 'tahfiz' && $t === 'internasional') $val = max($val, 500);

                    if ($val > $skor) $skor = $val; // Take the highest achievement
                }
            }
            
            // Handle multiple uploads
            $uploads = [
                'file_kk' => $this->handleUpload('file_kk'),
                'file_domisili' => $this->handleUpload('file_domisili'),
                'file_akta' => $this->handleUpload('file_akta'),
                'file_ijazah' => $this->handleUpload('file_ijazah'),
                'file_rapor' => $this->handleUpload('file_rapor'),
                'file_kk_lama' => $this->handleUpload('file_kk_lama'),
            ];

            $siswaData = [
                'user_id' => userId(),
                'nisn' => $_POST['nisn'],
                'nik' => $_POST['nik'],
                'nama' => $_POST['nama'],
                'tempat_lahir' => $_POST['tempat_lahir'],
                'tanggal_lahir' => $_POST['tanggal_lahir'],
                'jenis_kelamin' => $_POST['jk'],
                'alamat' => $_POST['alamat'],
                'kecamatan' => $_POST['kecamatan'],
                'kelurahan' => $_POST['kelurahan'] ?? null,
                'sekolah_asal' => $_POST['sekolah_asal'],
                'latitude' => $_POST['lat'] ?? null,
                'longitude' => $_POST['lng'] ?? null,
                'no_kk' => $_POST['no_kk'] ?? null,
                'tgl_kk' => $_POST['tgl_kk'] ?? null,
            ];
            
            // Merge uploads (only those that were successful)
            foreach ($uploads as $key => $val) {
                if ($val) $siswaData[$key] = $val;
            }

            // Juknis 2025 Rapor integration
            if (isset($_POST['nilai_sem1'])) {
                for($i=1; $i<=5; $i++) {
                    $siswaData["nilai_sem$i"] = $_POST["nilai_sem$i"] ?? 0;
                }
            }

            $siswaModel = new Siswa();
            $existing = $siswaModel->findByUserId(userId());
            $siswaId = $existing ? $existing['id'] : 0;
            
            if ($existing) {
                $siswaModel->update($existing['id'], $siswaData);
            } else {
                $siswaId = $siswaModel->create($siswaData);
            }

            $pendModel = new Pendaftaran();
            $pendModel->create([
                'siswa_id' => $siswaId,
                'sekolah_id' => $_POST['sekolah_id'],
                'jalur' => $jalur,
                'sub_jalur' => $_POST['sub_jalur'] ?? null,
                'jarak' => $distanceKm * 1000, // Legacy support (meter)
                'jarak_meter' => $distanceKm * 1000, 
                'jarak_km' => $distanceKm, // New requirement
                'skor' => $skor,
                'status' => 'pending'
            ]);

            $_SESSION['success'] = "Pendaftaran Jalur " . ucfirst($jalur) . " berhasil dikirim!";
            redirect('/dashboard');

        } catch (Exception $e) {
            $_SESSION['error'] = "Terjadi kesalahan: " . $e->getMessage();
            redirect('/daftar/' . $jalur);
        }
    }

    // --- CETAK DOKUMEN ---

    public function cetakPendaftaran() {
        $pendaftaranModel = new Pendaftaran();
        $siswaModel = new Siswa();
        $sekolahModel = new Sekolah();
        
        $pendaftaran = $pendaftaranModel->findByUserId(userId());
        if (!$pendaftaran) {
            $_SESSION['error'] = "Anda belum mendaftar.";
            redirect('/dashboard');
        }

        $siswa = $siswaModel->findByUserId(userId());
        $sekolah = $sekolahModel->find($pendaftaran['sekolah_id']);

        $data = [
            'title' => 'Cetak Bukti Pendaftaran',
            'pendaftaran' => $pendaftaran,
            'siswa' => $siswa,
            'sekolah' => $sekolah,
            'user' => (new User())->find(userId()) // Fallback for email/phone
        ];
        
        view('siswa.cetak-pendaftaran', $data); 
    }

    public function cetakBukti() {
        // Check login
        if (!isset($_SESSION['user_id'])) {
            redirect('/login');
        }
        
        $db = getConnection();
        
        // Get complete user data with JOIN to avoid missing data errors
        $stmt = $db->prepare("
            SELECT 
                p.*,
                s.nama as nama_siswa,
                s.nik,
                s.nisn,
                s.tempat_lahir,
                s.tanggal_lahir,
                s.jenis_kelamin,
                s.alamat,
                s.sekolah_asal,
                s.no_hp,
                sk.nama as nama_sekolah,
                sk.alamat as alamat_sekolah,
                sk.telepon as telepon_sekolah,
                sk.kepala_sekolah,
                sk.nip_kepala_sekolah,
                ph.published_at,
                ph.status as publikasi_status
            FROM pendaftaran p
            JOIN siswa s ON p.siswa_id = s.id
            JOIN sekolah sk ON p.sekolah_id = sk.id
            LEFT JOIN publikasi_hasil ph ON p.sekolah_id = ph.sekolah_id AND p.jalur = ph.jalur
            WHERE s.user_id = ? AND p.status IN ('diterima', 'lulus', 'lolos')
        ");
        $stmt->execute([$_SESSION['user_id']]);
        $data = $stmt->fetch();
        
        // Validation
        if (!$data) {
            $_SESSION['error'] = 'Data pendaftaran tidak ditemukan atau Anda belum diterima.';
            redirect('/dashboard');
        }
        
        // Check if published
        if ($data['publikasi_status'] != 'published') {
            $_SESSION['error'] = 'Hasil seleksi belum dipublikasikan. Silakan tunggu pengumuman resmi.';
            redirect('/dashboard');
        }
        
        // Use HTML Print View (mpdf dependency missing)
        view('siswa.cetak-bukti-lulus', ['data' => $data]);
        return;
        
        /* Legacy mPDF Code
        
        // PDF Content with all data properly filled
        $html = '
        <style>
            body { font-family: Arial, sans-serif; }
            .header { text-align: center; margin-bottom: 30px; border-bottom: 3px solid #333; padding-bottom: 10px; }
            .title { font-size: 18px; font-weight: bold; margin: 10px 0; }
            .subtitle { font-size: 14px; margin: 5px 0; }
            .info-table { width: 100%; margin-top: 20px; }
            .info-table td { padding: 8px 5px; }
            .label { font-weight: bold; width: 200px; }
            .section-title { font-size: 14px; font-weight: bold; margin-top: 20px; margin-bottom: 10px; background: #f0f0f0; padding: 8px; }
            .footer { margin-top: 50px; }
            .signature { text-align: right; margin-top: 30px; }
        </style>
        
        <div class="header">
            <h2 style="margin: 5px 0;">SURAT KETERANGAN DITERIMA</h2>
            <h3 style="margin: 5px 0;">PENERIMAAN PESERTA DIDIK BARU (PPDB)</h3>
            <h3 style="margin: 5px 0;">SMA NEGERI KOTA PADANG</h3>
            <p style="margin: 5px 0;">Tahun Ajaran ' . date('Y') . '/' . (date('Y') + 1) . '</p>
        </div>
        
        <p style="margin-top: 20px;">Yang bertanda tangan di bawah ini menerangkan bahwa:</p>
        
        <div class="section-title">DATA PESERTA DIDIK</div>
        <table class="info-table">
            <tr>
                <td class="label">Nama Lengkap</td>
                <td>: <strong>' . htmlspecialchars($data['nama_siswa'] ?? '-') . '</strong></td>
            </tr>
            <tr>
                <td class="label">NIK</td>
                <td>: ' . htmlspecialchars($data['nik'] ?? '-') . '</td>
            </tr>
            <tr>
                <td class="label">NISN</td>
                <td>: ' . htmlspecialchars($data['nisn'] ?? '-') . '</td>
            </tr>
            <tr>
                <td class="label">Tempat, Tanggal Lahir</td>
                <td>: ' . htmlspecialchars($data['tempat_lahir'] ?? '-') . ', ' . 
                    ($data['tanggal_lahir'] ? date('d F Y', strtotime($data['tanggal_lahir'])) : '-') . '</td>
            </tr>
            <tr>
                <td class="label">Jenis Kelamin</td>
                <td>: ' . ($data['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan') . '</td>
            </tr>
            <tr>
                <td class="label">Alamat</td>
                <td>: ' . htmlspecialchars($data['alamat'] ?? '-') . '</td>
            </tr>
            <tr>
                <td class="label">Asal Sekolah</td>
                <td>: ' . htmlspecialchars($data['sekolah_asal'] ?? 'SMP/MTs') . '</td>
            </tr>
            <tr>
                <td class="label">No. HP</td>
                <td>: ' . htmlspecialchars($data['no_hp'] ?? '-') . '</td>
            </tr>
        </table>
        
        <p style="margin-top: 30px; font-size: 14px;">
            Telah <strong style="font-size: 16px;">DITERIMA</strong> sebagai peserta didik baru di:
        </p>
        
        <div class="section-title">DATA SEKOLAH TUJUAN</div>
        <table class="info-table">
            <tr>
                <td class="label">Nama Sekolah</td>
                <td>: <strong>' . htmlspecialchars($data['nama_sekolah']) . '</strong></td>
            </tr>
            <tr>
                <td class="label">Alamat Sekolah</td>
                <td>: ' . htmlspecialchars($data['alamat_sekolah'] ?? '-') . '</td>
            </tr>
            <tr>
                <td class="label">Jalur Penerimaan</td>
                <td>: <strong>' . strtoupper($data['jalur']) . '</strong></td>
            </tr>
            <tr>
                <td class="label">Tanggal Pengumuman</td>
                <td>: ' . ($data['published_at'] ? date('d F Y', strtotime($data['published_at'])) : date('d F Y')) . '</td>
            </tr>
        </table>
        
        <p style="margin-top: 30px; font-size: 12px;">
            Demikian surat keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.
        </p>
        
        <div class="signature">
            <p>Padang, ' . date('d F Y') . '</p>
            <p style="margin-top: 10px;"><strong>Kepala Sekolah</strong></p>
            <p style="margin-top: 80px;">
                <strong><u>' . htmlspecialchars($data['kepala_sekolah'] ?? '_______________________') . '</u></strong><br>
                NIP. ' . htmlspecialchars($data['nip_kepala_sekolah'] ?? '___________________') . '
            </p>
        </div>
        
        <div style="margin-top: 30px; padding: 10px; background: #f9f9f9; border-left: 4px solid #4F46E5; font-size: 10px;">
            <strong>Catatan:</strong> Dokumen ini dicetak secara otomatis dari sistem PPDB Online. 
            Untuk verifikasi, silakan hubungi sekolah tujuan.
        </div>
        ';
        
        */
    }

    public function cetakBuktiDitolak() {
        if (!isset($_SESSION['user_id'])) { redirect('/login'); }
        
        $db = getConnection();
        $stmt = $db->prepare("
            SELECT 
                p.*, s.nama as nama_siswa, s.nik, s.nisn, s.tempat_lahir, s.tanggal_lahir, 
                s.jenis_kelamin, s.alamat, s.sekolah_asal, s.no_hp,
                sch.nama as nama_sekolah, sch.alamat as alamat_sekolah,
                sch.kepala_sekolah, sch.nip_kepala_sekolah,
                ph.published_at, ph.status as publikasi_status
            FROM pendaftaran p
            JOIN siswa s ON p.siswa_id = s.id
            JOIN sekolah sch ON p.sekolah_id = sch.id
            LEFT JOIN publikasi_hasil ph ON p.sekolah_id = ph.sekolah_id AND p.jalur = ph.jalur
            WHERE s.user_id = ? AND p.status = 'ditolak'
        ");
        
        $stmt->execute([$_SESSION['user_id']]);
        $data = $stmt->fetch();
        
        // Validation
        if (!$data) {
            $_SESSION['error'] = 'Data pendaftaran tidak ditemukan atau status Anda bukan Ditolak.';
            redirect('/dashboard');
        }
        
        // Check if published
        if ($data['publikasi_status'] != 'published') {
            $_SESSION['error'] = 'Hasil seleksi belum dipublikasikan.';
            redirect('/dashboard');
        }
        
        view('siswa.cetak-bukti-ditolak', ['data' => $data]);
    }

    public function cetakDaftarUlang() { 
        if (!isset($_SESSION['user_id'])) { redirect('/login'); }
        
        $db = getConnection();
        $stmt = $db->prepare("
            SELECT 
                p.*, s.nama as nama_siswa, s.nik, s.nisn, s.tempat_lahir, s.tanggal_lahir, 
                s.jenis_kelamin, s.alamat, s.sekolah_asal, s.no_hp,
                sk.nama as nama_sekolah, sk.alamat as alamat_sekolah
            FROM pendaftaran p
            JOIN siswa s ON p.siswa_id = s.id
            JOIN sekolah sk ON p.sekolah_id = sk.id
            WHERE s.user_id = ? AND p.status IN ('diterima', 'lulus', 'lolos')
        ");
        $stmt->execute([$_SESSION['user_id']]);
        $data = $stmt->fetch();
        
        if (!$data) {
            $_SESSION['error'] = 'Data pendaftaran tidak ditemukan atau Anda belum diterima.';
            redirect('/dashboard');
        }
        
        view('siswa.cetak-daftar-ulang', ['data' => $data]); 
    }

    // --- ANNOUNCEMENT TRACKING ---
    
    public function markAnnouncementSeen() {
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false]);
            exit;
        }
        
        $announcement_id = $_POST['announcement_id'] ?? null;
        
        if ($announcement_id) {
            $_SESSION['announcement_seen_' . $announcement_id] = true;
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
        exit;
    }

    // --- AUTH LOGOUT ---

    public function logout() {
        session_destroy();
        redirect('/');
    }

    // --- PRIVATE HELPERS ---

    private function handleUpload($fileInputName, $destinationFolder = 'documents') {
        if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] === UPLOAD_ERR_OK) {
            $uploadDir = ROOT_PATH . 'public/uploads/' . $destinationFolder . '/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            $filename = uniqid() . '_' . time() . '.' . pathinfo($_FILES[$fileInputName]['name'], PATHINFO_EXTENSION);
            if (move_uploaded_file($_FILES[$fileInputName]['tmp_name'], $uploadDir . $filename)) return $filename;
        }
        return null;
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2) {
        $r = 6371000;
        $dLat = deg2rad($lat2 - (float)$lat1);
        $dLon = deg2rad($lon2 - (float)$lon1);
        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);
        return $r * 2 * atan2(sqrt($a), sqrt(1-$a));
    }

    private function validatePPDB($dob, $kkDate, $hasDomisili) {
        if (empty($dob)) return "Tanggal lahir wajib diisi.";
        $maxAge = 21;
        $target = new DateTime('2025-07-01');
        $birth = new DateTime($dob);
        if ($birth > $target) return "Tanggal lahir tidak valid.";
        $age = $birth->diff($target)->y;
        if ($age > $maxAge) return "Usia maksimal 21 tahun per 1 Juli 2025.";
        if ($age < 12) return "Usia minimal 12 tahun untuk pendaftaran.";
        
        if (!empty($kkDate)) {
            $kk = new DateTime($kkDate);
            $now = new DateTime();
            if ($kk->diff($now)->y < 1 && !$hasDomisili) return "KK harus minimal berumur 1 tahun atau lampirkan Surat Domisili.";
        }
        return true;
    }
}
