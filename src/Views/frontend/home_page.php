<?php
include '../../../config/user_session.php';
require_once '../../Controllers/MovieController.php';
require_once '../../Controllers/NewsController.php';
require_once '../../Controllers/ScreeningController.php';
require_once '../../Controllers/CinemaController.php';
require_once '../../../config/functions.php';

$movieController = new MovieController();
$newsController = new NewsController();
$screeningController = new ScreeningController();
$cinemaController = new CinemaController();

$movies = $movieController->index();
$newsList = $newsController->index();
$todaysScreenings = $screeningController->getDailyScreenings();
$cinemas = $cinemaController->index();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Fast Lane Cine</title>
    <link href="../../../public/css/tailwind.css" rel="stylesheet">
</head>
<body class="bg-zinc-800">
    <?php include '../frontend/frontend_navigation.php'; ?>
    <main>
        <section id="screenings">
            <div class="mx-auto max-w-screen-xl px-4 py-8 sm:px-6 sm:py-12 lg:px-8">
                <header>
                    <h2 class="text-xl font-bold text-orange-600 sm:text-3xl">Daily Screenings</h2>
                    <p class="mt-4 max-w-lg text-sm sm:text-base text-zinc-300">See what’s playing today at our cinema and enjoy the best movies!</p>
                </header>
                <ul class="mt-8 grid gap-4 grid-cols-2 md:grid-cols-3 lg:grid-cols-5">
                    <?php if (!empty($todaysScreenings)): ?>
                        <?php foreach ($todaysScreenings as $screening): ?>
                            <li>
                                <a href="seat_reservation.php?screening_id=<?php echo urlencode($screening['ScreeningID']); ?>" class="group block">
                                    <div class="aspect-w-2 aspect-h-3 w-full overflow-hidden">
                                        <img src="<?php echo getFallbackImage($screening['ImageURL'], '/DWP-project/public/images/movie-default.jpg'); ?>" alt="<?php echo $screening['MovieTitle']; ?>" class="w-full h-full object-cover transition duration-500 group-hover:scale-105" loading="lazy"/>
                                    </div>
                                    <div class="relative bg-zinc-800 pt-3">
                                        <h3 class="text-base text-orange-600 group-hover:text-orange-500"><?php echo $screening['MovieTitle']; ?></h3>
                                        <p class="mt-2 text-sm sm:text-base text-zinc-200">Starting at: <?php echo date('g:i A', strtotime($screening['ScreeningTime'])); ?></p>
                                    </div>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="col-span-5">
                            <div class="bg-zinc-700 p-6 rounded-lg shadow-lg" role="alert">
                                <div class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-orange-600"><path stroke-linecap="round" stroke-linejoin="round" d="m15.75 10.5 4.72-4.72a.75.75 0 0 1 1.28.53v11.38a.75.75 0 0 1-1.28.53l-4.72-4.72M12 18.75H4.5a2.25 2.25 0 0 1-2.25-2.25V9m12.841 9.091L16.5 19.5m-1.409-1.409c.407-.407.659-.97.659-1.591v-9a2.25 2.25 0 0 0-2.25-2.25h-9c-.621 0-1.184.252-1.591.659m12.182 12.182L2.909 5.909M1.5 4.5l1.409 1.409"/></svg>
                                    <p class="text-lg font-medium text-zinc-100">No movies scheduled for today!</p>
                                </div>
                                <p class="mt-4 text-zinc-400 text-sm sm:text-base">We don't have any movie screenings for today. Please check back tomorrow or explore our list of movies!</p>
                                <div class="mt-6 flex gap-4">
                                    <a href="#movies" class="inline-block rounded-lg bg-orange-600 px-3 py-2 text-sm font-medium text-zinc-100 hover:bg-orange-500 transition ease-in-out duration-300">Browse Collection</a>
                                </div>
                            </div>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </section>
        <section id="news">
            <div class="mx-auto max-w-screen-xl px-4 py-8 sm:px-6 sm:py-12 lg:px-8">
                <header>
                    <h2 class="text-xl font-bold text-orange-600 sm:text-3xl">Latest News</h2>
                    <p class="mt-4 max-w-lg text-sm sm:text-base text-zinc-300">Stay updated with the latest announcements, events, and updates from Fast Lane Cine.</p>
                </header>
                <ul class="mt-8 grid gap-4 md:grid-cols-2 sm:grid-cols-1 lg:grid-cols-3">
                    <?php foreach ($newsList as $news): ?>
                        <?php
                        $category = $news['Category'];
                        $categoryClass = '';
                        switch ($category) {
                            case 'Announcement':
                                $categoryClass = 'bg-yellow-600';
                                break;
                            case 'Event':
                                $categoryClass = 'bg-orange-600';
                                break;
                            case 'Update':
                                $categoryClass = 'bg-red-600';
                                break;
                        }
                        ?>
                        <li class="h-full">
                            <article class="relative h-full flex flex-col overflow-hidden shadow transition hover:shadow-lg group">
                                <div class="aspect-w-16 aspect-h-11 w-full overflow-hidden">
                                    <img src="<?php echo getFallbackImage($news['ImageURL'], '/DWP-project/public/images/news-default.jpg'); ?>" alt="<?php echo $news['Title']; ?>" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy"/>
                                </div>
                                <div class="bg-zinc-700 rounded-b-lg p-4 sm:p-6 flex flex-col justify-between h-full">
                                    <div class="flex flex-col gap-2">
                                        <span class="inline-block <?php echo $categoryClass; ?> text-xs text-zinc-100 px-2 py-1 self-start"><?php echo $news['Category']; ?></span>
                                        <time datetime="<?php echo $news['DatePublished']; ?>" class="block text-xs text-zinc-300 mt-2"><?php echo date('jS M Y', strtotime($news['DatePublished'])); ?></time>
                                        <h3 class="text-lg text-zinc-200 mt-1 line-clamp-2"><?php echo $news['Title']; ?></h3>
                                        <p class="text-sm sm:text-base text-zinc-200 line-clamp-2"><?php echo $news['Content']; ?></p>
                                    </div>
                                    <a href="single_news.php?id=<?php echo $news['NewsID']; ?>" class="clickable-parent inline-flex items-center gap-1 mt-4 text-orange-600 hover:text-orange-500 text-sm font-medium transition ease-in-out duration-300">
                                        Read more 
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                                        </svg>
                                    </a>
                                </div>
                            </article>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </section>
        <section id="movies">
            <div class="mx-auto max-w-screen-xl px-4 py-8 sm:px-6 sm:py-12 lg:px-8">
                <header>
                    <h2 class="text-xl font-bold text-orange-600 sm:text-3xl">Movie Collection</h2>
                    <p class="mt-4 max-w-lg text-sm sm:text-base text-zinc-300">Browse through our collection of Fast & Furious movies.</p>
                </header>
                <ul class="mt-8 grid gap-4 grid-cols-2 md:grid-cols-3 lg:grid-cols-5">
                    <?php foreach ($movies as $movie): ?>
                        <li>
                            <a href="single_movie.php?id=<?php echo $movie['MovieID']; ?>" class="group block">
                                <div class="aspect-w-2 aspect-h-3 w-full overflow-hidden">
                                    <img src="<?php echo getFallbackImage($movie['ImageURL'], '/DWP-project/public/images/movie-default.jpg'); ?>" alt="<?php echo $movie['Title']; ?>" class="w-full h-full object-cover transition duration-500 group-hover:scale-105" loading="lazy"/>
                                </div>
                                <div class="relative bg-zinc-800 pt-3">
                                    <h3 class="text-base text-orange-600 group">
                                        <?php echo $movie['Title']; ?>:
                                    </h3>
                                    <h4 class="text-base text-zinc-200 group-hover:text-orange-600 transition ease-in-out duration-300">
                                        <?php echo $movie['Subtitle']; ?>
                                    </h4>
                                    <p class="mt-2 text-sm text-zinc-200">
                                        <?php echo $movie['ReleaseYear']; ?> | <?php echo $movie['Duration']; ?> mins
                                    </p>
                                </div>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </section>
        <section id="contact">
            <div class="mx-auto max-w-screen-xl px-4 py-8 sm:px-6 sm:py-12 lg:px-8">
                <?php
                if (!empty($cinemas)) {
                    foreach ($cinemas as $cinema) {
                        ?>
                        <div class="grid grid-cols-1 lg:grid-cols-2">
                            <div class="relative z-10 lg:py-16">
                                <div class="relative h-64 sm:h-80 lg:h-96">
                                    <img src="<?php echo getFallbackImage($cinema['ImageURL'], '/DWP-project/public/images/cinema-default.jpg'); ?>" alt="Fast Lane Cine"  class="absolute inset-0 h-full w-full object-cover" loading="lazy"/>
                                </div>
                            </div>
                            <div class="relative flex items-center bg-zinc-700 rounded-b-lg lg:rounded-b-none lg:rounded-r-lg overflow-hidden lg:overflow-visible">
                                <span class="hidden lg:absolute lg:inset-y-0 lg:-start-16 lg:block lg:w-16 bg-zinc-700 rounded-l-lg"></span>
                                <div class="relative flex items-center bg-zinc-700">
                                    <span class="hidden lg:absolute lg:inset-y-0 lg:-start-16 lg:block lg:w-16 bg-zinc-700"></span>
                                    <div class="p-8 sm:p-12 lg:p-12">
                                        <h2 class="text-xl font-bold text-orange-600 sm:text-3xl">
                                            <?php echo $cinema['Tagline']; ?>
                                        </h2>
                                        <p class="mt-4 text-zinc-300 text-sm sm:text-base">
                                            <?php echo $cinema['Description']; ?>
                                        </p>
                                        <div class="grid sm:grid-cols-2 gap-6 mt-10">
                                            <div class="space-y-6">
                                                <div class="flex flex-col">
                                                    <div class="flex items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-zinc-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c-.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                                                        </svg>
                                                        <span class="ml-2 font-semibold text-zinc-200">Phone Number:</span>
                                                    </div>
                                                    <div class="mt-1">
                                                        <p>
                                                            <a href="tel:<?php echo $cinema['PhoneNumber']; ?>" class="text-orange-600 hover:underline">
                                                                <?php echo $cinema['PhoneNumber']; ?>
                                                            </a>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="flex flex-col">
                                                    <div class="flex items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-zinc-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0Zm0 0c0 1.657 1.007 3 2.25 3S21 13.657 21 12a9 9 0 1 0-2.636 6.364M16.5 12V8.25" />
                                                        </svg>
                                                        <span class="ml-2 font-semibold text-zinc-200">Email Address:</span>
                                                    </div>
                                                    <div class="mt-1">
                                                        <p>
                                                            <a href="mailto:<?php echo $cinema['Email']; ?>" class="text-orange-600 hover:underline">
                                                                <?php echo $cinema['Email']; ?>
                                                            </a>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex flex-col">
                                                <div class="flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-zinc-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Zm0 2.25h.008v.008H16.5V15Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                                                    </svg>
                                                    <span class="ml-2 font-semibold text-zinc-200">Opening Hours:</span>
                                                </div>
                                                <div class="mt-1">
                                                    <p class="text-orange-600">
                                                        <?php echo nl2br($cinema['OpeningHours']); ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo '<p class="text-center text-zinc-500">No cinema details available at the moment.</p>';
                }
                ?>
            </div>
        </section>
    </main>
    <?php include '../frontend/footer.php'; ?>
</body>
</html>