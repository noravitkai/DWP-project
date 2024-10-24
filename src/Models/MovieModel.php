<?php

class Movie {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function getAllMovies() {
        $query = "SELECT * FROM Movie";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMovieById($id) {
        $query = "SELECT * FROM Movie WHERE MovieID = :id";
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

        return $stmt->execute();
    }

    public function deleteMovieById($id) {
        $query = "DELETE FROM Movie WHERE MovieID = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
