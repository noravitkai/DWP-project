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
    <title>Checkout</title>
    <link href="../../../public/css/tailwind.css" rel="stylesheet">
</head>
<body class="bg-zinc-800">
    <main class="mx-auto max-w-screen-xl px-4 py-8 sm:px-6 sm:py-12 lg:px-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-orange-600 mb-6">Checkout</h1>
        <div class="bg-zinc-700 p-6 rounded-lg text-zinc-200">
            <h2 class="text-xl font-semibold">Reservation Summary</h2>
            <p><strong>Movie:</strong> <?php echo $screeningDetails['MovieTitle']; ?></p>
            <p><strong>Date:</strong> <?php echo $screeningDetails['ScreeningDate']; ?></p>
            <p><strong>Time:</strong> <?php echo $screeningDetails['ScreeningTime']; ?></p>
            <p><strong>Seats:</strong> <?php echo implode(', ', array_column($selectedSeats, 'SeatNumber')); ?></p>
            <p><strong>Total Price:</strong> $<?php echo number_format($totalPrice, 2); ?></p>
        </div>
        <form action="#" method="POST" class="mt-6">
            <input type="hidden" name="reservation_id" value="<?php echo $reservationId; ?>">
            <button type="submit" class="mt-6 inline-flex items-center rounded-lg bg-orange-600 px-4 py-2 text-sm font-medium text-white hover:bg-orange-500 transition ease-in-out duration-300">
                Proceed to Payment
            </button>
        </form>
    </main>
</body>
</html>