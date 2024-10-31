<?php
require_once __DIR__ . '/../../config/dbcon.php';
require_once __DIR__ . '/../Models/NewsModel.php';

class NewsController {
    private $news;

    public function __construct() {
        $db = dbCon();
        $this->news = new News($db);
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
}
