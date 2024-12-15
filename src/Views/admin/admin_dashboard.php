<?php
include '../../../config/admin_session.php';
require_once '../../Controllers/MovieController.php';
require_once '../../Controllers/ScreeningController.php';
require_once '../../Controllers/NewsController.php';
require_once '../../Controllers/CinemaController.php';
require_once '../../Controllers/ReservationController.php';
require_once '../../../config/functions.php';

$movieController = new MovieController();
$screeningController = new ScreeningController();
$newsController = new NewsController();
$cinemaController = new CinemaController();
$reservationController = new ReservationController();

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
$screenings = $screeningController->index();
$rooms = $screeningController->getRooms();
$newsList = $newsController->index();
$cinemas = $cinemaController->index();
$reservations = $reservationController->index();
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
                <?php include 'movie_crud.php'; ?>
                <?php include 'screening_crud.php'; ?>
                <?php include 'news_crud.php'; ?>
                <?php include 'cinema_edit.php'; ?>
                <?php include 'reservation_overview.php'; ?>
                <div id="modalBackdrop" class="hidden fixed inset-0 z-40 bg-zinc-900 bg-opacity-50 lg:pl-72"></div>
            </main>
        </div>
    </div>
    <script src="../../../public/js/crud-modals.js"></script>
</body>
</html>