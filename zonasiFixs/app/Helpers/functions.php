<?php
// View Helper - Load views with data
function view($name, $data = []) {
    extract($data);
    $viewPath = ROOT_PATH . 'app/Views/' . str_replace('.', '/', $name) . '.php';
    
    if (file_exists($viewPath)) {
        require $viewPath;
    } else {
        echo "View not found: " . $name;
    }
}

// Redirect Helper
function redirect($path) {
    if (preg_match('/^https?:\/\//', $path)) {
        header('Location: ' . $path);
    } else {
        header('Location: ' . BASE_URL . ltrim($path, '/'));
    }
    exit;
}

// Asset URL Helper
function asset($path) {
    if (preg_match('/^https?:\/\//', $path)) {
        return $path;
    }
    return BASE_URL . 'assets/' . ltrim($path, '/');
}

// URL Helper
function url($path = '') {
    if (preg_match('/^https?:\/\//', $path)) {
        return $path;
    }
    return BASE_URL . ltrim($path, '/');
}

// Uploads Helper
function uploads($path = '') {
    return BASE_URL . 'uploads/' . ltrim($path, '/');
}

// Old Input Helper (for form refill)
function old($key, $default = '') {
    return $_POST[$key] ?? $_GET[$key] ?? $default;
}

// CSRF Token
function csrf_token() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// CSRF Field
function csrf_field() {
    return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Get current user ID  
function userId() {
    return $_SESSION['user_id'] ?? null;
}

// Escape HTML - handles null values for PHP 8.1+ compatibility
function e($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}
// Get Setting from Database
function get_setting($key, $default = null) {
    try {
        $db = getConnection();
        $stmt = $db->prepare("SELECT setting_value FROM settings WHERE setting_key = ?");
        $stmt->execute([$key]);
        $row = $stmt->fetch();
        return $row ? $row['setting_value'] : $default;
    } catch (Exception $e) {
        return $default;
    }
}
// Check if registration period is closed
function isRegistrationClosed() {
    $endDate = get_setting('tgl_selesai_pendaftaran');
    if (!$endDate) return false;
    
    $currentDate = date('Y-m-d');
    return $currentDate >= $endDate;
}

// Check if announcement period is active
function isAnnouncementPeriod() {
    $announceDate = get_setting('tgl_pengumuman');
    if (!$announceDate) return false;
    
    $currentDate = date('Y-m-d');
    return $currentDate >= $announceDate;
}


/**
 * Check if a specific jalur is currently open for registration
 * @param string $jalur - 'zonasi', 'afirmasi', 'prestasi', 'mutasi'
 * @return bool
 */
function isJalurOpen($jalur) {
    $db = getConnection();
    
    $stmt = $db->prepare("
        SELECT status 
        FROM jadwal 
        WHERE jalur = ? 
        AND nama_kegiatan LIKE 'Pendaftaran%' 
        LIMIT 1
    ");
    $stmt->execute([$jalur]);
    $result = $stmt->fetch();
    
    return ($result && $result['status'] == 'berlangsung');
}

/**
 * Get all open jalur
 * @return array
 */
function getOpenJalur() {
    $db = getConnection();
    
    $stmt = $db->query("
        SELECT jalur, nama_kegiatan 
        FROM jadwal 
        WHERE status = 'berlangsung' 
        AND jalur != 'semua'
        AND nama_kegiatan LIKE 'Pendaftaran%'
    ");
    
    return $stmt->fetchAll();
}

/**
 * Get jalur status message
 * @param string $jalur
 * @return string
 */
function getJalurStatusMessage($jalur) {
    if (isJalurOpen($jalur)) {
        return "Pendaftaran jalur " . ucfirst($jalur) . " sedang dibuka.";
    } else {
        return "Maaf, pendaftaran jalur " . ucfirst($jalur) . " sudah ditutup oleh Admin.";
    }
}

/**
 * Get jalur display name
 * @param string $jalur
 * @return string
 */
function getJalurName($jalur) {
    $names = [
        'zonasi' => 'Jalur Zonasi',
        'afirmasi' => 'Jalur Afirmasi',
        'prestasi' => 'Jalur Prestasi',
        'mutasi' => 'Jalur Mutasi'
    ];
    
    return $names[$jalur] ?? ucfirst($jalur);
}

/**
 * Check if results are published for specific school and jalur
 * @param int $sekolah_id
 * @param string $jalur
 * @return bool
 */
function isResultPublished($sekolah_id, $jalur) {
    $db = getConnection();
    
    $stmt = $db->prepare("
        SELECT status 
        FROM publikasi_hasil 
        WHERE sekolah_id = ? AND jalur = ? AND status = 'published'
    ");
    $stmt->execute([$sekolah_id, $jalur]);
    $result = $stmt->fetch();
    
    return ($result !== false);
}

/**
 * Get published results for user
 * @param int $user_id
 * @return array|null
 */
function getPublishedResultForUser($user_id) {
    $db = getConnection();
    
    $stmt = $db->prepare("
        SELECT p.*, s.nama as nama_sekolah, ph.published_at
        FROM pendaftaran p
        JOIN sekolah s ON p.sekolah_id = s.id
        LEFT JOIN publikasi_hasil ph ON p.sekolah_id = ph.sekolah_id AND p.jalur = ph.jalur
        WHERE p.siswa_id = ? AND ph.status = 'published'
    ");
    $stmt->execute([$user_id]);
    
    return $stmt->fetch();
}

/**
 * Get all published jalur
 * @return array
 */
function getPublishedJalur() {
    $db = getConnection();
    
    $stmt = $db->query("
        SELECT DISTINCT jalur, MAX(published_at) as latest_publish
        FROM publikasi_hasil 
        WHERE status = 'published'
        GROUP BY jalur
        ORDER BY latest_publish DESC
    ");
    
    return $stmt->fetchAll();
}

/**
 * Log admin activity
 * @param object $db Database connection
 * @param int $admin_id
 * @param string $action_type
 * @param string $description
 */
function logActivity($db, $admin_id, $action_type, $description) {
    try {
        $stmt = $db->prepare("
            INSERT INTO activity_logs (admin_id, action_type, description, created_at) 
            VALUES (?, ?, ?, NOW())
        ");
        $stmt->execute([$admin_id, $action_type, $description]);
    } catch (PDOException $e) {
        // Silently fail if table doesn't exist yet
        error_log("Activity log failed: " . $e->getMessage());
    }
}
