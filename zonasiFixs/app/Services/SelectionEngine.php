<?php
// app/Services/SelectionEngine.php

class SelectionEngine {
    private $db;
    private $hasRankingColumn = false;
    private $hasSkorColumn = false;
    private $hasVerifikasiFisikColumn = false;

    public function __construct() {
        $this->db = getConnection();
        $this->checkColumns();
    }

    private function checkColumns() {
        try {
            $stmt = $this->db->query("SHOW COLUMNS FROM pendaftaran");
            $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            $this->hasRankingColumn = in_array('ranking', $columns);
            $this->hasSkorColumn = in_array('skor', $columns);
            $this->hasVerifikasiFisikColumn = in_array('verifikasi_fisik', $columns);
        } catch (PDOException $e) {
            // Columns don't exist
        }
    }

    /**
     * Run selection for all schools
     */
    public function runAll() {
        if (!$this->hasRankingColumn) {
            throw new Exception("Database belum di-migrate. Silakan jalankan run_migration.php terlebih dahulu.");
        }

        $stmt = $this->db->query("SELECT id FROM sekolah WHERE is_active = 1");
        $schools = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($schools as $school) {
            $this->processSchool($school['id']);
        }
        return true;
    }

    /**
     * Process selection for a specific school
     */
    public function processSchool($sekolahId) {
        if (!$this->hasRankingColumn) {
            return false;
        }

        // 1. Get School Data (Quota)
        $stmt = $this->db->prepare("SELECT * FROM sekolah WHERE id = ?");
        $stmt->execute([$sekolahId]);
        $school = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$school) return false;

        // Reset previous results for this school
        try {
            $resetSql = "UPDATE pendaftaran SET status = 'pending'";
            if ($this->hasRankingColumn) {
                $resetSql .= ", ranking = NULL";
            }
            $resetSql .= " WHERE sekolah_id = ? AND status IN ('diterima', 'cadangan')";
            
            $this->db->prepare($resetSql)->execute([$sekolahId]);
        } catch (PDOException $e) {
            // Continue even if reset fails
        }

        // 2. Process by Pathways (Ordered to handle Quota Overflow)
        
        // --- AFIRMASI ---
        $afirmasiList = $this->getRankedList($sekolahId, 'afirmasi');
        $kuotaAfirmasi = $school['kuota_afirmasi'] ?? 0;
        $acceptedAfirmasi = array_slice($afirmasiList, 0, $kuotaAfirmasi);
        $this->updateStatus($acceptedAfirmasi, 'diterima');
        
        $sisaAfirmasi = $kuotaAfirmasi - count($acceptedAfirmasi);

        // --- MUTASI ---
        $mutasiList = $this->getRankedList($sekolahId, 'mutasi');
        $kuotaMutasi = $school['kuota_mutasi'] ?? 0;
        $acceptedMutasi = array_slice($mutasiList, 0, $kuotaMutasi);
        $this->updateStatus($acceptedMutasi, 'diterima');

        $sisaMutasi = $kuotaMutasi - count($acceptedMutasi);

        // --- PRESTASI ---
        $prestasiList = $this->getRankedList($sekolahId, 'prestasi');
        $kuotaPrestasi = $school['kuota_prestasi'] ?? 0;
        $acceptedPrestasi = array_slice($prestasiList, 0, $kuotaPrestasi);
        $this->updateStatus($acceptedPrestasi, 'diterima');

        $sisaPrestasi = $kuotaPrestasi - count($acceptedPrestasi);

        // --- ZONASI (With Overflow) ---
        $zonasiList = $this->getRankedList($sekolahId, 'zonasi');
        // Total Zonasi Quota = Base + Overflow from others
        $baseZonasi = $school['kuota_domisili'] ?? 0;
        $totalZonasiQuota = $baseZonasi + max(0, $sisaAfirmasi) + max(0, $sisaMutasi) + max(0, $sisaPrestasi);
        
        $acceptedZonasi = array_slice($zonasiList, 0, $totalZonasiQuota);
        $this->updateStatus($acceptedZonasi, 'diterima');

        // --- CADANGAN (Optional: Take top N remaining from Zonasi) ---
        $remainingZonasi = array_slice($zonasiList, $totalZonasiQuota, 10); // Example: top 10 as backup
        $this->updateStatus($remainingZonasi, 'cadangan');
    }

    private function getRankedList($sekolahId, $jalur) {
        try {
            // Juknis 2025 Ranking Priorities:
            // 1. Domisili: Rerata Rapor -> Jarak -> Usia (Tanggal Lahir ASC = Lebih Tua)
            // 2. Prestasi: Skor Prestasi -> Jarak -> Usia
            // 3. Afirmasi/Mutasi: Jarak -> Usia
            
            $rerataRapor = "(s.nilai_sem1 + s.nilai_sem2 + s.nilai_sem3 + s.nilai_sem4 + s.nilai_sem5) / 5";
            
            if ($jalur === 'zonasi') {
                $sort = "$rerataRapor DESC, p.jarak_meter ASC, s.tanggal_lahir ASC";
            } else if ($jalur === 'prestasi' && $this->hasSkorColumn) {
                $sort = "p.skor DESC, p.jarak_meter ASC, s.tanggal_lahir ASC";
            } else {
                $sort = "p.jarak_meter ASC, s.tanggal_lahir ASC";
            }
            
            $sql = "SELECT p.id FROM pendaftaran p 
                    JOIN siswa s ON p.siswa_id = s.id 
                    WHERE p.sekolah_id = ? AND p.jalur = ?";
            
            // Only add verifikasi_fisik condition if column exists
            if ($this->hasVerifikasiFisikColumn) {
                $sql .= " AND p.verifikasi_fisik = 1";
            }
            
            $sql .= " ORDER BY $sort";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$sekolahId, $jalur]);
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            return [];
        }
    }

    private function updateStatus($ids, $status) {
        if (empty($ids)) return;
        
        try {
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $sql = "UPDATE pendaftaran SET status = ?";
            
            if ($this->hasRankingColumn) {
                $sql .= ", ranking = NULL";
            }
            
            $sql .= " WHERE id IN ($placeholders)";
            $params = array_merge([$status], $ids);
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);

            // Update sequence ranking for 'diterima'
            if ($status === 'diterima' && $this->hasRankingColumn) {
                foreach ($ids as $index => $id) {
                    $this->db->prepare("UPDATE pendaftaran SET ranking = ? WHERE id = ?")
                             ->execute([$index + 1, $id]);
                }
            }
        } catch (PDOException $e) {
            // Log error but continue
        }
    }

    /**
     * Reset all results
     */
    public function resetAll() {
        try {
            $sql = "UPDATE pendaftaran SET status = 'pending'";
            if ($this->hasRankingColumn) {
                $sql .= ", ranking = NULL";
            }
            $sql .= " WHERE status IN ('diterima', 'cadangan')";
            
            return $this->db->exec($sql);
        } catch (PDOException $e) {
            throw new Exception("Gagal mereset seleksi: " . $e->getMessage());
        }
    }
}
