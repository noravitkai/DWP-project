<?php
class Cinema {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function getAllCinemas() {
        $query = "SELECT c.*, ci.ImageURL 
                  FROM Cinema c 
                  LEFT JOIN CinemaImage ci ON c.CinemaID = ci.CinemaID
                  ORDER BY c.CinemaID ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCinemaById($id) {
        $query = "SELECT c.*, ci.ImageURL 
                  FROM Cinema c 
                  LEFT JOIN CinemaImage ci ON c.CinemaID = ci.CinemaID
                  WHERE c.CinemaID = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function storeCinema($data) {
        $query = "INSERT INTO Cinema (Tagline, Description, PhoneNumber, Email, OpeningHours) 
                  VALUES (:tagline, :description, :phoneNumber, :email, :openingHours)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':tagline', $data['Tagline'], PDO::PARAM_STR);
        $stmt->bindParam(':description', $data['Description'], PDO::PARAM_STR);
        $stmt->bindParam(':phoneNumber', $data['PhoneNumber'], PDO::PARAM_STR);
        $stmt->bindParam(':email', $data['Email'], PDO::PARAM_STR);
        $stmt->bindParam(':openingHours', $data['OpeningHours'], PDO::PARAM_STR);
        $stmt->execute();
        return $this->db->lastInsertId();
    }

    public function updateCinemaById($id, $data) {
        $query = "UPDATE Cinema SET 
        Tagline = :tagline,
        Description = :description,
        PhoneNumber = :phoneNumber,
        Email = :email,
        OpeningHours = :openingHours
      WHERE CinemaID = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':tagline', $data['Tagline'], PDO::PARAM_STR);
        $stmt->bindParam(':description', $data['Description'], PDO::PARAM_STR);
        $stmt->bindParam(':phoneNumber', $data['PhoneNumber'], PDO::PARAM_STR);
        $stmt->bindParam(':email', $data['Email'], PDO::PARAM_STR);
        $stmt->bindParam(':openingHours', $data['OpeningHours'], PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function deleteCinemaById($id) {
        $query = "DELETE FROM Cinema WHERE CinemaID = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function addCinemaImage($cinemaID, $filePath) {
        $existingImage = $this->getImageByCinemaId($cinemaID);
        if ($existingImage) {
            $query = "UPDATE CinemaImage SET ImageURL = :imageURL WHERE CinemaID = :cinemaID";
        } else {
            $query = "INSERT INTO CinemaImage (ImageURL, CinemaID) VALUES (:imageURL, :cinemaID)";
        }
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':imageURL', $filePath, PDO::PARAM_STR);
        $stmt->bindParam(':cinemaID', $cinemaID, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getImageByCinemaId($cinemaID) {
        $query = "SELECT * FROM CinemaImage WHERE CinemaID = :cinemaID";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':cinemaID', $cinemaID, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteCinemaImageByCinemaId($cinemaID) {
        $query = "DELETE FROM CinemaImage WHERE CinemaID = :cinemaID";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':cinemaID', $cinemaID, PDO::PARAM_INT);
        return $stmt->execute();
    }
}