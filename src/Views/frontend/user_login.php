<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <link href="../../../public/css/tailwind.css" rel="stylesheet">
</head>
<body class="bg-zinc-900 text-zinc-100">
    <div class="mx-auto max-w-screen-xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-lg">
            <h1 class="text-center text-2xl font-bold text-orange-600 sm:text-3xl">User Log-in</h1>
            <p class="mx-auto mt-4 max-w-md text-center text-zinc-300">
                Please sign in to access your profile and make reservations.
            </p>
            <form action="../../../src/Controllers/CustomerController.php?action=login" method="post" class="mb-0 mt-6 rounded-lg p-4 shadow-lg sm:p-6 lg:p-8 bg-zinc-800">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? '';?>">
                <h2 class="text-center text-lg font-medium text-zinc-200 mb-4">Sign in to your account</h2>
                <div class="space-y-4">
                    <div>
                        <label for="email" class="sr-only">Email</label>
                        <div class="relative">
                            <input
                                type="email"
                                name="email"
                                id="email"
                                class="w-full rounded-lg border-zinc-600 px-5 py-3 text-sm shadow-sm bg-zinc-700 text-zinc-100 placeholder-zinc-300 focus:outline-none focus:ring-1 focus:ring-orange-600"
                                placeholder="Email"
                                required
                            />
                        </div>
                    </div>
                    <div>
                        <label for="password" class="sr-only">Password</label>
                        <div class="relative">
                            <input
                                type="password"
                                name="password"
                                id="password"
                                autocomplete="current-password"
                                class="w-full rounded-lg border-zinc-600 px-5 py-3 text-sm shadow-sm bg-zinc-700 text-zinc-100 placeholder-zinc-300 focus:outline-none focus:ring-1 focus:ring-orange-600"
                                placeholder="Password"
                                required
                            />
                        </div>
                    </div>
                    <button type="submit" class="block w-full rounded-lg bg-orange-600 px-5 py-3 text-sm font-medium text-zinc-100 hover:bg-orange-500 transition ease-in-out duration-300">
                        Sign in
                    </button>
                    <p class="mt-4 text-center text-sm text-zinc-400">
                        No account?
                        <a class="inline-flex items-center text-orange-600 hover:text-orange-400 transition ease-in-out duration-300" href="../../Views/frontend/user_signup.php">
                            Sign up
                            <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                    </p>
                    <?php if (isset($_GET['error']) && $_GET['error'] === 'invalid_credentials'): ?>
                    <p class="text-center text-red-600 mt-4">
                        Invalid email or password, please try again.
                    </p>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
</body>
</html>