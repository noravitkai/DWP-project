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
        $query = "
            SELECT Seat.SeatID 
            FROM Seat
            INNER JOIN Allocations ON Seat.SeatID = Allocations.SeatID
            INNER JOIN Reservation ON Allocations.ReservationID = Reservation.ReservationID
            WHERE Reservation.ScreeningID = :screeningId
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':screeningId', $screeningId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getReservationById($reservationId) {
        $query = "SELECT * FROM ReservationDetails WHERE ReservationID = :reservationId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':reservationId', $reservationId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getSeatsByReservationId($reservationId) {
        $query = "
            SELECT Seat.SeatNumber, Seat.RowLabel 
            FROM Allocations
            INNER JOIN Seat ON Allocations.SeatID = Seat.SeatID
            WHERE Allocations.ReservationID = :reservationId
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':reservationId', $reservationId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createReservation($data) {
        try {
            $this->db->beginTransaction();

            $seatIDs = $data['SeatIDs'];
            $screeningID = $data['ScreeningID'];

            if (empty($seatIDs)) {
                throw new Exception("No seats selected for reservation.");
            }

            $placeholders = implode(',', array_fill(0, count($seatIDs), '?'));
            $query = "SELECT SeatID FROM Seat WHERE SeatID IN ($placeholders) AND SeatStatus = 'Available'";
            $stmt = $this->db->prepare($query);
            foreach ($seatIDs as $index => $seatId) {
                $stmt->bindValue($index + 1, $seatId, PDO::PARAM_INT);
            }
            $stmt->execute();
            $availableSeats = $stmt->fetchAll(PDO::FETCH_COLUMN);

            if (count($availableSeats) !== count($seatIDs)) {
                throw new Exception("One or more selected seats are no longer available.");
            }

            $query = "
                INSERT INTO Reservation 
                (NumberOfSeats, ScreeningID, CustomerID, GuestFirstName, GuestLastName, GuestEmail, GuestPhoneNumber, Status)
                VALUES 
                (:numberOfSeats, :screeningId, :customerId, :guestFirstName, :guestLastName, :guestEmail, :guestPhone, :status)
            ";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':numberOfSeats', $data['NumberOfSeats'], PDO::PARAM_INT);
            $stmt->bindValue(':screeningId', $data['ScreeningID'], PDO::PARAM_INT);
            
            if ($data['CustomerID'] !== null) {
                $stmt->bindValue(':customerId', $data['CustomerID'], PDO::PARAM_INT);
            } else {
                $stmt->bindValue(':customerId', null, PDO::PARAM_NULL);
            }
            
            $stmt->bindValue(':guestFirstName', $data['GuestFirstName'], PDO::PARAM_STR);
            $stmt->bindValue(':guestLastName', $data['GuestLastName'], PDO::PARAM_STR);
            $stmt->bindValue(':guestEmail', $data['GuestEmail'], PDO::PARAM_STR);
            $stmt->bindValue(':guestPhone', $data['GuestPhoneNumber'], PDO::PARAM_STR);
            $stmt->bindValue(':status', 'Pending', PDO::PARAM_STR);
            $stmt->execute();

            $reservationId = $this->db->lastInsertId();

            $query = "INSERT INTO Allocations (ReservationID, SeatID) VALUES (:reservationId, :seatId)";
            $stmt = $this->db->prepare($query);
            foreach ($seatIDs as $seatId) {
                $stmt->bindValue(':reservationId', $reservationId, PDO::PARAM_INT);
                $stmt->bindValue(':seatId', $seatId, PDO::PARAM_INT);
                $stmt->execute();
            }

            $this->db->commit();
            return $reservationId;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error creating reservation: " . $e->getMessage());
            throw $e;
        }
    }

    public function cancelReservation($reservationId) {
        try {
            $this->db->beginTransaction();

            $query = "DELETE FROM Allocations WHERE ReservationID = :reservationId";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':reservationId', $reservationId, PDO::PARAM_INT);
            $stmt->execute();

            $query = "DELETE FROM Reservation WHERE ReservationID = :reservationId";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':reservationId', $reservationId, PDO::PARAM_INT);
            $stmt->execute();

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error cancelling reservation: " . $e->getMessage());
            throw $e;
        }
    }

    public function updateReservationStatus($reservationId, $status) {
        $query = "UPDATE Reservation SET Status = :status WHERE ReservationID = :reservationId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':reservationId', $reservationId, PDO::PARAM_INT);
        $stmt->execute();
    }
}
?>