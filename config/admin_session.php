<?php
require_once 'session.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../../Views/admin/admin_login.php");
    exit();
}