<?php
require_once __DIR__ . '/config/database.php';

try {
    $db = getConnection();
    $stmt = $db->query("DESCRIBE sekolah");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "--- Table: sekolah ---\n";
    print_r($columns);
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
