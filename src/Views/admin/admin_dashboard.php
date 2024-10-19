<?php
include '../../../config/session.php';
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
        <div class="mt-8 text-center">
            <a href="../../../src/Controllers/AdminController.php?action=logout" class="inline-block rounded-lg bg-orange-600 px-5 py-3 text-sm font-medium text-white hover:bg-orange-500 transition ease-in-out duration-300">
                Logout
            </a>
        </div>
    </div>
</body>
</html>