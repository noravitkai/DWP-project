<?php
require_once '../../Controllers/MovieController.php';

$movieController = new MovieController();
$movie = $movieController->getMovieById($_GET['id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $movie['Title']; ?></title>
    <link href="../../../public/css/tailwind.css" rel="stylesheet">
</head>
<body class="bg-zinc-800">
    <main class="mx-auto max-w-screen-xl px-4 py-8 sm:px-6 sm:py-12 lg:px-8">
        <section class="flex flex-col md:flex-row items-center md:items-start gap-8">
            <div class="w-full md:w-1/3">
                <img 
                    src="<?php echo $movie['ImageURL']; ?>" 
                    alt="<?php echo $movie['Title']; ?>" 
                    class="w-auto max-h-80 md:max-h-full shadow-md"
                />
            </div>
            <div class="w-full md:w-2/3">
                <h1 class="text-2xl sm:text-3xl font-bold text-orange-600 mb-2"><?php echo $movie['Title']; ?></h1>
                <h2 class="text-base sm:text-lg text-zinc-200 italic mb-6"><?php echo $movie['Subtitle']; ?></h2>
                <p class="text-sm sm:text-base text-zinc-300 mb-6"><?php echo $movie['MovieDescription']; ?></p>
                <div class="grid gap-4 sm:grid-cols-2 mb-4">
                    <div>
                        <h3 class="block text-xs sm:text-sm text-orange-600 uppercase mb-0.5">Duration:</h3>
                        <p class="text-sm sm:text-base text-zinc-300"><?php echo $movie['Duration']; ?> mins</p>
                    </div>
                    <div>
                        <h3 class="block text-xs sm:text-sm text-orange-600 uppercase mb-0.5">Genre:</h3>
                        <p class="text-sm sm:text-base text-zinc-300"><?php echo $movie['Genre']; ?></p>
                    </div>
                    <div>
                        <h3 class="block text-xs sm:text-sm text-orange-600 uppercase mb-0.5">Release Year:</h3>
                        <p class="text-sm sm:text-base text-zinc-300"><?php echo $movie['ReleaseYear']; ?></p>
                    </div>
                    <div>
                        <h3 class="block text-xs sm:text-sm text-orange-600 uppercase mb-0.5">Director:</h3>
                        <p class="text-sm sm:text-base text-zinc-300"><?php echo $movie['Director']; ?></p>
                    </div>
                </div>
                <div class="sm:col-span-2 mt-6">
                    <h3 class="block text-xs sm:text-sm text-orange-600 uppercase mb-0.5">Cast:</h3>
                    <?php if (!empty($movie['Actors'])): ?>
                        <ul>
                            <?php foreach ($movie['Actors'] as $actor): ?>
                                <li class="grid gap-0 sm:gap-4 sm:grid-cols-2 sm:items-center mb-2 md:mb-0">
                                    <span class="text-sm sm:text-base text-zinc-300"><?php echo $actor['FullName']; ?></span>
                                    <span class="text-sm sm:text-base text-zinc-300 italic"><?php echo $actor['Role']; ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-sm sm:text-base text-zinc-300 italic">No cast information available.</p>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>
</body>
</html>