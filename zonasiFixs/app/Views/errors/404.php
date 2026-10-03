<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center min-vh-100">
    <div class="container text-center">
        <div class="display-1 text-primary fw-bold">404</div>
        <h2 class="fw-bold mb-3">Halaman Tidak Ditemukan</h2>
        <p class="text-muted mb-4">Maaf, halaman yang Anda cari tidak tersedia.</p>
        <a href="<?php echo url('/'); ?>" class="btn btn-primary rounded-pill px-4">
            <i class="bi bi-house me-2"></i>Kembali ke Beranda
        </a>
    </div>
</body>
</html>
