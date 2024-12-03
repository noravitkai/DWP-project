<?php
require_once __DIR__ . '/../../config/dbcon.php';
require_once __DIR__ . '/../Models/ScreeningModel.php';

class ScreeningController {
    private $screening;

    public function __construct() {
        $db = dbCon();
        $this->screening = new Screening($db);
    }

    public function index() {
        return $this->screening->getAllScreenings();
    }

    public function store($data) {
        return $this->screening->storeScreening($data);
    }

    public function getRooms() {
        return $this->screening->getAllRooms();
    }

    public function getScreeningById($id) {
        return $this->screening->getScreeningById($id);
    }
    
    public function update($id, $data) {
        return $this->screening->updateScreening($id, $data);
    }

    public function delete($id) {
        return $this->screening->deleteScreeningById($id);
    }

    public function getScreeningsByMovieId($movieId) {
        return $this->screening->getScreeningsByMovieId($movieId);
    }

    public function getDailyScreenings() {
        return $this->screening->getDailyScreenings();
    }
}