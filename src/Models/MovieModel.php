<?php
class Movie {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function getAllMovies() {
        $query = "SELECT m.*, mi.ImageURL 
                  FROM Movie m 
                  LEFT JOIN MovieImage mi ON m.MovieID = mi.MovieID
                  ORDER BY m.MovieID ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMovieById($id) {
        $query = "SELECT m.*, mi.ImageURL 
                  FROM Movie m 
                  LEFT JOIN MovieImage mi ON m.MovieID = mi.MovieID 
                  WHERE m.MovieID = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateMovieById($id, $data) {
        $query = "UPDATE Movie SET 
                    Title = :title,
                    Subtitle = :subtitle,
                    ReleaseYear = :releaseYear,
                    Genre = :genre,
                    Director = :director,
                    Duration = :duration,
                    MovieDescription = :description
                  WHERE MovieID = :id";

        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':title', $data['Title'], PDO::PARAM_STR);
        $stmt->bindParam(':subtitle', $data['Subtitle'], PDO::PARAM_STR);
        $stmt->bindParam(':releaseYear', $data['ReleaseYear'], PDO::PARAM_INT);
        $stmt->bindParam(':genre', $data['Genre'], PDO::PARAM_STR);
        $stmt->bindParam(':director', $data['Director'], PDO::PARAM_STR);
        $stmt->bindParam(':duration', $data['Duration'], PDO::PARAM_INT);
        $stmt->bindParam(':description', $data['MovieDescription'], PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function storeMovie($data) {
        $query = "INSERT INTO Movie (Title, Subtitle, ReleaseYear, Genre, Director, Duration, MovieDescription) 
                  VALUES (:title, :subtitle, :releaseYear, :genre, :director, :duration, :description)";

        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':title', $data['Title'], PDO::PARAM_STR);
        $stmt->bindParam(':subtitle', $data['Subtitle'], PDO::PARAM_STR);
        $stmt->bindParam(':releaseYear', $data['ReleaseYear'], PDO::PARAM_INT);
        $stmt->bindParam(':genre', $data['Genre'], PDO::PARAM_STR);
        $stmt->bindParam(':director', $data['Director'], PDO::PARAM_STR);
        $stmt->bindParam(':duration', $data['Duration'], PDO::PARAM_INT);
        $stmt->bindParam(':description', $data['MovieDescription'], PDO::PARAM_STR);

        $stmt->execute();
        return $this->db->lastInsertId();
    }

    public function deleteMovieById($id) {
        $query = "DELETE FROM Movie WHERE MovieID = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function addMovieImage($movieID, $filePath) {
        $existingImage = $this->getImageByMovieId($movieID);
        if ($existingImage) {
            $query = "UPDATE MovieImage SET ImageURL = :imageURL WHERE MovieID = :movieID";
        } else {
            $query = "INSERT INTO MovieImage (ImageURL, MovieID) VALUES (:imageURL, :movieID)";
        }
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':imageURL', $filePath, PDO::PARAM_STR);
        $stmt->bindParam(':movieID', $movieID, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getImageByMovieId($movieID) {
        $query = "SELECT * FROM MovieImage WHERE MovieID = :movieID";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':movieID', $movieID, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteMovieImageByMovieId($movieID) {
        $query = "DELETE FROM MovieImage WHERE MovieID = :movieID";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':movieID', $movieID, PDO::PARAM_INT);
        return $stmt->execute();
    }
}