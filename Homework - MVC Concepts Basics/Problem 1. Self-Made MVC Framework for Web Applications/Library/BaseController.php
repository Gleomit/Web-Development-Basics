<?php

namespace Library;

use Config\Config;

abstract class BaseController
{
    protected $controller;
    protected $action;
    protected $layout = Config::DEFAULT_LAYOUT;

    public function __construct($controller, $action) {
        $this->controller = $controller;
        $this->action = $action;
    }

    protected function isLoggedIn() {
        return isset($_SESSION['userId']);
    }

    protected function authorize() {
        if(!$this->isLoggedIn()) {
            header('Location: Users/login');
            exit;
        }
    }

    protected function renderView($viewName = null, $isPartial = false) {
        if ($viewName == null) {
            $viewName = $this->action;
        }
        if (!$isPartial) {
            include_once('Views/Layouts/' . $this->layout . '/header.php');
        }
        include_once('views/' . $this->controller . '/' . $viewName . '.php');
        if (!$isPartial) {
            include_once('Views/Layouts/' . $this->layout . '/footer.php');
        }
    }
}