<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Admin - <?php echo APP_NAME; ?></title>
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Select2 for searchable dropdown -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    
    <style>
        :root {
            --sidebar-width: 280px;
            --primary-color: #4F46E5;
            --primary-hover: #4338CA;
            --bg-light: #F9FAFB;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-light);
            overflow-x: hidden;
        }
        
        /* SIDEBAR STYLING - CRITICAL FIX */
        #admin-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: white;
            border-right: 1px solid #E5E7EB;
            display: flex;
            flex-direction: column;
            z-index: 1000;
            overflow-y: auto;
        }
        
        .sidebar-header {
            padding: 1.5rem 1.25rem;
            border-bottom: 1px solid #E5E7EB;
        }
        
        .sidebar-brand-wrapper {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: inherit;
        }
        
        .sidebar-brand-icon {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, var(--primary-color), #6366F1);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
        }
        
        .sidebar-brand-text {
            font-weight: 700;
            font-size: 0.95rem;
            line-height: 1.3;
        }
        
        .sidebar-menu-heading {
            padding: 0.5rem 1.25rem;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            color: #9CA3AF;
            letter-spacing: 0.5px;
        }
        
        .sidebar-menu-group {
            padding: 0 0.75rem;
        }
        
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            margin: 0.25rem 0;
            border-radius: 10px;
            color: #6B7280;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.2s;
        }
        
        .sidebar-link:hover {
            background: #F3F4F6;
            color: var(--primary-color);
        }
        
        .sidebar-link.active {
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.1), rgba(99, 102, 241, 0.1));
            color: var(--primary-color);
            font-weight: 600;
        }
        
        .sidebar-link i {
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
        }
        
        /* Main Content Area */
        #main-content {
            margin-left: var(--sidebar-width);
            padding: 2rem;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }
        
        /* Floating Label Inputs */
        .form-floating {
            position: relative;
        }
        
        .form-floating > .form-control,
        .form-floating > .form-select {
            height: 58px;
            padding: 1rem 0.75rem;
            border: 1.5px solid #E5E7EB;
            border-radius: 12px;
            transition: all 0.2s;
        }
        
        .form-floating > .form-control:focus,
        .form-floating > .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }
        
        .form-floating > label {
            padding: 1rem 0.75rem;
            color: #6B7280;
            font-weight: 500;
        }
        
        /* Password Toggle */
        .password-wrapper {
            position: relative;
        }
        
        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #6B7280;
            cursor: pointer;
            padding: 8px;
            z-index: 10;
            transition: color 0.2s;
        }
        
        .password-toggle:hover {
            color: var(--primary-color);
        }
        
        /* Select2 Custom Styling */
        .select2-container--bootstrap-5 .select2-selection {
            height: 58px !important;
            padding: 1rem 0.75rem !important;
            border: 1.5px solid #E5E7EB !important;
            border-radius: 12px !important;
        }
        
        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
            line-height: 26px !important;
            padding-left: 0 !important;
        }
        
        .select2-container--bootstrap-5.select2-container--focus .select2-selection {
            border-color: var(--primary-color) !important;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1) !important;
        }
        
        /* Card Styling */
        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }
        
        /* Button Styling */
        .btn-primary {
            background: var(--primary-color);
            border: none;
            padding: 12px 32px;
            font-weight: 600;
            border-radius: 12px;
            transition: all 0.2s;
        }
        
        .btn-primary:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }
        
        /* Username Validation Feedback */
        .validation-feedback {
            font-size: 0.875rem;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .validation-feedback.valid {
            color: #10B981;
        }
        
        .validation-feedback.invalid {
            color: #EF4444;
        }
        
        .validation-feedback .spinner-border {
            width: 1rem;
            height: 1rem;
            border-width: 2px;
        }
        
        /* Responsive */
        @media (max-width: 991px) {
            #admin-sidebar {
                transform: translateX(-100%);
            }
            
            #main-content {
                margin-left: 0;
                padding: 1.5rem 1rem;
            }
        }
    </style>
</head>
<body>
    
    <?php view('admin.partials.sidebar'); ?>

    <!-- Main Content -->
    <main id="main-content">
        <!-- Header -->
        <div class="d-flex align-items-center mb-4">
            <a href="<?php echo url('/admin/users'); ?>" class="btn btn-light rounded-circle me-3 shadow-sm" style="width: 44px; height: 44px; display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-arrow-left fs-5"></i>
            </a>
            <div>
                <h3 class="fw-bold mb-1">Tambah Admin Baru</h3>
                <p class="text-muted small mb-0">Isi formulir untuk menambahkan admin baru ke sistem</p>
            </div>
        </div>

        <!-- Error Alert -->
        <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger d-flex align-items-center mb-4 rounded-4">
            <i class="bi bi-exclamation-circle-fill me-2 fs-5"></i>
            <div><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        </div>
        <?php endif; ?>

        <!-- Form Card -->
        <div class="card shadow-sm">
            <div class="card-body p-4 p-md-5">
                <form action="<?php echo url('/admin/users/tambah'); ?>" method="POST" id="addAdminForm">
                    <div class="row g-4">
                        
                        <!-- Nama Lengkap - Floating Label -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" name="nama" id="nama" class="form-control" placeholder="Nama Lengkap" required>
                                <label for="nama"><i class="bi bi-person me-2"></i>Nama Lengkap</label>
                            </div>
                        </div>

                        <!-- Username - Floating Label with Real-time Validation -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" name="username" id="username" class="form-control" placeholder="Username" required>
                                <label for="username"><i class="bi bi-at me-2"></i>Username</label>
                            </div>
                            <div id="usernameValidation" class="validation-feedback" style="display: none;"></div>
                        </div>

                        <!-- Password - Floating Label with Show/Hide -->
                        <div class="col-md-6">
                            <div class="form-floating password-wrapper">
                                <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                                <label for="password"><i class="bi bi-lock me-2"></i>Password</label>
                                <button type="button" class="password-toggle" id="togglePassword">
                                    <i class="bi bi-eye" id="eyeIcon"></i>
                                </button>
                            </div>
                            <small class="text-muted mt-1 d-block">Minimal 6 karakter</small>
                        </div>

                        <!-- Role - Floating Label -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select name="role" id="roleSelect" class="form-select" required>
                                    <option value="">Pilih Role</option>
                                    <option value="school_admin">Admin Sekolah</option>
                                    <option value="super_admin">Super Admin</option>
                                </select>
                                <label for="roleSelect"><i class="bi bi-shield-check me-2"></i>Role</label>
                            </div>
                        </div>

                        <!-- Sekolah - Select2 Searchable (Conditional) -->
                        <div class="col-12" id="sekolahContainer" style="display: none;">
                            <label class="form-label fw-semibold mb-2">
                                <i class="bi bi-building me-2"></i>Sekolah
                            </label>
                            <select name="sekolah_id" id="sekolahSelect" class="form-select">
                                <option value="">-- Pilih Sekolah --</option>
                                <?php foreach ($sekolah_list as $s): ?>
                                    <option value="<?php echo $s['id']; ?>"><?php echo htmlspecialchars($s['nama']); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-muted mt-2 d-block">
                                <i class="bi bi-info-circle me-1"></i>Ketik untuk mencari sekolah
                            </small>
                        </div>

                        <!-- Submit Button -->
                        <div class="col-12 text-end mt-4">
                            <button type="submit" class="btn btn-primary px-5" id="submitBtn">
                                <i class="bi bi-check-circle me-2"></i>Simpan Admin
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Initialize Select2 for Sekolah dropdown
            $('#sekolahSelect').select2({
                theme: 'bootstrap-5',
                placeholder: '-- Cari dan Pilih Sekolah --',
                allowClear: true,
                width: '100%'
            });

            // Toggle Sekolah field based on Role
            const roleSelect = $('#roleSelect');
            const sekolahContainer = $('#sekolahContainer');
            const sekolahSelect = $('#sekolahSelect');

            function toggleSekolahField() {
                if (roleSelect.val() === 'school_admin') {
                    sekolahContainer.slideDown(300);
                    sekolahSelect.prop('required', true);
                } else {
                    sekolahContainer.slideUp(300);
                    sekolahSelect.prop('required', false);
                    sekolahSelect.val(null).trigger('change');
                }
            }

            roleSelect.on('change', toggleSekolahField);
            toggleSekolahField(); // Run on init

            // Password Show/Hide Toggle
            $('#togglePassword').on('click', function() {
                const passwordInput = $('#password');
                const eyeIcon = $('#eyeIcon');
                
                if (passwordInput.attr('type') === 'password') {
                    passwordInput.attr('type', 'text');
                    eyeIcon.removeClass('bi-eye').addClass('bi-eye-slash');
                } else {
                    passwordInput.attr('type', 'password');
                    eyeIcon.removeClass('bi-eye-slash').addClass('bi-eye');
                }
            });

            // Real-time Username Validation
            let usernameTimeout;
            $('#username').on('input', function() {
                const username = $(this).val().trim();
                const validationDiv = $('#usernameValidation');

                if (username.length < 3) {
                    validationDiv.hide();
                    return;
                }

                clearTimeout(usernameTimeout);
                
                // Show loading
                validationDiv.html('<span class="spinner-border spinner-border-sm"></span> Memeriksa ketersediaan...').show();

                usernameTimeout = setTimeout(function() {
                    // AJAX check username availability
                    $.ajax({
                        url: '<?php echo url('/admin/users/check-username'); ?>',
                        method: 'POST',
                        data: { username: username },
                        dataType: 'json',
                        success: function(response) {
                            if (response.available) {
                                validationDiv.removeClass('invalid').addClass('valid')
                                    .html('<i class="bi bi-check-circle-fill"></i> Username tersedia');
                            } else {
                                validationDiv.removeClass('valid').addClass('invalid')
                                    .html('<i class="bi bi-x-circle-fill"></i> Username sudah digunakan');
                            }
                        },
                        error: function() {
                            validationDiv.removeClass('valid invalid')
                                .html('<i class="bi bi-exclamation-triangle"></i> Gagal memeriksa username');
                        }
                    });
                }, 500); // Debounce 500ms
            });

            // Form Validation on Submit
            $('#addAdminForm').on('submit', function(e) {
                const usernameValidation = $('#usernameValidation');
                
                if (usernameValidation.hasClass('invalid')) {
                    e.preventDefault();
                    alert('Username sudah digunakan. Silakan pilih username lain.');
                    return false;
                }

                // Disable submit button to prevent double submission
                $('#submitBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...');
            });
        });
    </script>
</body>
</html>
