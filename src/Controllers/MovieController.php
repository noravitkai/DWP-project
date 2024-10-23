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
}