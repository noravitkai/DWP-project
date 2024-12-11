<?php
require_once '../../../config/user_session.php';
require_once '../../../config/functions.php';

if (!isset($_SESSION['csrf_token'])) {
    regenerateCsrfToken();
}

$currentPage = basename($_SERVER['PHP_SELF']);
$navBase = 'home_page.php#';

if ($currentPage === 'home_page.php') {
    $navLink = '#';
} else {
    $navLink = 'home_page.php#';
}
?>

<header class="bg-zinc-900 sticky top-0 z-50">
    <div class="mx-auto flex h-16 max-w-screen-xl items-center justify-between px-4 sm:px-6 lg:px-8">
        <button type="button" class="-m-2.5 p-2.5 text-zinc-100 md:hidden" id="frontend-open-menu" aria-label="Open sidebar" aria-expanded="false">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-6 w-6">
                <path fill-rule="evenodd" d="M3 6.75A.75.75 0 0 1 3.75 6h16.5a.75.75 0 0 1 0 1.5H3.75A.75.75 0 0 1 3 6.75ZM3 12a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5H3.75A.75.75 0 0 1 3 12Zm0 5.25a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5H3.75a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" />
            </svg>
        </button>
        <a href="home_page.php" class="text-right md:text-left">
            <h1 class="text-orange-600 font-black text-lg sm:text-3xl tracking-wide drop-shadow-md">
                FLC
            </h1>
        </a>
        <div class="hidden md:flex items-center space-x-8">
            <nav aria-label="Global">
                <ul class="flex items-center gap-6 text-sm">
                    <li>
                        <a href="<?php echo ($currentPage === 'home_page.php') ? '#screenings' : $navLink . 'screenings'; ?>" class="text-zinc-300 hover:text-orange-600 transition ease-in-out duration-300">
                            Screenings
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo ($currentPage === 'home_page.php') ? '#news' : $navLink . 'news'; ?>" class="text-zinc-300 hover:text-orange-600 transition ease-in-out duration-300">
                            News
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo ($currentPage === 'home_page.php') ? '#movies' : $navLink . 'movies'; ?>" class="text-zinc-300 hover:text-orange-600 transition ease-in-out duration-300">
                            Movies
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo ($currentPage === 'home_page.php') ? '#contact' : $navLink . 'contact'; ?>" class="text-zinc-300 hover:text-orange-600 transition ease-in-out duration-300">
                            Contact
                        </a>
                    </li>
                </ul>
            </nav>
            <div class="flex items-center gap-4">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="profile_page.php" class="inline-flex items-center justify-center rounded-lg bg-orange-600 px-3 py-2 text-sm font-medium text-zinc-100 hover:bg-orange-500 transition ease-in-out duration-300">
                        Profile
                    </a>
                    <a href="../../../src/Controllers/CustomerController.php?action=logout" class="inline-flex items-center justify-center rounded-lg bg-orange-600 px-3 py-2 text-sm font-medium text-zinc-100 hover:bg-orange-500 transition ease-in-out duration-300">
                        Sign Out
                    </a>
                <?php else: ?>
                    <a href="user_login.php" class="inline-flex items-center justify-center rounded-lg bg-orange-600 px-3 py-2 text-sm font-medium text-zinc-100 hover:bg-orange-500 transition ease-in-out duration-300">
                        Log in
                    </a>
                    <a href="user_signup.php" class="inline-flex items-center justify-center rounded-lg bg-orange-600 px-3 py-2 text-sm font-medium text-zinc-100 hover:bg-orange-500 transition ease-in-out duration-300">
                        Sign up
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>
<nav class="relative z-50" aria-label="Sidebar">
    <div id="frontend-hamburger-menu" class="lg:hidden fixed inset-0 z-50 hidden transition-opacity" role="dialog" aria-modal="true">
        <div class="fixed inset-0 flex transition-transform ease-in-out duration-300 transform" id="frontend-menu-container">
            <div class="relative mr-16 flex w-full max-w-xs flex-1">
                <div class="absolute left-full top-0 flex w-16 justify-center pt-5">
                    <button type="button" class="-m-2.5 p-2.5" id="frontend-close-menu" aria-label="Close sidebar">
                        <svg class="h-6 w-6 text-zinc-100" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="flex grow flex-col gap-y-4 overflow-y-auto bg-zinc-900 px-4 pb-4 ring-1 ring-zinc-100/10">
                    <div class="mt-5">
                        <h2 class="text-orange-600 text-lg font-semibold">Fast Lane Cine</h2>
                    </div>
                    <ul class="flex flex-1 flex-col gap-y-2">
                        <li>
                            <ul class="-mx-2 space-y-1">
                                <li>
                                    <a href="<?php echo ($currentPage === 'home_page.php') ? '#screenings' : $navLink . 'screenings'; ?>" class="frontend-hamburger-menu-link group flex items-center rounded-md p-1 text-sm font-semibold leading-6 text-zinc-100 hover:text-orange-600 transition duration-200">
                                        Screenings
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo ($currentPage === 'home_page.php') ? '#news' : $navLink . 'news'; ?>" class="frontend-hamburger-menu-link group flex items-center rounded-md p-1 text-sm font-semibold leading-6 text-zinc-100 hover:text-orange-600 transition duration-200">
                                        News
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo ($currentPage === 'home_page.php') ? '#movies' : $navLink . 'movies'; ?>" class="frontend-hamburger-menu-link group flex items-center rounded-md p-1 text-sm font-semibold leading-6 text-zinc-100 hover:text-orange-600 transition duration-200">
                                        Movies
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo ($currentPage === 'home_page.php') ? '#contact' : $navLink . 'contact'; ?>" class="frontend-hamburger-menu-link group flex items-center rounded-md p-1 text-sm font-semibold leading-6 text-zinc-100 hover:text-orange-600 transition duration-200">
                                        Contact
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <div class="mt-auto mb-2 flex flex-col space-y-4 items-start max-w-max">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="profile_page.php" class="inline-flex items-center justify-center rounded-lg bg-orange-600 px-3 py-2 text-sm font-medium text-zinc-100 hover:bg-orange-500 transition ease-in-out duration-300 w-full">
                                Profile
                            </a>
                            <form action="../../Controllers/CustomerController.php?action=logout" method="POST" class="inline">
                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                                <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-orange-600 px-3 py-2 text-sm font-medium text-zinc-100 hover:bg-orange-500 transition ease-in-out duration-300 w-full">
                                    Sign Out
                                </button>
                            </form>
                        <?php else: ?>
                            <a href="user_login.php" class="inline-flex items-center justify-center rounded-lg bg-orange-600 px-3 py-2 text-sm font-medium text-zinc-100 hover:bg-orange-500 transition ease-in-out duration-300 w-full">
                                Log in
                            </a>
                            <a href="user_signup.php" class="inline-flex items-center justify-center rounded-lg bg-orange-600 px-3 py-2 text-sm font-medium text-zinc-100 hover:bg-orange-500 transition ease-in-out duration-300 w-full">
                                Sign up
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
<div id="frontend-backdrop" class="fixed inset-0 z-40 bg-zinc-800/80 hidden opacity-0 transition-opacity ease-linear duration-300" aria-hidden="true"></div>
<script src="../../../public/js/navigation.js"></script>