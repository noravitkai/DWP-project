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
        $cinemaList = $this->cinema->getAllCinemas();
        foreach ($cinemaList as &$cinema) {
            if ($cinema['ImageURL']) {
                $cinema['ImageURL'] = '/DWP-project/uploads/' . basename($cinema['ImageURL']);
            }
        }
        return $cinemaList;
    }

    public function store($data) {
        $data = [
            'Tagline' => $_POST['Tagline'],
            'Description' => $_POST['Description'],
            'PhoneNumber' => $_POST['PhoneNumber'],
            'Email' => $_POST['Email'],
            'OpeningHours' => $_POST['OpeningHours']
        ];

        $cinemaID = $this->cinema->storeCinema($data);

        if (isset($_FILES['cinemaImage']) && $_FILES['cinemaImage']['error'] === UPLOAD_ERR_OK) {
            $this->uploadCinemaImage($_FILES['cinemaImage'], $cinemaID);
        }

        return $cinemaID;
    }

    public function update($id, $data) {
        $data = [
            'Tagline' => $_POST['Tagline'],
            'Description' => $_POST['Description'],
            'PhoneNumber' => $_POST['PhoneNumber'],
            'Email' => $_POST['Email'],
            'OpeningHours' => $_POST['OpeningHours']
        ];

        $this->cinema->updateCinemaById($id, $data);

        if (isset($_FILES['cinemaImage']) && $_FILES['cinemaImage']['error'] === UPLOAD_ERR_OK) {
            $this->deleteAssociatedImage($id);
            $this->uploadCinemaImage($_FILES['cinemaImage'], $id);
        }
    }

    public function delete($id) {
        $this->deleteAssociatedImage($id);
        return $this->cinema->deleteCinemaById($id);
    }

    private function uploadCinemaImage($file, $cinemaID) {
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
                $this->cinema->addCinemaImage($cinemaID, $webAccessiblePath);
            } else {
                throw new Exception('Failed to upload file.');
            }
        } else {
            throw new Exception('File upload error.');
        }
    }

    private function deleteAssociatedImage($cinemaID) {
        $imageData = $this->cinema->getImageByCinemaId($cinemaID);
        if ($imageData && file_exists($this->uploadDir . basename($imageData['ImageURL']))) {
            unlink($this->uploadDir . basename($imageData['ImageURL']));
            $this->cinema->deleteCinemaImageByCinemaId($cinemaID);
        }
    }

    public function getCinemaById($id) {
        return $this->cinema->getCinemaById($id);
    }
}