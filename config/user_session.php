<?php
require_once 'session.php';


function requireLogin() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../../Views/frontend/user_login.php");
        exit();
    }
}