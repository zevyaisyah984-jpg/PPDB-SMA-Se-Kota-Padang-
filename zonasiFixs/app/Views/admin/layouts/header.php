<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($_SESSION['admin_sekolah_nama']) ? $_SESSION['admin_sekolah_nama'] . ' - ' : ''; ?><?php echo $title ?? 'Admin Panel'; ?> - <?php echo APP_NAME; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?php echo asset('css/style.css'); ?>" rel="stylesheet">
</head>
<body class="admin-body">
    <?php view('admin.partials.sidebar'); ?>
    
    <main id="admin-main">
        <!-- Admin Header -->
        <header class="admin-header">
            <div class="admin-breadcrumbs">
                <i class="bi bi-house-door"></i>
                <span>/</span>
                <span class="fw-semibold text-primary"><?php echo $title ?? 'Dashboard'; ?></span>
            </div>
            
            <div class="dropdown">
                <div class="profile-card" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="profile-avatar">
                        <?php echo substr($_SESSION['admin_name'] ?? 'A', 0, 1); ?>
                    </div>
                    <div class="profile-info show-desktop">
                        <div class="profile-name"><?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Admin'); ?></div>
                        <div class="profile-role"><?php echo ucwords(str_replace('_', ' ', $_SESSION['admin_role'] ?? 'Administrator')); ?></div>
                    </div>
                    <i class="bi bi-chevron-down ms-2 text-muted" style="font-size: 0.75rem;"></i>
                </div>
                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 py-2" style="min-width: 200px;">
                    <li>
                        <div class="px-3 py-2 border-bottom mb-2">
                            <div class="fw-bold"><?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Admin'); ?></div>
                            <div class="small text-muted"><?php echo htmlspecialchars($_SESSION['admin_email'] ?? ''); ?></div>
                        </div>
                    </li>
                    <?php if (isset($_SESSION['admin_role']) && $_SESSION['admin_role'] !== 'super_admin'): ?>
                    <li><a class="dropdown-item py-2 px-3" href="<?php echo url('/admin/profil-sekolah'); ?>"><i class="bi bi-building me-2 text-muted"></i>Profil Sekolah</a></li>
                    <li><hr class="dropdown-divider my-2"></li>
                    <?php endif; ?>
                    <li><a class="dropdown-item py-2 px-3 text-danger" href="<?php echo url('/admin/logout'); ?>"><i class="bi bi-box-arrow-right me-2"></i>Keluar Sistem</a></li>
                </ul>
            </div>
        </header>
