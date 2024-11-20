<?php
require_once __DIR__ . '/../../config/dbcon.php';
require_once __DIR__ . '/../Models/CinemaModel.php';

class CinemaController {
    private $cinema;
    private $uploadDir = __DIR__ . '/../../uploads/';

    public function __construct() {
        $db = dbCon();
        $this->cinema = new Cinema($db);

        if (!file_exists($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }

    public function index() {
        return $this->cinema->getAllCinemas();
    }
}
