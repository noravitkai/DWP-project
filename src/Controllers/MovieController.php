<?php
require_once __DIR__ . '/../../config/dbcon.php';
require_once __DIR__ . '/../Models/MovieModel.php';

class MovieController {
    private $movie;

    public function __construct() {
        $db = dbCon();
        $this->movie = new Movie($db);
    }

    public function index() {
        return $this->movie->getAllMovies();
    }

    public function update($id, $data) {
        return $this->movie->updateMovieById($id, $data);
    }

    public function delete($id) {
        return $this->movie->deleteMovieById($id);
    }

    public function store($data) {
        return $this->movie->storeMovie($data);
    }
}
