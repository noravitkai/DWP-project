<?php
include '../../../config/user_session.php';
require_once '../../Controllers/MovieController.php';

$movieController = new MovieController();
$movies = $movieController->index();
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
                        <a href="#" class="group block">
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
</body>
</html>