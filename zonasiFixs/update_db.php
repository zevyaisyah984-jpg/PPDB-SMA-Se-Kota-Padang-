<?php
require_once 'config/database.php';
$db = getConnection();

try {
    echo "Attempting to add 'pekerjaan_ayah' column...\n";
    $sql = "ALTER TABLE siswa ADD COLUMN pekerjaan_ayah VARCHAR(100) AFTER nama_ayah";
    $db->exec($sql);
    echo "Success: Column 'pekerjaan_ayah' added.\n";
} catch (PDOException $e) {
    echo "Notice: " . $e->getMessage() . "\n";
}

try {
    echo "Attempting to add 'pekerjaan_ibu' column...\n";
    $sql = "ALTER TABLE siswa ADD COLUMN pekerjaan_ibu VARCHAR(100) AFTER nama_ibu";
    $db->exec($sql);
    echo "Success: Column 'pekerjaan_ibu' added.\n";
} catch (PDOException $e) {
    echo "Notice: " . $e->getMessage() . "\n";
}

echo "Database update complete.\n";
