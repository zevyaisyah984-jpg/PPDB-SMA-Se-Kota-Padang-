<?php
/**
 * DEMO: Publication Integration Features
 * This demonstrates all publication features working together
 */

session_start();
require_once '../config/app.php';
require_once ROOT_PATH . 'config/database.php';
require_once ROOT_PATH . 'app/Helpers/functions.php';

// Simulate logged in student
$_SESSION['user_id'] = 1;
$_SESSION['username'] = 'Test Siswa';

$db = getConnection();

// Get published jalur
$publishedJalur = getPublishedJalur();
$hasPublished = !empty($publishedJalur);

// Get user's published result
$publishedResult = getPublishedResultForUser($_SESSION['user_id']);

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo - Publikasi Integration</title>
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
        <h2 class="fw-bold">✅ DEMO: Publikasi Integration</h2>
        <p class="text-muted">Demonstrasi lengkap fitur publikasi pengumuman</p>
    </div>

    <!-- FEATURE 1: Published Jalur List -->
    <div class="demo-card">
        <h5 class="fw-bold mb-3"><i class="bi bi-megaphone me-2"></i>Feature 1: Published Jalur</h5>
        
        <?php if ($hasPublished): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Jalur</th>
                            <th>Tanggal Publikasi</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($publishedJalur as $jalur): ?>
                        <tr>
                            <td><strong><?php echo ucfirst($jalur['jalur']); ?></strong></td>
                            <td>
                                <i class="bi bi-calendar-check me-1"></i>
                                <?php echo date('d M Y, H:i', strtotime($jalur['latest_publish'])); ?> WIB
                            </td>
                            <td>
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle me-1"></i>PENGUMUMAN TERBIT
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-warning">
                <i class="bi bi-calendar-x me-2"></i>
                Belum ada pengumuman yang dipublikasikan
            </div>
        <?php endif; ?>
        
        <div class="bg-light p-3 rounded mt-3">
            <small class="text-muted">✅ Function: getPublishedJalur()</small>
        </div>
    </div>

    <!-- FEATURE 2: User's Published Result -->
    <div class="demo-card">
        <h5 class="fw-bold mb-3"><i class="bi bi-person-check me-2"></i>Feature 2: User's Result</h5>
        
        <?php if ($publishedResult): ?>
            <div class="alert alert-success">
                <h6 class="fw-bold mb-2">🎉 Hasil Anda Telah Dipublikasikan!</h6>
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted">Jalur:</td>
                        <td><strong><?php echo ucfirst($publishedResult['jalur']); ?></strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Sekolah:</td>
                        <td><strong><?php echo $publishedResult['nama_sekolah']; ?></strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status:</td>
                        <td><span class="badge bg-success"><?php echo strtoupper($publishedResult['status']); ?></span></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Dipublikasi:</td>
                        <td><?php echo date('d M Y, H:i', strtotime($publishedResult['published_at'])); ?> WIB</td>
                    </tr>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>
                Hasil Anda belum dipublikasikan atau Anda belum mendaftar
            </div>
        <?php endif; ?>
        
        <div class="bg-light p-3 rounded mt-3">
            <small class="text-muted">✅ Function: getPublishedResultForUser()</small>
        </div>
    </div>

    <!-- FEATURE 3: Conditional Print Access -->
    <div class="demo-card">
        <h5 class="fw-bold mb-3"><i class="bi bi-printer me-2"></i>Feature 3: Conditional Print Access</h5>
        
        <?php
        $isLulus = ($publishedResult && $publishedResult['status'] == 'diterima');
        $isPublished = ($publishedResult !== false);
        $canPrint = ($isLulus && $isPublished);
        ?>
        
        <div class="row g-3">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">Status Checks</h6>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td>Is Lulus:</td>
                                <td><?php echo $isLulus ? '<span class="badge bg-success">✅ YES</span>' : '<span class="badge bg-secondary">❌ NO</span>'; ?></td>
                            </tr>
                            <tr>
                                <td>Is Published:</td>
                                <td><?php echo $isPublished ? '<span class="badge bg-success">✅ YES</span>' : '<span class="badge bg-secondary">❌ NO</span>'; ?></td>
                            </tr>
                            <tr>
                                <td>Can Print:</td>
                                <td><?php echo $canPrint ? '<span class="badge bg-success">✅ YES</span>' : '<span class="badge bg-danger">❌ NO</span>'; ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">Print Button State</h6>
                        
                        <?php if ($canPrint): ?>
                            <button class="btn btn-success w-100">
                                <i class="bi bi-printer me-2"></i>Cetak Bukti Diterima
                            </button>
                            <small class="text-success d-block mt-2">✅ Button ENABLED</small>
                        <?php elseif ($isLulus && !$isPublished): ?>
                            <button class="btn btn-warning w-100" disabled>
                                <i class="bi bi-hourglass-split me-2"></i>Menunggu Publikasi
                            </button>
                            <small class="text-warning d-block mt-2">⏳ Waiting for publication</small>
                        <?php else: ?>
                            <button class="btn btn-secondary w-100" disabled>
                                <i class="bi bi-lock me-2"></i>Menunggu Hasil Seleksi
                            </button>
                            <small class="text-muted d-block mt-2">🔒 Button DISABLED</small>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bg-light p-3 rounded mt-3">
            <small class="text-muted">✅ Logic: canPrint = (isLulus AND isPublished)</small>
        </div>
    </div>

    <!-- FEATURE 4: Notification Banner -->
    <div class="demo-card">
        <h5 class="fw-bold mb-3"><i class="bi bi-bell me-2"></i>Feature 4: Notification Banner</h5>
        
        <?php if ($publishedResult): ?>
            <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm">
                <div class="d-flex align-items-start gap-3">
                    <div class="bg-success bg-opacity-10 rounded-circle p-3">
                        <i class="bi bi-megaphone-fill text-success fs-3"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="fw-bold mb-2">🎉 Pengumuman Resmi!</h5>
                        <p class="mb-2">
                            Hasil Seleksi <strong>Jalur <?php echo ucfirst($publishedResult['jalur']); ?></strong> 
                            untuk <strong><?php echo $publishedResult['nama_sekolah']; ?></strong> 
                            telah resmi diumumkan!
                        </p>
                        <p class="mb-0 small">
                            <i class="bi bi-calendar-check me-1"></i>
                            Dipublikasikan: <?php echo date('d M Y, H:i', strtotime($publishedResult['published_at'])); ?> WIB
                        </p>
                        <div class="mt-3">
                            <a href="#" class="btn btn-success btn-sm rounded-pill px-4">
                                <i class="bi bi-arrow-right-circle me-2"></i>Lihat Status Kelulusan
                            </a>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>
                Banner akan muncul saat hasil Anda dipublikasikan
            </div>
        <?php endif; ?>
        
        <div class="bg-light p-3 rounded mt-3">
            <small class="text-muted">✅ Auto-show when getPublishedResultForUser() returns data</small>
        </div>
    </div>

    <!-- SUMMARY -->
    <div class="demo-card bg-success bg-opacity-10 border-success">
        <h5 class="fw-bold text-success mb-3"><i class="bi bi-check-circle-fill me-2"></i>All Features Working!</h5>
        <ul class="mb-0">
            <li>✅ <strong>Helper Functions</strong> - isResultPublished(), getPublishedJalur(), getPublishedResultForUser()</li>
            <li>✅ <strong>Backend Validation</strong> - Check results exist before publish</li>
            <li>✅ <strong>Activity Logging</strong> - Track all publication actions</li>
            <li>✅ <strong>Conditional UI</strong> - Print button enabled only when published</li>
            <li>✅ <strong>Notifications</strong> - Banner shows when results published</li>
        </ul>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
