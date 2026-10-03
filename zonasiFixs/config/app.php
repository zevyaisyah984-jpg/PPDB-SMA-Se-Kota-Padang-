<?php
// Application Configuration

// Application Settings
define('APP_NAME', 'PPDB SMA Negeri Kota Padang');
define('APP_VERSION', '1.0.0');
define('APP_ENV', 'development'); // development | production

// Base URL (auto-detect or set manually)
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';

// Auto-detect the /public base path so the app is not tied to a fixed folder name.
// SCRIPT_NAME is e.g. "/zonasiFixs/public/index.php" -> base path "/zonasiFixs/public".
// Falls back to "/zonasi/public" when it cannot be detected (e.g. CLI scripts).
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
$detectedBasePath = (PHP_SAPI !== 'cli' && $scriptName !== '')
    ? rtrim(str_replace('\\', '/', dirname($scriptName)), '/')
    : '';
if ($detectedBasePath === '.' || $detectedBasePath === '/') {
    $detectedBasePath = '';
}

define('BASE_PATH', $detectedBasePath !== '' ? $detectedBasePath : '/zonasi/public');
define('BASE_URL', $protocol . '://' . $host . BASE_PATH . '/');
define('ROOT_PATH', dirname(__DIR__) . '/');

// Timezone
date_default_timezone_set('Asia/Jakarta');

// Debug Mode
define('DEBUG_MODE', APP_ENV === 'development');

// Error Reporting
if (DEBUG_MODE) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}
