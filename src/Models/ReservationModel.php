<?php
class Reservation {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function getAllReservations() {
        $query = "
            SELECT * 
            FROM ReservationDetails
            ORDER BY CreatedAt DESC
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $reservations;
    }

    public function getReservationById($reservationId) {
        $query = "SELECT * FROM ReservationDetails WHERE ReservationID = :reservationId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':reservationId', $reservationId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
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
            SELECT Allocations.SeatID 
            FROM Allocations
            INNER JOIN Reservation ON Allocations.ReservationID = Reservation.ReservationID
            WHERE Reservation.ScreeningID = :screeningId 
              AND Reservation.Status IN ('Pending', 'Confirmed')
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':screeningId', $screeningId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
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

    public function getReservationsByCustomerId($customerId) {
        $query = "SELECT * FROM ReservationDetails WHERE CustomerID = :customerId ORDER BY CreatedAt DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':customerId', $customerId, PDO::PARAM_INT);
        $stmt->execute();
        $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($reservations as &$reservation) {
            $reservation['Seats'] = $this->getSeatsByReservationId($reservation['ReservationID']);
        }

        return $reservations;
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
            $query = "
                SELECT SeatID 
                FROM Allocations
                INNER JOIN Reservation ON Allocations.ReservationID = Reservation.ReservationID
                WHERE SeatID IN ($placeholders) 
                  AND Reservation.ScreeningID = ?
                  AND Reservation.Status IN ('Pending', 'Confirmed')
            ";
            $stmt = $this->db->prepare($query);
            foreach ($seatIDs as $index => $seatId) {
                $stmt->bindValue($index + 1, $seatId, PDO::PARAM_INT);
            }
            $stmt->bindValue(count($seatIDs) + 1, $screeningID, PDO::PARAM_INT);
            $stmt->execute();

            $reservedSeats = $stmt->fetchAll(PDO::FETCH_COLUMN);
            if (!empty($reservedSeats)) {
                throw new Exception("One or more selected seats are no longer available.");
            }

            $reservationToken = bin2hex(random_bytes(32));

            $query = "
                INSERT INTO Reservation 
                (NumberOfSeats, ScreeningID, CustomerID, GuestFirstName, GuestLastName, GuestEmail, GuestPhoneNumber, Status, ReservationToken)
                VALUES 
                (:numberOfSeats, :screeningId, :customerId, :guestFirstName, :guestLastName, :guestEmail, :guestPhone, :status, :reservationToken)
            ";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':numberOfSeats', $data['NumberOfSeats'], PDO::PARAM_INT);
            $stmt->bindValue(':screeningId', $data['ScreeningID'], PDO::PARAM_INT);
            $stmt->bindValue(':customerId', $data['CustomerID'] ?? null, PDO::PARAM_INT);
            $stmt->bindValue(':guestFirstName', $data['GuestFirstName'], PDO::PARAM_STR);
            $stmt->bindValue(':guestLastName', $data['GuestLastName'], PDO::PARAM_STR);
            $stmt->bindValue(':guestEmail', $data['GuestEmail'], PDO::PARAM_STR);
            $stmt->bindValue(':guestPhone', $data['GuestPhoneNumber'], PDO::PARAM_STR);
            $stmt->bindValue(':status', 'Pending', PDO::PARAM_STR);
            $stmt->bindValue(':reservationToken', $reservationToken, PDO::PARAM_STR);
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

            $query = "UPDATE Reservation SET Status = 'Canceled' WHERE ReservationID = :reservationId AND Status = 'Pending'";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':reservationId', $reservationId, PDO::PARAM_INT);
            $stmt->execute();

            $query = "DELETE FROM Allocations WHERE ReservationID = :reservationId";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':reservationId', $reservationId, PDO::PARAM_INT);
            $stmt->execute();

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error canceling reservation: " . $e->getMessage());
            throw $e;
        }
    }

    public function cancelExpiredReservations() {
        $timeout = 900;
        $query = "SELECT ReservationID 
                  FROM Reservation 
                  WHERE Status = 'Pending' 
                  AND TIMESTAMPDIFF(SECOND, CreatedAt, NOW()) > :timeout";
    
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':timeout', $timeout, PDO::PARAM_INT);
        $stmt->execute();
    
        $expiredReservations = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
        foreach ($expiredReservations as $reservationId) {
            $this->cancelReservation($reservationId);
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