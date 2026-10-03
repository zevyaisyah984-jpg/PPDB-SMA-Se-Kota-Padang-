<?php
// Pendaftaran Model
class Pendaftaran {
    private $db;

    public function __construct() {
        $this->db = getConnection();
    }

    // Find by ID
    public function find($id) {
        $sql = "SELECT p.*, s.nama as nama_siswa, s.nisn, s.nik, s.sekolah_asal,
                       s.file_kk, s.file_akta, s.file_ijazah,
                       sk.nama as nama_sekolah
                FROM pendaftaran p 
                JOIN siswa s ON p.siswa_id = s.id
                JOIN sekolah sk ON p.sekolah_id = sk.id
                WHERE p.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Find by User ID
    public function findByUserId($userId) {
        $sql = "SELECT p.*, s.nama as nama_siswa, s.nisn, s.nik, s.tempat_lahir, s.tanggal_lahir,
                       s.jenis_kelamin, s.alamat, s.kecamatan, s.sekolah_asal,
                       sk.nama as nama_sekolah, sk.alamat as alamat_sekolah
                FROM pendaftaran p 
                JOIN siswa s ON p.siswa_id = s.id
                JOIN sekolah sk ON p.sekolah_id = sk.id
                WHERE s.user_id = ?
                ORDER BY p.tanggal_daftar DESC
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }

    // Create new registration
    public function create($data) {
        // Generate registration number
        $noPendaftaran = 'PPDB' . date('Y') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        
        $sql = "INSERT INTO pendaftaran (no_pendaftaran, siswa_id, sekolah_id, jalur, sub_jalur, jarak, jarak_meter, data_prestasi, data_perpindahan, data_dokumen, catatan_verifikasi, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $noPendaftaran,
            $data['siswa_id'],
            $data['sekolah_id'],
            $data['jalur'],
            $data['sub_jalur'] ?? null,
            $data['jarak'] ?? null,
            $data['jarak_meter'] ?? null,
            $data['data_prestasi'] ?? null,
            $data['data_perpindahan'] ?? null,
            $data['data_dokumen'] ?? null,
            $data['catatan_verifikasi'] ?? null
        ]);
        
        return $this->db->lastInsertId();
    }


    // Update status
    public function updateStatus($id, $status, $catatan = null) {
        $sql = "UPDATE pendaftaran SET status = ?, catatan_verifikasi = ?, updated_at = NOW() WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$status, $catatan, $id]);
    }

    // Get by sekolah and jalur
    public function getBySekolahJalur($sekolahId, $jalur) {
        $sql = "SELECT p.*, s.nama as nama_siswa, s.nisn
                FROM pendaftaran p 
                JOIN siswa s ON p.siswa_id = s.id
                WHERE p.sekolah_id = ? AND p.jalur = ?
                ORDER BY p.tanggal_daftar";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$sekolahId, $jalur]);
        return $stmt->fetchAll();
    }

    // Count by status
    public function countByStatus($status = null) {
        if ($status) {
            $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM pendaftaran WHERE status = ?");
            $stmt->execute([$status]);
        } else {
            $stmt = $this->db->query("SELECT COUNT(*) as total FROM pendaftaran");
        }
        return $stmt->fetch()['total'];
    }

    // Get All Pendaftaran (with option to filter by sekolah)
    public function getAll($sekolahId = null) {
        $sql = "SELECT p.*, s.nama as nama_siswa, s.nisn, s.sekolah_asal, s.tgl_kk,
                       s.latitude as siswa_lat, s.longitude as siswa_lng,
                       sk.nama as nama_sekolah, sk.latitude as sekolah_lat, sk.longitude as sekolah_lng
                FROM pendaftaran p 
                JOIN siswa s ON p.siswa_id = s.id
                JOIN sekolah sk ON p.sekolah_id = sk.id";
        
        if ($sekolahId) {
            $sql .= " WHERE p.sekolah_id = ?";
            $sql .= " ORDER BY p.tanggal_daftar DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$sekolahId]);
        } else {
            $sql .= " ORDER BY p.tanggal_daftar DESC";
            $stmt = $this->db->query($sql);
        }
        
        return $stmt->fetchAll();
    }

    // Get Latest Registrations
    public function getLatest($limit = 5, $sekolahId = null) {
        $sql = "SELECT p.*, s.nama as nama_siswa, s.nisn 
                FROM pendaftaran p 
                JOIN siswa s ON p.siswa_id = s.id";
        
        if ($sekolahId) {
            $sql .= " WHERE p.sekolah_id = ?";
            $sql .= " ORDER BY p.tanggal_daftar DESC LIMIT " . (int)$limit;
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$sekolahId]);
        } else {
            $sql .= " ORDER BY p.tanggal_daftar DESC LIMIT " . (int)$limit;
            $stmt = $this->db->query($sql);
        }
        
        return $stmt->fetchAll();
    }

    // Get Ranked List for Public Announcement (Transparency)
    // Sorting based on jalur: zonasi=distance, prestasi=skor, others=usia
    public function getRankedList($limit = 50, $jalur = 'zonasi', $schoolId = null) {
        $params = [];
        
        // Base query - Only show students from schools that have PUBLISHED this jalur
        $sql = "SELECT p.*, s.nama as nama_siswa, s.nisn, s.sekolah_asal, s.tanggal_lahir,
                       sk.nama as sekolah_tujuan
                FROM pendaftaran p 
                JOIN siswa s ON p.siswa_id = s.id
                JOIN sekolah sk ON p.sekolah_id = sk.id
                JOIN publikasi_hasil ph ON p.sekolah_id = ph.sekolah_id AND p.jalur = ph.jalur
                WHERE p.jalur = ? AND ph.status = 'published'";
        $params[] = $jalur;
        
        // School filter
        if ($schoolId) {
            $sql .= " AND p.sekolah_id = ?";
            $params[] = $schoolId;
        }
        
        // Sorting based on jalur type
        switch ($jalur) {
            case 'zonasi':
                $sql .= " ORDER BY p.jarak_meter ASC, s.tanggal_lahir ASC";
                break;
            case 'prestasi':
                $sql .= " ORDER BY p.skor DESC, s.tanggal_lahir ASC";
                break;
            case 'afirmasi':
            case 'mutasi':
            default:
                $sql .= " ORDER BY s.tanggal_lahir ASC, p.jarak_meter ASC";
                break;
        }
        
        $sql .= " LIMIT " . (int)$limit;
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    // Get Ranking for school admin verification
    public function getVerificationList($sekolahId) {
        $sql = "SELECT p.*, s.nama as nama_siswa, s.nisn, s.sekolah_asal, s.tanggal_lahir,
                       sk.nama as nama_sekolah
                FROM pendaftaran p 
                JOIN siswa s ON p.siswa_id = s.id
                JOIN sekolah sk ON p.sekolah_id = sk.id
                WHERE p.sekolah_id = ?
                ORDER BY p.jarak_meter ASC, s.tanggal_lahir ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$sekolahId]);
        return $stmt->fetchAll();
    }
    // Get Applicant Counts by Path for a specific school
    public function getApplicantCounts($schoolId) {
        $sql = "SELECT jalur, COUNT(*) as total FROM pendaftaran WHERE sekolah_id = ? GROUP BY jalur";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$schoolId]);
        
        $counts = [
            'zonasi' => 0,
            'afirmasi' => 0,
            'prestasi' => 0,
            'mutasi' => 0
        ];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // map database 'jalur' values to our keys if needed, assuming they match
            // usually stored as 'yonasi', 'afirmasi', 'prestasi', 'mutasi'
            $key = strtolower($row['jalur']);
            if (isset($counts[$key])) {
                $counts[$key] = $row['total'];
            }
        }
        
        return $counts;
    }
}

