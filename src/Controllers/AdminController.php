<?php
session_start();
include '../../config/dbcon.php';

$action = $_GET['action'] ?? '';

if ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT UserID, Password, Role FROM User WHERE Email = :email AND Role = 'admin'");
    $stmt->execute(['email' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['Password'])) {
        $_SESSION['user_id'] = $user['UserID'];
        $_SESSION['role'] = $user['Role'];
        header("Location: ../../Views/admin/admin_dashboard.php");
        exit();
    } else {
        header("Location: ../../Views/admin/admin_login.php?error=invalid_credentials");
        exit();
    }
}
?>