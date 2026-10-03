<?php
require_once 'config/database.php';

try {
    $db = getConnection();
    echo "Updating School Credentials...\n";
    echo str_pad("School Name", 40) . " | " . str_pad("New Username", 20) . " | " . "New Password\n";
    echo str_repeat("-", 80) . "\n";

    // Get all schools JOINED with admins to ensure we update existing accounts
    $stmt = $db->query("SELECT s.id as sekolah_id, s.nama, a.id as admin_id FROM sekolah s JOIN admin a ON a.sekolah_id = s.id WHERE a.role = 'school_admin'");
    $schools = $stmt->fetchAll();

    $count = 0;

    foreach ($schools as $row) {
        $nama = $row['nama'];
        
        // Generate new Username: sman1padang
        $newUsername = strtolower(str_replace(' ', '', $nama));

        // Generate new Password: admin1
        // Extract number from string like "SMAN 1 Padang"
        if (preg_match('/\d+/', $nama, $matches)) {
            $number = $matches[0];
            $newPasswordPlain = "admin" . $number;
        } else {
            // Fallback for schools without numbers in name (unlikely given dataset but good practice)
            $newPasswordPlain = "admin" . $row['sekolah_id'];
        }

        $newPasswordHash = password_hash($newPasswordPlain, PASSWORD_DEFAULT);

        // Update Database
        $update = $db->prepare("UPDATE admin SET username = ?, password = ? WHERE id = ?");
        $update->execute([$newUsername, $newPasswordHash, $row['admin_id']]);

        echo str_pad(substr($nama, 0, 39), 40) . " | " . str_pad($newUsername, 20) . " | " . $newPasswordPlain . "\n";
        $count++;
    }

    echo "\nUpdated $count accounts successfully.\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
