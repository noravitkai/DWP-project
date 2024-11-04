<?php
require_once '../../../config/user_session.php';

requireLogin();

$userName = $_SESSION['user_name'] ?? 'User';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link href="../../../public/css/tailwind.css" rel="stylesheet">
</head>
<body class="bg-zinc-900 text-zinc-100">
    <div class="mx-auto max-w-screen-xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-lg text-center">
            <h1 class="text-2xl font-bold text-orange-600 sm:text-3xl">Welcome, <?php echo $userName; ?>!</h1>
            <p class="mt-4 text-lg text-zinc-300">
                You are successfully logged in.
            </p>
            <a href="../../../src/Controllers/CustomerController.php?action=logout" class="inline-block mt-6 rounded-lg bg-orange-600 px-5 py-3 text-sm font-medium text-zinc-100 hover:bg-orange-500 transition ease-in-out duration-300">
                Log Out
            </a>
        </div>
    </div>
</body>
</html>