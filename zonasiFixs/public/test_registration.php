<?php
/**
 * TEST REGISTRATION WITH VALIDATION
 * Demonstrates backend hard-lock working
 */

session_start();
require_once '../config/app.php';
require_once ROOT_PATH . 'config/database.php';
require_once ROOT_PATH . 'app/Helpers/functions.php';

$jalur = $_GET['jalur'] ?? 'zonasi';

// HARD-LOCK VALIDATION
if (!isJalurOpen($jalur)) {
    $_SESSION['error'] = "Maaf, pendaftaran jalur " . ucfirst($jalur) . " sudah ditutup oleh Admin.";
    header('Location: demo_jadwal_integration.php');
    exit;
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran <?php echo ucfirst($jalur); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex p-4 mb-3">
                            <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
                        </div>
                        <h3 class="fw-bold text-success">✅ Validasi Berhasil!</h3>
                        <p class="text-muted">Jalur <?php echo ucfirst($jalur); ?> sedang DIBUKA</p>
                    </div>

                    <div class="alert alert-success">
                        <h6 class="fw-bold mb-2"><i class="bi bi-shield-check me-2"></i>Backend Hard-Lock Validation Berfungsi</h6>
                        <p class="mb-0 small">
                            Anda berhasil mengakses halaman ini karena jalur <strong><?php echo ucfirst($jalur); ?></strong> 
                            memiliki status <code>berlangsung</code> di database. 
                            Jika status <code>selesai</code>, Anda akan otomatis di-redirect dengan pesan error.
                        </p>
                    </div>

                    <div class="bg-light p-4 rounded-3 mb-4">
                        <h6 class="fw-bold mb-3">Informasi Jalur</h6>
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td class="text-muted">Jalur:</td>
                                <td class="fw-bold"><?php echo getJalurName($jalur); ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Status:</td>
                                <td><span class="badge bg-success">✅ Dibuka</span></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Validasi:</td>
                                <td><span class="text-success fw-bold">PASSED</span></td>
                            </tr>
                        </table>
                    </div>

                    <div class="d-grid gap-2">
                        <a href="demo_jadwal_integration.php" class="btn btn-primary btn-lg rounded-pill">
                            <i class="bi bi-arrow-left me-2"></i> Kembali ke Demo
                        </a>
                    </div>

                    <div class="mt-4 p-3 bg-warning bg-opacity-10 rounded-3">
                        <small class="text-muted">
                            <i class="bi bi-info-circle me-1"></i>
                            <strong>Catatan:</strong> Ini adalah halaman demo. Di production, form pendaftaran lengkap akan ditampilkan di sini.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
