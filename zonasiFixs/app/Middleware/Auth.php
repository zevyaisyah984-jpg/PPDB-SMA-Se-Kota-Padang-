<?php
// Auth Middleware - Check if user is logged in

function requireAuth() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ' . BASE_URL . 'login.php');
        exit;
    }
}

function requireAdmin() {
    if (!isset($_SESSION['admin_id'])) {
        header('Location: ' . BASE_URL . 'admin/login.php');
        exit;
    }
}

function guest() {
    if (isset($_SESSION['user_id'])) {
        header('Location: ' . BASE_URL . 'dashboard.php');
        exit;
    }
}
