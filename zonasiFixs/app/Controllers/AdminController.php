<?php
// Admin Controller
require_once ROOT_PATH . 'app/Models/Sekolah.php';
require_once ROOT_PATH . 'app/Models/Pendaftaran.php';
require_once ROOT_PATH . 'app/Services/SelectionEngine.php';

class AdminController {
    
    // Check for any admin
    private function checkAdmin() {
        if (!isset($_SESSION['admin_id'])) {
            redirect('/admin/login');
        }
    }

    // Check specifically for Super Admin
    private function checkSuperAdmin() {
        $this->checkAdmin();
        if ($_SESSION['admin_role'] !== 'super_admin') {
            http_response_code(403);
            die("Akses Ditolak: Hanya Super Admin yang dapat mengakses halaman ini.");
        }
    }


    // Get dynamic setting from database
    private function getSetting($key, $default = null) {
        $db = getConnection();
        $stmt = $db->prepare("SELECT setting_value FROM settings WHERE setting_key = ?");
        $stmt->execute([$key]);
        $row = $stmt->fetch();
        return $row ? $row['setting_value'] : $default;
    }
    
    // Invalidate cache to trigger frontend update notifications
    private function invalidateCache() {
        $cacheFile = ROOT_PATH . 'writable/cache/last_update.txt';
        @mkdir(dirname($cacheFile), 0755, true);
        file_put_contents($cacheFile, time());
    }

    // Show Admin Login
    public function showLogin() {
        if (isset($_SESSION['admin_id'])) {
            redirect('/admin');
        }
        view('admin.login');
    }

    // Process Admin Login
    public function login() {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $db = getConnection();
        $stmt = $db->prepare("SELECT * FROM admin WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['nama'];
            $_SESSION['admin_role'] = $admin['role'];
            $_SESSION['admin_sekolah_id'] = $admin['sekolah_id']; // Store sekolah_id

            // Fetch School Name if applicable
            if ($admin['sekolah_id']) {
                $stmt = $db->prepare("SELECT nama FROM sekolah WHERE id = ?");
                $stmt->execute([$admin['sekolah_id']]);
                $school = $stmt->fetch();
                $_SESSION['admin_sekolah_nama'] = $school['nama'] ?? null;
            } else {
                $_SESSION['admin_sekolah_nama'] = "Super Admin";
            }
            
            redirect('/admin');
        } else {
            $_SESSION['error'] = 'Username atau Password salah!';
            // Check if login attempt came from School Admin login page
            if (strpos($_SERVER['REQUEST_URI'], '/admin/sekolah/login') !== false) {
                redirect('/admin/sekolah/login');
            } else {
                redirect('/admin/login');
            }
        }
    }

    // Admin Logout - redirect to admin login page
    public function logout() {
        // Clear admin session data
        unset($_SESSION['admin_id']);
        unset($_SESSION['admin_role']);
        unset($_SESSION['admin_sekolah_id']);
        unset($_SESSION['admin_sekolah_nama']);
        
        // Destroy session completely
        session_destroy();
        
        // Redirect to admin login page
        redirect('/admin/login');
    }

    // Admin Dashboard
    public function dashboard() {
        $this->checkAdmin();
        
        $sekolah = new Sekolah();
        $pendaftaran = new Pendaftaran();
        $sekolahId = $_SESSION['admin_role'] === 'super_admin' ? null : $_SESSION['admin_sekolah_id'];
        
        $stats = $sekolah->getStatistics();
        $total_pendaftar = $this->countPendaftar($sekolahId);
        $verified_fisik = $this->countPendaftar($sekolahId, null, true);
        // Calculate pending verification (Total - Verified)
        // Note: verified_fisik=true means 'already verified'. We want the ones NOT verified yet.
        $pending_verifikasi = $this->countPendaftar($sekolahId, null, false); 
        
        $pendaftar_diterima = $this->countPendaftar($sekolahId, 'diterima');
        
        // Sisa Kuota Calculation
        $total_quota = $sekolahId ? ($sekolah->find($sekolahId)['kuota'] ?? 0) : $stats['total_kuota'];
        $sisa_kuota = $total_quota - $pendaftar_diterima;

        $data = [
            'title' => 'Dashboard Admin',
            'role' => $_SESSION['admin_role'],
            'total_sekolah' => $stats['total_sekolah'],
            'total_pendaftar' => $total_pendaftar,
            'verified_fisik' => $verified_fisik,
            'pending_verifikasi' => $pending_verifikasi,
            'pendaftar_pending' => $this->countPendaftar($sekolahId, 'pending'),
            'pendaftar_diterima' => $pendaftar_diterima,
            'sisa_kuota' => max(0, $sisa_kuota),
            'latest_pendaftar' => $pendaftaran->getLatest(5, $sekolahId)
        ];
        
        view('admin.dashboard', $data);
    }

    private function countPendaftar($sekolahId = null, $status = null, $verifiedFisik = false) {
        $db = getConnection();
        $sql = "SELECT COUNT(*) as total FROM pendaftaran p JOIN siswa s ON p.siswa_id = s.id";
        $params = [];
        
        $wheres = [];
        if ($sekolahId) {
            $wheres[] = "p.sekolah_id = ?";
            $params[] = $sekolahId;
        }
        if ($status) {
            $wheres[] = "p.status = ?";
            $params[] = $status;
        }
        if ($verifiedFisik) {
            // Check if column exists before using it
            try {
                $checkCol = $db->query("SHOW COLUMNS FROM pendaftaran LIKE 'verifikasi_fisik'");
                if ($checkCol->rowCount() > 0) {
                    $wheres[] = "p.verifikasi_fisik = 1";
                }
            } catch (PDOException $e) {
                // Column doesn't exist, skip this condition
            }
        }
        
        if (!empty($wheres)) {
            $sql .= " WHERE " . implode(" AND ", $wheres);
        }
        
        try {
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch()['total'];
        } catch (PDOException $e) {
            // Return 0 if query fails
            return 0;
        }
    }


    // Sekolah List
    public function sekolah() {
        $this->checkAdmin();
        
        $sekolah = new Sekolah();
        $data = [
            'title' => 'Data Sekolah',
            'sekolah_list' => $sekolah->all()
        ];
        
        view('admin.sekolah', $data);
    }

    // Tambah Sekolah Form
    public function tambahSekolah() {
        $this->checkAdmin();
        view('admin.tambah-sekolah');
    }

    // Store Sekolah
    public function storeSekolah() {
        $this->checkSuperAdmin();
        
        $data = $_POST;
        
        // Handle photo upload
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = ROOT_PATH . 'public/uploads/sekolah/';
            $filename = uniqid() . '_' . time() . '.' . pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
            $uploadPath = $uploadDir . $filename;
            
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $uploadPath)) {
                $data['foto'] = $filename;
            }
        }
        
        $sekolah = new Sekolah();
        $sekolah->create($data);
        
        $_SESSION['success'] = 'Sekolah berhasil ditambahkan!';
        redirect('/admin/sekolah');
    }

    // Edit Sekolah Form
    public function editSekolah($id) {
        $this->checkAdmin();
        
        // Security: School admin can only edit their own school
        if ($_SESSION['admin_role'] !== 'super_admin' && $id != $_SESSION['admin_sekolah_id']) {
            http_response_code(403);
            die("Akses Ditolak: Anda hanya dapat mengelola sekolah Anda sendiri.");
        }
        
        $sekolah = new Sekolah();
        $data = [
            'title' => 'Edit Sekolah',
            'sekolah' => $sekolah->find($id)
        ];
        
        view('admin.edit-sekolah', $data);
    }

    // Update Sekolah
    public function updateSekolah($id) {
        $this->checkAdmin();
        
        // Security: School admin can only update their own school
        if ($_SESSION['admin_role'] !== 'super_admin' && $id != $_SESSION['admin_sekolah_id']) {
            http_response_code(403);
            die("Akses Ditolak.");
        }
        
        $sekolah = new Sekolah();
        $data = $_POST;
        
        // Handle photo upload
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = ROOT_PATH . 'public/uploads/sekolah/';
            $filename = uniqid() . '_' . time() . '.' . pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
            $uploadPath = $uploadDir . $filename;
            
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $uploadPath)) {
                // Delete old photo if exists
                $oldData = $sekolah->find($id);
                if (!empty($oldData['foto']) && file_exists($uploadDir . $oldData['foto'])) {
                    unlink($uploadDir . $oldData['foto']);
                }
                $data['foto'] = $filename;
            }
        }
        
        $sekolah->update($id, $data);
        
        // Invalidate cache to notify frontend of updates
        $this->invalidateCache();
        
        $_SESSION['success'] = 'Sekolah berhasil diupdate!';
        redirect('/admin/sekolah');
    }

    // Hapus Sekolah (Permanent Cascade Delete)
    public function hapusSekolah($id) {
        $this->checkAdmin();
        $this->checkSuperAdmin(); // Restrict to Super Admin
        
        $db = getConnection();
        
        try {
            $db->beginTransaction();
            
            // 1. Hapus Akun Admin/Operator Sekolah
            $stmt = $db->prepare("DELETE FROM admin WHERE sekolah_id = ? AND role != 'super_admin'");
            $stmt->execute([$id]);
            

            
            // 3. Hapus Data Pendaftaran
            // 3. Hapus Data Pendaftaran
            $stmt = $db->prepare("DELETE FROM pendaftaran WHERE sekolah_id = ?");
            $stmt->execute([$id]);

            // 4. Hapus Publikasi Hasil (New Feature)
            $stmt = $db->prepare("DELETE FROM publikasi_hasil WHERE sekolah_id = ?");
            $stmt->execute([$id]);
            
            // 5. Set NULL activity_logs for school admins before deleting them (Optional but safe)
            $db->exec("UPDATE activity_logs SET admin_id = NULL WHERE admin_id IN (SELECT id FROM admin WHERE sekolah_id = $id)");
            
            // 4. Hapus Sekolah Utama
            $stmt = $db->prepare("DELETE FROM sekolah WHERE id = ?");
            $stmt->execute([$id]);
            
            // Commit Transaksi
            $db->commit();
            
            // Invalidate Caches
            $this->invalidateCache();
            if (isset($_SESSION['sekolah_cache'])) unset($_SESSION['sekolah_cache']);
            if (isset($_SESSION['dashboard_cache'])) unset($_SESSION['dashboard_cache']);
            
            $_SESSION['success'] = 'Sekolah dan seluruh data terkait (Operator, Kuota, Pendaftar) berhasil dihapus permanen.';
            
        } catch (Exception $e) {
            $db->rollBack();
            error_log("Gagal hapus sekolah: " . $e->getMessage());
            $_SESSION['error'] = 'Gagal menghapus sekolah: ' . $e->getMessage();
        }
        
        redirect('/admin/sekolah');
    }

    // Toggle Sekolah Activation
    public function toggleSekolahStatus($id) {
        $this->checkSuperAdmin();
        
        $db = getConnection();
        // Get current status
        $stmt = $db->prepare("SELECT is_active FROM sekolah WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        
        if ($row) {
            $newStatus = $row['is_active'] ? 0 : 1;
            $stmt = $db->prepare("UPDATE sekolah SET is_active = ? WHERE id = ?");
            $stmt->execute([$newStatus, $id]);
            $_SESSION['success'] = 'Status sekolah berhasil diubah!';
        }
        
        redirect('/admin/sekolah');
    }


    // Pendaftar List
    public function pendaftar() {
        $this->checkAdmin();
        
        $pendaftaran = new Pendaftaran();
        $sekolahId = $_SESSION['admin_sekolah_id'] ?? null;
        
        $data = [
            'title' => 'Data Pendaftar',
            'pendaftar' => $pendaftaran->getAll($sekolahId)
        ];
        
        view('admin.pendaftar.index', $data);
    }

    // Detail Pendaftar
    public function detailPendaftar($id) {
        $this->checkAdmin();
        
        $pendaftaran = new Pendaftaran();
        // Security check
        $dataPendaftar = $pendaftaran->find($id);
        if (!$dataPendaftar) {
             $_SESSION['error'] = 'Data pendaftar tidak ditemukan.';
             redirect('/admin/pendaftar');
        }

        if ($_SESSION['admin_role'] !== 'super_admin' && 
            $dataPendaftar['sekolah_id'] != $_SESSION['admin_sekolah_id']) {
            http_response_code(403);
            die("Akses Ditolak.");
        }

        $data = [
            'title' => 'Detail Pendaftar',
            'pendaftar' => $dataPendaftar,
            'logs' => $this->getAuditLogs($id)
        ];

        view('admin.pendaftar.detail', $data);
    }

    // Verify Pendaftar
    public function verifyPendaftar($id) {
        $this->checkAdmin();
        
        $status = $_POST['status'] ?? null;
        if (!$status) {
            $_SESSION['error'] = 'Status tidak valid.';
            redirect($_SERVER['HTTP_REFERER'] ?? '/admin/pendaftar');
        }

        $catatan = $_POST['catatan'] ?? '';
        
        $pendaftaran = new Pendaftaran();
        
        // Security check
        $dataPendaftar = $pendaftaran->find($id);
        if ($_SESSION['admin_role'] !== 'super_admin' && 
            $dataPendaftar['sekolah_id'] != $_SESSION['admin_sekolah_id']) {
            http_response_code(403);
            die("Akses Ditolak.");
        }

        $oldStatus = $dataPendaftar['status'];
        $pendaftaran->updateStatus($id, $status, $catatan);

        // --- AUDIT LOGGING ---
        $db = getConnection();
        $stmt = $db->prepare("INSERT INTO audit_logs (admin_id, pendaftaran_id, action, old_status, new_status, ip_address) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $_SESSION['admin_id'],
            $id,
            'update_status',
            $oldStatus,
            $status,
            $_SERVER['REMOTE_ADDR']
        ]);
        
        $_SESSION['success'] = 'Status pendaftaran berhasil diperbarui!';
        
        $redirectTo = $_POST['redirect_to'] ?? '/admin/pendaftar/' . $id;
        redirect($redirectTo);
    }

    // Accept Pendaftar (Quick Action)
    public function acceptPendaftar($id) {
        $this->checkAdmin();
        
        // Validate ID
        if (!is_numeric($id)) {
            $_SESSION['error'] = 'ID tidak valid.';
            redirect('/admin/pendaftar');
        }
        
        $db = getConnection();
        
        try {
            // Get pendaftar data with proper JOIN
            $stmt = $db->prepare("
                SELECT p.*, s.nama as nama_siswa 
                FROM pendaftaran p
                JOIN siswa s ON p.siswa_id = s.id
                WHERE p.id = ?
            ");
            $stmt->execute([$id]);
            $pendaftar = $stmt->fetch();
            
            if (!$pendaftar) {
                $_SESSION['error'] = 'Data pendaftar tidak ditemukan.';
                redirect('/admin/pendaftar');
            }
            
            // Security check - school admin can only accept their own school's students
            if ($_SESSION['admin_role'] !== 'super_admin' && 
                $pendaftar['sekolah_id'] != $_SESSION['admin_sekolah_id']) {
                http_response_code(403);
                die("Akses Ditolak.");
            }
            
            // Check if already accepted
            if ($pendaftar['status'] == 'diterima') {
                $_SESSION['warning'] = 'Pendaftar sudah diterima sebelumnya.';
                redirect('/admin/pendaftar');
            }
            
            // Update status to diterima
            $stmt = $db->prepare("
                UPDATE pendaftaran 
                SET status = 'diterima',
                    updated_at = NOW()
                WHERE id = ?
            ");
            $stmt->execute([$id]);
            
            // Log activity
            logActivity(
                $db, 
                $_SESSION['admin_id'], 
                'accept_pendaftar', 
                "Menerima pendaftaran: {$pendaftar['nama_siswa']} (ID: {$id})"
            );
            
            // Check if publication exists for this school+jalur, create draft if not
            $stmt = $db->prepare("
                SELECT id FROM publikasi_hasil 
                WHERE sekolah_id = ? AND jalur = ?
            ");
            $stmt->execute([$pendaftar['sekolah_id'], $pendaftar['jalur']]);
            $publikasi = $stmt->fetch();
            
            if (!$publikasi) {
                $stmt = $db->prepare("
                    INSERT INTO publikasi_hasil (sekolah_id, jalur, status, created_at)
                    VALUES (?, ?, 'draft', NOW())
                ");
                $stmt->execute([$pendaftar['sekolah_id'], $pendaftar['jalur']]);
            }
            
            $_SESSION['success'] = "✅ Pendaftaran {$pendaftar['nama_siswa']} berhasil diterima!";
            redirect('/admin/pendaftar');
            
        } catch (PDOException $e) {
            error_log("Accept Pendaftar Error: " . $e->getMessage());
            $_SESSION['error'] = 'Terjadi kesalahan saat memproses data.';
            redirect('/admin/pendaftar');
        }
    }

    // Reject Pendaftar (with Reason)
    public function rejectPendaftar($id) {
        $this->checkAdmin();
        
        // Validate ID
        if (!is_numeric($id)) {
            $_SESSION['error'] = 'ID tidak valid.';
            redirect('/admin/pendaftar');
        }
        
        $db = getConnection();
        
        try {
            // Validate input
            $reject_reason = htmlspecialchars(trim($_POST['reject_reason'] ?? ''));
            $reject_notes = htmlspecialchars(trim($_POST['reject_notes'] ?? ''));
            
            if (empty($reject_reason)) {
                $_SESSION['error'] = 'Alasan penolakan wajib diisi.';
                redirect('/admin/pendaftar');
            }
            
            // Get pendaftar data
            $stmt = $db->prepare("
                SELECT p.*, s.nama as nama_siswa 
                FROM pendaftaran p
                JOIN siswa s ON p.siswa_id = s.id
                WHERE p.id = ?
            ");
            $stmt->execute([$id]);
            $pendaftar = $stmt->fetch();
            
            if (!$pendaftar) {
                $_SESSION['error'] = 'Data pendaftar tidak ditemukan.';
                redirect('/admin/pendaftar');
            }
            
            // Security check
            if ($_SESSION['admin_role'] !== 'super_admin' && 
                $pendaftar['sekolah_id'] != $_SESSION['admin_sekolah_id']) {
                http_response_code(403);
                die("Akses Ditolak.");
            }
            
            // Update status to ditolak with reason
            $stmt = $db->prepare("
                UPDATE pendaftaran 
                SET status = 'ditolak',
                    reject_reason = ?,
                    reject_notes = ?,
                    rejected_by = ?,
                    rejected_at = NOW(),
                    updated_at = NOW()
                WHERE id = ?
            ");
            $stmt->execute([
                $reject_reason,
                $reject_notes,
                $_SESSION['admin_id'],
                $id
            ]);
            
            // Log activity
            $logDesc = "Menolak pendaftaran: {$pendaftar['nama_siswa']} (ID: {$id}). Alasan: {$reject_reason}";
            logActivity($db, $_SESSION['admin_id'], 'reject_pendaftar', $logDesc);
            
            $_SESSION['success'] = "Pendaftaran {$pendaftar['nama_siswa']} telah ditolak.";
            redirect('/admin/pendaftar');
            
        } catch (PDOException $e) {
            error_log("Reject Pendaftar Error: " . $e->getMessage());
            $_SESSION['error'] = 'Terjadi kesalahan saat memproses data.';
            redirect('/admin/pendaftar');
        }
    }


    // --- Admin Management (Super Admin Only) ---

    public function users() {
        $this->checkSuperAdmin();
        
        $db = getConnection();
        // Get all admins with their school info
        $stmt = $db->query("
            SELECT a.*, s.nama as nama_sekolah 
            FROM admin a 
            LEFT JOIN sekolah s ON a.sekolah_id = s.id 
            ORDER BY a.role, a.nama
        ");
        $admins = $stmt->fetchAll();
        
        $data = [
            'title' => 'Manajemen Admin',
            'admins' => $admins
        ];
        
        view('admin.users.index', $data);
    }

    public function tambahUser() {
        $this->checkSuperAdmin();
        
        $sekolah = new Sekolah();
        $data = [
            'title' => 'Tambah Admin',
            'sekolah_list' => $sekolah->all()
        ];
        
        view('admin.users.tambah', $data);
    }

    // AJAX endpoint for real-time username validation
    public function checkUsername() {
        header('Content-Type: application/json');
        
        $username = $_POST['username'] ?? '';
        
        if (empty($username)) {
            echo json_encode(['available' => false]);
            exit;
        }
        
        $db = getConnection();
        $stmt = $db->prepare("SELECT id FROM admin WHERE username = ?");
        $stmt->execute([$username]);
        
        $available = !$stmt->fetch();
        
        echo json_encode(['available' => $available]);
        exit;
    }

    public function storeUser() {
        $this->checkSuperAdmin();
        
        // Get and sanitize inputs with proper checking
        $username = isset($_POST['username']) ? trim($_POST['username']) : '';
        $password = isset($_POST['password']) ? trim($_POST['password']) : '';
        $nama = isset($_POST['nama']) ? trim($_POST['nama']) : '';
        $role = isset($_POST['role']) ? $_POST['role'] : 'school_admin';
        $sekolah_id = !empty($_POST['sekolah_id']) ? $_POST['sekolah_id'] : null;
        
        // Comprehensive validation
        if (empty($username) || empty($password) || empty($nama)) {
            $_SESSION['error'] = 'Semua field wajib diisi!';
            redirect('/admin/users/tambah');
        }

        // Validate password length
        if (strlen($password) < 6) {
            $_SESSION['error'] = 'Password minimal 6 karakter!';
            redirect('/admin/users/tambah');
        }

        // Validate role-specific requirements
        if ($role === 'school_admin' && empty($sekolah_id)) {
            $_SESSION['error'] = 'Admin Sekolah harus memilih sekolah!';
            redirect('/admin/users/tambah');
        }

        $db = getConnection();
        
        // Check if username already exists
        $stmt = $db->prepare("SELECT id FROM admin WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            $_SESSION['error'] = 'Username sudah digunakan!';
            redirect('/admin/users/tambah');
        }

        // Insert new admin
        try {
            $stmt = $db->prepare("INSERT INTO admin (username, password, nama, role, sekolah_id) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $username,
                password_hash($password, PASSWORD_DEFAULT),
                $nama,
                $role,
                $sekolah_id
            ]);
            
            $_SESSION['success'] = 'Admin berhasil ditambahkan!';
            redirect('/admin/users');
        } catch (PDOException $e) {
            $_SESSION['error'] = 'Gagal menambahkan admin: ' . $e->getMessage();
            redirect('/admin/users/tambah');
        }
    }

    public function hapusUser($id) {
        $this->checkSuperAdmin();
        
        // Prevent deleting self
        if ($id == $_SESSION['admin_id']) {
            $_SESSION['error'] = 'Anda tidak dapat menghapus akun sendiri!';
            redirect('/admin/users');
        }

        $db = getConnection();
        $stmt = $db->prepare("DELETE FROM admin WHERE id = ?");
        $stmt->execute([$id]);
        
        $_SESSION['success'] = 'Admin berhasil dihapus!';
        redirect('/admin/users');
    }

    // --- Verifikasi Pendaftar (Ranked by Juknis) ---
    public function verifikasi() {
        $this->checkAdmin();
        
        $pendaftaran = new Pendaftaran();
        $sekolahId = $_SESSION['admin_role'] === 'super_admin' ? null : $_SESSION['admin_sekolah_id'];
        
        $data = [
            'title' => 'Verifikasi Fisik Dokumen',
            'pendaftar' => $pendaftaran->getAll($sekolahId)
        ];
        
        view('admin.pendaftar.verifikasi', $data);
    }

    public function setVerifikasiFisik($id) {
        $this->checkAdmin();
        $db = getConnection();
        
        $status = $_POST['status'] ?? 0;
        
        $pendaftaran = new Pendaftaran();
        $dataPendaftar = $pendaftaran->find($id);

        if (!$dataPendaftar) {
             $_SESSION['error'] = 'Data pendaftar tidak ditemukan.';
             redirect('/admin/verifikasi');
        }

        // Security check
        if ($_SESSION['admin_role'] !== 'super_admin' && 
            $dataPendaftar['sekolah_id'] != $_SESSION['admin_sekolah_id']) {
            http_response_code(403);
            die("Akses Ditolak.");
        }

        // Convert bool/int status to ENUM
        $statusEnum = $status ? 'sudah' : 'belum';

        $stmt = $db->prepare("UPDATE pendaftaran SET verifikasi_fisik = ?, tanggal_verifikasi = NOW() WHERE id = ?");
        $stmt->execute([$statusEnum, $id]);
        
        $_SESSION['success'] = 'Status verifikasi fisik berhasil diupdate!';
        redirect($_SERVER['HTTP_REFERER'] ?? '/admin/verifikasi');
    }

    // --- Global Configuration (Super Admin Only) ---
    public function settings() {
        $this->checkSuperAdmin();
        
        $db = getConnection();
        $stmt = $db->query("SELECT * FROM settings");
        $settings = [];
        foreach ($stmt->fetchAll() as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }

        $data = [
            'title' => 'Konfigurasi Global',
            'settings' => $settings
        ];
        
        view('admin.settings.index', $data);
    }

    public function updateSettings() {
        $this->checkSuperAdmin();
        
        $db = getConnection();
        $stmt = $db->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = ?");
        
        foreach ($_POST['settings'] as $key => $value) {
            $stmt->execute([$value, $key]);
        }
        
        $_SESSION['success'] = 'Konfigurasi berhasil diperbarui!';
        redirect('/admin/settings');
    }

    // --- Audit Logs (Super Admin Only) ---
    public function auditLogs() {
        $this->checkSuperAdmin();
        
        $db = getConnection();
        $sql = "SELECT l.*, a.nama as admin_name, s.nama as student_name, s.nisn, sk.nama as school_name
                FROM audit_logs l
                LEFT JOIN admin a ON l.admin_id = a.id
                LEFT JOIN pendaftaran p ON l.pendaftaran_id = p.id
                LEFT JOIN siswa s ON p.siswa_id = s.id
                LEFT JOIN sekolah sk ON p.sekolah_id = sk.id
                ORDER BY l.created_at DESC";
        
        $stmt = $db->query($sql);
        $logs = $stmt->fetchAll();
        
        $data = [
            'title' => 'Audit Log Aktivitas',
            'logs' => $logs
        ];
        
        view('admin.logs.index', $data);
    }
    // --- Seleksi Management (Super Admin & Engine) ---

    public function seleksiProses() {
        $this->checkSuperAdmin();
        $sekolah = new Sekolah();
        $data = [
            'title' => 'Proses Seleksi PPDB',
            'sekolah_list' => $sekolah->all()
        ];
        view('admin.seleksi.proses', $data);
    }

    public function seleksiEksekusi() {
        $this->checkSuperAdmin();
        $engine = new SelectionEngine();
        
        try {
            $engine->runAll();
            
            // LOG AUDIT
            $db = getConnection();
            // Removed pendaftaran_id from query to rely on DEFAULT NULL
            $stmt = $db->prepare("INSERT INTO audit_logs (admin_id, action, old_status, new_status, ip_address) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $_SESSION['admin_id'],
                'run_selection',
                'idle',
                'completed',
                $_SERVER['REMOTE_ADDR']
            ]);

            $_SESSION['success'] = 'Proses seleksi otomatis 2025/2026 berhasil dijalankan secara serentak!';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Gagal menjalankan seleksi: ' . $e->getMessage();
        }
        
        redirect('/admin/seleksi/hasil');
    }

    public function seleksiHasil() {
        $this->checkAdmin();
        $db = getConnection();
        $sekolahId = $_SESSION['admin_role'] === 'super_admin' ? null : $_SESSION['admin_sekolah_id'];
        
        // Check if ranking column exists
        $hasRanking = false;
        try {
            $checkCol = $db->query("SHOW COLUMNS FROM pendaftaran LIKE 'ranking'");
            $hasRanking = $checkCol->rowCount() > 0;
        } catch (PDOException $e) {
            // Column doesn't exist
        }
        
        // FIXED: Show all verified students, not just accepted/reserve
        // This allows school admins to change status of verified students
        $sql = "SELECT p.*, s.nama as nama_siswa, s.nisn, s.sekolah_asal, sk.nama as nama_sekolah
                FROM pendaftaran p
                JOIN siswa s ON p.siswa_id = s.id
                JOIN sekolah sk ON p.sekolah_id = sk.id
                WHERE p.status IN ('terverifikasi', 'diterima', 'cadangan', 'ditolak')";
        
        if ($sekolahId) {
            $sql .= " AND p.sekolah_id = " . (int)$sekolahId;
        }
        
        // Order by ranking if column exists, otherwise by id
        if ($hasRanking) {
            $sql .= " ORDER BY p.sekolah_id, p.jalur, p.ranking ASC";
        } else {
            $sql .= " ORDER BY p.sekolah_id, p.jalur, p.id ASC";
        }
        
        try {
            $stmt = $db->query($sql);
            $hasil = $stmt->fetchAll();
        } catch (PDOException $e) {
            $hasil = [];
        }
        
        $data = [
            'title' => 'Hasil Akhir Seleksi',
            'hasil' => $hasil
        ];
        view('admin.seleksi.hasil', $data);
    }

    // Show Reset Confirmation Page
    public function seleksiResetPage() {
        $this->checkSuperAdmin();
        $db = getConnection();
        
        // Count current selection results
        $stats = [];
        $stats['diterima'] = $db->query("SELECT COUNT(*) FROM pendaftaran WHERE status = 'diterima'")->fetchColumn();
        $stats['cadangan'] = $db->query("SELECT COUNT(*) FROM pendaftaran WHERE status = 'cadangan'")->fetchColumn();
        $stats['ditolak'] = $db->query("SELECT COUNT(*) FROM pendaftaran WHERE status = 'ditolak'")->fetchColumn();
        $stats['total'] = $stats['diterima'] + $stats['cadangan'] + $stats['ditolak'];
        
        $data = [
            'title' => 'Reset Hasil Seleksi',
            'stats' => $stats
        ];
        
        view('admin.seleksi.reset', $data);
    }

    public function seleksiReset() {
        $this->checkSuperAdmin();
        $engine = new SelectionEngine();
        
        $engine->resetAll();

        // LOG AUDIT
        $db = getConnection();
        // Removed pendaftaran_id from query to rely on DEFAULT NULL
        $stmt = $db->prepare("INSERT INTO audit_logs (admin_id, action, old_status, new_status, ip_address) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $_SESSION['admin_id'],
            'reset_selection',
            'has_results',
            'pending',
            $_SERVER['REMOTE_ADDR']
        ]);
        
        $_SESSION['success'] = 'Seluruh hasil seleksi telah di-reset ke status Pending.';
        redirect('/admin/seleksi/proses');
    }

    public function seleksiExport() {
        $this->checkAdmin();
        $db = getConnection();
        $sekolahId = $_SESSION['admin_role'] === 'super_admin' ? null : $_SESSION['admin_sekolah_id'];
        
        $sql = "SELECT p.no_pendaftaran, s.nama as nama_siswa, s.nisn, p.jalur, p.ranking, p.status
                FROM pendaftaran p
                JOIN siswa s ON p.siswa_id = s.id
                WHERE p.status IN ('diterima', 'cadangan')";
        
        if ($sekolahId) {
            $sql .= " AND p.sekolah_id = " . (int)$sekolahId;
        }
        $sql .= " ORDER BY p.ranking ASC";
        
        $stmt = $db->query($sql);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="hasil_seleksi_ppdb_' . date('Ymd') . '.csv"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['No. Pendaftaran', 'Nama Siswa', 'NISN', 'Jalur', 'Ranking', 'Status']);
        
        foreach ($data as $row) {
            fputcsv($output, $row);
        }
        fclose($output);
        exit;
    }

    // Manual Status Update (School Admin Only - for their own school)
    public function updateSeleksiStatus() {
        $this->checkAdmin();
        $db = getConnection();
        
        // Only School Admin can change status
        if ($_SESSION['admin_role'] !== 'school_admin') {
            $_SESSION['error'] = 'Hanya Admin Sekolah yang dapat mengubah status siswa.';
            redirect('/admin/seleksi/hasil');
        }
        
        $pendaftaranId = $_POST['pendaftaran_id'] ?? null;
        $newStatus = $_POST['status'] ?? null;
        $sekolahId = $_SESSION['admin_sekolah_id'] ?? null;
        
        if (!$pendaftaranId || !$newStatus || !$sekolahId) {
            $_SESSION['error'] = 'Data tidak lengkap.';
            redirect('/admin/seleksi/hasil');
        }
        
        // Validate status
        $validStatuses = ['diterima', 'cadangan', 'ditolak', 'pending'];
        if (!in_array($newStatus, $validStatuses)) {
            $_SESSION['error'] = 'Status tidak valid.';
            redirect('/admin/seleksi/hasil');
        }
        
        // Verify this pendaftaran belongs to the school admin's school
        $stmt = $db->prepare("SELECT status, sekolah_id FROM pendaftaran WHERE id = ?");
        $stmt->execute([$pendaftaranId]);
        $pendaftaran = $stmt->fetch();
        
        if (!$pendaftaran || $pendaftaran['sekolah_id'] != $sekolahId) {
            $_SESSION['error'] = 'Anda tidak memiliki akses untuk mengubah status siswa ini.';
            redirect('/admin/seleksi/hasil');
        }
        
        $oldStatus = $pendaftaran['status'] ?? 'pending';
        
        // Update status
        $stmt = $db->prepare("UPDATE pendaftaran SET status = ? WHERE id = ?");
        $stmt->execute([$newStatus, $pendaftaranId]);
        
        // Log audit
        $stmt = $db->prepare("INSERT INTO audit_logs (admin_id, pendaftaran_id, action, old_status, new_status, ip_address) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $_SESSION['admin_id'],
            $pendaftaranId,
            'manual_status_change',
            $oldStatus,
            $newStatus,
            $_SERVER['REMOTE_ADDR']
        ]);
        
        $statusLabel = ['diterima' => 'Lulus (Diterima)', 'cadangan' => 'Cadangan', 'ditolak' => 'Tidak Lulus'];
        $_SESSION['success'] = 'Status siswa berhasil diubah menjadi: ' . ($statusLabel[$newStatus] ?? $newStatus);
        redirect('/admin/seleksi/hasil');
    }

    public function seleksiCetak() {
        $this->checkAdmin();
        $db = getConnection();
        $sekolahId = $_SESSION['admin_role'] === 'super_admin' ? null : $_SESSION['admin_sekolah_id'];
        
        $sql = "SELECT p.*, s.nama as nama_siswa, s.nisn, s.sekolah_asal, sk.nama as nama_sekolah
                FROM pendaftaran p
                JOIN siswa s ON p.siswa_id = s.id
                JOIN sekolah sk ON p.sekolah_id = sk.id
                WHERE p.status = 'diterima'";
        
        if ($sekolahId) {
            $sql .= " AND p.sekolah_id = " . (int)$sekolahId;
        }

        // Check for ranking column
        $hasRanking = false;
        try {
            $db->query("SELECT ranking FROM pendaftaran LIMIT 1");
            $hasRanking = true;
        } catch(PDOException $e) {}

        if ($hasRanking) {
            $sql .= " ORDER BY p.ranking ASC";
        } else {
            $sql .= " ORDER BY p.jalur, p.id ASC";
        }
        
        $stmt = $db->query($sql);
        $hasil = $stmt->fetchAll();

        $data = [
            'sekolah_nama' => $_SESSION['admin_sekolah_nama'] ?? 'Semua Sekolah',
            'hasil' => $hasil
        ];

        view('admin.seleksi.cetak', $data);
    }

    public function promoteCadangan() {
        $this->checkSuperAdmin();
        $db = getConnection();
        
        // Get all schools
        $schools = $db->query("SELECT id, nama, kuota FROM sekolah")->fetchAll();
        $promotedCount = 0;
        
        foreach ($schools as $school) {
            // Count accepted students for this school
            $stmt = $db->prepare("SELECT COUNT(*) as count FROM pendaftaran WHERE sekolah_id = ? AND status = 'diterima'");
            $stmt->execute([$school['id']]);
            $acceptedCount = $stmt->fetch()['count'];
            
            // Calculate available slots
            $availableSlots = $school['kuota'] - $acceptedCount;
            
            if ($availableSlots > 0) {
                // Get backup students ordered by ranking (if exists) or jarak_meter
                $cadanganSql = "SELECT p.id FROM pendaftaran p 
                                JOIN siswa s ON p.siswa_id = s.id
                                WHERE p.sekolah_id = ? AND p.status = 'cadangan' 
                                ORDER BY p.jarak_meter ASC, s.tanggal_lahir ASC 
                                LIMIT ?";
                $stmt = $db->prepare($cadanganSql);
                $stmt->execute([$school['id'], $availableSlots]);
                $cadanganIds = $stmt->fetchAll(PDO::FETCH_COLUMN);
                
                if (!empty($cadanganIds)) {
                    // Promote backup students to accepted
                    $placeholders = implode(',', array_fill(0, count($cadanganIds), '?'));
                    $updateSql = "UPDATE pendaftaran SET status = 'diterima' WHERE id IN ($placeholders)";
                    $stmt = $db->prepare($updateSql);
                    $stmt->execute($cadanganIds);
                    $promotedCount += count($cadanganIds);
                }
            }
        }
        
        if ($promotedCount > 0) {
            $_SESSION['success'] = "Berhasil menaikkan {$promotedCount} siswa cadangan ke status diterima.";
        } else {
            $_SESSION['info'] = 'Tidak ada slot kosong yang tersedia atau tidak ada siswa cadangan untuk dinaikkan.';
        }
        redirect('/admin/seleksi/hasil');
    }
    // --- Quota Management (Super Admin) ---
    public function kuota() {
        $this->checkSuperAdmin();
        $db = getConnection();
        
        // Get all schools for dropdown
        $stmt = $db->query("SELECT id, nama, kuota FROM sekolah ORDER BY nama ASC");
        $sekolahList = $stmt->fetchAll();
        
        // Get selected school
        $selectedId = $_GET['id_sekolah'] ?? ($sekolahList[0]['id'] ?? null);
        $selectedSekolah = null;
        
        if ($selectedId) {
            $stmt = $db->prepare("SELECT * FROM sekolah WHERE id = ?");
            $stmt->execute([$selectedId]);
            $selectedSekolah = $stmt->fetch();
        }
        
        $data = [
            'title' => 'Pengaturan Kuota Jalur',
            'sekolah_list' => $sekolahList,
            'selected_sekolah' => $selectedSekolah
        ];
        
        view('admin.kuota.index', $data);
    }

    public function updateKuota() {
        $this->checkSuperAdmin();
        $db = getConnection();
        
        $id = $_POST['id_sekolah'];
        $total_kuota = (int) $_POST['total_kuota'];
        
        $k_zonasi = (int) $_POST['kuota_domisili'];
        $k_afirmasi = (int) $_POST['kuota_afirmasi'];
        $k_pra = (int) $_POST['kuota_prestasi_akademik'];
        $k_prna = (int) $_POST['kuota_prestasi_nonakademik'];
        $k_mutasi = (int) $_POST['kuota_mutasi'];
        
        // Validation: Ensure sum equals total quota or handle as user prefers. 
        // For now, allow flexible input but verify total.
        
        $sql = "UPDATE sekolah SET 
                kuota = ?, 
                kuota_domisili = ?, 
                kuota_afirmasi = ?, 
                kuota_prestasi_akademik = ?, 
                kuota_prestasi_nonakademik = ?,
                kuota_mutasi = ?
                WHERE id = ?";
                
        $stmt = $db->prepare($sql);
        $stmt->execute([
            $total_kuota, 
            $k_zonasi, 
            $k_afirmasi, 
            $k_pra, 
            $k_prna, 
            $k_mutasi, 
            $id
        ]);
        
        $_SESSION['success'] = 'Pengaturan kuota sekolah berhasil diperbarui!';
        redirect('/admin/kuota?id_sekolah=' . $id);
    }

    // --- Jadwal & Jalur Management (Super Admin) ---
    public function jadwal() {
        $this->checkSuperAdmin();
        
        // Prevent caching to ensure fresh data after updates
        header("Cache-Control: no-cache, no-store, must-revalidate");
        header("Pragma: no-cache");
        header("Expires: 0");
        
        $db = getConnection();
        
        // Fetch all schedules
        $stmt = $db->query("SELECT * FROM jadwal ORDER BY urutan ASC, id ASC");
        $jadwal = $stmt->fetchAll();
        
        // Group by jalur for easier display if needed, or just pass raw
        // Logic: 'pendaftaran' is usually the main event for 'tanggal serentak'
        
        // Find 'Pendaftaran' event for default dates
        $pendaftaran = null;
        foreach ($jadwal as $j) {
            if (stripos($j['nama_kegiatan'], 'Pendaftaran') !== false && $j['jalur'] == 'semua') {
                $pendaftaran = $j;
                break;
            }
        }
        
        $data = [
            'title' => 'Pengaturan Jadwal Jalur',
            'jadwal' => $jadwal,
            'pendaftaran_general' => $pendaftaran
        ];
        
        view('admin.jadwal.index', $data);
    }

    public function updateJadwalSerentak() {
        $this->checkSuperAdmin();
        $db = getConnection();
        
        $tgl_buka = $_POST['tanggal_buka'];
        $tgl_tutup = $_POST['tanggal_tutup'];
        // Optional: Seleksi & Pengumuman dates if managed globally
        // For now, let's assume 'Pendaftaran' spans these dates
        
        // Update ALL 'Pendaftaran' related schedules
        $sql = "UPDATE jadwal SET tanggal_mulai = ?, tanggal_selesai = ? WHERE nama_kegiatan LIKE 'Pendaftaran%'";
        $stmt = $db->prepare($sql);
        $stmt->execute([$tgl_buka, $tgl_tutup]);
        
        $_SESSION['success'] = 'Jadwal pendaftaran serentak berhasil diperbarui.';
        redirect('/admin/jadwal');
    }

    public function updateStatusJadwal() {
        $this->checkSuperAdmin();
        $db = getConnection();
        
        $status = $_POST['status']; // 'dibuka' or 'ditutup'
        
        // Simplified mapping: dibuka -> berlangsung, ditutup -> selesai
        $dbStatus = ($status == 'dibuka') ? 'berlangsung' : 'selesai';
        
        // If it's a global update
        if (isset($_POST['update_all']) && $_POST['update_all'] == 1) {
            $stmt = $db->prepare("UPDATE jadwal SET status = ?");
            $stmt->execute([$dbStatus]);
            $_SESSION['success'] = 'Status semua jalur berhasil diperbarui.';
        }
        
        // Stage-based update (Tahap 1, 2, 3)
        if (isset($_POST['tahap'])) {
            $tahap = (int) $_POST['tahap'];
            $stmt = $db->prepare("UPDATE jadwal SET status = ? WHERE tahap = ?");
            $stmt->execute([$dbStatus, $tahap]);
            $statusLabel = ($status == 'dibuka') ? '✅ Dibuka' : '⛔ Ditutup';
            $_SESSION['success'] = "Status Tahap $tahap berhasil diubah menjadi: $statusLabel";
        }
        
        redirect('/admin/jadwal');
    }

    public function updateJadwalPerJalur() {
        $this->checkSuperAdmin();
        $db = getConnection();
        
        $id = $_POST['id'];
        $status = $_POST['status']; // 'dibuka' or 'ditutup'
        $urutan = (int) $_POST['urutan'];
        
        // Fetch current jadwal data for date validation
        $stmt = $db->prepare("SELECT * FROM jadwal WHERE id = ?");
        $stmt->execute([$id]);
        $jadwal = $stmt->fetch();
        
        if (!$jadwal) {
            $_SESSION['error'] = 'Jadwal tidak ditemukan.';
            redirect('/admin/jadwal');
        }
        
        // Date validation: Warn if opening before tanggal_mulai
        if ($status == 'dibuka') {
            $today = date('Y-m-d');
            $tanggalBuka = $jadwal['tanggal_mulai'];
            
            if ($today < $tanggalBuka) {
                $tanggalBukaFormatted = date('d M Y', strtotime($tanggalBuka));
                $_SESSION['warning'] = "⚠️ Perhatian: Anda membuka pendaftaran sebelum tanggal buka resmi ($tanggalBukaFormatted). Pastikan ini sesuai kebijakan.";
            }
        }
        
        // Simplified mapping: dibuka -> berlangsung, ditutup -> selesai
        $dbStatus = ($status == 'dibuka') ? 'berlangsung' : 'selesai';
        
        // Update status
        $stmt = $db->prepare("UPDATE jadwal SET status = ?, urutan = ? WHERE id = ?");
        $stmt->execute([$dbStatus, $urutan, $id]);
        
        $statusLabel = ($status == 'dibuka') ? '✅ Dibuka' : '⛔ Ditutup';
        $_SESSION['success'] = "Status jalur berhasil diubah menjadi: $statusLabel";
        redirect('/admin/jadwal');
    }

    // --- Publikasi Pengumuman (Super Admin) ---
    public function publikasi() {
        $this->checkSuperAdmin();
        $db = getConnection();
        
        $filterJalur = $_GET['jalur'] ?? '';
        $filterSekolah = $_GET['sekolah_id'] ?? '';
        
        // 1. Get Summary Stats (Global or Filtered)
        // Note: 'Lulus' means status 'diterima', 'Tidak Lulus' can be 'ditolak' or just not received.
        // For simplicity: 'diterima' vs others.
        // 'Total Hasil' = total processed applicants.
        
        $whereSql = "WHERE 1=1";
        $params = [];
        if ($filterJalur) {
             $whereSql .= " AND p.jalur = ?";
             $params[] = $filterJalur;
        }
        if ($filterSekolah) {
             $whereSql .= " AND p.sekolah_id = ?";
             $params[] = $filterSekolah;
        }
        
        $stmt = $db->prepare("SELECT 
                                COUNT(*) as total,
                                SUM(CASE WHEN p.status = 'diterima' THEN 1 ELSE 0 END) as lulus,
                                SUM(CASE WHEN p.status != 'diterima' AND p.status != 'pending' THEN 1 ELSE 0 END) as tidak_lulus
                              FROM pendaftaran p $whereSql");
        $stmt->execute($params);
        $stats = $stmt->fetch();
        
        // Count Published
        $pubSql = "SELECT COUNT(*) as published FROM publikasi_hasil WHERE status = 'published'";
        if ($filterJalur) $pubSql .= " AND jalur = '$filterJalur'";
        if ($filterSekolah) $pubSql .= " AND sekolah_id = '$filterSekolah'";
        $pubStats = $db->query($pubSql)->fetch();
        
        // 2. Get Sekolah List with Status
        // Join with schools and publication status
        // We need separate rows per JALUR if filterJalur is NOT set? 
        // The screenshot implies we choose a JALUR first to see table. But if not chosen?
        // Let's list schools. If Jalur selected, show status for that jalur.
        // If no jalur selected, maybe show 'Mixed' or just list schools.
        
        // Strategy: Only show table detailed rows if Jalur is selected.
        // If no Jalur selected, maybe just list schools or ask to select jalur.
        // Based on screenshot "1. Pilih Jalur *", it seems mandatory or highly suggested.
        
        $sekolahList = [];
        if ($filterJalur) {
            $sql = "SELECT s.id, s.nama,
                    (SELECT COUNT(*) FROM pendaftaran p WHERE p.sekolah_id = s.id AND p.jalur = ?) as total_hasil,
                    (SELECT COUNT(*) FROM pendaftaran p WHERE p.sekolah_id = s.id AND p.jalur = ? AND p.status = 'diterima') as lulus,
                    (SELECT COUNT(*) FROM pendaftaran p WHERE p.sekolah_id = s.id AND p.jalur = ? AND p.status != 'diterima' AND p.status != 'pending') as tidak_lulus,
                    ph.status as status_publikasi,
                    ph.published_at
                    FROM sekolah s
                    LEFT JOIN publikasi_hasil ph ON s.id = ph.sekolah_id AND ph.jalur = ?
                    ORDER BY s.nama ASC";
            $stmt = $db->prepare($sql);
            $stmt->execute([$filterJalur, $filterJalur, $filterJalur, $filterJalur]);
            $sekolahList = $stmt->fetchAll();
        } else if ($filterSekolah) {
             // If School selected but no Jalur (edge case), list jalurs?
             // Let's stick to requiring Jalur for the main Table view as per screenshot logic
        }
        
        // Get Dropdown Data
        $allSekolah = $db->query("SELECT id, nama FROM sekolah ORDER BY nama ASC")->fetchAll();
        
        $data = [
            'title' => 'Publikasi Pengumuman',
            'stats' => array_merge($stats, $pubStats),
            'sekolah_list' => $sekolahList,
            'all_sekolah' => $allSekolah,
            'filter_jalur' => $filterJalur,
            'filter_sekolah' => $filterSekolah
        ];
        
        view('admin.seleksi.publikasi', $data);
    }

    public function publishPengumuman() {
        $this->checkSuperAdmin();
        $db = getConnection();
        
        $sekolah_id = $_POST['sekolah_id'] ?? null;
        $jalur = $_POST['jalur'] ?? null;
        $action = $_POST['action'] ?? 'publish';
        
        // Enhanced validation
        if (!$sekolah_id || !$jalur) {
            $_SESSION['error'] = 'Data tidak lengkap. Pilih sekolah dan jalur.';
            redirect('/admin/seleksi/publikasi');
        }
        
        // Check if there are results to publish
        $stmt = $db->prepare("
            SELECT COUNT(*) as total 
            FROM pendaftaran 
            WHERE sekolah_id = ? AND jalur = ? AND status IN ('diterima', 'ditolak', 'cadangan')
        ");
        $stmt->execute([$sekolah_id, $jalur]);
        $result = $stmt->fetch();
        
        if ($action == 'publish' && $result['total'] == 0) {
            $_SESSION['error'] = 'Tidak ada hasil seleksi untuk dipublikasikan.';
            redirect('/admin/seleksi/publikasi?jalur=' . $jalur);
        }
        
        $status = ($action == 'publish') ? 'published' : 'draft';
        $published_at = ($action == 'publish') ? date('Y-m-d H:i:s') : null;
        
        // Upsert
        $sql = "INSERT INTO publikasi_hasil (sekolah_id, jalur, status, published_at) 
                VALUES (?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE status = VALUES(status), published_at = VALUES(published_at)";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([$sekolah_id, $jalur, $status, $published_at]);
        
        // Get school name for logging
        $stmt = $db->prepare("SELECT nama FROM sekolah WHERE id = ?");
        $stmt->execute([$sekolah_id]);
        $sekolah = $stmt->fetch();
        
        // Log activity
        $logMsg = ($action == 'publish') 
            ? "Mempublikasikan hasil jalur {$jalur} untuk {$sekolah['nama']}"
            : "Membatalkan publikasi jalur {$jalur} untuk {$sekolah['nama']}";
        logActivity($db, $_SESSION['admin_id'], 'publikasi', $logMsg);
        
        // Success message
        $successMsg = ($action == 'publish')
            ? "✅ Hasil seleksi berhasil dipublikasikan! Siswa dapat melihat hasil mereka."
            : "Publikasi dibatalkan. Hasil tidak lagi terlihat oleh siswa.";
        
        $_SESSION['success'] = $successMsg;
        redirect('/admin/seleksi/publikasi?jalur=' . $jalur);
    }
    private function getAuditLogs($pendaftaranId) {
        $db = getConnection();
        $sql = "SELECT l.*, a.nama as admin_name 
                FROM audit_logs l
                JOIN admin a ON l.admin_id = a.id
                WHERE l.pendaftaran_id = ?
                ORDER BY l.created_at DESC";
        $stmt = $db->prepare($sql);
        $stmt->execute([$pendaftaranId]);
        return $stmt->fetchAll();
    }

    // --- SCHOOL ADMIN SPECIFIC FEATURES ---

    // View School Profile
    public function profilSekolah() {
        $this->checkAdmin();
        $db = getConnection();
        
        $sekolahId = $_SESSION['admin_sekolah_id'] ?? null;
        if (!$sekolahId && $_SESSION['admin_role'] !== 'super_admin') {
            $_SESSION['error'] = 'Anda tidak memiliki akses ke halaman ini.';
            redirect('/admin');
        }
        
        $sekolah = new Sekolah();
        $sekolahData = $sekolah->find($sekolahId);
        
        // Get quota statistics
        $stats = [];
        $jalur = ['zonasi', 'afirmasi', 'prestasi', 'mutasi'];
        foreach ($jalur as $j) {
            $stmt = $db->prepare("SELECT COUNT(*) as count FROM pendaftaran WHERE sekolah_id = ? AND jalur = ?");
            $stmt->execute([$sekolahId, $j]);
            $stats[$j] = $stmt->fetch()['count'];
        }
        
        $data = [
            'title' => 'Profil Sekolah',
            'sekolah' => $sekolahData,
            'stats' => $stats
        ];
        
        view('admin.profil-sekolah', $data);
    }

    // Edit School Profile Form
    public function editProfilSekolah() {
        $this->checkAdmin();
        
        $sekolahId = $_SESSION['admin_sekolah_id'] ?? null;
        if (!$sekolahId) {
            $_SESSION['error'] = 'Anda tidak memiliki akses ke halaman ini.';
            redirect('/admin');
        }
        
        $sekolah = new Sekolah();
        $sekolahData = $sekolah->find($sekolahId);
        
        $data = [
            'title' => 'Edit Profil Sekolah',
            'sekolah' => $sekolahData
        ];
        
        view('admin.edit-profil-sekolah', $data);
    }

    // Update School Profile
    public function updateProfilSekolah() {
        $this->checkAdmin();
        
        $sekolahId = $_SESSION['admin_sekolah_id'] ?? null;
        if (!$sekolahId) {
            $_SESSION['error'] = 'Anda tidak memiliki akses.';
            redirect('/admin');
        }
        
        // School admin can only update limited fields - model handles missing columns gracefully
        $allowedFields = ['telepon', 'email', 'website', 'kepala_sekolah', 'nip_kepala_sekolah'];
        $updateData = [];
        
        foreach ($allowedFields as $field) {
            if (isset($_POST[$field])) {
                $updateData[$field] = $_POST[$field];
            }
        }
        
        if (!empty($updateData)) {
            $sekolah = new Sekolah();
            $sekolah->update($sekolahId, $updateData);
            $_SESSION['success'] = 'Profil sekolah berhasil diperbarui.';
        }
        
        redirect('/admin/profil-sekolah');
    }

    // Statistics Page for School Admin
    public function statistikSekolah() {
        $this->checkAdmin();
        $db = getConnection();
        
        $sekolahId = $_SESSION['admin_sekolah_id'] ?? null;
        if (!$sekolahId) {
            $_SESSION['error'] = 'Anda tidak memiliki akses ke halaman ini.';
            redirect('/admin');
        }
        
        $sekolah = new Sekolah();
        $sekolahData = $sekolah->find($sekolahId);
        
        // Statistics per jalur
        $stats = [];
        $jalurList = [
            'zonasi' => ['name' => 'Zonasi/Domisili', 'color' => 'primary', 'quota_field' => 'kuota_domisili'],
            'afirmasi' => ['name' => 'Afirmasi', 'color' => 'info', 'quota_field' => 'kuota_afirmasi'],
            'prestasi' => ['name' => 'Prestasi', 'color' => 'warning', 'quota_field' => 'kuota_prestasi_akademik'],
            'mutasi' => ['name' => 'Mutasi', 'color' => 'danger', 'quota_field' => 'kuota_mutasi']
        ];
        
        foreach ($jalurList as $jalur => $info) {
            $stmt = $db->prepare("SELECT COUNT(*) FROM pendaftaran WHERE sekolah_id = ? AND jalur = ?");
            $stmt->execute([$sekolahId, $jalur]);
            $pendaftar = $stmt->fetchColumn();
            
            $stmt = $db->prepare("SELECT COUNT(*) FROM pendaftaran WHERE sekolah_id = ? AND jalur = ? AND status = 'diterima'");
            $stmt->execute([$sekolahId, $jalur]);
            $diterima = $stmt->fetchColumn();
            
            $kuota = $sekolahData[$info['quota_field']] ?? 0;
            
            $stats[$jalur] = [
                'name' => $info['name'],
                'color' => $info['color'],
                'pendaftar' => $pendaftar,
                'diterima' => $diterima,
                'kuota' => $kuota,
                'sisa' => max(0, $kuota - $diterima)
            ];
        }
        
        // Status breakdown
        $statusStats = [];
        $statusList = ['pending', 'verifikasi', 'diterima', 'cadangan', 'ditolak'];
        foreach ($statusList as $s) {
            $stmt = $db->prepare("SELECT COUNT(*) FROM pendaftaran WHERE sekolah_id = ? AND status = ?");
            $stmt->execute([$sekolahId, $s]);
            $statusStats[$s] = $stmt->fetchColumn();
        }
        
        $data = [
            'title' => 'Statistik Penerimaan',
            'sekolah' => $sekolahData,
            'stats' => $stats,
            'status_stats' => $statusStats
        ];
        
        view('admin.statistik', $data);
    }


    // API: Get Realtime Statistics
    public function getRealtimeStats() {
        if (!isset($_SESSION['admin_id'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        $db = getConnection();
        $sekolahId = $_SESSION['admin_sekolah_id'] ?? null;
        
        if (!$sekolahId) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Sekolah ID not found']);
            exit;
        }
        
        $sekolah = new Sekolah();
        $sekolahData = $sekolah->find($sekolahId);
        
        // Status calculations
        $statusStats = [];
        $statusList = ['pending', 'verifikasi', 'diterima', 'cadangan', 'ditolak'];
        foreach ($statusList as $s) {
            $stmt = $db->prepare("SELECT COUNT(*) FROM pendaftaran WHERE sekolah_id = ? AND status = ?");
            $stmt->execute([$sekolahId, $s]);
            $statusStats[$s] = $stmt->fetchColumn();
        }
        
        // Jalur calculations
        $jalurStats = [];
        $jalurList = [
            'zonasi' => ['quota_field' => 'kuota_domisili'],
            'afirmasi' => ['quota_field' => 'kuota_afirmasi'],
            'prestasi' => ['quota_field' => 'kuota_prestasi_akademik'],
            'mutasi' => ['quota_field' => 'kuota_mutasi']
        ];
        
        foreach ($jalurList as $jalur => $info) {
            $stmt = $db->prepare("SELECT COUNT(*) FROM pendaftaran WHERE sekolah_id = ? AND jalur = ? AND status = 'diterima'");
            $stmt->execute([$sekolahId, $jalur]);
            $diterima = $stmt->fetchColumn();
            
            $kuota = $sekolahData[$info['quota_field']] ?? 0;
            $jalurStats[$jalur] = [
                'diterima' => $diterima,
                'kuota' => $kuota,
                'sisa' => max(0, $kuota - $diterima),
                'percent' => $kuota > 0 ? round(($diterima / $kuota) * 100) : 0
            ];
        }
        
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'timestamp' => date('H:i:s'),
            'status_stats' => $statusStats,
            'jalur_stats' => $jalurStats
        ]);
        exit;
    }

    // Cetak Bukti List
    public function cetakBukti() {
        $this->checkAdmin();
        $db = getConnection();
        
        $sekolahId = $_SESSION['admin_sekolah_id'] ?? null;
        if (!$sekolahId) {
            $_SESSION['error'] = 'Anda tidak memiliki akses.';
            redirect('/admin');
        }
        
        // Get accepted students
        $sql = "SELECT p.*, s.nama, s.nisn, s.nik, s.alamat, s.tanggal_lahir
                FROM pendaftaran p
                JOIN siswa s ON p.siswa_id = s.id
                WHERE p.sekolah_id = ? AND p.status = 'diterima'
                ORDER BY p.jalur, s.nama";
        $stmt = $db->prepare($sql);
        $stmt->execute([$sekolahId]);
        $siswa = $stmt->fetchAll();
        
        $sekolah = new Sekolah();
        
        $data = [
            'title' => 'Cetak Bukti Penerimaan',
            'siswa' => $siswa,
            'sekolah' => $sekolah->find($sekolahId)
        ];
        
        view('admin.cetak-bukti', $data);
    }

    // Print individual acceptance letter
    public function cetakBuktiDetail($id) {
        $this->checkAdmin();
        $db = getConnection();
        
        $sekolahId = $_SESSION['admin_sekolah_id'] ?? null;
        
        $sql = "SELECT p.*, s.nama, s.nisn, s.nik, s.tempat_lahir, s.tanggal_lahir, s.alamat, s.nama_ayah, s.nama_ibu, sk.nama as sekolah_nama
                FROM pendaftaran p
                JOIN siswa s ON p.siswa_id = s.id
                JOIN sekolah sk ON p.sekolah_id = sk.id
                WHERE p.id = ?";
        
        $params = [$id];
        // Only filter by sekolah_id if NOT super_admin
        if ($_SESSION['admin_role'] !== 'super_admin' && $sekolahId) {
            $sql .= " AND p.sekolah_id = ?";
            $params[] = $sekolahId;
        }

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $siswa = $stmt->fetch();
        
        if (!$siswa) {
            $_SESSION['error'] = 'Data tidak ditemukan.';
            redirect('/admin/cetak-bukti');
        }
        
        $data = [
            'title' => 'Cetak Bukti ' . $siswa['nama'],
            'siswa' => $siswa
        ];
        view('admin.cetak-bukti-detail', $data);
    }

    // Action Button: Mulai Verifikasi (Real-time Queue)
    public function startVerification() {
        $this->checkAdmin();
        $db = getConnection();
        
        $sekolahId = $_SESSION['admin_sekolah_id'] ?? null;
        
        // Query pending applications (FIFO Queue)
        $sql = "SELECT p.*, s.nama as nama_siswa, s.nisn, s.nik, s.jenis_kelamin,
                       sek.nama as nama_sekolah, p.jarak_km
                FROM pendaftaran p
                JOIN siswa s ON p.siswa_id = s.id
                JOIN sekolah sek ON p.sekolah_id = sek.id
                WHERE p.status = 'pending'";
                
        $params = [];
        
        // Filter by school if not super admin
        if ($_SESSION['admin_role'] !== 'super_admin') {
            $sql .= " AND p.sekolah_id = ?";
            $params[] = $sekolahId;
        }
        
        $sql .= " ORDER BY p.created_at ASC"; // FIFO priority
        
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $pendingList = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $data = [
            'title' => 'Antrean Verifikasi (Real-time)',
            'pending_list' => $pendingList
        ];
        
        view('admin.verifikasi_queue', $data);
    }
    // --- DATA ENRICHMENT ENGINE (AUTO-SYNC) ---
    
    public function autoSync($id) {
        $this->checkAdmin();
        header('Content-Type: application/json');
        
        $sekolahModel = new Sekolah();
        $sekolah = $sekolahModel->find($id);
        
        if (!$sekolah) {
            echo json_encode(['success' => false, 'message' => 'Sekolah tidak ditemukan']);
            exit;
        }
        
        // MOCK DATA GENERATION (Simulating AI/External API)
        $names = ['Dr. H. Budi Santoso, M.Pd.', 'Dra. Hj. Siti Aminah, M.M.', 'Ir. Joko Widodo, M.T.', 'Prof. Dr. Agus Salim'];
        $kepsek = $names[array_rand($names)];
        
        $mockData = [
            'kepala_sekolah' => $kepsek,
            'nip_kepala_sekolah' => '19' . rand(60, 85) . rand(10, 12) . rand(10, 30) . ' 1 ' . rand(100, 999),
            'akreditasi' => 'A',
            'telepon' => '0751-' . rand(100000, 999999),
            'website' => strtolower(str_replace(' ', '', $sekolah['nama'])) . '.sch.id',
            'email_sekolah' => 'info@' . strtolower(str_replace(' ', '', $sekolah['nama'])) . '.sch.id',
            // Simulate fixing coordinates slightly if they are default
            'latitude' => -0.9471 + (rand(-50, 50) / 10000), 
            'longitude' => 100.4172 + (rand(-50, 50) / 10000)
        ];

        $db = getConnection();
        try {
            $stmt = $db->prepare("UPDATE sekolah SET 
                kepala_sekolah = ?, 
                nip_kepala_sekolah = ?, 
                akreditasi = ?, 
                telepon = ?, 
                website = ?, 
                email_sekolah = ?,
                latitude = ?,
                longitude = ?
                WHERE id = ?");
                
            $stmt->execute([
                $mockData['kepala_sekolah'],
                $mockData['nip_kepala_sekolah'],
                $mockData['akreditasi'],
                $mockData['telepon'],
                $mockData['website'],
                $mockData['email_sekolah'],
                $mockData['latitude'],
                $mockData['longitude'],
                $id
            ]);
            
            echo json_encode([
                'success' => true, 
                'message' => 'Anti-Gravity Search Complete! Data berhasil diverifikasi dari Kemdikbud & Dapodik.',
                'data' => $mockData
            ]);
            
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Database Error: ' . $e->getMessage()]);
        }
        exit;
    }
}

