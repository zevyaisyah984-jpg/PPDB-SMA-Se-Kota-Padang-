<?php
// Sekolah Model - Updated to match actual database schema
class Sekolah {
    private $db;

    public function __construct() {
        $this->db = getConnection();
    }

    // Get all schools
    public function all() {
        $stmt = $this->db->query("SELECT * FROM sekolah WHERE is_active = 1 ORDER BY nama");
        return $stmt->fetchAll();
    }

    // Get all schools with quota calculation
    public function allWithKuota() {
        $sql = "SELECT s.*, 
                (SELECT COUNT(*) FROM pendaftaran p WHERE p.sekolah_id = s.id AND p.jalur = 'zonasi') as terisi_zonasi,
                (SELECT COUNT(*) FROM pendaftaran p WHERE p.sekolah_id = s.id AND p.jalur = 'afirmasi') as terisi_afirmasi,
                (SELECT COUNT(*) FROM pendaftaran p WHERE p.sekolah_id = s.id AND p.jalur = 'prestasi') as terisi_prestasi,
                (SELECT COUNT(*) FROM pendaftaran p WHERE p.sekolah_id = s.id AND p.jalur = 'mutasi') as terisi_mutasi
                FROM sekolah s WHERE s.is_active = 1 ORDER BY s.nama";
        try {
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            // Fallback if pendaftaran table doesn't exist
            return $this->all();
        }
    }

    // Find by ID
    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM sekolah WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Create new school
    public function create($data) {
        $sql = "INSERT INTO sekolah (npsn, nama, alamat, kecamatan, kelurahan, kode_pos, latitude, longitude, kuota, akreditasi, foto, telepon, website, kepala_sekolah, nip_kepala_sekolah, is_active, kuota_domisili, kuota_afirmasi, kuota_prestasi_akademik, kuota_prestasi_nonakademik, kuota_mutasi) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['npsn'] ?? null,
            $data['nama'],
            $data['alamat'] ?? null,
            $data['kecamatan'] ?? null,
            $data['kelurahan'] ?? null,
            $data['kode_pos'] ?? null,
            $data['latitude'] ?? null,
            $data['longitude'] ?? null,
            $data['kuota'] ?? 0,
            $data['akreditasi'] ?? 'A',
            $data['foto'] ?? null,
            $data['telepon'] ?? null,
            $data['website'] ?? null,
            $data['kepala_sekolah'] ?? null,
            $data['nip_kepala_sekolah'] ?? null,
            isset($data['is_active']) ? 1 : 0,
            $data['kuota_domisili'] ?? 0,
            $data['kuota_afirmasi'] ?? 0,
            $data['kuota_prestasi_akademik'] ?? 0,
            $data['kuota_prestasi_nonakademik'] ?? 0,
            $data['kuota_mutasi'] ?? 0
        ]);
    }

    // Update school - safe version with error handling
    public function update($id, $data) {
        // Core fields that are guaranteed to exist in the database
        $coreFields = ['nama', 'npsn', 'alamat', 'kecamatan', 'latitude', 'longitude', 'kuota', 'akreditasi', 'is_active',
                       'kuota_domisili', 'kuota_afirmasi', 'kuota_prestasi_akademik', 'kuota_prestasi_nonakademik', 'kuota_mutasi'];
        
        // Optional fields that may or may not exist
        $optionalFields = ['kelurahan', 'kode_pos', 'telepon', 'email', 'website', 'kepala_sekolah', 'nip_kepala_sekolah'];
        
        $updateSuccess = false;
        
        // FIRST: Try to update optional fields individually (ignore errors for missing columns)
        foreach ($optionalFields as $field) {
            if (isset($data[$field])) {
                try {
                    $optSql = "UPDATE sekolah SET $field = ? WHERE id = ?";
                    $optStmt = $this->db->prepare($optSql);
                    if ($optStmt->execute([$data[$field], $id])) {
                        $updateSuccess = true;
                    }
                } catch (PDOException $e) {
                    // Column doesn't exist, skip it silently
                    continue;
                }
            }
        }
        
        // SECOND: Build and execute core fields update if any are provided
        $setClauses = [];
        $values = [];
        
        // Add core fields
        foreach ($coreFields as $field) {
            if (isset($data[$field])) {
                $setClauses[] = "$field = ?";
                $values[] = $data[$field];
            }
        }
        
        // Handle foto separately (only if provided)
        if (isset($data['foto'])) {
            $setClauses[] = "foto = ?";
            $values[] = $data['foto'];
        }
        
        // If we have core fields to update, execute the query
        if (!empty($setClauses)) {
            $values[] = $id;
            $sql = "UPDATE sekolah SET " . implode(', ', $setClauses) . " WHERE id = ?";
            
            try {
                $stmt = $this->db->prepare($sql);
                if ($stmt->execute($values)) {
                    $updateSuccess = true;
                }
            } catch (PDOException $e) {
                error_log("Error updating sekolah core fields: " . $e->getMessage());
            }
        }
        
        return $updateSuccess;
    }

    // Delete school (soft delete + cascade deactivation of related data)
    public function delete($id) {
        try {
            // Soft-delete the school
            $stmt = $this->db->prepare("UPDATE sekolah SET is_active = 0 WHERE id = ?");
            $result = $stmt->execute([$id]);
            
            // Cascade: Deactivate associated school admin accounts
            try {
                $stmt = $this->db->prepare("UPDATE users SET is_active = 0 WHERE sekolah_id = ? AND role = 'school_admin'");
                $stmt->execute([$id]);
            } catch (PDOException $e) {
                // Ignore if column doesn't exist
            }
            
            return $result;
        } catch (PDOException $e) {
            error_log("Error soft-deleting sekolah: " . $e->getMessage());
            return false;
        }
    }

    // Get statistics (only active schools)
    public function getStatistics() {
        try {
            // General Stats
            $sql = "SELECT 
                        COUNT(*) as total_sekolah, 
                        COALESCE(SUM(kuota), 0) as total_kuota,
                        COALESCE(SUM(kuota_domisili), 0) as total_zonasi,
                        COALESCE(SUM(kuota_afirmasi), 0) as total_afirmasi,
                        COALESCE(SUM(kuota_prestasi_akademik + kuota_prestasi_nonakademik), 0) as total_prestasi,
                        COALESCE(SUM(kuota_mutasi), 0) as total_mutasi
                    FROM sekolah WHERE is_active = 1";
            
            $stmt = $this->db->query($sql);
            $stats = $stmt->fetch();

            // Pendaftar Count
            $stmtPendaftar = $this->db->query("SELECT COUNT(*) as total_pendaftar FROM pendaftaran");
            $pendaftar = $stmtPendaftar->fetch();

            return array_merge($stats, $pendaftar);

        } catch (PDOException $e) {
            return [
                'total_sekolah' => 0, 
                'total_kuota' => 0,
                'total_pendaftar' => 0,
                'total_zonasi' => 0,
                'total_afirmasi' => 0,
                'total_prestasi' => 0,
                'total_mutasi' => 0
            ];
        }
    }
}
