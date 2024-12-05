<?php
require_once '../../../config/session.php';
require_once '../../../config/env_loader.php';
require_once '../../Controllers/ReservationController.php';
require_once '../../Controllers/ScreeningController.php';
require_once '../../Controllers/PaymentController.php';
require_once '../../../stripe-php/init.php';

use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

$reservationId = $_GET['reservation_id'] ?? null;
$sessionId = $_GET['session_id'] ?? null;

if (!$reservationId || !is_numeric($reservationId) || !$sessionId) {
    die("Invalid reservation or session ID.");
}

$stripeSecretKey = getenv('STRIPE_SECRET_KEY');
if (!$stripeSecretKey) {
    die('Stripe secret key is not set. Please check your .env file.');
}
Stripe::setApiKey($stripeSecretKey);

try {
    $checkoutSession = StripeSession::retrieve($sessionId);

    if ($checkoutSession->payment_status === 'paid') {
        $paymentController = new PaymentController();
        $paymentController->updatePaymentStatus($sessionId, 'Completed', $checkoutSession->payment_intent);

        $reservationController = new ReservationController();
        $confirmation = $reservationController->confirmReservation($reservationId);
    } else {
        $paymentController = new PaymentController();
        $paymentController->updatePaymentStatus($sessionId, 'Pending');
        $confirmation = false;
    }
} catch (Exception $e) {
    die('Error retrieving Stripe session: ' . htmlspecialchars($e->getMessage()));
}

$reservationController = new ReservationController();
$reservationDetails = $reservationController->getReservationById($reservationId);
$screeningController = new ScreeningController();
$screeningDetails = $screeningController->getScreeningById($reservationDetails['ScreeningID']);
$selectedSeats = $reservationController->getSeatsByReservationId($reservationId);

if (!empty($reservationDetails['CustomerID'])) {
    require_once '../../Controllers/CustomerController.php';
    $customerId = $reservationDetails['CustomerID'];
    $customerController = new CustomerController();
    $customerDetails = $customerController->getCustomerProfile($customerId);
    $customerName = htmlspecialchars($customerDetails['FirstName'] . ' ' . $customerDetails['LastName']);
    $customerEmail = htmlspecialchars($customerDetails['Email']);
} else {
    $customerName = htmlspecialchars($reservationDetails['GuestFirstName'] . ' ' . $reservationDetails['GuestLastName']);
    $customerEmail = htmlspecialchars($reservationDetails['GuestEmail']);
}

$movieTitle = htmlspecialchars($screeningDetails['MovieTitle']);
$screeningTime = date("Y-m-d H:i", strtotime($screeningDetails['ScreeningDate'] . ' ' . $screeningDetails['ScreeningTime']));
$ticketPrice = number_format($screeningDetails['Price'], 2);
$numberOfSeats = htmlspecialchars($reservationDetails['NumberOfSeats']);
$totalPrice = number_format($numberOfSeats * $screeningDetails['Price'], 2);
$seatList = array_map(function ($seat) {
    return htmlspecialchars($seat['RowLabel'] . $seat['SeatNumber']);
}, $selectedSeats);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Successful</title>
    <link href="../../../public/css/tailwind.css" rel="stylesheet">
</head>
<body class="bg-zinc-900 text-zinc-200 min-h-screen flex items-center justify-center">
    <div class="container max-w-4xl mx-auto p-6">
        <div class="bg-zinc-800 shadow-md p-8 mb-8">
            <div class="flex items-center mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-8 w-8 text-orange-600 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75M21 12A9 9 0 11 3 12a9 9 0 0118 0z" />
                </svg>
                <h1 class="text-2xl sm:text-3xl font-bold text-orange-600">Payment Successful</h1>
            </div>
            <p class="text-sm sm:text-base text-zinc-300">
                Thank you! Your payment for the movie <span class="font-bold text-zinc-100"><?php echo $movieTitle; ?></span> was successful.
            </p>
            <p class="text-sm sm:text-base text-zinc-300 mb-6">
                You paid a total of <span class="font-bold"><?php echo $totalPrice; ?> DKK</span>.
            </p>
            <a href="home_page.php" class="inline-block rounded-lg bg-orange-600 px-4 py-2 text-sm font-medium text-zinc-100 hover:bg-orange-500 transition ease-in-out duration-300">
                Return to Home
            </a>
        </div>
        <div class="bg-zinc-800 shadow-lg border-2 border-zinc-300 p-6">
            <h2 class="text-semibold sm:text-lg text-zinc-200 uppercase mb-2 text-left">Invoice</h2>
            <p class="mb-6 text-sm sm:text-base text-zinc-300">Review and keep this information. If any data is incorrect or missing, please contact us!</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-6">
                <div class="mb-4">
                    <p>
                        <span class="block text-xs sm:text-sm text-zinc-100 uppercase">Customer's Name:</span>
                        <span class="text-sm sm:text-base text-zinc-300"><?php echo $customerName; ?></span>
                    </p>
                </div>
                <div class="mb-4">
                    <p>
                        <span class="block text-xs sm:text-sm text-zinc-100 uppercase">Email:</span>
                        <span class="text-sm sm:text-base text-zinc-300"><?php echo $customerEmail; ?></span>
                    </p>
                </div>
                <div class="mb-4">
                    <p>
                        <span class="block text-xs sm:text-sm text-zinc-100 uppercase">Movie Title:</span>
                        <span class="text-sm sm:text-base text-zinc-300"><?php echo $movieTitle; ?></span>
                    </p>
                </div>
                <div class="mb-4">
                    <p>
                        <span class="block text-xs sm:text-sm text-zinc-100 uppercase">Reserved Seats:</span>
                        <span class="text-sm sm:text-base text-zinc-300"><?php echo implode(', ', $seatList); ?></span>
                    </p>
                </div>
                <div class="mb-4">
                    <p>
                        <span class="block text-xs sm:text-sm text-zinc-100 uppercase">Date:</span>
                        <span class="text-sm sm:text-base text-zinc-300"><?php echo date("Y-m-d", strtotime($screeningDetails['ScreeningDate'])); ?></span>
                    </p>
                </div>
                <div class="mb-4">
                    <p>
                        <span class="block text-xs sm:text-sm text-zinc-100 uppercase">Starting Time:</span>
                        <span class="text-sm sm:text-base text-zinc-300"><?php echo date("H:i", strtotime($screeningDetails['ScreeningTime'])); ?></span>
                    </p>
                </div>
                <div class="mb-4">
                    <p>
                        <span class="block text-xs sm:text-sm text-zinc-100 uppercase">Number of Seats:</span>
                        <span class="text-sm sm:text-base text-zinc-300"><?php echo $numberOfSeats; ?></span>
                    </p>
                </div>
                <div class="mb-4">
                    <p>
                        <span class="block text-xs sm:text-sm text-zinc-100 uppercase">Ticket Price:</span>
                        <span class="text-sm sm:text-base text-zinc-300"><?php echo $ticketPrice; ?> DKK</span>
                    </p>
                </div>
            </div>
            <p class="mt-2 text-lg text-orange-600 font-bold text-left">
                Total: <span class="text-2xl"><?php echo $totalPrice; ?> DKK</span>
            </p>
        </div>
    </div>
</body>
</html>