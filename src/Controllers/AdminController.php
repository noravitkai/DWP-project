<?php
require_once '../../config/session.php';
require_once '../../config/functions.php';
require_once '../../config/dbcon.php';

$pdo = dbCon();

$action = isset($_GET['action']) ? sanitizeInput($_GET['action']) : '';

$allowedActions = ['login', 'logout'];
if (!in_array($action, $allowedActions, true)) {
    header("Location: ../../src/Views/admin/admin_login.php");
    exit();
}

if ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !verifyCsrfToken($_POST['csrf_token'])) {
        header("Location: ../../src/Views/admin/admin_login.php");
        exit();
    }

    $email = isset($_POST['email']) ? sanitizeInput($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../../src/Views/admin/admin_login.php");
        exit();
    }

    $stmt = $pdo->prepare("SELECT AdminID, Password FROM Admin WHERE Email = :email");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && password_verify($password, $admin['Password'])) {
        session_regenerate_id(true);
        
        $_SESSION['admin_id'] = $admin['AdminID'];
        $_SESSION['admin_email'] = $email;
        
        $_SESSION['session_hash'] = generateSessionHash();
        regenerateCsrfToken();
        
        header("Location: ../../src/Views/admin/admin_dashboard.php");
        exit();
    } else {
        header("Location: ../../src/Views/admin/admin_login.php");
        exit();
    }
}

if ($action === 'logout') {
    session_unset();
    session_destroy();
    header("Location: ../../src/Views/admin/admin_login.php");
    exit();
}
?>