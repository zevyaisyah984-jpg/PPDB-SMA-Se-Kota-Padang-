<?php
require_once 'config/database.php';
$db = getConnection();
$tables = ['siswa'];
foreach($tables as $table) {
    echo "--- Table: $table ---\n";
    $stmt = $db->query("DESCRIBE $table");
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
}
