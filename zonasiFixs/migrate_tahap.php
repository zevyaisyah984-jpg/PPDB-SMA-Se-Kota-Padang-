<?php
require_once 'config/database.php';
$db = getConnection();

try {
    // Add tahap column if not exists
    $columns = $db->query("SHOW COLUMNS FROM jadwal LIKE 'tahap'")->fetchAll();
    if (empty($columns)) {
        $db->exec("ALTER TABLE jadwal ADD COLUMN tahap INT(1) DEFAULT 1 AFTER jalur");
        echo "Column 'tahap' added successfully.\n";
    }

    // Initialize tahap values based on Juknis 2025
    // Tahap 1: Afirmasi, Prestasi, Mutasi
    $db->exec("UPDATE jadwal SET tahap = 1 WHERE jalur IN ('afirmasi', 'prestasi', 'mutasi')");
    
    // Tahap 2: Zonasi
    $db->exec("UPDATE jadwal SET tahap = 2 WHERE jalur = 'zonasi'");
    
    // Tahap 3: Pemenuhan Kuota / Cadangan (Optional, based on implementation)
    // For now, let's keep others at 1 or assign appropriately
    
    echo "Jadwal stages initialized successfully.\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
