<?php
// Auth Controller - Login/Logout
require_once ROOT_PATH . 'app/Models/User.php';

class AuthController {
    
    // Show login page
    public function showLogin() {
        if (isLoggedIn()) {
            redirect('/dashboard');
        }
        
        $data = ['title' => 'Login'];
        view('auth.login', $data);
    }

    // Process login
    public function login() {
        $nisn = $_POST['nisn'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($nisn) || empty($password)) {
            $_SESSION['error'] = 'NISN dan Password harus diisi!';
            redirect('/login');
        }

        $user = new User();
        $auth = $user->authenticate($nisn, $password);

        if ($auth) {
            $_SESSION['user_id'] = $auth['id'];
            $_SESSION['username'] = $auth['nama'];
            $_SESSION['nisn'] = $auth['nisn'];
            redirect('/dashboard');
        } else {
            $_SESSION['error'] = 'NISN atau Password salah!';
            redirect('/login');
        }
    }

    // Logout
    public function logout() {
        session_destroy();
        redirect('/login');
    }

    // Show register page
    public function showRegister() {
        if (isRegistrationClosed()) {
            return view('auth.register_closed');
        }

        if (isLoggedIn()) {
            redirect('/dashboard');
        }
        
        $data = ['title' => 'Daftar Akun'];
        view('auth.register', $data);
    }

    // Process registration
    public function register() {
        if (isRegistrationClosed()) {
            return view('auth.register_closed');
        }

        $data = [
            'nisn' => $_POST['nisn'] ?? '',
            'nik' => $_POST['nik'] ?? '',
            'nama' => $_POST['nama'] ?? '',
            'password' => $_POST['password'] ?? '',
            'email' => $_POST['email'] ?? null,
            'no_hp' => $_POST['no_hp'] ?? null
        ];

        // Validation
        if (empty($data['nisn']) || empty($data['nik']) || empty($data['nama']) || empty($data['password'])) {
            $_SESSION['error'] = 'Semua field wajib harus diisi!';
            redirect('/register');
        }

        $user = new User();
        
        // Check if NISN already exists
        if ($user->findByNisn($data['nisn'])) {
            $_SESSION['error'] = 'NISN sudah terdaftar!';
            redirect('/register');
        }

        // Create user
        $userId = $user->create($data);
        
        if ($userId) {
            $_SESSION['success'] = 'Pendaftaran berhasil! Silakan login.';
            redirect('/login');
        } else {
            $_SESSION['error'] = 'Terjadi kesalahan. Coba lagi.';
            redirect('/register');
        }
    }
}
