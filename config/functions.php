<?php
function sanitizeInput($value) {
    return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
}

function verifyCsrfToken($token) {
    if (!isset($_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

function regenerateCsrfToken() {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function getFallbackImage($imageUrl, $defaultImagePath) {
    $imageUrl = trim($imageUrl);

    if (!empty($imageUrl)) {
        $fullImagePath = $_SERVER['DOCUMENT_ROOT'] . '/' . ltrim($imageUrl, '/');

        if (file_exists($fullImagePath)) {
            return '/' . ltrim($imageUrl, '/');
        }
    }

    return '/' . ltrim($defaultImagePath, '/');
}
?>