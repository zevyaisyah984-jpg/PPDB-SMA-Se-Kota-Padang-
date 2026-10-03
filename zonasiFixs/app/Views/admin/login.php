<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - <?php echo APP_NAME; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary-purple: #5b4ddf;
            --bg-gradient-start: #2c2560;
            --bg-gradient-end: #5b4ddf;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, var(--bg-gradient-start) 0%, var(--bg-gradient-end) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }
        
        .login-card {
            background: #ffffff;
            border-radius: 32px;
            padding: 48px;
            width: 100%;
            max-width: 480px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            text-align: center;
        }

        .logo-img {
            width: 80px;
            height: auto;
            margin-bottom: 24px;
        }

        .page-title {
            font-size: 24px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .page-subtitle {
            color: #6c757d;
            font-size: 14px;
            margin-bottom: 32px;
        }

        .form-control {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px 20px;
            font-size: 15px;
            color: #333;
            background-color: #fff;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: var(--primary-purple);
            box-shadow: 0 0 0 3px rgba(91, 77, 223, 0.15);
        }

        .form-control::placeholder {
            color: #94a3b8;
            font-weight: 400;
        }

        .btn-primary {
            background-color: #5548d9;
            border: none;
            border-radius: 12px;
            padding: 16px;
            font-weight: 600;
            font-size: 16px;
            width: 100%;
            margin-bottom: 24px;
            transition: transform 0.2s, background-color 0.2s;
        }

        .btn-primary:hover {
            background-color: #463bb8;
            transform: translateY(-2px);
        }

        .divider {
            height: 1px;
            background-color: #e2e8f0;
            margin: 0 auto 24px auto;
            width: 100%;
        }

        .back-link {
            color: #64748b;
            text-decoration: none;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
        }

        .back-link:hover {
            color: #333;
        }

        .alert {
            border-radius: 12px;
            font-size: 14px;
            margin-bottom: 24px;
            border: none;
        }
    </style>
</head>
<body>

    <?php 
    $isSchoolLogin = (strpos($_SERVER['REQUEST_URI'], '/admin/sekolah/login') !== false);
    $loginTitle = $isSchoolLogin ? 'Login Admin Sekolah' : 'Administrator Panel';
    $loginSubtitle = $isSchoolLogin ? 'Masuk sebagai operator sekolah' : 'Masuk untuk mengelola sistem PPDB';
    ?>

    <div class="login-card">
        <!-- Logo -->
        <img src="<?php echo asset('images/logo_kemdikbud.png'); ?>" alt="Logo" class="logo-img">
        
        <!-- Header -->
        <h1 class="page-title"><?php echo $loginTitle; ?></h1>
        <p class="page-subtitle"><?php echo $loginSubtitle; ?></p>

        <!-- Alerts -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger bg-danger bg-opacity-10 text-danger">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <!-- Form -->
        <form action="<?php echo $isSchoolLogin ? url('/admin/sekolah/login') : url('/admin/login'); ?>" method="POST">
            <?php echo csrf_field(); ?>
            
            <input type="text" 
                   class="form-control" 
                   name="username" 
                   placeholder="Username" 
                   required>
            
            <input type="password" 
                   class="form-control" 
                   name="password" 
                   placeholder="Password" 
                   required>

            <button type="submit" class="btn btn-primary text-white d-flex justify-content-center align-items-center gap-2">
                Masuk Dashboard <i class="bi bi-arrow-right"></i>
            </button>
        </form>

        <!-- Divider -->
        <div class="divider"></div>

        <div class="d-flex flex-column gap-3">
            <!-- Switch Admin Link -->
            <?php if ($isSchoolLogin): ?>
                <a href="<?php echo url('/admin/login'); ?>" class="back-link justify-content-center text-primary">
                    Login sebagai Super Admin
                </a>
            <?php else: ?>
                <a href="<?php echo url('/admin/sekolah/login'); ?>" class="back-link justify-content-center text-primary">
                    Login sebagai Admin Sekolah
                </a>
            <?php endif; ?>

            <!-- Back Link -->
            <a href="<?php echo url('/'); ?>" class="back-link justify-content-center">
                <i class="bi bi-arrow-left"></i> Kembali ke Halaman Utama
            </a>
        </div>
    </div>

</body>
</html>
