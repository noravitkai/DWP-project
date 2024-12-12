<?php
require_once __DIR__ . '/../../../config/functions.php';
require_once __DIR__ . '/../../../config/dbcon.php';
require_once __DIR__ . '/../../Controllers/ReservationController.php';
require_once __DIR__ . '/../../Controllers/PaymentController.php';

if (isset($_GET['reservationId'])) {
    $reservationId = sanitizeInput($_GET['reservationId']);
} else {
    $reservationId = null;
}

if (!$reservationId || !is_numeric($reservationId)) {
    die("Invalid reservation ID.");
}

$reservationId = (int)$reservationId;

$reservationController = new ReservationController();
$paymentController = new PaymentController();

$reservation = $reservationController->getReservationById($reservationId);
if (!$reservation) {
    die("Reservation not found.");
}

$payment = $paymentController->getPaymentByReservationId($reservationId);

$invoiceNumber = 'INV-' . str_pad($reservationId, 6, '0', STR_PAD_LEFT);
$date = date('Y-m-d');

$customerName = $reservation['CustomerID'] 
    ? $reservation['CustomerFirstName'] . ' ' . $reservation['CustomerLastName']
    : $reservation['GuestFirstName'] . ' ' . $reservation['GuestLastName'];

$customerEmail = $reservation['CustomerID'] 
    ? $reservation['CustomerEmail'] 
    : $reservation['GuestEmail'];

$totalPrice = $reservation['TotalPrice'];
$movieTitle = $reservation['MovieTitle'];
$screeningDateTime = $reservation['ScreeningDate'] . ' ' . $reservation['ScreeningTime'];
$seats = $reservation['Seats'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Invoice <?php echo $invoiceNumber; ?></title>
<link href="../../../public/css/tailwind.css" rel="stylesheet">
</head>
<body class="h-full bg-zinc-100 text-zinc-900 p-8">
    <div class="max-w-3xl mx-auto bg-white p-8 shadow-md">
        <div class="border-b border-zinc-200 pb-5 mb-5">
            <h1 class="text-3xl font-bold text-zinc-900">Invoice</h1>
            <p class="text-sm text-zinc-600 mt-2">Number: <?php echo $invoiceNumber; ?></p>
            <p class="text-sm text-zinc-600">Date: <?php echo $date; ?></p>
        </div>
        <div class="mb-5">
            <h2 class="text-xl font-semibold mb-2 text-zinc-900">Customer Details</h2>
            <p class="text-sm">Name: <?php echo $customerName; ?></p>
            <p class="text-sm">Email: <?php echo $customerEmail; ?></p>
        </div>
        <div class="mb-5">
            <h2 class="text-xl font-semibold mb-2 text-zinc-900">Reservation Details</h2>
            <div class="divide-y divide-zinc-200">
                <div class="grid grid-cols-2 py-2">
                    <div class="text-xs font-semibold text-zinc-600 uppercase">Movie</div>
                    <div class="text-sm"><?php echo $movieTitle; ?></div>
                </div>
                <div class="grid grid-cols-2 py-2">
                    <div class="text-xs font-semibold text-zinc-600 uppercase">Screening</div>
                    <div class="text-sm"><?php echo $screeningDateTime; ?></div>
                </div>
                <div class="grid grid-cols-2 py-2">
                    <div class="text-xs font-semibold text-zinc-600 uppercase">Seats</div>
                    <div class="text-sm">
                        <?php foreach ($seats as $seat): ?>
                            <span class="mr-2 inline-block bg-zinc-200 rounded px-2 py-1"><?php echo $seat['RowLabel'] . $seat['SeatNumber']; ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="grid grid-cols-2 py-2">
                    <div class="text-xs font-semibold text-zinc-600 uppercase">Status</div>
                    <div class="text-sm"><?php echo $reservation['Status']; ?></div>
                </div>
                <div class="grid grid-cols-2 py-2">
                    <div class="text-xs font-semibold text-zinc-600 uppercase">Total Price</div>
                    <div class="text-sm font-bold"><?php echo number_format($totalPrice, 2) . ' DKK'; ?></div>
                </div>
            </div>
        </div>
        <div class="mb-5">
            <h2 class="text-xl font-semibold mb-2 text-zinc-900">Payment Details</h2>
            <?php if ($payment): ?>
                <div class="divide-y divide-zinc-200">
                    <div class="grid grid-cols-2 py-2">
                        <div class="text-xs font-semibold text-zinc-600 uppercase">Payment Status</div>
                        <div class="text-sm"><?php echo $payment['PaymentStatus']; ?></div>
                    </div>
                    <div class="grid grid-cols-2 py-2">
                        <div class="text-xs font-semibold text-zinc-600 uppercase">Transaction Amount</div>
                        <div class="text-sm"><?php echo number_format($payment['TransactionAmount'], 2) . ' DKK'; ?></div>
                    </div>
                    <div class="grid grid-cols-2 py-2">
                        <div class="text-xs font-semibold text-zinc-600 uppercase">Transaction Date</div>
                        <div class="text-sm"><?php echo $payment['TransactionDate']; ?></div>
                    </div>
                </div>
            <?php else: ?>
                <p class="text-sm text-zinc-700">No payment information available.</p>
            <?php endif; ?>
        </div>
        <button onclick="window.print()" class="inline-flex items-center rounded-lg bg-orange-600 px-3 py-2 text-sm text-white hover:bg-orange-500 transition ease-in-out duration-300">
            <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z" />
            </svg>
            Print/Save
        </button>
        <div class="mt-5 text-xs text-zinc-500">
            <p>Thank you for choosing our cinema. Enjoy the show!</p>
        </div>
    </div>
</body>
</html>
