<?php
require_once 'config/database.php';
$db = getConnection();

try {
    echo "Attempting to add 'no_hp' column...\n";
    // Add no_hp for student's personal phone number (e.g. WhatsApp)
    $sql = "ALTER TABLE siswa ADD COLUMN no_hp VARCHAR(20) AFTER no_hp_ortu";
    $db->exec($sql);
    echo "Success: Column 'no_hp' added.\n";
} catch (PDOException $e) {
    echo "Notice: " . $e->getMessage() . "\n";
}

echo "Database update complete.\n";
