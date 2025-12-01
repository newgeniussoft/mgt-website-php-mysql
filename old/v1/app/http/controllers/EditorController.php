<?php

require_once __DIR__ . '/../../models/User.php';

require_once 'Controller.php';

class EditorController extends Controller
{
    public function __construct($language = null)
    {
        parent::__construct($language);
    }

    public function index()
    {
        $userModel = new User();
        $user = $_SESSION['user'];
        echo $this->view('admin.pages.editor', ['user' => $user]);
    }
}
?>