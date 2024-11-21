<?php
class Reservation {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function getSeatsByRoomId($roomId) {
        $query = "SELECT * FROM Seat WHERE RoomID = :roomId ORDER BY RowLabel, SeatNumber";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':roomId', $roomId, PDO::PARAM_INT);
        $stmt->execute();
        $seats = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $groupedSeats = [];
        foreach ($seats as $seat) {
            $groupedSeats[$seat['RowLabel']][] = $seat;
        }
        return $groupedSeats;
    }

    public function getReservedSeatsByScreeningId($screeningId) {
        $query = "SELECT SeatID FROM Allocations
                  WHERE ReservationID IN (
                      SELECT ReservationID FROM Reservation WHERE ScreeningID = :screeningId AND ReservationStatus = 'Confirmed'
                  )";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':screeningId', $screeningId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}