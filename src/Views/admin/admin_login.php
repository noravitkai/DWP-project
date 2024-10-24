<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="../../../public/css/tailwind.css" rel="stylesheet">
</head>
<body class="bg-zinc-900 text-zinc-100">
    <div class="mx-auto max-w-screen-xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-lg">
            <h1 class="text-center text-2xl font-bold text-orange-600 sm:text-3xl">Admin Login</h1>
            <p class="mx-auto mt-4 max-w-md text-center text-zinc-300">
                Please sign in to access the dashboard.
            </p>
            <form action="../../../src/Controllers/AdminController.php?action=login" method="post" class="mb-0 mt-6 space-y-4 rounded-lg p-4 shadow-lg sm:p-6 lg:p-8 bg-zinc-800">    
                <h2 class="text-center text-lg font-medium text-zinc-200">Sign in to your account</h2>
                <div>
                    <label for="username" class="sr-only">Username</label>
                    <div class="relative">
                        <input
                            type="text"
                            name="username"
                            id="username"
                            class="w-full rounded-lg border-zinc-600 p-4 pr-12 text-sm shadow-sm bg-zinc-700 text-zinc-100 placeholder-zinc-400"
                            placeholder="Username"
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
                            class="w-full rounded-lg border-zinc-600 p-4 pr-12 text-sm shadow-sm bg-zinc-700 text-zinc-100 placeholder-zinc-400"
                            placeholder="Password"
                            required
                        />
                    </div>
                </div>
                <button type="submit" class="block w-full rounded-lg bg-orange-600 px-3 py-2 text-sm font-medium text-zinc-100 hover:bg-orange-500 transition ease-in-out duration-300">
                    Sign in
                </button>
                <?php if (isset($_GET['error']) && $_GET['error'] === 'invalid_credentials'): ?>
                <p class="text-center text-red-600 mt-4">
                    Invalid email or password, please try again.
                </p>
            <?php endif; ?>
            </form>
        </div>
    </div>
</body>
</html>