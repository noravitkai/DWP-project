<?php
class News {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function getAllNews() {
        $query = "SELECT n.*, ni.ImageURL 
                  FROM News n 
                  LEFT JOIN NewsImage ni ON n.NewsID = ni.NewsID
                  ORDER BY n.NewsID ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getNewsById($id) {
        $query = "SELECT n.*, ni.ImageURL 
                  FROM News n 
                  LEFT JOIN NewsImage ni ON n.NewsID = ni.NewsID 
                  WHERE n.NewsID = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}