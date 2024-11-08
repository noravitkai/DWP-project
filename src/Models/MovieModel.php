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

    public function addActorToMovie($movieID, $fullName, $role) {
        $checkQuery = "SELECT ActorID FROM Actor WHERE FullName = :fullName AND Role = :role";
        $checkStmt = $this->db->prepare($checkQuery);
        $checkStmt->bindParam(':fullName', $fullName, PDO::PARAM_STR);
        $checkStmt->bindParam(':role', $role, PDO::PARAM_STR);
        $checkStmt->execute();
        $actorID = $checkStmt->fetchColumn();
    
        if (!$actorID) {
            $insertQuery = "INSERT INTO Actor (FullName, Role) VALUES (:fullName, :role)";
            $insertStmt = $this->db->prepare($insertQuery);
            $insertStmt->bindParam(':fullName', $fullName, PDO::PARAM_STR);
            $insertStmt->bindParam(':role', $role, PDO::PARAM_STR);
            $insertStmt->execute();
            $actorID = $this->db->lastInsertId();
        }
    
        $featuresCheckQuery = "SELECT 1 FROM Features WHERE MovieID = :movieID AND ActorID = :actorID";
        $featuresCheckStmt = $this->db->prepare($featuresCheckQuery);
        $featuresCheckStmt->bindParam(':movieID', $movieID, PDO::PARAM_INT);
        $featuresCheckStmt->bindParam(':actorID', $actorID, PDO::PARAM_INT);
        $featuresCheckStmt->execute();
        
        if (!$featuresCheckStmt->fetch()) {
            $featuresInsertQuery = "INSERT INTO Features (MovieID, ActorID) VALUES (:movieID, :actorID)";
            $featuresInsertStmt = $this->db->prepare($featuresInsertQuery);
            $featuresInsertStmt->bindParam(':movieID', $movieID, PDO::PARAM_INT);
            $featuresInsertStmt->bindParam(':actorID', $actorID, PDO::PARAM_INT);
            $featuresInsertStmt->execute();
        }
    }

    public function getActorsByMovieId($movieID) {
        $query = "SELECT a.FullName, a.Role 
                  FROM Actor a 
                  INNER JOIN Features f ON a.ActorID = f.ActorID 
                  WHERE f.MovieID = :movieID";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':movieID', $movieID, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function removeActorsFromMovie($movieID) {
        $actorIDsQuery = "SELECT ActorID FROM Features WHERE MovieID = :movieID";
        $actorIDsStmt = $this->db->prepare($actorIDsQuery);
        $actorIDsStmt->bindParam(':movieID', $movieID, PDO::PARAM_INT);
        $actorIDsStmt->execute();
        $actorIDs = $actorIDsStmt->fetchAll(PDO::FETCH_COLUMN);
    
        $featuresQuery = "DELETE FROM Features WHERE MovieID = :movieID";
        $featuresStmt = $this->db->prepare($featuresQuery);
        $featuresStmt->bindParam(':movieID', $movieID, PDO::PARAM_INT);
        $featuresStmt->execute();
    
        foreach ($actorIDs as $actorID) {
            $checkActorQuery = "SELECT COUNT(*) FROM Features WHERE ActorID = :actorID";
            $checkActorStmt = $this->db->prepare($checkActorQuery);
            $checkActorStmt->bindParam(':actorID', $actorID, PDO::PARAM_INT);
            $checkActorStmt->execute();
    
            if ($checkActorStmt->fetchColumn() == 0) {
                $actorDeleteQuery = "DELETE FROM Actor WHERE ActorID = :actorID";
                $actorDeleteStmt = $this->db->prepare($actorDeleteQuery);
                $actorDeleteStmt->bindParam(':actorID', $actorID, PDO::PARAM_INT);
                $actorDeleteStmt->execute();
            }
        }
    }
}