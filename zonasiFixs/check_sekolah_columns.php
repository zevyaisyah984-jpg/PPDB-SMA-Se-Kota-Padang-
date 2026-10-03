<?php
require_once 'app/Config/Database.php';

try {
    $db = getConnection();
    $stmt = $db->query("DESCRIBE sekolah");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "Columns in 'sekolah' table:\n";
    print_r($columns);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
