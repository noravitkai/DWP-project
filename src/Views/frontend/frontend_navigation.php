<header class="bg-zinc-900 sticky top-0 z-50">
  <div class="mx-auto flex h-16 max-w-screen-xl items-center gap-8 px-4 sm:px-6 lg:px-8">
    <a href="home_page.php">
      <h1 class="text-orange-600 font-black sm:text-3xl tracking-wide drop-shadow-md">
        FLC
      </h1>
    </a>

    <div class="flex flex-1 items-center justify-end md:justify-between">
      <nav aria-label="Global" class="hidden md:block">
        <ul class="flex items-center gap-6 text-sm">
          <li><a class="text-zinc-300 hover:text-orange-600 transition ease-in-out duration-300" href="#daily-showings">Screenings</a></li>
          <li><a class="text-zinc-300 hover:text-orange-600 transition ease-in-out duration-300" href="#news">News</a></li>
          <li><a class="text-zinc-300 hover:text-orange-600 transition ease-in-out duration-300" href="#movie-collection">Movies</a></li>
          <li><a class="text-zinc-300 hover:text-orange-600 transition ease-in-out duration-300" href="#about">About</a></li>
        </ul>
      </nav>
      <div class="hidden md:flex items-center gap-4">
        <?php if (isset($_SESSION['user_id'])): ?>
          <a class="inline-block rounded-lg bg-orange-600 px-3 py-2 text-sm font-medium text-zinc-100 hover:bg-orange-500 transition ease-in-out duration-300" href="profile_page.php">
            Profile
          </a>
        <?php else: ?>
          <a class="inline-block rounded-lg bg-orange-600 px-3 py-2 text-sm font-medium text-zinc-100 hover:bg-orange-500 transition ease-in-out duration-300" href="user_login.php">
            Login
          </a>
          <a class="inline-block rounded-lg bg-orange-600 px-3 py-2 text-sm font-medium text-zinc-100 hover:bg-orange-500 transition ease-in-out duration-300" href="user_signup.php">
            Sign up
          </a>
        <?php endif; ?>
      </div>
      <div class="relative md:hidden">
        <button id="menu-toggle" class="block p-2.5 text-zinc-300 hover:text-orange-600 transition ease-in-out duration-300">
          <span class="sr-only">Toggle menu</span>
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
            <path fill-rule="evenodd" d="M3 6.75A.75.75 0 0 1 3.75 6h16.5a.75.75 0 0 1 0 1.5H3.75A.75.75 0 0 1 3 6.75ZM3 12a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5H3.75A.75.75 0 0 1 3 12Zm0 5.25a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5H3.75a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" />
          </svg>
        </button>
        <div id="dropdown-menu" class="absolute right-0 top-12 w-48 bg-zinc-900 shadow-lg hidden drop-shadow-2xl">
          <ul class="flex flex-col gap-4 p-4">
            <li><a class="text-zinc-300 hover:text-orange-600 transition ease-in-out duration-300" href="#daily-showings">Screenings</a></li>
            <li><a class="text-zinc-300 hover:text-orange-600 transition ease-in-out duration-300" href="#news">News</a></li>
            <li><a class="text-zinc-300 hover:text-orange-600 transition ease-in-out duration-300" href="#movie-collection">Movies</a></li>
            <li><a class="text-zinc-300 hover:text-orange-600 transition ease-in-out duration-300" href="#about">About</a></li>
            <?php if (isset($_SESSION['user_id'])): ?>
              <li>
                <a class="block rounded-lg text-center bg-orange-600 px-3 py-2 text-sm font-medium text-zinc-100 hover:bg-orange-500 transition ease-in-out duration-300" href="profile_page.php">
                  Profile
                </a>
              </li>
            <?php else: ?>
              <li>
                <a class="block rounded-lg text-center bg-orange-600 px-3 py-2 text-sm font-medium text-zinc-100 hover:bg-orange-500 transition ease-in-out duration-300" href="user_login.php">
                  Login
                </a>
              </li>
              <li>
                <a class="block rounded-lg text-center bg-orange-600 px-3 py-2 text-sm font-medium text-zinc-100 hover:bg-orange-500 transition ease-in-out duration-300" href="user_signup.php">
                  Sign up
                </a>
              </li>
            <?php endif; ?>
          </ul>
        </div>
      </div>
    </div>
  </div>
</header>

<script src="../../../public/js/frontend-navigation.js"></script>
