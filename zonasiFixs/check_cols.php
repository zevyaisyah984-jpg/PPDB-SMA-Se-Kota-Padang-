<?php
try {
    $db = new PDO('mysql:host=localhost;dbname=smapadang', 'root', '');
    $stmt = $db->query("DESCRIBE pendaftaran");
    $cols = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "COLUMNS FOUND: " . implode(', ', $cols);
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
