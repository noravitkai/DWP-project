<?php
require_once '../../../config/session.php';
require_once '../../../config/functions.php';
require_once '../../../config/env_loader.php';
require_once '../../Controllers/ReservationController.php';
require_once '../../Controllers/ScreeningController.php';

$reservationController = new ReservationController();
$screeningController = new ScreeningController();

if (isset($_GET['reservation_id'])) {
    $reservationId = sanitizeInput($_GET['reservation_id']);
} else {
    $reservationId = null;
}

if (!$reservationId || !is_numeric($reservationId)) {
    header("Location: home_page.php");
    exit();
}

$reservationId = intval($reservationId);
$reservationDetails = $reservationController->getReservationById($reservationId);

if (!$reservationDetails) {
    header("Location: home_page.php");
    exit();
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

$formMessage = $_SESSION['form_message'] ?? null;
unset($_SESSION['form_message']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <link href="../../../public/css/tailwind.css" rel="stylesheet">
</head>
<body class="bg-zinc-800 text-zinc-200">
    <?php include '../frontend/frontend_navigation.php'; ?>
    <main class="mx-auto max-w-screen-xl px-4 py-8 sm:px-6 sm:py-12 lg:px-8">
        <?php if ($formMessage && strpos($formMessage, 'successfully') !== false): ?>
            <div class="mb-4 p-4 bg-green-600 text-white rounded">
                <?php echo $formMessage; ?>
            </div>
        <?php endif; ?>
        <header>
            <h1 class="text-xl font-bold text-orange-600 sm:text-3xl">Checkout</h1>
            <p class="mt-4 max-w-4xl text-sm sm:text-base text-zinc-300">
                Review the screening and reservation details below. Please note that you must arrive at least 20 minutes before the movie starts.
            </p>
        </header>
        <section class="flex flex-col gap-8 my-8 text-sm sm:text-base text-zinc-300">
            <div>
                <h2 class="block text-orange-600 uppercase mb-0.5">Screening Details:</h2>
                <p>
                    <span class="font-medium">Movie:</span> <?php echo $screeningDetails['MovieTitle']; ?>
                </p>
                <p>
                    <span class="font-medium">Date:</span> <?php echo $screeningDetails['ScreeningDate']; ?>
                </p>
                <p>
                    <span class="font-medium">Time:</span> <?php echo $screeningDetails['ScreeningTime']; ?>
                </p>
            </div>
            <div>
                <h2 class="block text-orange-600 uppercase mb-0.5">Reservation Details:</h2>
                <p>
                    <span class="font-medium">Seats:</span> 
                    <?php 
                        echo implode(', ', array_map(function ($seat) {
                            return $seat['RowLabel'] . $seat['SeatNumber'];
                        }, $selectedSeats)); 
                    ?>
                </p>
                <p>
                    <span class="font-medium">Number of Seats:</span> <?php echo $numberOfSeats; ?>
                </p>
                <p>
                    <span class="font-medium">Ticket Price:</span> <?php echo number_format($ticketPrice, 2); ?> DKK
                </p>
                <p class="mt-8">
                    <span class="font-medium text-orange-600 uppercase">Total Price:</span>
                    <span class="text-base sm:text-lg font-bold text-zinc-100">
                        <?php echo number_format($totalPrice, 2); ?> DKK
                    </span>
                </p>
            </div>
        </section>
        <hr class="border-zinc-700 mb-8">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <a href="home_page.php" class="inline-flex items-center gap-1 text-sm font-medium text-orange-600 hover:text-orange-500 transition ease-in-out duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" 
                     stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
                Back to Home
            </a>
            <form action="payment_page.php" method="POST" class="inline-block">
                <input type="hidden" name="reservation_id" value="<?php echo $reservationId; ?>">
                <input type="hidden" name="amount" value="<?php echo ($totalPrice * 100); ?>">
                <input type="hidden" name="currency" value="DKK">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <button type="submit" class="inline-flex items-center rounded-lg bg-orange-600 px-4 py-2 text-sm font-medium text-white hover:bg-orange-500 transition ease-in-out duration-300">
                    Proceed to Payment
                </button>
            </form>
        </div>
    </main>
    <?php include '../frontend/footer.php'; ?>
</body>
</html>