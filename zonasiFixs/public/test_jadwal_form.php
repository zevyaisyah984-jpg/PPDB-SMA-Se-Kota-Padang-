<?php
session_start();
require_once '../config/app.php';
require_once ROOT_PATH . 'config/database.php';

// Simulate super admin session
$_SESSION['admin_id'] = 1;
$_SESSION['admin_role'] = 'super_admin';
$_SESSION['admin_name'] = 'Super Admin';

echo "<h3>Testing Form Submission</h3>";

try {
    $db = getConnection();
    
    // Get first jadwal item for testing
    $stmt = $db->query("SELECT * FROM jadwal WHERE jalur != 'semua' LIMIT 1");
    $jadwal = $stmt->fetch();
    
    if (!$jadwal) {
        echo "<p style='color:red'>No jadwal data found!</p>";
        exit;
    }
    
    echo "<h4>Testing Update for:</h4>";
    echo "ID: {$jadwal['id']}<br>";
    echo "Nama: {$jadwal['nama_kegiatan']}<br>";
    echo "Current Status: {$jadwal['status']}<br>";
    echo "Current Urutan: {$jadwal['urutan']}<br>";
    
    // Simulate form submission
    $_POST['id'] = $jadwal['id'];
    $_POST['status'] = 'ditutup'; // Change to ditutup
    $_POST['urutan'] = 5;
    
    echo "<h4>Simulating Update To:</h4>";
    echo "Status: ditutup<br>";
    echo "Urutan: 5<br>";
    
    // Execute update logic from controller
    $id = $_POST['id'];
    $status = $_POST['status'];
    $urutan = (int) $_POST['urutan'];
    
    // Map status
    $dbStatus = 'akan_datang';
    if ($status == 'dibuka' || $status == 'seleksi' || $status == 'pengumuman') $dbStatus = 'berlangsung';
    if ($status == 'ditutup') $dbStatus = 'selesai';
    
    echo "<h4>Mapped DB Status: $dbStatus</h4>";
    
    $stmt = $db->prepare("UPDATE jadwal SET status = ?, urutan = ? WHERE id = ?");
    $result = $stmt->execute([$dbStatus, $urutan, $id]);
    
    echo "<h4>Update Result:</h4>";
    echo "Success: " . ($result ? 'YES' : 'NO') . "<br>";
    echo "Rows Affected: " . $stmt->rowCount() . "<br>";
    
    // Fetch updated data
    $stmt = $db->prepare("SELECT * FROM jadwal WHERE id = ?");
    $stmt->execute([$id]);
    $updated = $stmt->fetch();
    
    echo "<h4>After Update:</h4>";
    echo "Status: {$updated['status']}<br>";
    echo "Urutan: {$updated['urutan']}<br>";
    
    if ($updated['status'] == $dbStatus && $updated['urutan'] == $urutan) {
        echo "<p style='color:green; font-weight:bold'>✅ UPDATE SUCCESSFUL!</p>";
    } else {
        echo "<p style='color:red; font-weight:bold'>❌ UPDATE FAILED - Data not changed</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color:red'>Error: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
