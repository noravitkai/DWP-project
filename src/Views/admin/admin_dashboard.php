<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="../../../public/css/tailwind.css" rel="stylesheet">
</head>
<body class="bg-zinc-900 text-zinc-100">
    <div class="container mx-auto py-12">
        <h1 class="text-3xl font-bold text-center text-orange-600">Admin Dashboard</h1>
        <p class="text-center text-zinc-300 mt-4">Welcome, Admin! Here you can manage content and settings.</p>
    </div>
</body>
</html>