<?php
// Migration: Add missing columns to sekolah table

require_once __DIR__ . '/config/database.php';

try {
    $db = getConnection();
    
    $columnsToAdd = [
        'kelurahan' => "ALTER TABLE sekolah ADD COLUMN kelurahan VARCHAR(100) NULL AFTER kecamatan",
        'kode_pos' => "ALTER TABLE sekolah ADD COLUMN kode_pos VARCHAR(10) NULL AFTER kelurahan",
        'telepon' => "ALTER TABLE sekolah ADD COLUMN telepon VARCHAR(20) NULL AFTER kuota",
        'website' => "ALTER TABLE sekolah ADD COLUMN website VARCHAR(255) NULL AFTER telepon",
        'kepala_sekolah' => "ALTER TABLE sekolah ADD COLUMN kepala_sekolah VARCHAR(150) NULL AFTER website",
        'nip_kepala_sekolah' => "ALTER TABLE sekolah ADD COLUMN nip_kepala_sekolah VARCHAR(30) NULL AFTER kepala_sekolah"
    ];
    
    // Check existing columns
    $stmt = $db->query("SHOW COLUMNS FROM sekolah");
    $existingColumns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    $added = [];
    foreach ($columnsToAdd as $column => $sql) {
        if (!in_array($column, $existingColumns)) {
            try {
                $db->exec($sql);
                $added[] = $column;
                echo "✅ Added column: $column\n";
            } catch (PDOException $e) {
                echo "⚠️ Could not add $column: " . $e->getMessage() . "\n";
            }
        } else {
            echo "ℹ️ Column '$column' already exists.\n";
        }
    }
    
    if (empty($added)) {
        echo "\nAll columns already exist. No changes made.\n";
    } else {
        echo "\n✅ Migration complete. Added " . count($added) . " column(s).\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Migration failed: " . $e->getMessage() . "\n";
}
