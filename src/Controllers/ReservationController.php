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
}