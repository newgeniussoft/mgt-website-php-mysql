<?php

require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../models/Video.php';

require_once 'Controller.php';

require_once __DIR__ . '/../middleware/AuthMiddleware.php';

class VideoController extends Controller
{
    public function __construct($language = null)
    {
        parent::__construct($language);
    }   

    public function index()
    {
        AuthMiddleware::check();
        $modelVideo = new Video();
        $videos = $modelVideo->all();
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'title' => $_POST['title'],
                'title_es' => $_POST['title_es'],
                'subtitle' => $_POST['subtitle'],
                'subtitle_es' => $_POST['subtitle_es'],
                'link' => $_POST['link'],
            ];
            if($_POST['video_action'] == 'create') {
                $modelVideo->create($data);
            } elseif ($_POST['video_action'] == 'edit') {
                $modelVideo->update($_POST['id'], $data);
            } elseif ($_POST['video_action'] == 'delete') {
                $modelVideo->delete($_POST['id']);
            }
            
            echo "ok";
            exit;
        }

        echo $this->view('admin.pages.info.video', ['videos' => $videos]);
    }
}