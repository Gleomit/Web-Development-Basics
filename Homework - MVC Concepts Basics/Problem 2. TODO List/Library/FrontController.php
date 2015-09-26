<?php

namespace Framework\Library;

use Framework\Config\Config;

class FrontController
{
    private $controllerName;
    private $actionName;
    private $requestParams;

    private $controller;

    public function __construct($controllerName, $actionName, $requestParams = []) {
        $this->controllerName = $controllerName;
        $this->actionName = $actionName;
        $this->requestParams = $requestParams;
    }
    
    public function run() {
        $this->initController();
        $this->invokeRoute();
    }

    private function invokeRoute() {
        View::$controllerName = $this->controllerName;
        View::$actionName = $this->actionName;

        call_user_func_array(
            [
                $this->controller,
                $this->actionName
            ],
            $this->requestParams
        );
    }

    private function initController() {
        $controllerName =
            Config::CONTROLLERS_NAMESPACE
            . $this->controllerName
            . Config::CONTROLLERS_SUFFIX;

        $this->controller = new $controllerName();
    }
}