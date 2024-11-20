<?php
class Cinema {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function getAllCinemas() {
        $query = "SELECT c.CinemaID, c.Tagline, c.Description, c.PhoneNumber, c.Email, ci.ImageURL 
                  FROM Cinema c
                  LEFT JOIN CinemaImage ci ON c.CinemaID = ci.CinemaID";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCinemaById($id) {
        $query = "SELECT c.CinemaID, c.Tagline, c.Description, c.PhoneNumber, c.Email, ci.ImageURL 
                  FROM Cinema c
                  LEFT JOIN CinemaImage ci ON c.CinemaID = ci.CinemaID
                  WHERE c.CinemaID = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}