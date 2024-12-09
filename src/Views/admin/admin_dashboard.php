<?php
include '../../../config/admin_session.php';
require_once '../../Controllers/MovieController.php';
require_once '../../Controllers/NewsController.php';
require_once '../../Controllers/ScreeningController.php';
require_once '../../Controllers/CinemaController.php';
require_once '../../../config/functions.php';

$newsController = new NewsController();
$movieController = new MovieController();
$screeningController = new ScreeningController();
$cinemaController = new CinemaController();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addMovieBtn'])) {
    if (verifyCsrfToken($_POST['csrf_token'])) {
        $data = [
            'Title' => sanitizeInput($_POST['Title']),
            'Subtitle' => sanitizeInput($_POST['Subtitle']),
            'ReleaseYear' => sanitizeInput($_POST['ReleaseYear']),
            'Genre' => sanitizeInput($_POST['Genre']),
            'Director' => sanitizeInput($_POST['Director']),
            'Duration' => sanitizeInput($_POST['Duration']),
            'MovieDescription' => sanitizeInput($_POST['MovieDescription']),
        ];

        if (isset($_POST['ActorFirstNames'], $_POST['ActorLastNames'], $_POST['ActorRoles'])) {
            $data['Actors'] = [];
            foreach ($_POST['ActorFirstNames'] as $index => $firstName) {
                $data['Actors'][] = [
                    'FirstName' => sanitizeInput($firstName),
                    'LastName' => sanitizeInput($_POST['ActorLastNames'][$index]),
                    'Role' => sanitizeInput($_POST['ActorRoles'][$index])
                ];
            }
        }

        $movieController->store($data);
    }
    header('Location: admin_dashboard.php#movies');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateMovieBtn'])) {
    if (verifyCsrfToken($_POST['csrf_token'])) {
        $movieId = sanitizeInput($_POST['MovieID']);
        $data = [
            'Title' => sanitizeInput($_POST['Title']),
            'Subtitle' => sanitizeInput($_POST['Subtitle']),
            'ReleaseYear' => sanitizeInput($_POST['ReleaseYear']),
            'Genre' => sanitizeInput($_POST['Genre']),
            'Director' => sanitizeInput($_POST['Director']),
            'Duration' => sanitizeInput($_POST['Duration']),
            'MovieDescription' => sanitizeInput($_POST['MovieDescription']),
        ];

        if (isset($_POST['ActorFirstNames'], $_POST['ActorLastNames'], $_POST['ActorRoles'])) {
            $data['Actors'] = [];
            foreach ($_POST['ActorFirstNames'] as $index => $firstName) {
                $data['Actors'][] = [
                    'FirstName' => sanitizeInput($firstName),
                    'LastName' => sanitizeInput($_POST['ActorLastNames'][$index]),
                    'Role' => sanitizeInput($_POST['ActorRoles'][$index])
                ];
            }
        }

        $movieController->update($movieId, $data);
    }
    header('Location: admin_dashboard.php#movies');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteMovieBtn'])) {
    if (verifyCsrfToken($_POST['csrf_token'])) {
        $movieId = sanitizeInput($_POST['deleteMovieID']);
        $movieController->delete($movieId);
    }
    header('Location: admin_dashboard.php#movies');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addScreeningBtn'])) {
    if (verifyCsrfToken($_POST['csrf_token'])) {
        $data = [
            'MovieID' => sanitizeInput($_POST['MovieID']),
            'ScreeningDate' => sanitizeInput($_POST['ScreeningDate']),
            'ScreeningTime' => sanitizeInput($_POST['ScreeningTime']),
            'RoomID' => sanitizeInput($_POST['RoomID']),
            'Price' => sanitizeInput($_POST['Price']),
        ];
        $screeningController->store($data);
    }
    header('Location: admin_dashboard.php#screenings');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateScreeningBtn'])) {
    if (verifyCsrfToken($_POST['csrf_token'])) {
        $screeningId = sanitizeInput($_POST['ScreeningID']);
        $data = [
            'Price' => sanitizeInput($_POST['Price']),
            'ScreeningDate' => sanitizeInput($_POST['ScreeningDate']),
            'ScreeningTime' => sanitizeInput($_POST['ScreeningTime']),
            'RoomID' => sanitizeInput($_POST['RoomID']),
        ];
        $screeningController->update($screeningId, $data);
    }
    header('Location: admin_dashboard.php#screenings');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteScreeningBtn'])) {
    if (verifyCsrfToken($_POST['csrf_token'])) {
        $screeningId = sanitizeInput($_POST['deleteScreeningID']);
        $screeningController->delete($screeningId);
    }
    header('Location: admin_dashboard.php#screenings');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addNewsBtn'])) {
    if (verifyCsrfToken($_POST['csrf_token'])) {
        $data = [
            'Title' => sanitizeInput($_POST['Title']),
            'Content' => sanitizeInput($_POST['Content']),
            'Category' => sanitizeInput($_POST['Category']),
            'DatePublished' => sanitizeInput($_POST['DatePublished']),
        ];
        $newsController->store($data);
    }
    header('Location: admin_dashboard.php#news');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateNewsBtn'])) {
    if (verifyCsrfToken($_POST['csrf_token'])) {
        $newsId = sanitizeInput($_POST['NewsID']);
        $data = [
            'Title' => sanitizeInput($_POST['Title']),
            'Content' => sanitizeInput($_POST['Content']),
            'Category' => sanitizeInput($_POST['Category']),
            'DatePublished' => sanitizeInput($_POST['DatePublished']),
        ];
        $newsController->update($newsId, $data);
    }
    header('Location: admin_dashboard.php#news');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteNewsBtn'])) {
    if (verifyCsrfToken($_POST['csrf_token'])) {
        $newsId = sanitizeInput($_POST['deleteNewsID']);
        $newsController->delete($newsId);
    }
    header('Location: admin_dashboard.php#news');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateCinemaBtn'])) {
    if (verifyCsrfToken($_POST['csrf_token'])) {
        $cinemaId = sanitizeInput($_POST['CinemaID']);
        $data = [
            'Tagline' => sanitizeInput($_POST['Tagline']),
            'Description' => sanitizeInput($_POST['Description']),
            'PhoneNumber' => sanitizeInput($_POST['PhoneNumber']),
            'Email' => sanitizeInput($_POST['Email']),
            'OpeningHours' => sanitizeInput($_POST['OpeningHours'])
        ];

        $cinemaController->update($cinemaId, $data);
    }
    header('Location: admin_dashboard.php#cinema');
    exit;
}

$movies = $movieController->index();
$newsList = $newsController->index();
$screenings = $screeningController->index();
$rooms = $screeningController->getRooms();
$cinemas = $cinemaController->index();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="../../../public/css/tailwind.css" rel="stylesheet">
</head>
<body class="h-full bg-zinc-100 text-zinc-900">
    <div class="h-full">
        <?php include 'dashboard_navigation.php'; ?>
        <div class="py-10 lg:pl-72">
            <main class="px-4 sm:px-6 lg:px-8">
                <section id="movies" class="mb-10">
                    <h2 class="text-3xl font-bold text-zinc-900">Movies</h2>
                    <p class="mt-5 text-base text-zinc-700">Manage the movie listings and other movie-related tasks here.</p>
                    <div class="mt-5">
                        <button type="button" onclick="showAddMovieModal()" class="inline-flex items-center rounded-lg bg-orange-600 px-3 py-2 text-sm font-medium text-white hover:bg-orange-500 transition ease-in-out duration-300">
                            <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            Add Movie
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full my-10 text-zinc-900">
                            <thead>
                                <tr>
                                    <th class="px-2 py-1 sm:px-4 sm:py-2 border-b-2 border-zinc-200 text-left text-xs font-semibold text-zinc-600 uppercase">ID</th>
                                    <th class="px-2 py-1 sm:px-4 sm:py-2 border-b-2 border-zinc-200 text-left text-xs font-semibold text-zinc-600 uppercase">Title</th>
                                    <th class="px-2 py-1 sm:px-4 sm:py-2 border-b-2 border-zinc-200 text-left text-xs font-semibold text-zinc-600 uppercase">Subtitle</th>
                                    <th class="px-2 py-1 sm:px-4 sm:py-2 border-b-2 border-zinc-200 text-left text-xs font-semibold text-zinc-600 uppercase">Release Year</th>
                                    <th class="px-2 py-1 sm:px-4 sm:py-2 border-b-2 border-zinc-200 text-left text-xs font-semibold text-zinc-600 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($movies as $movie): ?>
                                <tr>
                                    <td class="px-2 py-1 sm:px-4 sm:py-2 border-b border-zinc-200 text-sm"><?php echo $movie['MovieID']; ?></td>
                                    <td class="px-2 py-1 sm:px-4 sm:py-2 border-b border-zinc-200 text-sm"><?php echo $movie['Title']; ?></td>
                                    <td class="px-2 py-1 sm:px-4 sm:py-2 border-b border-zinc-200 text-sm"><?php echo $movie['Subtitle']; ?></td>
                                    <td class="px-2 py-1 sm:px-4 sm:py-2 border-b border-zinc-200 text-sm"><?php echo $movie['ReleaseYear']; ?></td>
                                    <td class="px-2 py-1 sm:px-4 sm:py-2 border-b border-zinc-200 text-sm">
                                        <button type="button" onclick="showPreviewMovieModal(<?php echo htmlspecialchars(json_encode($movie)); ?>)" class="flex w-full items-center py-1 text-zinc-600 hover:text-zinc-900 transition ease-in-out duration-300">
                                            <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                                                <path fill-rule="evenodd" d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 0 1 0-1.113ZM17.25 12a5.25 5.25 0 1 1-10.5 0 5.25 5.25 0 0 1 10.5 0Z" clip-rule="evenodd" />
                                            </svg>
                                            Preview
                                        </button>
                                        <button type="button" onclick="showEditMovieModal(<?php echo htmlspecialchars(json_encode($movie)); ?>)" class="flex w-full items-center py-1 text-zinc-600 hover:text-zinc-900 transition ease-in-out duration-300">
                                            <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z" />
                                                <path d="M5.25 5.25a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3V13.5a.75.75 0 0 0-1.5 0v5.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V8.25a1.5 1.5 0 0 1 1.5-1.5h5.25a.75.75 0 0 0 0-1.5H5.25Z" />
                                            </svg>
                                            Edit
                                        </button>
                                        <button type="button" onclick="showDeleteMovieModal(<?php echo htmlspecialchars(json_encode($movie)); ?>)" class="flex w-full items-center py-1 text-red-600 hover:text-red-800 transition ease-in-out duration-300">
                                            <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                                <path fill-rule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z" clip-rule="evenodd" />
                                            </svg>
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div id="addMovieModal" tabindex="-1" aria-hidden="true" class="hidden fixed inset-0 z-50 items-center justify-center lg:pl-72 p-4">
                        <div class="relative max-w-2xl w-full max-h-[95vh] bg-zinc-100 rounded-lg shadow p-6 sm:p-8 overflow-y-auto">
                            <div class="flex justify-between items-center pb-4 mb-4 border-b border-zinc-200">
                                <h3 class="text-lg font-semibold text-zinc-900">Add New Movie</h3>
                                <button type="button" class="text-zinc-600 text-sm hover:text-zinc-900" onclick="hideModal('addMovieModal')">
                                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                        <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm-1.72 6.97a.75.75 0 1 0-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 1 0 1.06 1.06L12 13.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L13.06 12l1.72-1.72a.75.75 0 1 0-1.06-1.06L12 10.94l-1.72-1.72Z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                            <form id="addMovieForm" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                <input type="hidden" name="addNewMovie" value="1">
                                <div class="grid gap-4 sm:grid-cols-2">
                                    <div>
                                        <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Title:</label>
                                        <input type="text" name="Title" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600" required>
                                    </div>
                                    <div>
                                        <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Subtitle:</label>
                                        <input type="text" name="Subtitle" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600">
                                    </div>
                                    <div>
                                        <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Release Year:</label>
                                        <input type="number" name="ReleaseYear" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600" required>
                                    </div>
                                    <div>
                                        <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Genre:</label>
                                        <input type="text" name="Genre" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600">
                                    </div>
                                    <div>
                                        <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Director:</label>
                                        <input type="text" name="Director" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600">
                                    </div>
                                    <div>
                                        <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Duration:</label>
                                        <input type="number" name="Duration" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600">
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Description:</label>
                                        <textarea name="MovieDescription" rows="6" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600"></textarea>
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Cast:</label>
                                        <div id="actorContainer" class="flex flex-col gap-2">
                                        </div>
                                        <div id="actorLimitMessage" class="text-sm text-red-600 mt-2 hidden">
                                            You can only add up to 10 actors.
                                        </div>
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label for="movieImage">Upload Image:</label>
                                        <input type="file" name="movieImage" id="movieImage" accept="image/jpeg, image/png, image/gif">
                                    </div>
                                    <div class="sm:col-span-2 text-right">
                                        <button type="submit" name="addMovieBtn" class="inline-block rounded-lg bg-orange-600 px-3 py-2 text-sm font-medium text-zinc-100 hover:bg-orange-500 transition ease-in-out duration-300">
                                            Add
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div id="previewMovieModal" tabindex="-1" aria-hidden="true" class="hidden fixed inset-0 z-50 items-center justify-center lg:pl-72 p-4">
                        <div class="relative max-w-2xl w-full max-h-[95vh] bg-zinc-100 rounded-lg shadow p-6 sm:p-8 overflow-y-auto">
                            <div class="flex justify-between items-center pb-4 mb-4 border-b border-zinc-200">
                                <h3 class="text-lg font-semibold text-zinc-900" id="previewMovieTitle">Movie Details</h3>
                                <button type="button" class="text-zinc-600 text-sm hover:text-zinc-900 transition ease-in-out duration-300" onclick="hideModal('previewMovieModal')">
                                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                        <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm-1.72 6.97a.75.75 0 1 0-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 1 0 1.06 1.06L12 13.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L13.06 12l1.72-1.72a.75.75 0 1 0-1.06-1.06L12 10.94l-1.72-1.72Z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">ID:</label>
                                    <p id="previewMovieID" class="text-sm text-zinc-900"></p>
                                </div>
                                <div>
                                    <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Subtitle:</label>
                                    <p id="previewMovieSubtitle" class="text-sm text-zinc-900"></p>
                                </div>
                                <div>
                                    <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Duration:</label>
                                    <p id="previewMovieDuration" class="text-sm text-zinc-900"></p>
                                </div>
                                <div>
                                    <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Genre:</label>
                                    <p id="previewMovieGenre" class="text-sm text-zinc-900"></p>
                                </div>
                                <div>
                                    <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Release Year:</label>
                                    <p id="previewMovieReleaseYear" class="text-sm text-zinc-900"></p>
                                </div>
                                <div>
                                    <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Director:</label>
                                    <p id="previewMovieDirector" class="text-sm text-zinc-900"></p>
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Description:</label>
                                    <p id="previewMovieDescription" class="text-sm text-zinc-900"></p>
                                </div>
                                <div>
                                    <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Cast:</label>
                                    <ul id="previewMovieCast" class="list-none pl-0 text-sm text-zinc-900"></ul>
                                </div>
                                <div>
                                    <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Image:</label>
                                    <img id="previewMovieImage" src="" alt="Movie Image" class="w-32 h-auto"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="editMovieModal" tabindex="-1" aria-hidden="true" class="hidden fixed inset-0 z-50 items-center justify-center lg:pl-72 p-4">
                        <div class="relative max-w-2xl w-full max-h-[95vh] bg-zinc-100 rounded-lg shadow p-6 sm:p-8 overflow-y-auto">
                            <div class="flex justify-between items-center pb-4 mb-4 border-b border-zinc-200">
                                <h3 class="text-lg font-semibold text-zinc-900">Edit Movie</h3>
                                <button type="button" class="text-zinc-600 text-sm hover:text-zinc-900 transition ease-in-out duration-300" onclick="hideModal('editMovieModal')">
                                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                        <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm-1.72 6.97a.75.75 0 1 0-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 1 0 1.06 1.06L12 13.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L13.06 12l1.72-1.72a.75.75 0 1 0-1.06-1.06L12 10.94l-1.72-1.72Z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                            <form id="editMovieForm" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                <input type="hidden" id="editMovieID" name="MovieID">
                                <div class="grid gap-4 sm:grid-cols-2">
                                    <div>
                                        <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Title:</label>
                                        <input type="text" id="editMovieTitle" name="Title" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600" required>
                                    </div>
                                    <div>
                                        <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Subtitle:</label>
                                        <input type="text" id="editMovieSubtitle" name="Subtitle" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600">
                                    </div>
                                    <div>
                                        <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Release Year:</label>
                                        <input type="number" id="editMovieReleaseYear" name="ReleaseYear" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600" required>
                                    </div>
                                    <div>
                                        <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Genre:</label>
                                        <input type="text" id="editMovieGenre" name="Genre" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600">
                                    </div>
                                    <div>
                                        <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Director:</label>
                                        <input type="text" id="editMovieDirector" name="Director" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600">
                                    </div>
                                    <div>
                                        <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Duration:</label>
                                        <input type="number" id="editMovieDuration" name="Duration" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600">
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Description:</label>
                                        <textarea id="editMovieDescription" name="MovieDescription" rows="6" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600"></textarea>
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Cast:</label>
                                        <div id="actorContainerEdit" class="flex flex-col gap-2">
                                        </div>
                                        <div id="actorLimitMessageEdit" class="text-sm text-red-600 mt-2 hidden">
                                            You can only add up to 10 actors.
                                        </div>
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label for="movieImage">Upload Image:</label>
                                        <input type="file" name="movieImage" id="movieImage" accept="image/jpeg, image/png, image/gif">
                                    </div>
                                    <div class="sm:col-span-2 text-right">
                                        <button type="submit" name="updateMovieBtn" class="inline-block rounded-lg bg-orange-600 px-3 py-2 text-sm font-medium text-zinc-100 hover:bg-orange-500 transition ease-in-out duration-300">
                                            Update
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div id="deleteMovieModal" tabindex="-1" aria-hidden="true" class="hidden fixed inset-0 z-50 items-center justify-center lg:pl-72 p-4">
                        <div class="relative max-w-md w-full max-h-[95vh] bg-zinc-100 rounded-lg shadow p-6 sm:p-8 overflow-y-auto">
                        <div class="flex justify-between items-center pb-4 mb-4 border-b border-zinc-200">
                                <h3 class="text-lg font-semibold text-zinc-900">Delete Movie</h3>
                                <button type="button" class="text-zinc-600 text-sm hover:text-zinc-900 transition ease-in-out duration-300" onclick="hideModal('deleteMovieModal')">
                                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                        <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm-1.72 6.97a.75.75 0 1 0-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 1 0 1.06 1.06L12 13.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L13.06 12l1.72-1.72a.75.75 0 1 0-1.06-1.06L12 10.94l-1.72-1.72Z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                            <p id="deleteMovieTitle" class="mb-4 text-sm font-semibold text-zinc-900"></p>
                            <p class="mb-4 text-sm text-zinc-600">Are you sure you want to delete this movie? This action cannot be undone.</p>
                            <form id="deleteMovieForm" method="POST">
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                <input type="hidden" id="deleteMovieID" name="deleteMovieID">
                                <div class="flex justify-end gap-4">
                                    <button type="button" class="rounded-lg bg-zinc-600 px-3 py-2 text-sm font-medium text-zinc-100 hover:bg-zinc-800 transition ease-in-out duration-300" onclick="hideModal('deleteMovieModal')">Cancel</button>
                                    <button type="submit" name="deleteMovieBtn" class="rounded-lg bg-red-600 px-3 py-2 text-sm font-medium text-zinc-100 hover:bg-red-800 transition ease-in-out duration-300">Delete</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>
                <section id="screenings" class="mb-10">
                    <h2 class="text-3xl font-bold text-zinc-900">Screenings</h2>
                    <p class="mt-5 text-base text-zinc-700">Manage the screening schedules along with room assignments here.</p>
                    <div class="mt-5">
                        <button type="button" onclick="showAddScreeningModal()" class="inline-flex items-center rounded-lg bg-orange-600 px-3 py-2 text-sm font-medium text-white hover:bg-orange-500 transition ease-in-out duration-300">
                            <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            Add Screening
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full my-10 text-zinc-900">
                            <thead>
                                <tr>
                                    <th class="px-2 py-1 sm:px-4 sm:py-2 border-b-2 border-zinc-200 text-left text-xs font-semibold text-zinc-600 uppercase">ID</th>
                                    <th class="px-2 py-1 sm:px-4 sm:py-2 border-b-2 border-zinc-200 text-left text-xs font-semibold text-zinc-600 uppercase">Movie Title</th>
                                    <th class="px-2 py-1 sm:px-4 sm:py-2 border-b-2 border-zinc-200 text-left text-xs font-semibold text-zinc-600 uppercase">Date & Time</th>
                                    <th class="px-2 py-1 sm:px-4 sm:py-2 border-b-2 border-zinc-200 text-left text-xs font-semibold text-zinc-600 uppercase">Room</th>
                                    <th class="px-2 py-1 sm:px-4 sm:py-2 border-b-2 border-zinc-200 text-left text-xs font-semibold text-zinc-600 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($screenings as $screening): ?>
                                <tr>
                                    <td class="px-2 py-1 sm:px-4 sm:py-2 border-b border-zinc-200 text-sm"><?php echo $screening['ScreeningID']; ?></td>
                                    <td class="px-2 py-1 sm:px-4 sm:py-2 border-b border-zinc-200 text-sm"><?php echo $screening['MovieTitle']; ?></td>
                                    <td class="px-2 py-1 sm:px-4 sm:py-2 border-b border-zinc-200 text-sm">
                                        <?php echo $screening['ScreeningDate'] . ', ' . $screening['ScreeningTime']; ?>                                    </td>
                                    <td class="px-2 py-1 sm:px-4 sm:py-2 border-b border-zinc-200 text-sm">
                                        ID: <?php echo $screening['RoomID']; ?>; label: <?php echo $screening['RoomLabel']; ?>
                                    </td>
                                    <td class="px-2 py-1 sm:px-4 sm:py-2 border-b border-zinc-200 text-sm">
                                        <button type="button" onclick="showPreviewScreeningModal(<?php echo htmlspecialchars(json_encode($screening)); ?>)" class="flex w-full items-center py-1 text-zinc-600 hover:text-zinc-900 transition ease-in-out duration-300">
                                            <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                                                <path fill-rule="evenodd" d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 0 1 0-1.113ZM17.25 12a5.25 5.25 0 1 1-10.5 0 5.25 5.25 0 0 1 10.5 0Z" clip-rule="evenodd" />
                                            </svg>    
                                            Preview
                                        </button>
                                        <button type="button" onclick="showEditScreeningModal(<?php echo htmlspecialchars(json_encode($screening)); ?>)" class="flex w-full items-center py-1 text-zinc-600 hover:text-zinc-900 transition ease-in-out duration-300">
                                            <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z" />
                                                <path d="M5.25 5.25a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3V13.5a.75.75 0 0 0-1.5 0v5.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V8.25a1.5 1.5 0 0 1 1.5-1.5h5.25a.75.75 0 0 0 0-1.5H5.25Z" />
                                            </svg>    
                                            Edit
                                        </button>
                                        <button type="button" onclick="showDeleteScreeningModal(<?php echo htmlspecialchars(json_encode($screening)); ?>)" class="flex w-full items-center py-1 text-red-600 hover:text-red-800 transition ease-in-out duration-300">
                                            <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                                <path fill-rule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z" clip-rule="evenodd" />
                                            </svg>    
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div id="addScreeningModal" tabindex="-1" aria-hidden="true" class="hidden fixed inset-0 z-50 items-center justify-center lg:pl-72 p-4">
                        <div class="relative max-w-2xl w-full max-h-[95vh] bg-zinc-100 rounded-lg shadow p-6 sm:p-8 overflow-y-auto">
                            <div class="flex justify-between items-center pb-4 mb-4 border-b border-zinc-200">
                                <h3 class="text-lg font-semibold text-zinc-900">Add New Screening</h3>
                                <button type="button" class="text-zinc-600 text-sm hover:text-zinc-900" onclick="hideModal('addScreeningModal')">
                                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                        <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm-1.72 6.97a.75.75 0 1 0-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 1 0 1.06 1.06L12 13.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L13.06 12l1.72-1.72a.75.75 0 1 0-1.06-1.06L12 10.94l-1.72-1.72Z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                            <form id="addScreeningForm" method="POST">
                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                <input type="hidden" name="addNewScreening" value="1">
                                <div class="grid gap-4 sm:grid-cols-2">
                                    <div class="sm:col-span-2">
                                        <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Movie:</label>
                                        <select name="MovieID" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 bg-white focus:outline-none focus:ring-1 focus:ring-orange-600" required>
                                            <option value="" disabled selected>Select movie</option>
                                            <?php foreach ($movies as $movie): ?>
                                                <option value="<?php echo $movie['MovieID']; ?>"><?php echo $movie['Title']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Screening Date:</label>
                                        <input type="date" name="ScreeningDate" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600" required>
                                    </div>
                                    <div>
                                        <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Screening Time:</label>
                                        <input type="time" name="ScreeningTime" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600" required>
                                    </div>
                                    <div>
                                        <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Room:</label>
                                        <select name="RoomID" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 bg-white focus:outline-none focus:ring-1 focus:ring-orange-600" required>
                                            <option value="" disabled selected>Select room</option>
                                            <?php foreach ($rooms as $room): ?>
                                                <option value="<?php echo htmlspecialchars($room['RoomID']); ?>"><?php echo htmlspecialchars($room['RoomLabel']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Price:</label>
                                        <input type="number" step="0.01" name="Price" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600" required>
                                    </div>
                                    <div class="sm:col-span-2 text-right">
                                        <button type="submit" name="addScreeningBtn" class="inline-block rounded-lg bg-orange-600 px-3 py-2 text-sm font-medium text-white hover:bg-orange-500 transition ease-in-out duration-300">
                                            Add Screening
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div id="previewScreeningModal" tabindex="-1" aria-hidden="true" class="hidden fixed inset-0 z-50 items-center justify-center lg:pl-72 p-4">
                        <div class="relative max-w-2xl w-full max-h-[95vh] bg-zinc-100 rounded-lg shadow p-6 sm:p-8 overflow-y-auto">
                            <div class="flex justify-between items-center pb-4 mb-4 border-b border-zinc-200">
                                <h3 class="text-lg font-semibold text-zinc-900">Screening Details</h3>
                                <button type="button" class="text-zinc-600 text-sm hover:text-zinc-900" onclick="hideModal('previewScreeningModal')">
                                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                        <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm-1.72 6.97a.75.75 0 1 0-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 1 0 1.06 1.06L12 13.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L13.06 12l1.72-1.72a.75.75 0 1 0-1.06-1.06L12 10.94l-1.72-1.72Z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Screening ID:</label>
                                    <p id="previewScreeningID" class="text-sm text-zinc-900"></p>
                                </div>
                                <div>
                                    <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Movie Title:</label>
                                    <p id="previewScreeningMovieTitle" class="text-sm text-zinc-900"></p>
                                </div>
                                <div>
                                    <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Date:</label>
                                    <p id="previewScreeningDate" class="text-sm text-zinc-900"></p>
                                </div>
                                <div>
                                    <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Time:</label>
                                    <p id="previewScreeningTime" class="text-sm text-zinc-900"></p>
                                </div>
                                <div>
                                    <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Room:</label>
                                    <p id="previewScreeningRoom" class="text-sm text-zinc-900"></p>
                                </div>
                                <div>
                                    <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Price:</label>
                                    <p id="previewScreeningPrice" class="text-sm text-zinc-900"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="editScreeningModal" tabindex="-1" aria-hidden="true" class="hidden fixed inset-0 z-50 items-center justify-center lg:pl-72 p-4">
                        <div class="relative max-w-2xl w-full max-h-[95vh] bg-zinc-100 rounded-lg shadow p-6 sm:p-8 overflow-y-auto">
                            <div class="flex justify-between items-center pb-4 mb-4 border-b border-zinc-200">
                                <h3 class="text-lg font-semibold text-zinc-900">Edit Screening</h3>
                                <button type="button" class="text-zinc-600 text-sm hover:text-zinc-900 transition ease-in-out duration-300" onclick="hideModal('editScreeningModal')">
                                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                        <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm-1.72 6.97a.75.75 0 1 0-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 1 0 1.06 1.06L12 13.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L13.06 12l1.72-1.72a.75.75 0 1 0-1.06-1.06L12 10.94l-1.72-1.72Z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                            <p id="editScreeningMovieTitle" class="mb-4 text-sm font-semibold text-zinc-900"></p>
                            <form id="editScreeningForm" method="POST">
                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                <input type="hidden" id="editScreeningID" name="ScreeningID">
                                <div class="grid gap-4 sm:grid-cols-2">
                                    <div>
                                        <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Screening Date:</label>
                                        <input type="date" id="editScreeningDate" name="ScreeningDate" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600" required>
                                    </div>
                                    <div>
                                        <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Screening Time:</label>
                                        <input type="time" id="editScreeningTime" name="ScreeningTime" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600" required>
                                    </div>
                                    <div>
                                        <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Room:</label>
                                        <select id="editScreeningRoomID" name="RoomID" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600 bg-white" required>
                                            <option value="" disabled>Select room</option>
                                            <?php foreach ($rooms as $room): ?>
                                                <option value="<?php echo htmlspecialchars($room['RoomID']); ?>"><?php echo htmlspecialchars($room['RoomLabel']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Price:</label>
                                        <input type="number" step="0.01" id="editScreeningPrice" name="Price" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600" required>
                                    </div>
                                    <div class="sm:col-span-2 text-right">
                                        <button type="submit" name="updateScreeningBtn" class="inline-block rounded-lg bg-orange-600 px-3 py-2 text-sm font-medium text-zinc-100 hover:bg-orange-500 transition ease-in-out duration-300">
                                            Update
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div id="deleteScreeningModal" tabindex="-1" aria-hidden="true" class="hidden fixed inset-0 z-50 items-center justify-center lg:pl-72 p-4">
                        <div class="relative max-w-md w-full max-h-[95vh] bg-zinc-100 rounded-lg shadow p-6 sm:p-8 overflow-y-auto">
                            <div class="flex justify-between items-center pb-4 mb-4 border-b border-zinc-200">
                                <h3 class="text-lg font-semibold text-zinc-900">Delete Screening</h3>
                                <button type="button" class="text-zinc-600 text-sm hover:text-zinc-900 transition ease-in-out duration-300" onclick="hideModal('deleteScreeningModal')">
                                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                        <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm-1.72 6.97a.75.75 0 1 0-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 1 0 1.06 1.06L12 13.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L13.06 12l1.72-1.72a.75.75 0 1 0-1.06-1.06L12 10.94l-1.72-1.72Z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                            <p id="deleteScreeningMovieTitle" class="mb-4 text-sm font-semibold text-zinc-900"></p>
                            <p class="mb-4 text-sm text-zinc-600">Are you sure you want to delete this screening? This action cannot be undone.</p>
                            <form id="deleteScreeningForm" method="POST">
                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                <input type="hidden" id="deleteScreeningID" name="deleteScreeningID">
                                <div class="flex justify-end gap-4">
                                    <button type="button" class="rounded-lg bg-zinc-600 px-3 py-2 text-sm font-medium text-zinc-100 hover:bg-zinc-800 transition ease-in-out duration-300" onclick="hideModal('deleteScreeningModal')">Cancel</button>
                                    <button type="submit" name="deleteScreeningBtn" class="rounded-lg bg-red-600 px-3 py-2 text-sm font-medium text-zinc-100 hover:bg-red-800 transition ease-in-out duration-300">Delete</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>
                <section id="news" class="mb-10">
                    <h2 class="text-3xl font-bold text-zinc-900">News</h2>
                    <p class="mt-5 text-base text-zinc-700">Manage news articles about the latest events, offers, and announcements related to the cinema here.</p>
                    <div class="mt-5">
                        <button type="button" onclick="showAddNewsModal()" class="inline-flex items-center rounded-lg bg-orange-600 px-3 py-2 text-sm font-medium text-white hover:bg-orange-500 transition ease-in-out duration-300">
                            <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            Add News
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full my-10 text-zinc-900">
                            <thead>
                                <tr>
                                    <th class="px-2 py-1 sm:px-4 sm:py-2 border-b-2 border-zinc-200 text-left text-xs font-semibold text-zinc-600 uppercase">ID</th>
                                    <th class="px-2 py-1 sm:px-4 sm:py-2 border-b-2 border-zinc-200 text-left text-xs font-semibold text-zinc-600 uppercase">Title</th>
                                    <th class="px-2 py-1 sm:px-4 sm:py-2 border-b-2 border-zinc-200 text-left text-xs font-semibold text-zinc-600 uppercase">Date Published</th>
                                    <th class="px-2 py-1 sm:px-4 sm:py-2 border-b-2 border-zinc-200 text-left text-xs font-semibold text-zinc-600 uppercase">Category</th>
                                    <th class="px-2 py-1 sm:px-4 sm:py-2 border-b-2 border-zinc-200 text-left text-xs font-semibold text-zinc-600 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($newsList as $news): ?>
                                <tr>
                                    <td class="px-2 py-1 sm:px-4 sm:py-2 border-b border-zinc-200 text-sm"><?php echo $news['NewsID']; ?></td>
                                    <td class="px-2 py-1 sm:px-4 sm:py-2 border-b border-zinc-200 text-sm"><?php echo $news['Title']; ?></td>
                                    <td class="px-2 py-1 sm:px-4 sm:py-2 border-b border-zinc-200 text-sm"><?php echo $news['DatePublished']; ?></td>
                                    <td class="px-2 py-1 sm:px-4 sm:py-2 border-b border-zinc-200 text-sm"><?php echo $news['Category']; ?></td>

                                    <td class="px-2 py-1 sm:px-4 sm:py-2 border-b border-zinc-200 text-sm">
                                        <button type="button" onclick="showPreviewNewsModal(<?php echo htmlspecialchars(json_encode($news)); ?>)" class="flex w-full items-center py-1 text-zinc-600 hover:text-zinc-900 transition ease-in-out duration-300">
                                            <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                                                <path fill-rule="evenodd" d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 0 1 0-1.113ZM17.25 12a5.25 5.25 0 1 1-10.5 0 5.25 5.25 0 0 1 10.5 0Z" clip-rule="evenodd" />
                                            </svg>    
                                            Preview
                                        </button>
                                        <button type="button" onclick="showEditNewsModal(<?php echo htmlspecialchars(json_encode($news)); ?>)" class="flex w-full items-center py-1 text-zinc-600 hover:text-zinc-900 transition ease-in-out duration-300">
                                            <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z" />
                                                <path d="M5.25 5.25a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3V13.5a.75.75 0 0 0-1.5 0v5.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V8.25a1.5 1.5 0 0 1 1.5-1.5h5.25a.75.75 0 0 0 0-1.5H5.25Z" />
                                            </svg>    
                                            Edit
                                        </button>
                                        <button type="button" onclick="showDeleteNewsModal(<?php echo htmlspecialchars(json_encode($news)); ?>)" class="flex w-full items-center py-1 text-red-600 hover:text-red-800 transition ease-in-out duration-300">
                                            <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                                <path fill-rule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z" clip-rule="evenodd" />
                                            </svg>    
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div id="addNewsModal" tabindex="-1" aria-hidden="true" class="hidden fixed inset-0 z-50 items-center justify-center lg:pl-72 p-4">
                        <div class="relative max-w-2xl w-full max-h-[95vh] bg-zinc-100 rounded-lg shadow p-6 sm:p-8 overflow-y-auto">
                            <div class="flex justify-between items-center pb-4 mb-4 border-b border-zinc-200">
                                <h3 class="text-lg font-semibold text-zinc-900">Add News Article</h3>
                                <button type="button" class="text-zinc-600 text-sm hover:text-zinc-900 transition ease-in-out duration-300" onclick="hideModal('addNewsModal')">
                                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                        <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm-1.72 6.97a.75.75 0 1 0-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 1 0 1.06 1.06L12 13.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L13.06 12l1.72-1.72a.75.75 0 1 0-1.06-1.06L12 10.94l-1.72-1.72Z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                            <form id="addNewsForm" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                <input type="hidden" name="addNewNews" value="1">
                                <div class="grid gap-4 sm:grid-cols-2">
                                    <div class="sm:col-span-2">
                                        <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Title:</label>
                                        <input type="text" name="Title" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600" required>
                                    </div>
                                    <div>
                                        <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Category:</label>
                                        <select name="Category" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600 bg-white" required>
                                            <option value="Announcement" selected>Announcement</option>
                                            <option value="Event">Event</option>
                                            <option value="Update">Update</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Date Published:</label>
                                        <input type="date" name="DatePublished" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600" required>
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Content:</label>
                                        <textarea name="Content" rows="6" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600" required></textarea>
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label for="newsImage">Upload Image:</label>
                                        <input type="file" name="newsImage" id="newsImage" accept="image/jpeg, image/png, image/gif">
                                    </div>
                                    <div class="sm:col-span-2 text-right">
                                        <button type="submit" name="addNewsBtn" class="inline-block rounded-lg bg-orange-600 px-3 py-2 text-sm font-medium text-zinc-100 hover:bg-orange-500 transition ease-in-out duration-300">
                                            Add News
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div id="previewNewsModal" tabindex="-1" aria-hidden="true" class="hidden fixed inset-0 z-50 items-center justify-center lg:pl-72 p-4">
                        <div class="relative max-w-2xl w-full max-h-[95vh] bg-zinc-100 rounded-lg shadow p-6 sm:p-8 overflow-y-auto">
                            <div class="flex justify-between items-center pb-4 mb-4 border-b border-zinc-200">
                                <h3 class="text-lg font-semibold text-zinc-900" id="previewNewsTitle">News Details</h3>
                                <button type="button" class="text-zinc-600 text-sm hover:text-zinc-900 transition ease-in-out duration-300" onclick="hideModal('previewNewsModal')">
                                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                        <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm-1.72 6.97a.75.75 0 1 0-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 1 0 1.06 1.06L12 13.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L13.06 12l1.72-1.72a.75.75 0 1 0-1.06-1.06L12 10.94l-1.72-1.72Z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Title:</label>
                                    <p id="previewNewsTitleText" class="text-sm text-zinc-900"></p>
                                </div>
                                <div>
                                    <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Category:</label>
                                    <p id="previewNewsCategory" class="text-sm text-zinc-900"></p>
                                </div>
                                <div>
                                    <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Date Published:</label>
                                    <p id="previewNewsDatePublished" class="text-sm text-zinc-900"></p>
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Content:</label>
                                    <p id="previewNewsContent" class="text-sm text-zinc-900"></p>
                                </div>
                                <div class="sm:col-span-2" id="previewNewsImageContainer">
                                    <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Image:</label>
                                    <img id="previewNewsImage" src="" alt="News Image Preview" class="w-32 h-auto">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="editNewsModal" tabindex="-1" aria-hidden="true" class="hidden fixed inset-0 z-50 items-center justify-center lg:pl-72 p-4">
                        <div class="relative max-w-2xl w-full max-h-[95vh] bg-zinc-100 rounded-lg shadow p-6 sm:p-8 overflow-y-auto">
                            <div class="flex justify-between items-center pb-4 mb-4 border-b border-zinc-200">
                                <h3 class="text-lg font-semibold text-zinc-900">Edit News</h3>
                                <button type="button" class="text-zinc-600 text-sm hover:text-zinc-900 transition ease-in-out duration-300" onclick="hideModal('editNewsModal')">
                                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                        <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm-1.72 6.97a.75.75 0 1 0-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 1 0 1.06 1.06L12 13.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L13.06 12l1.72-1.72a.75.75 0 1 0-1.06-1.06L12 10.94l-1.72-1.72Z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                            <form id="editNewsForm" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                <input type="hidden" id="editNewsID" name="NewsID">
                                <div class="grid gap-4 sm:grid-cols-2">
                                    <div class="sm:col-span-2">
                                        <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Title:</label>
                                        <input type="text" id="editNewsTitle" name="Title" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600" required>
                                    </div>
                                    <div>
                                        <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Category:</label>
                                        <select id="editNewsCategory" name="Category" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600 bg-white">
                                            <option value="Announcement" selected>Announcement</option>
                                            <option value="Event">Event</option>
                                            <option value="Update">Update</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Date Published:</label>
                                        <input type="date" id="editNewsDatePublished" name="DatePublished" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600" required>
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Content:</label>
                                        <textarea id="editNewsContent" name="Content" rows="6" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600" required></textarea>
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label for="newsImage">Upload Image:</label>
                                        <input type="file" name="newsImage" id="newsImage" accept="image/jpeg, image/png, image/gif">
                                    </div>
                                    <div class="sm:col-span-2 text-right">
                                        <button type="submit" name="updateNewsBtn" class="inline-block rounded-lg bg-orange-600 px-3 py-2 text-sm font-medium text-zinc-100 hover:bg-orange-500 transition ease-in-out duration-300">
                                            Update
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div id="deleteNewsModal" tabindex="-1" aria-hidden="true" class="hidden fixed inset-0 z-50 items-center justify-center lg:pl-72 p-4">
                        <div class="relative max-w-md w-full max-h-[95vh] bg-zinc-100 rounded-lg shadow p-6 sm:p-8 overflow-y-auto">
                            <div class="flex justify-between items-center pb-4 mb-4 border-b border-zinc-200">
                                <h3 class="text-lg font-semibold text-zinc-900">Delete News</h3>
                                <button type="button" class="text-zinc-600 text-sm hover:text-zinc-900 transition ease-in-out duration-300" onclick="hideModal('deleteNewsModal')">
                                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                        <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm-1.72 6.97a.75.75 0 1 0-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 1 0 1.06 1.06L12 13.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L13.06 12l1.72-1.72a.75.75 0 1 0-1.06-1.06L12 10.94l-1.72-1.72Z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                            <p id="deleteNewsTitle" class="mb-4 text-sm font-semibold text-zinc-900"></p>
                            <p class="mb-4 text-sm text-zinc-600">Are you sure you want to delete this news? This action cannot be undone.</p>
                            <form id="deleteNewsForm" method="POST">
                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                <input type="hidden" id="deleteNewsID" name="deleteNewsID">
                                <div class="flex justify-end gap-4">
                                    <button type="button" class="rounded-lg bg-zinc-600 px-3 py-2 text-sm font-medium text-zinc-100 hover:bg-zinc-800 transition ease-in-out duration-300" onclick="hideModal('deleteNewsModal')">Cancel</button>
                                    <button type="submit" name="deleteNewsBtn" class="rounded-lg bg-red-600 px-3 py-2 text-sm font-medium text-zinc-100 hover:bg-red-800 transition ease-in-out duration-300">Delete</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>
                <section id="cinema" class="mb-10">
                    <h2 class="text-3xl font-bold text-zinc-900">Cinema Details</h2>
                    <p class="mt-5 text-base text-zinc-700">Manage the company presentation such as contact details and other essential information here.</p>
                    <?php foreach ($cinemas as $cinema): ?>
                        <div class="mt-5">
                                <button 
                                    type="button" 
                                    onclick="showEditCinemaModal(<?php echo htmlspecialchars(json_encode($cinema), ENT_QUOTES, 'UTF-8'); ?>)" 
                                    class="inline-flex items-center rounded-lg bg-orange-600 px-3 py-2 text-sm font-medium text-white hover:bg-orange-500 transition ease-in-out duration-300">
                                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                    </svg>
                                    Edit Details
                                </button>
                        </div>
                        <div class="mt-10">
                            <dl class="text-sm text-zinc-900">
                                    <div class="divide-y divide-zinc-200">
                                        <div class="grid grid-cols-1 gap-1 py-3 sm:grid-cols-2">
                                            <dt class="text-xs font-semibold text-zinc-600 uppercase">Title</dt>
                                            <dd><?php echo htmlspecialchars($cinema['Tagline']); ?></dd>
                                        </div>
                                        <div class="grid grid-cols-1 gap-1 py-3 sm:grid-cols-2">
                                            <dt class="text-xs font-semibold text-zinc-600 uppercase">Description</dt>
                                            <dd><?php echo htmlspecialchars($cinema['Description']); ?></dd>
                                        </div>
                                        <div class="grid grid-cols-1 gap-1 py-3 sm:grid-cols-2">
                                            <dt class="text-xs font-semibold text-zinc-600 uppercase">Phone</dt>
                                            <dd><?php echo htmlspecialchars($cinema['PhoneNumber']); ?></dd>
                                        </div>
                                        <div class="grid grid-cols-1 gap-1 py-3 sm:grid-cols-2">
                                            <dt class="text-xs font-semibold text-zinc-600 uppercase">Email</dt>
                                            <dd><?php echo htmlspecialchars($cinema['Email']); ?></dd>
                                        </div>
                                        <div class="grid grid-cols-1 gap-1 py-3 sm:grid-cols-2">
                                            <dt class="text-xs font-semibold text-zinc-600 uppercase">Opening Hours</dt>
                                            <dd><?php echo htmlspecialchars($cinema['OpeningHours']); ?></dd>
                                        </div>
                                        <div class="grid grid-cols-1 gap-1 py-3 sm:grid-cols-2">
                                            <dt class="text-xs font-semibold text-zinc-600 uppercase">Image</dt>
                                            <dd>
                                                <?php if (!empty($cinema['ImageURL'])): ?>
                                                    <img src="<?php echo htmlspecialchars($cinema['ImageURL']); ?>" alt="Cinema Image" class="w-32 h-auto">
                                                <?php else: ?>
                                                    <span>No image available.</span>
                                                <?php endif; ?>
                                            </dd>
                                        </div>
                                    </div>
                            </dl>
                        </div>
                    <?php endforeach; ?>
                    <div id="editCinemaModal" tabindex="-1" aria-hidden="true" class="hidden fixed inset-0 z-50 items-center justify-center lg:pl-72 p-4">
                        <div class="relative max-w-2xl w-full max-h-[95vh] bg-zinc-100 rounded-lg shadow p-6 sm:p-8 overflow-y-auto">
                            <div class="flex justify-between items-center pb-4 mb-4 border-b border-zinc-200">
                                <h3 class="text-lg font-semibold text-zinc-900">Edit Cinema Details</h3>
                                <button type="button" class="text-zinc-600 text-sm hover:text-zinc-900" onclick="hideModal('editCinemaModal')">
                                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                        <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm-1.72 6.97a.75.75 0 1 0-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 1 0 1.06 1.06L12 13.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L13.06 12l1.72-1.72a.75.75 0 1 0-1.06-1.06L12 10.94l-1.72-1.72Z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                            <form id="editCinemaForm" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                <input type="hidden" id="editCinemaID" name="CinemaID">
                                <div class="grid gap-4 sm:grid-cols-2">
                                    <div class="sm:col-span-2">
                                        <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Title:</label>
                                        <input type="text" id="editCinemaTitle" name="Tagline" required class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600">
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Description:</label>
                                        <textarea id="editCinemaDescription" name="Description" required class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600"></textarea>
                                    </div>
                                    <div>
                                        <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Phone:</label>
                                        <input type="text" id="editCinemaPhone" name="PhoneNumber" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600">
                                    </div>
                                    <div>
                                        <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Email:</label>
                                        <input type="email" id="editCinemaEmail" name="Email" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600">
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label class="block mb-1 text-xs font-semibold text-zinc-600 uppercase">Opening Hours:</label>
                                        <textarea id="editCinemaOpeningHours" name="OpeningHours" class="w-full p-2 border border-zinc-300 rounded-md text-sm text-zinc-900 focus:outline-none focus:ring-1 focus:ring-orange-600"></textarea>
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label for="cinemaImage">Upload Image:</label>
                                        <input type="file" name="cinemaImage" id="cinemaImage" accept="image/jpeg, image/png, image/gif">
                                    </div>
                                    <div class="sm:col-span-2 text-right">
                                        <button type="submit" name="updateCinemaBtn" class="inline-block rounded-lg bg-orange-600 px-3 py-2 text-sm font-medium text-zinc-100 hover:bg-orange-500 transition ease-in-out duration-300">
                                            Update
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>
                <div id="modalBackdrop" class="hidden fixed inset-0 z-40 bg-zinc-900 bg-opacity-50 lg:pl-72"></div>
            </main>
        </div>
    </div>
    <script src="../../../public/js/crud-modals.js"></script>
</body>
</html>