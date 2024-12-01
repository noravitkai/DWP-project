<?php
require_once '../../../config/session.php';
require_once '../../Controllers/ReservationController.php';
require_once '../../Controllers/ScreeningController.php';

$reservationController = new ReservationController();
$screeningController = new ScreeningController();

if (!isset($_GET['reservation_id']) || !is_numeric($_GET['reservation_id'])) {
    die("Invalid reservation ID.");
}

$reservationId = $_GET['reservation_id'];
$reservationDetails = $reservationController->getReservationById($reservationId);

if (!$reservationDetails) {
    die("Reservation not found.");
}

$screeningDetails = $screeningController->getScreeningById($reservationDetails['ScreeningID']);
$selectedSeats = $reservationController->getSeatsByReservationId($reservationId);

$ticketPrice = $screeningDetails['Price'];
$numberOfSeats = $reservationDetails['NumberOfSeats'];
$totalPrice = $ticketPrice * $numberOfSeats;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="../../../public/css/tailwind.css" rel="stylesheet">
</head>
<body class="bg-zinc-900 text-zinc-200 min-h-screen flex items-center justify-center">
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
                        <p><strong>Movie:</strong> <?php echo $screeningDetails['MovieTitle']; ?></p>
                        <p><strong>Date:</strong> <?php echo $screeningDetails['ScreeningDate']; ?></p>
                        <p><strong>Starting Time:</strong> <?php echo $screeningDetails['ScreeningTime']; ?></p>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-orange-400 mb-2">Reservation Details</h2>
                        <p><strong>Seats:</strong> 
                            <?php 
                                echo implode(', ', array_map(function ($seat) {
                                    return $seat['RowLabel'] . $seat['SeatNumber'];
                                }, $selectedSeats)); 
                            ?>
                        </p>
                        <p><strong>Number of Seats:</strong> <?php echo $numberOfSeats; ?></p>
                        <p><strong>Ticket Price: </strong><?php echo number_format($ticketPrice, 2); ?> DKK</p>
                        <p class="mt-2 text-lg"><strong class="text-orange-500">Total Price:</strong> <span class="text-xl font-bold"><?php echo number_format($totalPrice, 2); ?> DKK</span></p>
                    </div>
                </div>
            </section>
            <hr class="border-zinc-700 my-6">
            <section class="flex items-center justify-between">
                <a href="home_page.php" class="inline-flex items-center gap-1 text-sm font-medium text-orange-600 hover:text-orange-500 transition ease-in-out duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                    Back to Home
                </a>
                <form action="payment_page.php" method="POST">
                    <input type="hidden" name="reservation_id" value="<?php echo $reservationId; ?>">
                    <input type="hidden" name="amount" value="<?php echo $totalPrice * 100; ?>">
                    <input type="hidden" name="currency" value="DKK">
                    <button type="submit" class="inline-block rounded-lg bg-orange-600 px-3 py-2 text-sm font-medium text-zinc-100 hover:bg-orange-500 transition ease-in-out duration-300">
                        Proceed to Payment
                    </button>
                </form>
            </section>
        </div>
    </div>
</body>
</html>