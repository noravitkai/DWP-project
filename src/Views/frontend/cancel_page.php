<?php
require_once '../../../config/session.php';
require_once '../../../config/env_loader.php';
require_once '../../Controllers/PaymentController.php';
require_once '../../Controllers/ReservationController.php';

$reservationId = $_GET['reservation_id'] ?? null;

if ($reservationId && is_numeric($reservationId)) {
    $paymentController = new PaymentController();
    $payment = $paymentController->getPaymentByReservationId($reservationId);

    if ($payment) {
        $paymentController->updatePaymentStatus($payment['StripeSessionID'], 'Canceled');
    }

    $reservationController = new ReservationController();
    $reservationController->cancelReservation($reservationId);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Canceled</title>
    <link href="../../../public/css/tailwind.css" rel="stylesheet">
</head>
<body class="bg-zinc-900 text-zinc-200 min-h-screen flex items-center justify-center">
    <div class="container max-w-4xl mx-auto p-6">
        <div class="bg-zinc-800 shadow-md p-8 mb-8">
            <div class="flex items-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-8 w-8 text-red-600 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <h1 class="text-2xl sm:text-3xl font-bold text-red-600">Payment Canceled</h1>
            </div>
            <p class="text-sm sm:text-base text-zinc-300 mb-6">
                Unfortunately, your payment was canceled. If this was a mistake, please try again.
            </p>
            <a href="home_page.php" class="inline-block rounded-lg bg-orange-600 px-4 py-2 text-sm font-medium text-white hover:bg-orange-500 transition ease-in-out duration-300">
                Return to Home
            </a>
        </div>
    </div>
</body>
</html>