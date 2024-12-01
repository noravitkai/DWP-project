<?php
require_once '../../../config/session.php';
require_once '../../Controllers/ReservationController.php';
require_once '../../Controllers/ScreeningController.php';

$reservationId = $_GET['reservation_id'] ?? null;

if (!$reservationId || !is_numeric($reservationId)) {
    die("Invalid reservation ID.");
}

$reservationController = new ReservationController();
$screeningController = new ScreeningController();
$reservationDetails = $reservationController->getReservationById($reservationId);

if (!$reservationDetails) {
    die("Reservation not found.");
}

$screeningDetails = $screeningController->getScreeningById($reservationDetails['ScreeningID']);
$movieTitle = $screeningDetails['MovieTitle'];
$totalPrice = number_format($reservationDetails['NumberOfSeats'] * $screeningDetails['Price'], 2);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful</title>
    <link href="../../../public/css/tailwind.css" rel="stylesheet">
</head>
<body class="bg-zinc-900 text-zinc-200 min-h-screen flex items-center justify-center">
    <div class="container max-w-4xl mx-auto p-6">
        <div class="bg-zinc-800 shadow-md rounded-lg p-8 text-center">
            <div class="flex justify-center mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-8 w-8 text-orange-600">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-orange-600 mb-4">Payment Successful</h1>
            <p class="text-lg mb-6 text-zinc-300">
                Thank you! Your payment for the movie <span class="font-bold text-zinc-100"><?php echo $movieTitle; ?></span> was successful.
            </p>
            <p class="text-lg mb-6">
                You paid a total of <span class="font-bold"><?php echo $totalPrice; ?> DKK</span>.
            </p>
            <a href="home_page.php" class="inline-block rounded-lg bg-orange-600 px-4 py-2 text-sm font-medium text-white hover:bg-orange-500 transition ease-in-out duration-300">
                Return to Home
            </a>
        </div>
    </div>
</body>
</html>