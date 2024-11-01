<?php
session_start();

require_once '../../config/dbcon.php';
require_once '../../config/functions.php';

$pdo = dbCon();

$action = sanitizeInput($_GET['action'] ?? '');

$allowedActions = ['login', 'logout'];
if (!in_array($action, $allowedActions, true)) {
    header("Location: ../../src/Views/admin/admin_login.php?error=invalid_action");
    exit();
}

if ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitizeInput($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT AdminID, Password FROM Admin WHERE Email = :email");
    $stmt->execute(['email' => $email]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && password_verify($password, $admin['Password'])) {
        $_SESSION['admin_id'] = $admin['AdminID'];
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