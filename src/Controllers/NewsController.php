<?php
require_once __DIR__ . '/../../config/dbcon.php';
require_once __DIR__ . '/../Models/NewsModel.php';

class NewsController {
    private $news;

    private $uploadDir = __DIR__ . '/../../uploads/';

    public function __construct() {
        $db = dbCon();
        $this->news = new News($db);

        if (!file_exists($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }

    public function index() {
        $newsList = $this->news->getAllNews();
        
        foreach ($newsList as &$news) {
            if ($news['ImageURL']) {
                $news['ImageURL'] = '/DWP-project/uploads/' . basename($news['ImageURL']);
            }
        }
        return $newsList;
    }

    public function store($data) {
        $newsID = $this->news->storeNews($data);
    
        if (isset($_FILES['newsImage']) && $_FILES['newsImage']['error'] === UPLOAD_ERR_OK) {
            $this->uploadNewsImage($_FILES['newsImage'], $newsID);
        }
    
        return $newsID;
    }

    public function update($id, $data) {
        $this->news->updateNewsById($id, $data);

        if (isset($_FILES['newsImage']) && $_FILES['newsImage']['error'] === UPLOAD_ERR_OK) {
            $this->deleteAssociatedImage($id);
            $this->uploadNewsImage($_FILES['newsImage'], $id);
        }
    }

    public function delete($id) {
        $this->deleteAssociatedImage($id);
        return $this->news->deleteNewsById($id);
    }

    private function uploadNewsImage($file, $newsID) {
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
                $this->news->addNewsImage($newsID, $webAccessiblePath);
            } else {
                throw new Exception('Failed to upload file.');
            }
        } else {
            throw new Exception('File upload error.');
        }
    }

    private function deleteAssociatedImage($newsID) {
        $imageData = $this->news->getImageByNewsId($newsID);
        if ($imageData && file_exists($this->uploadDir . basename($imageData['ImageURL']))) {
            unlink($this->uploadDir . basename($imageData['ImageURL']));
            $this->news->deleteNewsImageByNewsId($newsID);
        }
    }
}