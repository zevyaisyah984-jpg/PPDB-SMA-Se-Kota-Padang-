<?php
session_start();
require_once '../config/app.php';
require_once ROOT_PATH . 'config/database.php';
require_once ROOT_PATH . 'app/Models/Sekolah.php';

// Simulate admin session
$_SESSION['admin_id'] = 1;
$_SESSION['admin_role'] = 'school_admin';
$_SESSION['admin_sekolah_id'] = 10;

echo "<h3>Testing Profile Update</h3>";

// Test data
$testData = [
    'telepon' => '0751-999999',
    'website' => 'https://test.sch.id',
    'kepala_sekolah' => 'Test Kepala Sekolah',
    'nip_kepala_sekolah' => '123456789'
];

echo "<h4>Test Data:</h4>";
echo "<pre>";
print_r($testData);
echo "</pre>";

try {
    $sekolah = new Sekolah();
    
    // Before update
    echo "<h4>Before Update:</h4>";
    $before = $sekolah->find(10);
    echo "<pre>";
    print_r($before);
    echo "</pre>";
    
    // Update
    echo "<h4>Updating...</h4>";
    $result = $sekolah->update(10, $testData);
    echo "Update result: " . ($result ? 'SUCCESS' : 'FAILED') . "<br>";
    
    // After update
    echo "<h4>After Update:</h4>";
    $after = $sekolah->find(10);
    echo "<pre>";
    print_r($after);
    echo "</pre>";
    
} catch (Exception $e) {
    echo "<p style='color:red'>Error: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
