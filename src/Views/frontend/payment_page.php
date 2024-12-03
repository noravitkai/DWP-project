<?php
require_once __DIR__ . '/../../../stripe-php/init.php';
require_once '../../../config/session.php';
require_once '../../../config/env_loader.php';

use Stripe\Stripe;
use Stripe\Checkout\Session;

loadEnv('../../../config/.env');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stripeSecretKey = getenv('STRIPE_SECRET_KEY');

    if (!$stripeSecretKey) {
        die('Stripe secret key is not set. Please check your .env file.');
    }

    Stripe::setApiKey($stripeSecretKey);

    $reservationId = $_POST['reservation_id'];
    $amount = $_POST['amount'];
    $currency = $_POST['currency'];

    try {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'];
        $basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') . '/';

        $checkoutSession = Session::create([
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
            'success_url' => $protocol . $host . $basePath . 'success_page.php?reservation_id=' . $reservationId,
            'cancel_url' => $protocol . $host . $basePath . 'cancel_page.php',
        ]);

        header('Location: ' . $checkoutSession->url);
        exit;
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
}
?>