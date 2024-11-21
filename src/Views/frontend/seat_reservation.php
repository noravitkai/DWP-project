<?php
require_once '../../Controllers/ScreeningController.php';
require_once '../../Controllers/ReservationController.php';

$screeningController = new ScreeningController();
$reservationController = new ReservationController();

if (!isset($_GET['screening_id']) || !is_numeric($_GET['screening_id'])) {
    die("Invalid screening ID.");
}

$screeningId = $_GET['screening_id'];
$screeningDetails = $screeningController->getScreeningById($screeningId);

if (!$screeningDetails) {
    die("Screening not found.");
}

$roomId = $screeningDetails['RoomID'];
$roomSeats = $reservationController->getSeatsByRoomId($roomId);

$reservedSeats = $reservationController->getReservedSeatsByScreeningId($screeningId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reserve Your Seats</title>
    <link href="../../../public/css/tailwind.css" rel="stylesheet">
</head>
<body class="bg-zinc-800">
    <main class="mx-auto max-w-screen-xl px-4 py-8 sm:px-6 sm:py-12 lg:px-8">
        <form action="confirm_reservation.php" method="POST" id="seatReservationForm">
            <input type="hidden" name="screening_id" value="<?php echo $screeningId; ?>">
            <div class="flex flex-col lg:flex-row lg:justify-between gap-8">
                <div class="w-full lg:w-auto lg:flex-1">
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
                    <button type="submit" class="mt-6 inline-flex items-center rounded-lg bg-orange-600 px-4 py-2 text-sm font-medium text-white hover:bg-orange-500 transition ease-in-out duration-300">
                        Confirm
                    </button>
                </div>
                <div class="w-full lg:w-auto flex flex-col items-start lg:items-end">
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
                                            <?php $isReserved = in_array($seat['SeatID'], array_column($reservedSeats, 'SeatID')); ?>
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
    <script src="../../../public/js/seat-reservation.js"></script>
</body>
</html>