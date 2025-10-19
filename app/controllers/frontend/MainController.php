<?php

require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../../core/BladeEngine.php';


class MainController extends Controller {

    public $language;

    public function __construct($language = null)
        {
            parent::__construct($language);
            $this->language = $language;
        }

    public function index()
    {
            $this->render('frontend.view', [
            'title' => 'Welcome',
            'users' => [
                ['name' => 'John', 'email' => 'john@example.com'],
                ['name' => 'Jane', 'email' => 'jane@example.com']
            ]
        ]);
    }

    public function welcome() {
        $this->render('welcome', [
            'title' => 'Welcome',
                'users' => [
                ['name' => 'John', 'email' => 'john@example.com'],
                ['name' => 'Jane', 'email' => 'jane@example.com']
            ]
        ]);
    }
}

?>