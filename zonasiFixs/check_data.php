<?php
define('DEBUG_MODE', true);
require 'config/database.php';

try {
    $db = getConnection();
    $stmt = $db->query("SELECT id, npsn, nama, alamat, telepon, website, akreditasi FROM sekolah LIMIT 5");
    $schools = $stmt->fetchAll(PDO::FETCH_ASSOC);
    print_r($schools);
} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage();
}
