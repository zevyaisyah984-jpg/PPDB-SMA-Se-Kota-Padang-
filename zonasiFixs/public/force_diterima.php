<?php
session_start();
require_once '../config/app.php';
require_once ROOT_PATH . 'config/database.php';

try {
    $db = getConnection();
    
    // Update student ID 4 to 'diterima' status
    $stmt = $db->prepare("UPDATE pendaftaran SET status = 'diterima' WHERE id = 4");
    $stmt->execute();
    
    echo "<h3>Status Updated Successfully!</h3>";
    echo "<p>Student ID 4 has been set to 'diterima'</p>";
    echo "<p><a href='" . BASE_URL . "admin/cetak-bukti'>Go to Cetak Bukti Page</a></p>";
    echo "<p><a href='" . BASE_URL . "admin/seleksi/hasil'>Go to Hasil Seleksi Page</a></p>";
    
} catch (Exception $e) {
    echo "<p style='color:red'>Error: " . $e->getMessage() . "</p>";
}
