<?php
define('ROOT_PATH', __DIR__ . '/');
require_once 'config/app.php';
require_once 'config/database.php';

$db = getConnection();
$stmt = $db->query("SELECT * FROM jadwal WHERE nama_kegiatan LIKE '%Pendaftaran%'");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "DEBUG JADWAL DATA:\n";
print_r($rows);

echo "\nCurrent Server Date: " . date('Y-m-d') . "\n";
foreach ($rows as $row) {
    if (strpos($row['nama_kegiatan'], 'Pendaftaran') !== false) {
        $today = date('Y-m-d');
        $start = $row['tanggal_mulai'];
        $end = $row['tanggal_selesai'];
        $isOpen = ($today >= $start && $today <= $end);
        echo "Checking: " . $row['nama_kegiatan'] . "\n";
        echo "Start: $start, End: $end, Today: $today\n";
        echo "Is Open? " . ($isOpen ? "YES" : "NO") . "\n";
        echo "Status DB: " . $row['status'] . "\n";
    }
}
?>
