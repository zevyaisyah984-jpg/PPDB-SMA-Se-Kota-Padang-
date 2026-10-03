<?php
try {
    $db = new PDO('mysql:host=localhost;dbname=smapadang', 'root', '');
    $sql = "ALTER TABLE pendaftaran 
            ADD COLUMN reject_reason TEXT NULL AFTER status,
            ADD COLUMN reject_notes TEXT NULL AFTER reject_reason,
            ADD COLUMN rejected_by INT(11) NULL AFTER reject_notes,
            ADD COLUMN rejected_at DATETIME NULL AFTER rejected_by";
    
    $db->exec($sql);
    echo "SUCCESS: Columns added to pendaftaran table.";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), "Duplicate column name") !== false) {
        echo "INFO: Columns already exist.";
    } else {
        echo "ERROR: " . $e->getMessage();
    }
}
