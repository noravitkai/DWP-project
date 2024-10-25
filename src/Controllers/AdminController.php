<?php
require_once '../../config/session.php';
include '../../config/dbcon.php';
require_once '../../config/functions.php';

$pdo = dbCon();

$action = sanitizeInput($_GET['action'] ?? '');

$allowedActions = ['login', 'logout'];
if (!in_array($action, $allowedActions, true)) {
    header("Location: ../../src/Views/admin/admin_login.php?error=invalid_action");
    exit();
}

if ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitizeInput($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT UserID, Password, Role FROM User WHERE Email = :email AND Role = 'admin'");
    $stmt->execute(['email' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['Password'])) {
        $_SESSION['user_id'] = $user['UserID'];
        $_SESSION['role'] = $user['Role'];

        $_SESSION['session_hash'] = generateSessionHash();

        header("Location: ../../src/Views/admin/admin_dashboard.php");
        exit();
    } else {
        header("Location: ../../src/Views/admin/admin_login.php?error=invalid_credentials");
        exit();
    }
}

if ($action === 'logout') {
    session_unset();
    session_destroy();
    header("Location: ../../src/Views/admin/admin_login.php");
    exit();
}