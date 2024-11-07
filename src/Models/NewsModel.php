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

    public function storeNews($data) {
        $query = "INSERT INTO News (Title, Content, Category, DatePublished) 
                  VALUES (:title, :content, :category, :datePublished)";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':title', $data['Title'], PDO::PARAM_STR);
        $stmt->bindParam(':content', $data['Content'], PDO::PARAM_STR);
        $stmt->bindParam(':category', $data['Category'], PDO::PARAM_STR);
        $stmt->bindParam(':datePublished', $data['DatePublished'], PDO::PARAM_STR);
        
        $stmt->execute();
        return $this->db->lastInsertId();
    }

    public function updateNewsById($id, $data) {
        $query = "UPDATE News SET 
                    Title = :title,
                    Content = :content,
                    Category = :category,
                    DatePublished = :datePublished
                  WHERE NewsID = :id";

        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':title', $data['Title'], PDO::PARAM_STR);
        $stmt->bindParam(':content', $data['Content'], PDO::PARAM_STR);
        $stmt->bindParam(':category', $data['Category'], PDO::PARAM_STR);
        $stmt->bindParam(':datePublished', $data['DatePublished'], PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function deleteNewsById($id) {
        $query = "DELETE FROM News WHERE NewsID = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function addNewsImage($newsID, $filePath) {
        $existingImage = $this->getImageByNewsId($newsID);
        if ($existingImage) {
            $query = "UPDATE NewsImage SET ImageURL = :imageURL WHERE NewsID = :newsID";
        } else {
            $query = "INSERT INTO NewsImage (ImageURL, NewsID) VALUES (:imageURL, :newsID)";
        }

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':imageURL', $filePath, PDO::PARAM_STR);
        $stmt->bindParam(':newsID', $newsID, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getImageByNewsId($newsID) {
        $query = "SELECT * FROM NewsImage WHERE NewsID = :newsID";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':newsID', $newsID, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteNewsImageByNewsId($newsID) {
        $query = "DELETE FROM NewsImage WHERE NewsID = :newsID";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':newsID', $newsID, PDO::PARAM_INT);
        return $stmt->execute();
    }
}