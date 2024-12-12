<?php
require_once '../../../config/session.php';
require_once '../../../config/env_loader.php';
require_once '../../Controllers/ReservationController.php';
require_once '../../Controllers/ScreeningController.php';
require_once '../../Controllers/PaymentController.php';
require_once '../../../stripe-php/init.php';
require_once '../../../config/functions.php';

use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

if (isset($_GET['reservation_id'])) {
    $reservationId = sanitizeInput($_GET['reservation_id']);
} else {
    $reservationId = null;
}

if (isset($_GET['session_id'])) {
    $sessionId = sanitizeInput($_GET['session_id']);
} else {
    $sessionId = null;
}

if (!$reservationId || !is_numeric($reservationId) || !$sessionId) {
    header("Location: home_page.php");
    exit();
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
        $confirmation = $reservationController->confirmReservation((int)$reservationId);
    } else {
        $paymentController = new PaymentController();
        $paymentController->updatePaymentStatus($sessionId, 'Pending');
        $confirmation = false;
    }
} catch (Exception $e) {
    die('Error retrieving Stripe session: ' . htmlspecialchars($e->getMessage()));
}

$reservationController = new ReservationController();
$reservationDetails = $reservationController->getReservationById((int)$reservationId);

if (!$reservationDetails) {
    header("Location: home_page.php");
    exit();
}

$screeningController = new ScreeningController();
$screeningDetails = $screeningController->getScreeningById($reservationDetails['ScreeningID']);
$selectedSeats = $reservationController->getSeatsByReservationId((int)$reservationId);

if (!empty($reservationDetails['CustomerID'])) {
    require_once '../../Controllers/CustomerController.php';
    $customerId = $reservationDetails['CustomerID'];
    $customerController = new CustomerController();
    $customerDetails = $customerController->getCustomerProfile($customerId);
    $customerName = $customerDetails['FirstName'] . ' ' . $customerDetails['LastName'];
    $customerEmail = $customerDetails['Email'];
} else {
    $customerName = $reservationDetails['GuestFirstName'] . ' ' . $reservationDetails['GuestLastName'];
    $customerEmail = $reservationDetails['GuestEmail'];
}

$movieTitle = $screeningDetails['MovieTitle'];
$screeningTime = date("Y-m-d H:i", strtotime($screeningDetails['ScreeningDate'] . ' ' . $screeningDetails['ScreeningTime']));
$ticketPrice = number_format($screeningDetails['Price'], 2);
$numberOfSeats = $reservationDetails['NumberOfSeats'];
$totalPrice = number_format($numberOfSeats * $screeningDetails['Price'], 2);
$seatList = array_map(function ($seat) {
    return $seat['RowLabel'] . $seat['SeatNumber'];
}, $selectedSeats);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Successful</title>
    <link href="../../../public/css/tailwind.css" rel="stylesheet">
</head>
<body class="bg-zinc-800 text-zinc-200"
      data-reservation-id="<?php echo $reservationId; ?>"
      data-screening-id="<?php echo $screeningDetails['ScreeningID']; ?>">
    <?php include '../frontend/frontend_navigation.php'; ?>
    <main class="mx-auto max-w-screen-xl px-4 py-8 sm:px-6 sm:py-12 lg:px-8">
        <header class="mb-8">
            <div class="flex items-center gap-2 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" 
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                     class="h-8 w-8 text-orange-600">
                    <path stroke-linecap="round" stroke-linejoin="round" 
                          d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                </svg>
                <h1 class="text-xl font-bold text-orange-600 sm:text-3xl">Payment Successful</h1>
            </div>
            <p class="max-w-4xl text-sm sm:text-base text-zinc-300">
                Thank you! Your payment for the movie <span class="font-bold text-zinc-100"><?php echo $movieTitle; ?></span> was successful.  
                You paid a total of <span class="font-bold"><?php echo $totalPrice; ?> DKK</span>.
            </p>
        </header>
        <div class="mb-8">
            <a href="home_page.php" class="inline-block rounded-lg bg-orange-600 px-4 py-2 text-sm font-medium text-zinc-100 hover:bg-orange-500 transition ease-in-out duration-300">
                Return to Home
            </a>
        </div>
        <div class="bg-zinc-800 p-6 border border-zinc-700">
            <h2 class="text-base font-semibold text-orange-600 sm:text-lg uppercase mb-4">Invoice</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-6 text-sm sm:text-base text-zinc-300">
                <div>
                    <span class="block text-xs sm:text-sm text-zinc-100 uppercase">Customer's Name:</span>
                    <span><?php echo $customerName; ?></span>
                </div>
                <div>
                    <span class="block text-xs sm:text-sm text-zinc-100 uppercase">Email:</span>
                    <span><?php echo $customerEmail; ?></span>
                </div>
                <div>
                    <span class="block text-xs sm:text-sm text-zinc-100 uppercase">Movie Title:</span>
                    <span><?php echo $movieTitle; ?></span>
                </div>
                <div>
                    <span class="block text-xs sm:text-sm text-zinc-100 uppercase">Reserved Seats:</span>
                    <span><?php echo implode(', ', $seatList); ?></span>
                </div>
                <div>
                    <span class="block text-xs sm:text-sm text-zinc-100 uppercase">Date:</span>
                    <span><?php echo date("Y-m-d", strtotime($screeningDetails['ScreeningDate'])); ?></span>
                </div>
                <div>
                    <span class="block text-xs sm:text-sm text-zinc-100 uppercase">Starting Time:</span>
                    <span><?php echo date("H:i", strtotime($screeningDetails['ScreeningTime'])); ?></span>
                </div>
                <div>
                    <span class="block text-xs sm:text-sm text-zinc-100 uppercase">Number of Seats:</span>
                    <span><?php echo $numberOfSeats; ?></span>
                </div>
                <div>
                    <span class="block text-xs sm:text-sm text-zinc-100 uppercase">Ticket Price:</span>
                    <span><?php echo $ticketPrice; ?> DKK</span>
                </div>
            </div>
            <p class="mt-4 text-sm sm:text-base font-medium text-orange-600 uppercase">
                Total: <span class="text-base sm:text-lg font-bold text-zinc-100"><?php echo $totalPrice; ?> DKK</span>
            </p>
        </div>
    </main>
    <?php include '../frontend/footer.php'; ?>
</body>
</html>