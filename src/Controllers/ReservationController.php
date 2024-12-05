<?php
require_once __DIR__ . '/../../config/dbcon.php';
require_once __DIR__ . '/../Models/ReservationModel.php';
require_once __DIR__ . '/../Controllers/PaymentController.php';

class ReservationController {
    private $reservation;
    private $paymentController;

    public function __construct() {
        $db = dbCon();
        $this->reservation = new Reservation($db);
        $this->paymentController = new PaymentController();
    }

    public function getSeatsByRoomId($roomId) {
        return $this->reservation->getSeatsByRoomId($roomId);
    }

    public function getReservedSeatsByScreeningId($screeningId) {
        return $this->reservation->getReservedSeatsByScreeningId($screeningId);
    }

    public function getReservationById($reservationId) {
        return $this->reservation->getReservationById($reservationId);
    }
    
    public function getSeatsByReservationId($reservationId) {
        return $this->reservation->getSeatsByReservationId($reservationId);
    }

    public function createReservation($data) {
        try {
            $reservationId = $this->reservation->createReservation($data);
            return $reservationId;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function cancelReservation($reservationId) {
        try {
            $cancellationSuccess = $this->reservation->cancelReservation($reservationId);
            return $cancellationSuccess;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function confirmReservation($reservationId) {
        $payment = $this->paymentController->getPaymentByReservationId($reservationId);

        if ($payment && $payment['PaymentStatus'] === 'Completed') {
            $this->reservation->updateReservationStatus($reservationId, 'Confirmed');
            return true;
        } else {
            return false;
        }
    }
}
?>