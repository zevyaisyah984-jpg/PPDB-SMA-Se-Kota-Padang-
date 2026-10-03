<?php
// Redirect Helper Functions

// Redirect to URL
function redirectTo($path) {
    header('Location: ' . BASE_URL . ltrim($path, '/'));
    exit;
}

// Redirect back
function redirectBack() {
    $referer = $_SERVER['HTTP_REFERER'] ?? BASE_URL;
    header('Location: ' . $referer);
    exit;
}

// Redirect with message
function redirectWithMessage($path, $type, $message) {
    $_SESSION['flash'][$type] = $message;
    redirectTo($path);
}
