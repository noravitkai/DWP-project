<?php
require_once __DIR__ . '/../../config/dbcon.php';
require_once __DIR__ . '/../Models/ReservationModel.php';

class ReservationController {
    private $reservation;

    public function __construct() {
        $db = dbCon();
        $this->reservation = new Reservation($db);
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
}
?>