<?php

namespace Library;

use Config\Config;

class FrontController
{
    private $controller;
    private $action;
    private $params;

    public function __construct() {
        $this->controller = Config::DEFAULT_CONTROLLER;
        $this->action = Config::DEFAULT_ACTION;
    }
    
    public function run() {
        if(isset($_GET['uri'])) {
            $uriParts = explode('/', $_GET['uri']);

            $controller = array_shift($uriParts);
            $action = array_shift($uriParts);



        } else {
            $this->invokeRoute();
        }
    }

    private function invokeRoute() {

    }
}