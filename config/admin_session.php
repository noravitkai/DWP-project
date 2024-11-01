<?php
require_once 'session.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../Views/admin/admin_login.php");
    exit();
}