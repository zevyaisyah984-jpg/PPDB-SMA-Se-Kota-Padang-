<?php
define('ROOT_PATH', __DIR__ . '/');
require_once ROOT_PATH . 'config/database.php';

try {
    $db = getConnection();
    $sql = "ALTER TABLE siswa ADD COLUMN IF NOT EXISTS kelurahan VARCHAR(100) NULL AFTER kecamatan";
    $db->exec($sql);
    echo "Migration successful: Column 'kelurahan' added to 'siswa' table.\n";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), "Duplicate column name") !== false) {
        echo "Column 'kelurahan' already exists.\n";
    } else {
        echo "Migration failed: " . $e->getMessage() . "\n";
    }
}
?>
