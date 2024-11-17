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
}