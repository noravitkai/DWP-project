<?php
ini_set('session.cookie_httponly', 1);

session_start();

define('SECRET_KEY', '1fc92767d2c74bc0c4c889ac688d11cc3050e7a356818cb45fa5b666cc74c766'); 

function generateSessionHash() {
    return hash_hmac('sha256', session_id(), SECRET_KEY);
}

if (!isset($_SESSION['session_hash'])) {
    $_SESSION['session_hash'] = generateSessionHash();
} elseif ($_SESSION['session_hash'] !== generateSessionHash()) {
    session_unset();
    session_destroy();
    exit();
}

if (!isset($_SESSION['created']) || time() - $_SESSION['created'] > 300 ) {
    session_regenerate_id(true);
    $_SESSION['created'] = time();
    $_SESSION['session_hash'] = generateSessionHash();
}

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}