<?php
session_start();
require_once '../config/app.php';
require_once ROOT_PATH . 'config/database.php';

// Simulate super admin session
$_SESSION['admin_id'] = 1;
$_SESSION['admin_role'] = 'super_admin';
$_SESSION['admin_name'] = 'Super Admin';

echo "<h3>Testing Jadwal Update</h3>";

try {
    $db = getConnection();
    
    // Check current jadwal data
    echo "<h4>Current Jadwal Data:</h4>";
    $stmt = $db->query("SELECT * FROM jadwal ORDER BY tahap, jalur");
    $jadwal = $stmt->fetchAll();
    
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>Nama</th><th>Jalur</th><th>Tahap</th><th>Status</th><th>Tanggal Mulai</th><th>Tanggal Selesai</th><th>Urutan</th></tr>";
    foreach ($jadwal as $j) {
        echo "<tr>";
        echo "<td>{$j['id']}</td>";
        echo "<td>{$j['nama_kegiatan']}</td>";
        echo "<td>{$j['jalur']}</td>";
        echo "<td>{$j['tahap']}</td>";
        echo "<td>{$j['status']}</td>";
        echo "<td>{$j['tanggal_mulai']}</td>";
        echo "<td>{$j['tanggal_selesai']}</td>";
        echo "<td>{$j['urutan']}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Test update status for tahap 1
    echo "<h4>Testing Update Status Tahap 1:</h4>";
    $dbStatus = 'berlangsung';
    $tahap = 1;
    
    $stmt = $db->prepare("UPDATE jadwal SET status = ? WHERE tahap = ?");
    $result = $stmt->execute([$dbStatus, $tahap]);
    
    echo "Update result: " . ($result ? 'SUCCESS' : 'FAILED') . "<br>";
    echo "Rows affected: " . $stmt->rowCount() . "<br>";
    
    // Check after update
    echo "<h4>After Update:</h4>";
    $stmt = $db->query("SELECT * FROM jadwal WHERE tahap = 1");
    $updated = $stmt->fetchAll();
    
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>Nama</th><th>Jalur</th><th>Status</th></tr>";
    foreach ($updated as $u) {
        echo "<tr>";
        echo "<td>{$u['id']}</td>";
        echo "<td>{$u['nama_kegiatan']}</td>";
        echo "<td>{$u['jalur']}</td>";
        echo "<td style='background:" . ($u['status'] == 'berlangsung' ? 'lightgreen' : 'white') . "'>{$u['status']}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
} catch (Exception $e) {
    echo "<p style='color:red'>Error: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
