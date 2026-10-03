<?php
// Role Middleware - Check user role permissions

function hasRole($role) {
    return isset($_SESSION['role']) && $_SESSION['role'] === $role;
}

function requireRole($role) {
    if (!hasRole($role)) {
        http_response_code(403);
        die('Forbidden: You do not have permission to access this page.');
    }
}

function isSuperAdmin() {
    return isset($_SESSION['admin_role']) && $_SESSION['admin_role'] === 'super_admin';
}

function isOperator() {
    return isset($_SESSION['admin_role']) && $_SESSION['admin_role'] === 'operator_sekolah';
}

function isVerifikator() {
    return isset($_SESSION['admin_role']) && $_SESSION['admin_role'] === 'verifikator';
}
