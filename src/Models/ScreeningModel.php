<?php
class Screening {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function getAllScreenings() {
        $query = "SELECT s.ScreeningID, s.Price, s.ScreeningDate, s.ScreeningTime, 
                         m.Title AS MovieTitle, r.RoomLabel, r.RoomID 
                  FROM Screening s
                  JOIN Movie m ON s.MovieID = m.MovieID
                  JOIN Room r ON s.RoomID = r.RoomID
                  ORDER BY s.ScreeningDate, s.ScreeningTime";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function storeScreening($data) {
        $query = "INSERT INTO Screening (Price, ScreeningDate, ScreeningTime, MovieID, RoomID) 
                  VALUES (:price, :screeningDate, :screeningTime, :movieID, :roomID)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':price', $data['Price']);
        $stmt->bindParam(':screeningDate', $data['ScreeningDate']);
        $stmt->bindParam(':screeningTime', $data['ScreeningTime']);
        $stmt->bindParam(':movieID', $data['MovieID']);
        $stmt->bindParam(':roomID', $data['RoomID']);
        return $stmt->execute();
    }

    public function getAllRooms() {
        $query = "SELECT * FROM Room";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getScreeningById($id) {
        $query = "SELECT Screening.*, Movie.Title AS MovieTitle 
                  FROM Screening 
                  INNER JOIN Movie ON Screening.MovieID = Movie.MovieID
                  WHERE ScreeningID = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function updateScreening($id, $data) {
        $query = "UPDATE Screening 
                  SET Price = :price, ScreeningDate = :date, ScreeningTime = :time, 
                      RoomID = :roomID 
                  WHERE ScreeningID = :id";
    
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':price', $data['Price']);
        $stmt->bindParam(':date', $data['ScreeningDate']);
        $stmt->bindParam(':time', $data['ScreeningTime']);
        $stmt->bindParam(':roomID', $data['RoomID']);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
        return $stmt->execute();
    }

    public function deleteScreeningById($id) {
        $query = "DELETE FROM Screening WHERE ScreeningID = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}