<?php
require_once '../../../config/session.php';
require_once '../../Controllers/ReservationController.php';
require_once '../../../config/functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit;
}

$reservationId = isset($_POST['reservation_id']) ? intval($_POST['reservation_id']) : 0;
$csrfToken = $_POST['csrf_token'] ?? '';

if ($reservationId <= 0) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid reservation ID.']);
    exit;
}

if (!verifyCsrfToken($csrfToken)) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Invalid CSRF token.']);
    exit;
}

$reservationController = new ReservationController();

try {
    $reservation = $reservationController->getReservationById($reservationId);

    if (!$reservation) {
        throw new Exception('Reservation not found.');
    }

    $userId = $_SESSION['user_id'] ?? null;
    if ($userId) {
        if ($reservation['CustomerID'] !== $userId) {
            throw new Exception('You do not have permission to cancel this reservation.');
        }
    } else {
        if ($reservation['ReservationToken'] !== session_id()) {
            throw new Exception('You do not have permission to cancel this reservation.');
        }
    }

    if ($reservation['Status'] === 'Pending') {
        $canceled = $reservationController->cancelReservation($reservationId);

        if ($canceled) {
            regenerateCsrfToken();
            echo json_encode(['status' => 'success', 'message' => 'Reservation canceled successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to cancel reservation.']);
        }
    } else {
        echo json_encode(['status' => 'info', 'message' => 'Reservation is already processed.']);
    }
} catch (Exception $e) {
    error_log('Error in release_reservation.php: ' . $e->getMessage());

    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'An unexpected error occurred. Please try again later.']);
}