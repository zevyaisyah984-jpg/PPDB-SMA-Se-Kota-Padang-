<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - <?php echo APP_NAME; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4F46E5;
            --primary-hover: #4338CA;
            --bg-gradient: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f3f4f6;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow-x: hidden;
            position: relative;
        }
        
        /* Dynamic Background Pattern */
        .bg-pattern {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background-color: #4F46E5;
            background-image: 
                radial-gradient(at 0% 0%, hsla(253,16%,7%,1) 0, transparent 50%), 
                radial-gradient(at 50% 0%, hsla(225,39%,30%,1) 0, transparent 50%), 
                radial-gradient(at 100% 0%, hsla(339,49%,30%,1) 0, transparent 50%);
            z-index: -1;
        }
        
        /* Floating Bubbles Animation */
        .circles {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            overflow: hidden;
            z-index: -1;
        }
        .circles li {
            position: absolute;
            display: block;
            list-style: none;
            width: 20px; height: 20px;
            background: rgba(255, 255, 255, 0.1);
            animation: animate 25s linear infinite;
            bottom: -150px;
            border-radius: 50%;
        }
        .circles li:nth-child(1) { left: 25%; width: 80px; height: 80px; animation-delay: 0s; }
        .circles li:nth-child(2) { left: 10%; width: 20px; height: 20px; animation-delay: 2s; animation-duration: 12s; }
        .circles li:nth-child(3) { left: 70%; width: 20px; height: 20px; animation-delay: 4s; }
        .circles li:nth-child(4) { left: 40%; width: 60px; height: 60px; animation-delay: 0s; animation-duration: 18s; }
        .circles li:nth-child(5) { left: 65%; width: 20px; height: 20px; animation-delay: 0s; }
        @keyframes animate {
            0% { transform: translateY(0) rotate(0deg); opacity: 1; border-radius: 0; }
            100% { transform: translateY(-1000px) rotate(720deg); opacity: 0; border-radius: 50%; }
        }

        /* Card Styling */
        .register-card {
            width: 100%;
            max-width: 520px;
            background: rgba(255, 255, 255, 1);
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            animation: fadeInUp 0.6s ease-out;
        }
        
        .logo-container img {
            filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1));
            transition: transform 0.3s ease;
        }
        .logo-container img:hover { transform: scale(1.05); }
        
        .form-floating > .form-control {
            border-radius: 12px;
            border: 2px solid #e5e7eb;
            padding-left: 1rem;
        }
        .form-floating > .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }
        .form-floating > label { opacity: 0.7; }
        
        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            padding: 12px;
            font-weight: 600;
            border-radius: 12px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .btn-primary:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3);
        }
        
        .links a {
            color: var(--primary-color);
            font-weight: 600;
            transition: color 0.2s;
        }
        .links a:hover { color: var(--primary-hover); text-decoration: underline !important; }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Password Toggle Style */
        .password-toggle {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #6c757d;
            cursor: pointer;
            z-index: 10;
            padding: 0;
            transition: color 0.2s;
        }
        .password-toggle:hover { color: var(--primary-color); }
    </style>
</head>
<body>
    <div class="bg-pattern"></div>
    <ul class="circles">
        <li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li>
    </ul>

    <div class="container p-3">
        <div class="row justify-content-center">
            <div class="col-12 d-flex justify-content-center">
                <div class="register-card">
                    <div class="text-center mb-4 logo-container">
                        <img src="<?php echo asset('images/logo_kemdikbud.png'); ?>" alt="Logo" width="70" height="70" class="mb-3">
                        <h4 class="fw-bold mb-1 text-dark">Buat Akun Baru</h4>
                        <p class="text-muted small">Lengkapi data diri untuk memulai pendaftaran PPDB</p>
                    </div>
                    
                    <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger d-flex align-items-center mb-4 rounded-3">
                        <i class="bi bi-exclamation-circle-fill me-2 fs-5"></i>
                        <small><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></small>
                    </div>
                    <?php endif; ?>
                    
                    <form action="<?php echo url('/register'); ?>" method="POST" autocomplete="off" id="registerForm">
                        <?php echo csrf_field(); ?>
                        
                        <!-- Hack to prevent Chrome valid autofill -->
                        <input type="text" style="display:none" autocomplete="false" name="fakeusernameremembered">
                        <input type="password" style="display:none" autocomplete="false" name="fakepasswordremembered">
                        
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="nisn" id="nisn" placeholder="NISN" required maxlength="10" autocomplete="off" inputmode="numeric" data-validate="nisn">
                                    <label for="nisn">NISN (10 Digit)</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="nik" id="nik" placeholder="NIK" required maxlength="16" autocomplete="off" inputmode="numeric" data-validate="nik">
                                    <label for="nik">NIK (16 Digit)</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="nama" id="nama" placeholder="Nama Lengkap" required autocomplete="off" minlength="3">
                            <label for="nama">Nama Lengkap Sesuai Ijazah</label>
                        </div>

                        <div class="form-floating mb-4 position-relative">
                            <input type="password" class="form-control" name="password" id="password" placeholder="Password" required minlength="8" autocomplete="new-password" style="padding-right: 50px;" data-validate="password">
                            <label for="password">Password (Min. 8 Karakter)</label>
                            <button type="button" class="password-toggle" onclick="toggleRegisterPassword()" aria-label="Lihat Password">
                                <i class="bi bi-eye" id="toggleIconReg"></i>
                            </button>
                        </div>

                        <div class="d-flex gap-2 mb-4">
                            <button type="submit" class="btn btn-primary flex-grow-1 shadow-lg">
                                Daftar Sekarang <i class="bi bi-person-plus-fill ms-2"></i>
                            </button>
                            <button type="reset" class="btn btn-light bg-light border" title="Bersihkan Form" style="width: 50px;">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </button>
                        </div>

                        <div class="text-center links">
                            <p class="mb-2 text-muted small">
                                Sudah memiliki akun? <a href="<?php echo url('/login'); ?>" class="text-decoration-none">Login Disini</a>
                            </p>
                            <hr class="my-4 opacity-10">
                            <a href="<?php echo url('/'); ?>" class="text-secondary text-decoration-none small d-flex align-items-center justify-content-center transition-hover">
                                <i class="bi bi-arrow-left-short fs-5 me-1"></i> Kembali ke Halaman Utama
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo asset('js/form-validator.js'); ?>"></script>
    <script>
        function toggleRegisterPassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIconReg');
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
    <!-- Closed Modal -->
    <?php view('portal.partials.closed_modal'); ?>
</body>
</html>

