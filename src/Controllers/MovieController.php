<?php
require_once __DIR__ . '/../../config/dbcon.php';
require_once __DIR__ . '/../Models/MovieModel.php';

class MovieController {
    private $movie;
    private $uploadDir = __DIR__ . '/../../uploads/';

    public function __construct() {
        $db = dbCon();
        $this->movie = new Movie($db);

        if (!file_exists($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }

    public function index() {
        $movies = $this->movie->getAllMovies();
        foreach ($movies as &$movie) {
            if ($movie['ImageURL']) {
                $movie['ImageURL'] = '/DWP-project/uploads/' . basename($movie['ImageURL']);
            }
            $movie['Actors'] = $this->movie->getActorsByMovieId($movie['MovieID']);
        }
        return $movies;
    }

    public function getMovieById($id) {
        $movie = $this->movie->getMovieById($id);
        if ($movie && $movie['ImageURL']) {
            $movie['ImageURL'] = '/DWP-project/uploads/' . basename($movie['ImageURL']);
        }
        $movie['Actors'] = $this->movie->getActorsByMovieId($id);
        return $movie;
    }

    public function store($data) {
        $movieID = $this->movie->storeMovie($data);
    
        if (isset($_FILES['movieImage']) && $_FILES['movieImage']['error'] === UPLOAD_ERR_OK) {
            $this->uploadMovieImage($_FILES['movieImage'], $movieID);
        }
    
        if (isset($data['Actors'])) {
            foreach ($data['Actors'] as $actor) {
                if (!empty($actor['FullName']) && !empty($actor['Role'])) {
                    $this->movie->addActorToMovie($movieID, $actor['FullName'], $actor['Role']);
                }
            }
        }
    
        return $movieID;
    }
    
    public function update($id, $data) {
        $this->movie->updateMovieById($id, $data);
    
        if (isset($_FILES['movieImage']) && $_FILES['movieImage']['error'] === UPLOAD_ERR_OK) {
            $this->deleteAssociatedImage($id);
            $this->uploadMovieImage($_FILES['movieImage'], $id);
        }
    
        if (isset($data['Actors']) && !empty($data['Actors'])) {
            $this->movie->removeActorsFromMovie($id);
    
            foreach ($data['Actors'] as $actor) {
                if (!empty($actor['FullName']) && !empty($actor['Role'])) {
                    $this->movie->addActorToMovie($id, $actor['FullName'], $actor['Role']);
                }
            }
        }
    }

    public function delete($id) {
        $this->deleteAssociatedImage($id);
        $this->movie->removeActorsFromMovie($id);
        return $this->movie->deleteMovieById($id);
    }

    private function uploadMovieImage($file, $movieID) {
        if ($file['error'] === UPLOAD_ERR_OK) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $maxFileSize = 2 * 1024 * 1024;

            if (!in_array($file['type'], $allowedTypes)) {
                throw new Exception('Invalid file type. Only JPG, PNG, and GIF are allowed.');
            }
            if ($file['size'] > $maxFileSize) {
                throw new Exception('File is too large. Maximum size is 2MB.');
            }

            $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $fileName = uniqid() . '.' . $fileExtension;
            $filePath = $this->uploadDir . $fileName;

            if (move_uploaded_file($file['tmp_name'], $filePath)) {
                $webAccessiblePath = '/DWP-project/uploads/' . $fileName;
                return $this->movie->addMovieImage($movieID, $webAccessiblePath);
            } else {
                throw new Exception('Failed to upload file.');
            }
        } else {
            throw new Exception('File upload error.');
        }
    }

    private function deleteAssociatedImage($movieID) {
        $imageData = $this->movie->getImageByMovieId($movieID);
        if ($imageData && file_exists(__DIR__ . '/../../uploads/' . basename($imageData['ImageURL']))) {
            unlink(__DIR__ . '/../../uploads/' . basename($imageData['ImageURL']));
            $this->movie->deleteMovieImageByMovieId($movieID);
        }
    }
}