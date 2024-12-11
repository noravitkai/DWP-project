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

$formMessage = $_SESSION['form_message'] ?? null;
unset($_SESSION['form_message']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Canceled</title>
    <link href="../../../public/css/tailwind.css" rel="stylesheet">
</head>
<body class="bg-zinc-800 text-zinc-200">
    <?php include '../frontend/frontend_navigation.php'; ?>
    <main class="mx-auto max-w-screen-xl px-4 py-8 sm:px-6 sm:py-16 lg:px-8">
        <header class="mb-8">
            <div class="flex items-center gap-2 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-8 w-8 text-red-600">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <h1 class="text-xl font-bold text-red-600 sm:text-3xl">Payment Canceled</h1>
            </div>
            <p class="mt-4 max-w-4xl text-sm sm:text-base text-zinc-300">
                Unfortunately, your payment was canceled. If this was a mistake, please try again.
            </p>
        </header>
        <a href="home_page.php" class="inline-block rounded-lg bg-orange-600 px-4 py-2 text-sm font-medium text-white hover:bg-orange-500 transition ease-in-out duration-300">
            Return to Home
        </a>
    </main>
    <?php include '../frontend/footer.php'; ?>
    <?php if ($formMessage): ?>
        <script>
            <?php if (strpos($formMessage, 'successfully') !== false): ?>
                alert("<?php echo addslashes($formMessage); ?>");
            <?php endif; ?>
        </script>
    <?php endif; ?>
</body>
</html>