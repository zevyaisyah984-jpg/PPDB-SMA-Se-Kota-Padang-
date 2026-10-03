<?php
require_once 'config/database.php';

try {
    $db = getConnection();
    $stmt = $db->query("
        SELECT s.nama as sekolah_nama, s.npsn, a.username, a.id, a.sekolah_id 
        FROM admin a 
        JOIN sekolah s ON a.sekolah_id = s.id 
        where a.role = 'school_admin'
        ORDER BY s.nama
    ");
    $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $filename = 'daftar_akun_sekolah.txt';
    $content = "DAFTAR AKUN ADMIN SEKOLAH (UPDATED " . date('Y-m-d H:i') . ")\n";
    $content .= "Format Username: nama sekolah (kecil, tanpa spasi)\n";
    $content .= "Format Password: admin[angka]\n\n";
    $content .= str_pad("Nama Sekolah", 40) . " | " . str_pad("Username", 20) . " | " . "Password\n";
    $content .= str_repeat("-", 80) . "\n";

    foreach ($admins as $admin) {
        $nama = $admin['sekolah_nama'];
        
        // Re-derive password logic for display since we can't unhash
        // This assumes the update_credentials.php logic was just run
         if (preg_match('/\d+/', $nama, $matches)) {
            $number = $matches[0];
            $password = "admin" . $number;
        } else {
            $password = "admin" . $admin['sekolah_id'];
        }

        $line = str_pad(substr($nama, 0, 39), 40) . " | " . 
                str_pad($admin['username'], 20) . " | " . 
                $password . "\n";
        
        $content .= $line;
    }

    file_put_contents($filename, $content);
    echo "File '$filename' berhasil diperbarui.\n";
    echo $content;

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
