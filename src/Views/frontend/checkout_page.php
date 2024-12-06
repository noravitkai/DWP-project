<?php
require_once '../../../config/session.php';
require_once '../../../config/functions.php';
require_once '../../../config/env_loader.php';
require_once '../../Controllers/ReservationController.php';
require_once '../../Controllers/ScreeningController.php';

$reservationController = new ReservationController();
$screeningController = new ScreeningController();

if (!isset($_GET['reservation_id']) || !is_numeric($_GET['reservation_id'])) {
    die("Invalid reservation ID.");
}

$reservationId = intval($_GET['reservation_id']);
$reservationDetails = $reservationController->getReservationById($reservationId);

if (!$reservationDetails) {
    die("Reservation not found.");
}

$screeningDetails = $screeningController->getScreeningById($reservationDetails['ScreeningID']);
$selectedSeats = $reservationController->getSeatsByReservationId($reservationId);

$ticketPrice = $screeningDetails['Price'];
$numberOfSeats = $reservationDetails['NumberOfSeats'];
$totalPrice = $ticketPrice * $numberOfSeats;

$reservationToken = $reservationDetails['ReservationToken'] ?? '';

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="../../../public/css/tailwind.css" rel="stylesheet">
</head>
<body class="bg-zinc-900 text-zinc-200 min-h-screen flex items-center justify-center"
      data-reservation-id="<?php echo htmlspecialchars($reservationId, ENT_QUOTES, 'UTF-8'); ?>"
      data-screening-id="<?php echo htmlspecialchars($screeningDetails['ScreeningID'], ENT_QUOTES, 'UTF-8'); ?>">
    <div class="container max-w-4xl mx-auto p-6">
        <div class="bg-zinc-800 shadow-md rounded-lg p-8">
            <header class="mb-6">
                <h1 class="text-3xl font-bold text-orange-500">Checkout</h1>
                <p class="text-zinc-400 mt-2">Review your reservation details below. Please note that you have to arrive at the cinema at least 20 minutes before the movie starts!</p>
            </header>
            <section class="mb-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h2 class="text-xl font-semibold text-orange-400 mb-2">Movie Information</h2>
                        <p>Movie: <?php echo htmlspecialchars($screeningDetails['MovieTitle'], ENT_QUOTES, 'UTF-8'); ?></p>
                        <p>Date: <?php echo htmlspecialchars($screeningDetails['ScreeningDate'], ENT_QUOTES, 'UTF-8'); ?></p>
                        <p>Starting Time: <?php echo htmlspecialchars($screeningDetails['ScreeningTime'], ENT_QUOTES, 'UTF-8'); ?></p>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-orange-400 mb-2">Reservation Details</h2>
                        <p>Seats: 
                            <?php 
                                echo implode(', ', array_map(function ($seat) {
                                    return htmlspecialchars($seat['RowLabel'] . $seat['SeatNumber'], ENT_QUOTES, 'UTF-8');
                                }, $selectedSeats)); 
                            ?>
                        </p>
                        <p>Number of Seats: <?php echo htmlspecialchars($numberOfSeats, ENT_QUOTES, 'UTF-8'); ?></p>
                        <p>Ticket Price: <?php echo number_format($ticketPrice, 2); ?> DKK</p>
                        <p class="mt-2 text-lg text-orange-500">Total Price:<span class="text-xl font-bold"><?php echo number_format($totalPrice, 2); ?> DKK</span></p>
                    </div>
                </div>
            </section>
            <hr class="border-zinc-700 my-6">
            <section class="flex items-center justify-between">
                <a href="home_page.php" id="backToHomeBtn" class="inline-flex items-center gap-1 text-sm font-medium text-orange-600 hover:text-orange-500 transition ease-in-out duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                    Back to Home
                </a>
                <form action="payment_page.php" method="POST" id="proceedToPaymentForm">
                    <input type="hidden" name="reservation_id" value="<?php echo htmlspecialchars($reservationId, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="amount" value="<?php echo htmlspecialchars($totalPrice * 100, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="currency" value="DKK">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
                    <button type="submit" class="inline-block rounded-lg bg-orange-600 px-3 py-2 text-sm font-medium text-zinc-100 hover:bg-orange-500 transition ease-in-out duration-300">
                        Proceed to Payment
                    </button>
                </form>
            </section>
        </div>
    </div>
</body>
</html>