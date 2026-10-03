<?php
require_once 'config/database.php';
try {
    $db = getConnection();
    $pass = password_hash('adminsma2', PASSWORD_DEFAULT);
    $stmt = $db->prepare("UPDATE admin SET password = ? WHERE username = 'adminsma2'");
    $stmt->execute([$pass]);
    echo "Password reset successfully.";
} catch (Exception $e) {
    echo $e->getMessage();
}
