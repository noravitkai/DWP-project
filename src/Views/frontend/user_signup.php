<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Signup</title>
    <link href="../../../public/css/tailwind.css" rel="stylesheet">
</head>
<body class="bg-zinc-900 text-zinc-100">
    <main class="mx-auto max-w-screen-xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-2xl">
            <h1 class="text-center text-2xl font-bold text-orange-600 sm:text-3xl">User Sign-up</h1>
            <p class="mx-auto mt-4 max-w-lg text-center text-zinc-300">
                Create an account to make reservations and access your profile.
            </p>
            <form action="../../../src/Controllers/CustomerController.php?action=register" method="post" class="mb-0 mt-6 rounded-lg p-6 shadow-lg sm:p-8 lg:p-10 bg-zinc-800">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? '';?>">
                <p class="text-sm text-zinc-400 mb-4">
                    Fields marked with * are required to complete. Password must be at least 8 characters long.
                </p>
                <div class="space-y-4 mb-4">
                    <h2 class="text-lg font-medium text-zinc-200">Account Details</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <input type="email" name="email" id="email" placeholder="Email *" required autocomplete="email" class="w-full rounded-lg border-zinc-600 px-5 py-3 text-sm shadow-sm bg-zinc-700 text-zinc-100 placeholder-zinc-300 focus:outline-none focus:ring-1 focus:ring-orange-600">
                        </div>
                        <div>
                            <input type="password" name="password" id="password" placeholder="Password *" required minlength="8" autocomplete="new-password" class="w-full rounded-lg border-zinc-600 px-5 py-3 text-sm shadow-sm bg-zinc-700 text-zinc-100 placeholder-zinc-300 focus:outline-none focus:ring-1 focus:ring-orange-600">
                        </div>
                    </div>
                </div>
                <div class="space-y-4 mb-4">
                    <h2 class="text-lg font-medium text-zinc-200">Personal Information</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <input type="text" name="first_name" id="first_name" placeholder="First Name *" required autocomplete="given-name" class="w-full rounded-lg border-zinc-600 px-5 py-3 text-sm shadow-sm bg-zinc-700 text-zinc-100 placeholder-zinc-300 focus:outline-none focus:ring-1 focus:ring-orange-600">
                        </div>
                        <div>
                            <input type="text" name="last_name" id="last_name" placeholder="Last Name *" required autocomplete="family-name" class="w-full rounded-lg border-zinc-600 px-5 py-3 text-sm shadow-sm bg-zinc-700 text-zinc-100 placeholder-zinc-300 focus:outline-none focus:ring-1 focus:ring-orange-600">
                        </div>
                        <div>
                            <input type="tel" name="phone" id="phone" placeholder="Phone Number" autocomplete="tel" class="w-full rounded-lg border-zinc-600 px-5 py-3 text-sm shadow-sm bg-zinc-700 text-zinc-100 placeholder-zinc-300 focus:outline-none focus:ring-1 focus:ring-orange-600">
                        </div>
                        <div>
                            <input type="text" name="country" id="country" placeholder="Country *" required autocomplete="country-name" class="w-full rounded-lg border-zinc-600 px-5 py-3 text-sm shadow-sm bg-zinc-700 text-zinc-100 placeholder-zinc-300 focus:outline-none focus:ring-1 focus:ring-orange-600">
                        </div>
                        <div>
                            <input type="text" name="street" id="street" placeholder="Street" autocomplete="address-line1" class="w-full rounded-lg border-zinc-600 px-5 py-3 text-sm shadow-sm bg-zinc-700 text-zinc-100 placeholder-zinc-300 focus:outline-none focus:ring-1 focus:ring-orange-600">
                        </div>
                        <div>
                            <input type="text" name="suite" id="suite" placeholder="Suite Number" autocomplete="address-line2" class="w-full rounded-lg border-zinc-600 px-5 py-3 text-sm shadow-sm bg-zinc-700 text-zinc-100 placeholder-zinc-300 focus:outline-none focus:ring-1 focus:ring-orange-600">
                        </div>
                        <div>
                            <input type="text" name="city" id="city" placeholder="City" autocomplete="address-level2" class="w-full rounded-lg border-zinc-600 px-5 py-3 text-sm shadow-sm bg-zinc-700 text-zinc-100 placeholder-zinc-300 focus:outline-none focus:ring-1 focus:ring-orange-600">
                        </div>
                        <div>
                            <input type="text" name="postal_code" id="postal_code" placeholder="Postal Code *" required autocomplete="postal-code" class="w-full rounded-lg border-zinc-600 px-5 py-3 text-sm shadow-sm bg-zinc-700 text-zinc-100 placeholder-zinc-300 focus:outline-none focus:ring-1 focus:ring-orange-600">
                        </div>
                    </div>
                </div>
                    <div class="text-center">
                    <button type="submit" class="rounded-lg bg-orange-600 px-6 py-3 text-sm font-medium text-zinc-100 hover:bg-orange-500 transition ease-in-out duration-300">
                        Sign up
                    </button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>