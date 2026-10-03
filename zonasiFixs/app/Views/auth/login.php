<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>Masuk - <?php echo APP_NAME; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary: #1A56DB;
            --primary-dark: #1E40AF;
            --primary-light: #3B82F6;
            --surface-bg: #F9FAFB;
            --accent-emerald: #10B981;
        }
        
        * { box-sizing: border-box; }
        
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 50%, var(--primary-light) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }
        
        .login-card {
            background: #ffffff;
            border-radius: 24px;
            padding: 40px;
            width: 100%;
            max-width: 440px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.25);
            text-align: center;
        }
        
        @media (max-width: 480px) {
            .login-card { padding: 32px 24px; border-radius: 20px; }
        }

        .logo-img {
            width: 72px;
            height: auto;
            margin-bottom: 20px;
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: #111827;
            margin-bottom: 8px;
        }

        .page-subtitle {
            color: #6B7280;
            font-size: 0.9375rem;
            margin-bottom: 32px;
        }
        
        .form-group {
            margin-bottom: 16px;
            text-align: left;
        }
        
        .form-label {
            font-weight: 600;
            font-size: 0.875rem;
            color: #374151;
            margin-bottom: 6px;
            display: block;
        }

        .form-control {
            border: 2px solid #E5E7EB;
            border-radius: 12px;
            padding: 14px 16px;
            font-size: 1rem;
            color: #111827;
            background-color: #fff;
            width: 100%;
            transition: all 0.2s ease;
            min-height: 48px;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(26, 86, 219, 0.15);
            outline: none;
        }

        .form-control::placeholder {
            color: #9CA3AF;
        }

        .btn-primary {
            background: var(--primary);
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-weight: 700;
            font-size: 1rem;
            width: 100%;
            color: white;
            margin-top: 8px;
            transition: all 0.2s ease;
            min-height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(26, 86, 219, 0.3);
        }

        .register-text {
            color: #6B7280;
            font-size: 0.9375rem;
            margin: 24px 0;
        }

        .register-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 700;
        }

        .register-link:hover {
            text-decoration: underline;
        }

        .divider {
            height: 1px;
            background: #E5E7EB;
            margin: 24px 0;
        }

        .back-link {
            color: #6B7280;
            text-decoration: none;
            font-size: 0.875rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-weight: 500;
            transition: color 0.2s;
        }

        .back-link:hover { color: var(--primary); }

        .alert {
            border-radius: 12px;
            font-size: 0.9375rem;
            margin-bottom: 24px;
            border: none;
            padding: 12px 16px;
            text-align: left;
        }
        
        .alert-danger { background: #FEE2E2; color: #991B1B; }
        .alert-success { background: #D1FAE5; color: #065F46; }
        
        .help-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            background: #F3F4F6;
            color: #374151;
            border-radius: 24px;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s;
        }
        
        .help-btn:hover {
            background: #E5E7EB;
            color: #111827;
        }
        
        /* Password Toggle */
        .password-wrapper {
            position: relative;
        }
        
        .password-wrapper .form-control {
            padding-right: 48px;
        }
        
        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #6B7280;
            cursor: pointer;
            padding: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s;
        }
        
        .toggle-password:hover {
            color: var(--primary);
        }
        
        .toggle-password i {
            font-size: 1.25rem;
        }
    </style>
</head>
<body>

<div class="login-card">
    <!-- Logo -->
    <img src="<?php echo asset('images/logo_kemdikbud.png'); ?>" alt="Logo" class="logo-img">
    
    <!-- Header - Friendly -->
    <h1 class="page-title">Selamat Datang Kembali! 👋</h1>
    <p class="page-subtitle">Masuk ke akun PPDB-mu untuk melanjutkan pendaftaran</p>

    <!-- Alerts - Friendly -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-circle me-2"></i><?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <i class="bi bi-check-circle me-2"></i><?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <!-- Form -->
    <form action="<?php echo url('/login'); ?>" method="POST" id="loginForm">
        <?php echo csrf_field(); ?>
        
        <div class="form-group position-relative">
            <label class="form-label">NISN</label>
            <input type="text" 
                   class="form-control" 
                   name="nisn" 
                   inputmode="numeric"
                   placeholder="Masukkan 10 digit NISN" 
                   required
                   data-validate="nisn">
        </div>
        
        <div class="form-group position-relative">
            <label class="form-label">Password</label>
            <div class="password-wrapper">
                <input type="password" 
                       class="form-control" 
                       name="password" 
                       id="passwordInput"
                       placeholder="Masukkan password" 
                       required>
                <button type="button" class="toggle-password" onclick="togglePassword()" aria-label="Lihat Password">
                    <i class="bi bi-eye" id="toggleIcon"></i>
                </button>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">
            Masuk Sekarang <i class="bi bi-arrow-right"></i>
        </button>
    </form>

    <script src="<?php echo asset('js/form-validator.js'); ?>"></script>
    <script>
    function togglePassword() {
        const passwordInput = document.getElementById('passwordInput');
        const toggleIcon = document.getElementById('toggleIcon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('bi-eye');
            toggleIcon.classList.add('bi-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('bi-eye-slash');
            toggleIcon.classList.add('bi-eye');
        }
    }
    </script>

    <!-- Register Link -->
    <p class="register-text">
        Belum punya akun? <a href="<?php echo url('/register'); ?>" class="register-link">Daftar Sekarang</a>
    </p>

    <!-- Divider -->
    <div class="divider"></div>

    <!-- Back Link -->
    <a href="<?php echo url('/'); ?>" class="back-link">
        <i class="bi bi-arrow-left"></i> Kembali ke Beranda
    </a>

    <!-- WhatsApp Help -->
    <div class="mt-4">
        <?php 
        $waNum = get_setting('helpdesk_wa', '6281234567890'); 
        $waMsg = urlencode("Halo, saya butuh bantuan login PPDB.");
        ?>
        <a href="https://wa.me/<?php echo $waNum; ?>?text=<?php echo $waMsg; ?>" target="_blank" class="help-btn">
            <i class="bi bi-whatsapp text-success"></i> Butuh Bantuan?
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
