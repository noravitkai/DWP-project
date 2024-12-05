<?php
require_once __DIR__ . '/../../../stripe-php/init.php';
require_once '../../../config/session.php';
require_once '../../../config/env_loader.php';
require_once '../../Controllers/PaymentController.php';
require_once '../../Controllers/ReservationController.php';
require_once '../../Controllers/ScreeningController.php';

use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stripeSecretKey = getenv('STRIPE_SECRET_KEY');

    if (!$stripeSecretKey) {
        die('Stripe secret key is not set. Please check your .env file.');
    }

    Stripe::setApiKey($stripeSecretKey);

    $reservationId = $_POST['reservation_id'];
    $amount = $_POST['amount'];
    $currency = $_POST['currency'];

    if (!is_numeric($reservationId) || !is_numeric($amount) || !in_array($currency, ['DKK'])) {
        die("Invalid input data.");
    }

    $paymentController = new PaymentController();
    $reservationController = new ReservationController();
    $screeningController = new ScreeningController();

    $reservationDetails = $reservationController->getReservationById($reservationId);
    if (!$reservationDetails) {
        die("Reservation not found.");
    }

    $screeningDetails = $screeningController->getScreeningById($reservationDetails['ScreeningID']);
    if (!$screeningDetails) {
        die("Screening details not found.");
    }

    $ticketPrice = $screeningDetails['Price'];
    $numberOfSeats = $reservationDetails['NumberOfSeats'];
    $totalPrice = $ticketPrice * $numberOfSeats;

    if ($amount != $totalPrice * 100) {
        die("Invalid payment amount.");
    }

    try {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'];
        $basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') . '/';

        $checkoutSession = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => $currency,
                    'product_data' => [
                        'name' => 'Cinema Ticket Reservation',
                        'description' => 'Reservation ID: ' . $reservationId,
                    ],
                    'unit_amount' => $amount,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $protocol . $host . $basePath . 'success_page.php?reservation_id=' . $reservationId . '&session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => $protocol . $host . $basePath . 'cancel_page.php?reservation_id=' . $reservationId,
        ]);

        $paymentController->createPaymentRecord($reservationId, $checkoutSession->id, $totalPrice);

        header('Location: ' . $checkoutSession->url);
        exit;
    } catch (Exception $e) {
        error_log("Stripe Checkout Error: " . $e->getMessage());
        die('An error occurred while processing your payment. Please try again.');
    }
}
?>