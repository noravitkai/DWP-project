<?php
require_once '../../Controllers/MovieController.php';
require_once '../../Controllers/ScreeningController.php';
require_once '../../../config/functions.php';

if (isset($_GET['id'])) {
    $movieId = sanitizeInput($_GET['id']);
} else {
    $movieId = null;
}

if (!$movieId || !is_numeric($movieId)) {
    header("Location: home_page.php");
    exit();
}

$movieController = new MovieController();
$movie = $movieController->getMovieById((int)$movieId);

if (!$movie) {
    header("Location: home_page.php");
    exit();
}

$screeningController = new ScreeningController();
$screenings = $screeningController->getScreeningsByMovieId($movie['MovieID']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $movie['Title']; ?></title>
    <link href="../../../public/css/tailwind.css" rel="stylesheet">
</head>
<body class="bg-zinc-800">
    <?php include '../frontend/frontend_navigation.php'; ?>
    <main class="mx-auto max-w-screen-xl px-4 py-8 sm:px-6 sm:py-12 lg:px-8">
        <section class="flex flex-col md:flex-row items-start gap-8">
            <div class="relative w-1/2 md:w-1/3 aspect-[2/3] overflow-hidden">
                <img src="<?php echo getFallbackImage($movie['ImageURL'], '/DWP-project/public/images/movie-default.jpg'); ?>" alt="<?php echo $movie['Title']; ?>" class="object-cover h-full w-full" loading="lazy"/>
            </div>
            <div class="w-full md:w-2/3">
                <h1 class="text-2xl sm:text-3xl font-bold text-orange-600 mb-2"><?php echo $movie['Title']; ?></h1>
                <h2 class="text-base sm:text-lg text-zinc-200 italic mb-6"><?php echo $movie['Subtitle']; ?></h2>
                <p class="text-sm sm:text-base text-zinc-300 mb-10"><?php echo $movie['MovieDescription']; ?></p>
                <section class="grid gap-4 sm:grid-cols-2 mb-4">
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
                </section>
                <section class="sm:col-span-2 mt-6">
                    <h3 class="block text-xs sm:text-sm text-orange-600 uppercase mb-0.5">Cast:</h3>
                    <?php if (!empty($movie['Actors'])): ?>
                        <ul>
                            <?php foreach ($movie['Actors'] as $actor): ?>
                                <li class="grid gap-0 sm:gap-4 sm:grid-cols-2 sm:items-center mb-2 md:mb-0">
                                    <span class="text-sm sm:text-base text-zinc-300">
                                        <?php echo $actor['FirstName'] . ' ' . $actor['LastName']; ?>
                                    </span>
                                    <span class="text-sm sm:text-base text-zinc-300 italic">
                                        <?php echo $actor['Role']; ?>
                                    </span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-sm sm:text-base text-zinc-300 italic">No cast information available.</p>
                    <?php endif; ?>
                </section>
                <section class="sm:col-span-2 mt-10">
                    <h3 class="block text-xs sm:text-sm text-orange-600 uppercase mb-0.5">Screenings:</h3>
                    <?php if (!empty($screenings)): ?>
                        <form action="seat_reservation.php" method="GET" class="flex flex-col sm:flex-row items-start sm:items-center gap-y-2 sm:gap-y-0 sm:gap-x-2">
                            <label for="screening_select" class="sr-only">Select a screening</label>
                            <div class="relative w-full sm:w-auto flex-grow">
                                <select id="screening_select" name="screening_id" class="mb-4 sm:mb-0 block py-2.5 px-0 w-full text-sm text-zinc-300 bg-transparent border-0 border-b border-zinc-200 appearance-none focus:outline-none focus:ring-0 focus:border-orange-600 peer" required>
                                    <option disabled selected value="">Select a screening</option>
                                    <?php foreach ($screenings as $screening): ?>
                                        <option value="<?php echo $screening['ScreeningID']; ?>">
                                            <?php echo $screening['ScreeningDate'] . ' | ' . $screening['ScreeningTime'] . ' | Room: ' . $screening['RoomLabel'] . ' | ' . $screening['Price'] . ' DKK'; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <svg class="absolute right-2 top-1/2 transform -translate-y-1/2 pointer-events-none w-5 h-5 text-zinc-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                            <button type="submit" class="inline-flex items-center rounded-lg bg-orange-600 px-3 py-2 text-sm font-medium text-white hover:bg-orange-500 transition ease-in-out duration-300 w-auto">
                                <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m15.75 10.5 4.72-4.72a.75.75 0 0 1 1.28.53v11.38a.75.75 0 0 1-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25h-9A2.25 2.25 0 0 0 2.25 7.5v9a2.25 2.25 0 0 0 2.25 2.25Z" />
                                </svg>
                                Reserve
                            </button>
                        </form>
                    <?php else: ?>
                        <p class="text-sm sm:text-base text-zinc-300 italic">No screenings available for this movie.</p>
                    <?php endif; ?>
                </section>
            </div>
        </section>
    </main>
    <?php include '../frontend/footer.php'; ?>
</body>
</html>