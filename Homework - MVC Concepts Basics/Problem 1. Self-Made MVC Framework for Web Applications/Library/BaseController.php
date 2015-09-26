<?php

namespace Framework\Library;

use Framework\Config\Config;
use Framework\Helpers\RouteService;

abstract class BaseController
{
    protected $layout = Config::DEFAULT_LAYOUT;

    protected function isLogged() {
        return isset($_SESSION['userId']);
    }

    protected function authorize() {
        if(!$this->isLogged()) {
            RouteService::redirect('users', 'login', true);
        }
    }
}