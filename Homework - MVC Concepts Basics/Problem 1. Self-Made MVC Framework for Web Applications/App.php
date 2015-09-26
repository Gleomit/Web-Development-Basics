<?php

namespace Framework;

use Framework\Config\Config;
use Framework\Helpers\RouteService;
use Framework\Library\Database;

class App
{
    private $controller = "";
    private $action = "";

    private $requestParams = [];

    public function init() {
        $this->controller = ucfirst(Config::DEFAULT_CONTROLLER);
        $this->action = ucfirst(Config::DEFAULT_ACTION);

        $this->initRouteService();
        $this->initDatabase();
    }

    private function initDatabase() {
        Database::setInstance(
            Config::DB_INSTANCE,
            Config::DB_DRIVER,
            Config::DB_USER,
            Config::DB_PASSWORD,
            Config::DB_NAME,
            Config::DB_HOST
        );
    }

    private function initRouteService() {
        $phpSelf = $_SERVER['PHP_SELF'];
        $index = basename($phpSelf);

        RouteService::init(str_replace($index, '', $phpSelf));
    }

    public function start() {
        if(isset($_GET['uri'])) {
            $this->requestParams = explode('/', $_GET['uri']);

            $this->controller = ucfirst(array_shift($this->requestParams));
            $this->action = ucfirst(array_shift($this->requestParams));
        }
    }

    /**
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @return array
     */
    public function getRequestParams()
    {
        return $this->requestParams;
    }
}