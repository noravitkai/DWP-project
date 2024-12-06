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
        $this->cancelExpiredReservations();
        return $this->reservation->getSeatsByRoomId($roomId);
    }

    public function getReservedSeatsByScreeningId($screeningId) {
        $this->cancelExpiredReservations();
        return $this->reservation->getReservedSeatsByScreeningId($screeningId);
    }

    public function getReservationById($reservationId) {
        $this->cancelExpiredReservations();
        return $this->reservation->getReservationById($reservationId);
    }
    
    public function getSeatsByReservationId($reservationId) {
        $this->cancelExpiredReservations();
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

    private function cancelExpiredReservations() {
        $this->reservation->cancelExpiredReservations();
    }
}
?>