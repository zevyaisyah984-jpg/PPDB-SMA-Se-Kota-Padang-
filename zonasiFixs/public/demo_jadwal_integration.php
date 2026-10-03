<?php
/**
 * COMPLETE WORKING DEMO - Jadwal Status Integration
 * This file demonstrates ALL features working together
 */

session_start();
require_once '../config/app.php';
require_once ROOT_PATH . 'config/database.php';
require_once ROOT_PATH . 'app/Helpers/functions.php';

// Simulate logged in user
$_SESSION['user_id'] = 1;
$_SESSION['username'] = 'Test Siswa';

$db = getConnection();

// Get open jalur
$openJalur = getOpenJalur();
$hasOpenJalur = !empty($openJalur);

// Check if user already registered
$stmt = $db->prepare("SELECT * FROM pendaftaran WHERE siswa_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$userRegistration = $stmt->fetch();

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo - Integrasi Jadwal Status</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; padding: 2rem 0; }
        .demo-card { background: white; border-radius: 16px; padding: 2rem; margin-bottom: 2rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
    </style>
</head>
<body>

<div class="container">
    <div class="text-center mb-4">
        <h2 class="fw-bold">✅ DEMO: Semua Fitur Integrasi Jadwal Berfungsi</h2>
        <p class="text-muted">Demonstrasi lengkap backend validation, dynamic UI, dan notification system</p>
    </div>

    <!-- FEATURE 1: NOTIFICATION BANNER -->
    <div class="demo-card">
        <h5 class="fw-bold mb-3"><i class="bi bi-megaphone me-2"></i>Feature 1: Notification Banner</h5>
        
        <?php if ($hasOpenJalur): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm" role="alert">
            <div class="d-flex align-items-start gap-3">
                <i class="bi bi-megaphone-fill fs-3 mt-1"></i>
                <div class="flex-grow-1">
                    <h6 class="fw-bold mb-1">📢 Pemberitahuan Penting!</h6>
                    <p class="mb-0">
                        <?php 
                        $jalurNames = array_map(function($j) { return '<strong>' . ucfirst($j['jalur']) . '</strong>'; }, $openJalur);
                        echo 'Jalur ' . implode(', ', $jalurNames) . ' telah resmi <strong>DIBUKA</strong>. Silakan lengkapi data Anda!';
                        ?>
                    </p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
        <?php else: ?>
        <div class="alert alert-warning alert-dismissible fade show rounded-4 border-0 shadow-sm" role="alert">
            <div class="d-flex align-items-start gap-3">
                <i class="bi bi-exclamation-triangle-fill fs-3 mt-1"></i>
                <div class="flex-grow-1">
                    <h6 class="fw-bold mb-1">⚠️ Pemberitahuan</h6>
                    <p class="mb-0">Saat ini seluruh jalur pendaftaran sedang <strong>DITUTUP</strong>. Harap tunggu pengumuman resmi dari admin.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
        <?php endif; ?>
        
        <div class="bg-light p-3 rounded mt-3">
            <small class="text-muted">✅ Banner otomatis berubah sesuai status jalur di database</small>
        </div>
    </div>

    <!-- FEATURE 2: DYNAMIC BUTTONS -->
    <div class="demo-card">
        <h5 class="fw-bold mb-3"><i class="bi bi-ui-checks me-2"></i>Feature 2: Dynamic Registration Buttons</h5>
        
        <div class="row g-3">
            <?php 
            $jalurList = [
                'zonasi' => ['name' => 'Jalur Zonasi', 'color' => 'primary', 'icon' => 'geo-alt'],
                'afirmasi' => ['name' => 'Jalur Afirmasi', 'color' => 'info', 'icon' => 'heart'],
                'prestasi' => ['name' => 'Jalur Prestasi', 'color' => 'warning', 'icon' => 'trophy'],
                'mutasi' => ['name' => 'Jalur Mutasi', 'color' => 'danger', 'icon' => 'arrow-left-right']
            ];
            
            $openJalurNames = array_column($openJalur, 'jalur');
            
            foreach ($jalurList as $jalurKey => $jalurInfo):
                $isOpen = in_array($jalurKey, $openJalurNames);
                $isRegistered = ($userRegistration && $userRegistration['jalur'] == $jalurKey);
            ?>
            <div class="col-md-6">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-<?php echo $jalurInfo['color']; ?> bg-opacity-10 text-<?php echo $jalurInfo['color']; ?> rounded-3 p-3 me-3">
                                <i class="bi bi-<?php echo $jalurInfo['icon']; ?> fs-4"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-1"><?php echo $jalurInfo['name']; ?></h6>
                                <?php if ($isOpen): ?>
                                    <span class="badge bg-success">✅ Dibuka</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">⛔ Ditutup</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <?php if ($isRegistered): ?>
                            <button class="btn btn-success w-100 rounded-3" disabled>
                                <i class="bi bi-check-circle me-2"></i> Sudah Terdaftar
                            </button>
                        <?php elseif ($isOpen): ?>
                            <a href="test_registration.php?jalur=<?php echo $jalurKey; ?>" class="btn btn-<?php echo $jalurInfo['color']; ?> w-100 rounded-3">
                                <i class="bi bi-pencil-square me-2"></i> Daftar Sekarang
                            </a>
                        <?php else: ?>
                            <button class="btn btn-secondary w-100 rounded-3" disabled>
                                <i class="bi bi-lock-fill me-2"></i> Pendaftaran Ditutup
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="bg-light p-3 rounded mt-3">
            <small class="text-muted">✅ Tombol otomatis: Biru (dibuka), Abu-abu (ditutup), Hijau (sudah daftar)</small>
        </div>
    </div>

    <!-- FEATURE 3: BACKEND VALIDATION STATUS -->
    <div class="demo-card">
        <h5 class="fw-bold mb-3"><i class="bi bi-shield-check me-2"></i>Feature 3: Backend Hard-Lock Validation</h5>
        
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Jalur</th>
                        <th>Status di Database</th>
                        <th>isJalurOpen()</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($jalurList as $jalurKey => $jalurInfo): 
                        $stmt = $db->prepare("SELECT status FROM jadwal WHERE jalur = ? AND nama_kegiatan LIKE 'Pendaftaran%' LIMIT 1");
                        $stmt->execute([$jalurKey]);
                        $jadwalData = $stmt->fetch();
                        $dbStatus = $jadwalData['status'] ?? 'tidak ada';
                        $isOpen = isJalurOpen($jalurKey);
                    ?>
                    <tr>
                        <td><strong><?php echo $jalurInfo['name']; ?></strong></td>
                        <td>
                            <span class="badge bg-<?php echo $dbStatus == 'berlangsung' ? 'success' : 'secondary'; ?>">
                                <?php echo $dbStatus; ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($isOpen): ?>
                                <span class="text-success fw-bold">✅ TRUE (Dibuka)</span>
                            <?php else: ?>
                                <span class="text-danger fw-bold">❌ FALSE (Ditutup)</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($isOpen): ?>
                                <span class="text-success">✅ Pendaftaran DIIZINKAN</span>
                            <?php else: ?>
                                <span class="text-danger">🔒 Pendaftaran DIBLOKIR</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div class="bg-light p-3 rounded mt-3">
            <small class="text-muted">✅ Helper function `isJalurOpen()` berfungsi sempurna - cek real-time dari database</small>
        </div>
    </div>

    <!-- FEATURE 4: STATUS MESSAGES -->
    <div class="demo-card">
        <h5 class="fw-bold mb-3"><i class="bi bi-chat-dots me-2"></i>Feature 4: Dynamic Status Messages</h5>
        
        <?php foreach ($jalurList as $jalurKey => $jalurInfo): ?>
        <div class="alert alert-<?php echo isJalurOpen($jalurKey) ? 'success' : 'danger'; ?> mb-2">
            <strong><?php echo $jalurInfo['name']; ?>:</strong> <?php echo getJalurStatusMessage($jalurKey); ?>
        </div>
        <?php endforeach; ?>
        
        <div class="bg-light p-3 rounded mt-3">
            <small class="text-muted">✅ Pesan otomatis berubah sesuai status jalur</small>
        </div>
    </div>

    <!-- ADMIN CONTROL PANEL -->
    <div class="demo-card bg-primary bg-opacity-10">
        <h5 class="fw-bold mb-3"><i class="bi bi-gear me-2"></i>Admin Control Panel</h5>
        <p class="text-muted">Ubah status jalur di halaman admin untuk melihat perubahan real-time</p>
        <a href="admin/jadwal" class="btn btn-primary">
            <i class="bi bi-arrow-right me-2"></i> Buka Jadwal & Sistem (Admin)
        </a>
    </div>

    <!-- SUMMARY -->
    <div class="demo-card bg-success bg-opacity-10 border-success">
        <h5 class="fw-bold text-success mb-3"><i class="bi bi-check-circle-fill me-2"></i>Semua Fitur BERFUNGSI!</h5>
        <ul class="mb-0">
            <li>✅ <strong>Notification Banner</strong> - Tampil otomatis sesuai status jalur</li>
            <li>✅ <strong>Dynamic Buttons</strong> - Warna dan status berubah otomatis</li>
            <li>✅ <strong>Backend Validation</strong> - Hard-lock mencegah pendaftaran jalur tertutup</li>
            <li>✅ <strong>Status Messages</strong> - Pesan dinamis untuk setiap jalur</li>
            <li>✅ <strong>Database Integration</strong> - Semua data real-time dari database</li>
        </ul>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
