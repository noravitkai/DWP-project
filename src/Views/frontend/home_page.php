<?php
include '../../../config/user_session.php';
require_once '../../Controllers/MovieController.php';
require_once '../../Controllers/NewsController.php';

$movieController = new MovieController();
$newsController = new NewsController();

$movies = $movieController->index();
$newsList = $newsController->index();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Fast Lane Cine</title>
    <link href="../../../public/css/tailwind.css" rel="stylesheet">
</head>
<body class="bg-zinc-800">
    <section>
        <div class="mx-auto max-w-screen-xl px-4 py-8 sm:px-6 sm:py-12 lg:px-8">
            <header>
                <h2 class="text-xl font-bold text-orange-600 sm:text-3xl">Daily Showings</h2>
                <p class="mt-4 max-w-lg text-base text-zinc-300">
                    Browse through our collection of Fast & Furious movies. Book your seats and join the Family!
                </p>
            </header>
            <ul class="mt-8 grid gap-4 grid-cols-2 md:grid-cols-3 lg:grid-cols-5">
                <?php foreach ($movies as $movie): ?>
                    <li>
                        <a href="single_movie.php?id=<?php echo $movie['MovieID']; ?>" class="group block">
                            <div class="aspect-w-2 aspect-h-3 w-full overflow-hidden">
                                <img
                                    src="<?php echo $movie['ImageURL']; ?>"
                                    alt="<?php echo $movie['Title']; ?>"
                                    class="w-full h-full object-cover transition duration-500 group-hover:scale-105"
                                />
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
    <section>
    <div class="mx-auto max-w-screen-xl px-4 py-8 sm:px-6 sm:py-12 lg:px-8">
        <header>
            <h2 class="text-xl font-bold text-orange-600 sm:text-3xl">Latest News</h2>
            <p class="mt-4 max-w-lg text-base text-zinc-300">
                Stay updated with the latest announcements, events, and updates from Fast Lane Cine.
            </p>
        </header>
        <ul class="mt-8 grid gap-4 md:grid-cols-2 sm:grid-cols-1 lg:grid-cols-3">
            <?php foreach ($newsList as $news): ?>
                <li class="h-full">
                    <article class="h-full flex flex-col overflow-hidden shadow transition hover:shadow-lg group">
                        <div class="aspect-w-16 aspect-h-11 w-full overflow-hidden">
                            <img
                                src="<?php echo $news['ImageURL']; ?>"
                                alt="<?php echo $news['Title']; ?>"
                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                            />
                        </div>
                        <div class="bg-zinc-700 p-4 sm:p-6 flex flex-col justify-between h-full">
                            <div class="flex flex-col gap-2">
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
                                <span class="inline-block <?php echo $categoryClass; ?> text-xs text-zinc-100 px-2 py-1 self-start">
                                    <?php echo $news['Category']; ?>
                                </span>
                                <time datetime="<?php echo $news['DatePublished']; ?>" class="block text-xs text-zinc-300 mt-2">
                                    <?php echo date('jS M Y', strtotime($news['DatePublished'])); ?>
                                </time>
                                <h3 class="text-lg text-zinc-200 mt-1 line-clamp-2">
                                    <?php echo $news['Title']; ?>
                                </h3>
                                <p class="text-sm text-zinc-200 line-clamp-2">
                                    <?php echo $news['Content']; ?>
                                </p>
                            </div>
                            <a href="single_news.php?id=<?php echo $news['NewsID']; ?>" class="inline-flex items-center gap-1 mt-4 text-orange-600 hover:text-orange-500 text-sm font-medium transition ease-in-out duration-300">
                                Read more 
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                </svg>
                            </a>
                        </div>
                    </article>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>
</body>
</html>