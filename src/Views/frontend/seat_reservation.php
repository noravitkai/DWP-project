<?php
require_once '../../../config/session.php';
require_once '../../../config/functions.php';
require_once '../../Controllers/ReservationController.php';
require_once '../../Controllers/ScreeningController.php';
require_once '../../Controllers/CustomerController.php';

$reservationController = new ReservationController();
$screeningController = new ScreeningController();
$customerController = new CustomerController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !verifyCsrfToken($_POST['csrf_token'])) {
        $_SESSION['error_message'] = "Invalid CSRF token. Please try again.";
        header("Location: seat_reservation.php?screening_id=" . urlencode($_POST['screening_id']));
        exit;
    }

    $isLoggedIn = isset($_SESSION['user_id']);
    $seatIDsRaw = $_POST['seat_ids'] ?? [];
    $seatIDs = array_map('sanitizeInput', $seatIDsRaw);

    if (empty($seatIDs)) {
        $_SESSION['error_message'] = "Please select at least one seat.";
        header("Location: seat_reservation.php?screening_id=" . urlencode($_POST['screening_id']));
        exit;
    }

    if (count($seatIDs) > 5) {
        $_SESSION['error_message'] = "You can select up to 5 seats.";
        header("Location: seat_reservation.php?screening_id=" . urlencode($_POST['screening_id']));
        exit;
    }

    $screeningIDRaw = $_POST['screening_id'] ?? null;
    $screeningID = sanitizeInput($screeningIDRaw);

    if (!$screeningID || !is_numeric($screeningID)) {
        $_SESSION['error_message'] = "Invalid screening ID.";
        header("Location: seat_reservation.php?screening_id=" . urlencode($_POST['screening_id']));
        exit;
    }

    $reservationData = [
        'NumberOfSeats' => count($seatIDs),
        'ScreeningID' => $screeningID,
        'CustomerID' => $isLoggedIn ? $_SESSION['user_id'] : null,
        'GuestFirstName' => null,
        'GuestLastName' => null,
        'GuestEmail' => null,
        'GuestPhoneNumber' => null,
        'SeatIDs' => $seatIDs
    ];

    if (!$isLoggedIn) {
        $guestFirstName = sanitizeInput($_POST['guest_first_name'] ?? '');
        $guestLastName = sanitizeInput($_POST['guest_last_name'] ?? '');
        $guestEmail = sanitizeInput($_POST['guest_email'] ?? '');
        $guestPhone = sanitizeInput($_POST['guest_phone'] ?? '');

        if (empty($guestFirstName) || empty($guestLastName) || empty($guestEmail)) {
            $_SESSION['error_message'] = "Please fill in all required guest details.";
            header("Location: seat_reservation.php?screening_id=" . urlencode($screeningID));
            exit;
        }

        if (!filter_var($guestEmail, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error_message'] = "Please provide a valid email address.";
            header("Location: seat_reservation.php?screening_id=" . urlencode($screeningID));
            exit;
        }

        $reservationData['GuestFirstName'] = $guestFirstName;
        $reservationData['GuestLastName'] = $guestLastName;
        $reservationData['GuestEmail'] = $guestEmail;
        $reservationData['GuestPhoneNumber'] = $guestPhone;
    }

    try {
        $reservationId = $reservationController->createReservation($reservationData);
        regenerateCsrfToken();
        header('Location: checkout_page.php?reservation_id=' . urlencode($reservationId));
        exit;
    } catch (Exception $e) {
        $_SESSION['error_message'] = $e->getMessage();
        header("Location: seat_reservation.php?screening_id=" . urlencode($screeningID));
        exit;
    }
}

$isLoggedIn = isset($_SESSION['user_id']);

$screeningIDRaw = $_GET['screening_id'] ?? null;
$screeningId = sanitizeInput($screeningIDRaw);

if (!isset($_GET['screening_id']) || !is_numeric($screeningId)) {
    die("Invalid screening ID.");
}

$screeningDetails = $screeningController->getScreeningById($screeningId);
if (!$screeningDetails) die("Screening not found.");

$roomId = $screeningDetails['RoomID'];
$roomSeats = $reservationController->getSeatsByRoomId($roomId);
$reservedSeats = $reservationController->getReservedSeatsByScreeningId($screeningId);

$customerData = $isLoggedIn ? $customerController->fetchLoggedInCustomerData() : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reserve Your Seats</title>
    <link href="../../../public/css/tailwind.css" rel="stylesheet">
</head>
<body class="bg-zinc-800">
<?php include '../frontend/frontend_navigation.php'; ?>
    <main class="mx-auto max-w-screen-xl px-4 py-8 sm:px-6 sm:py-12 lg:px-8">
        <?php
        if (isset($_SESSION['error_message'])) {
            echo "<div class='mb-4 p-4 bg-red-500 text-white rounded'>" . $_SESSION['error_message'] . "</div>";
            unset($_SESSION['error_message']);
        }
        ?>
        <form action="seat_reservation.php?screening_id=<?php echo $screeningId; ?>" method="POST" id="seatReservationForm">
            <input type="hidden" name="screening_id" value="<?php echo $screeningId; ?>">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <div class="flex flex-col lg:flex-row lg:justify-between gap-8">
                <div class="w-full lg:w-auto lg:flex-1 flex flex-col">
                    <h1 class="text-2xl sm:text-3xl font-bold text-orange-600 mb-6">Reserve Your Seats</h1>
                    <div class="mb-6 text-sm sm:text-base text-zinc-300">
                        <p><span class="font-semibold">Movie:</span> <?php echo $screeningDetails['MovieTitle']; ?></p>
                        <p><span class="font-semibold">Date:</span> <?php echo $screeningDetails['ScreeningDate']; ?></p>
                        <p><span class="font-semibold">Time:</span> <?php echo $screeningDetails['ScreeningTime']; ?></p>
                        <p><span class="font-semibold">Room:</span> <?php echo $screeningDetails['RoomLabel']; ?></p>
                        <p class="mt-4">Select your seats by clicking on the ones you prefer. You can choose up to 5 seats for screenings.</p>
                    </div>
                    <div class="space-y-2 text-xs sm:text-sm text-zinc-300">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 bg-red-500 border border-red-500 rounded-md"></span>
                            <span>Reserved Seat</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 bg-zinc-700 border border-orange-600 rounded-md"></span>
                            <span>Available Seat</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 bg-orange-600 border border-orange-600 rounded-md"></span>
                            <span>Selected Seat</span>
                        </div>
                    </div>
                    <div class="w-full overflow-x-auto mt-6 block lg:hidden" id="seatContainer">
                        <div class="max-w-max">
                            <div class="w-full bg-zinc-700 text-center text-zinc-200 py-2 rounded-md mt-2 mb-4">
                                Screen
                            </div>
                            <?php foreach ($roomSeats as $rowLabel => $seats): ?>
                                <div class="flex items-center mb-2 text-xs sm:text-sm">
                                    <span class="text-orange-600 font-bold mr-4 w-6 text-center"><?php echo $rowLabel; ?></span>
                                    <div class="flex flex-wrap gap-2">
                                        <?php foreach ($seats as $seat): ?>
                                            <?php 
                                                $isReserved = in_array($seat['SeatID'], $reservedSeats);
                                            ?>
                                            <label class="relative">
                                                <input type="checkbox" name="seat_ids[]" value="<?php echo $seat['SeatID']; ?>"
                                                       class="hidden peer seat-checkbox" <?php echo $isReserved ? 'disabled' : ''; ?>>
                                                <span class="w-8 h-8 flex items-center justify-center rounded-md border
                                                    <?php echo $isReserved
                                                        ? 'bg-red-500 text-zinc-200 border-red-500 cursor-not-allowed'
                                                        : 'bg-zinc-700 text-orange-600 border-orange-600 hover:bg-orange-600 hover:text-zinc-200 cursor-pointer peer-checked:bg-orange-600 peer-checked:text-zinc-200'; ?>">
                                                    <?php echo $seat['SeatNumber']; ?>
                                                </span>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php if (!$isLoggedIn): ?>
                        <div class="mt-6">
                            <h2 class="text-base sm:text-lg font-medium text-zinc-300">Guest Details</h2>
                            <p class="mt-2 text-zinc-300 text-xs sm:text-sm">
                                Please provide your details to reserve seats. Fields marked with * are required to complete. You can sign up later to manage your reservation and enjoy more features.
                            </p>
                            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <label for="guest_first_name" class="relative block overflow-hidden border-b border-gray-500 bg-transparent pt-3 focus-within:border-orange-600">
                                    <input
                                        type="text"
                                        name="guest_first_name"
                                        id="guest_first_name"
                                        placeholder="First Name"
                                        required
                                        autocomplete="given-name"
                                        class="peer h-8 w-full border-none bg-transparent p-0 placeholder-transparent focus:border-transparent focus:outline-none focus:ring-0 sm:text-sm text-zinc-100"
                                    />
                                    <span class="absolute start-0 top-2 -translate-y-1/2 text-xs text-zinc-300 transition-all peer-placeholder-shown:top-1/2 peer-placeholder-shown:text-sm peer-placeholder-shown:text-zinc-400 peer-focus:top-2 peer-focus:text-xs peer-focus:text-orange-600">
                                        First Name *
                                    </span>
                                </label>
                                <label for="guest_last_name" class="relative block overflow-hidden border-b border-gray-500 bg-transparent pt-3 focus-within:border-orange-600">
                                    <input
                                        type="text"
                                        name="guest_last_name"
                                        id="guest_last_name"
                                        placeholder="Last Name"
                                        required
                                        autocomplete="family-name"
                                        class="peer h-8 w-full border-none bg-transparent p-0 placeholder-transparent focus:border-transparent focus:outline-none focus:ring-0 sm:text-sm text-zinc-100"
                                    />
                                    <span class="absolute start-0 top-2 -translate-y-1/2 text-xs text-zinc-300 transition-all peer-placeholder-shown:top-1/2 peer-placeholder-shown:text-sm peer-placeholder-shown:text-zinc-400 peer-focus:top-2 peer-focus:text-xs peer-focus:text-orange-600">
                                        Last Name *
                                    </span>
                                </label>
                                <label for="guest_email" class="relative block overflow-hidden border-b border-gray-500 bg-transparent pt-3 focus-within:border-orange-600">
                                    <input
                                        type="email"
                                        name="guest_email"
                                        id="guest_email"
                                        placeholder="Email"
                                        required
                                        autocomplete="email"
                                        class="peer h-8 w-full border-none bg-transparent p-0 placeholder-transparent focus:border-transparent focus:outline-none focus:ring-0 sm:text-sm text-zinc-100"
                                    />
                                    <span class="absolute start-0 top-2 -translate-y-1/2 text-xs text-zinc-300 transition-all peer-placeholder-shown:top-1/2 peer-placeholder-shown:text-sm peer-placeholder-shown:text-zinc-400 peer-focus:top-2 peer-focus:text-xs peer-focus:text-orange-600">
                                        Email *
                                    </span>
                                </label>
                                <label for="guest_phone" class="relative block overflow-hidden border-b border-gray-500 bg-transparent pt-3 focus-within:border-orange-600">
                                    <input
                                        type="tel"
                                        name="guest_phone"
                                        id="guest_phone"
                                        placeholder="Phone Number"
                                        autocomplete="tel"
                                        class="peer h-8 w-full border-none bg-transparent p-0 placeholder-transparent focus:border-transparent focus:outline-none focus:ring-0 sm:text-sm text-zinc-100"
                                    />
                                    <span class="absolute start-0 top-2 -translate-y-1/2 text-xs text-zinc-300 transition-all peer-placeholder-shown:top-1/2 peer-placeholder-shown:text-sm peer-placeholder-shown:text-zinc-400 peer-focus:top-2 peer-focus:text-xs peer-focus:text-orange-600">
                                        Phone Number
                                    </span>
                                </label>
                            </div>
                        </div>
                    <?php endif; ?>
                    <button type="submit" class="mt-8 sm:mt-6 self-start inline-flex items-center rounded-lg bg-orange-600 px-4 py-2 text-sm font-medium text-white hover:bg-orange-500 transition ease-in-out duration-300">
                        Confirm Reservation
                    </button>
                </div>
                <div class="w-full lg:w-auto lg:flex flex-col items-start lg:items-end hidden">
                    <div class="w-full overflow-x-auto" id="seatContainer">
                        <div class="max-w-max lg:mx-auto">
                            <div class="w-full bg-zinc-700 text-center text-zinc-200 py-2 rounded-md mt-2 mb-4">
                                Screen
                            </div>
                            <?php foreach ($roomSeats as $rowLabel => $seats): ?>
                                <div class="flex items-center mb-2 text-xs sm:text-sm">
                                    <span class="text-orange-600 font-bold mr-4 w-6 text-center"><?php echo $rowLabel; ?></span>
                                    <div class="flex flex-wrap gap-2">
                                        <?php foreach ($seats as $seat): ?>
                                            <?php 
                                                $isReserved = in_array($seat['SeatID'], $reservedSeats);
                                            ?>
                                            <label class="relative">
                                                <input type="checkbox" name="seat_ids[]" value="<?php echo $seat['SeatID']; ?>"
                                                       class="hidden peer seat-checkbox" <?php echo $isReserved ? 'disabled' : ''; ?>>
                                                <span class="w-8 h-8 flex items-center justify-center rounded-md border
                                                    <?php echo $isReserved
                                                        ? 'bg-red-500 text-zinc-200 border-red-500 cursor-not-allowed'
                                                        : 'bg-zinc-700 text-orange-600 border-orange-600 hover:bg-orange-600 hover:text-zinc-200 cursor-pointer peer-checked:bg-orange-600 peer-checked:text-zinc-200'; ?>">
                                                    <?php echo $seat['SeatNumber']; ?>
                                                </span>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </main>
    <?php include '../frontend/footer.php'; ?>
    <script src="../../../public/js/seat-reservation.js"></script>
</body>
</html>