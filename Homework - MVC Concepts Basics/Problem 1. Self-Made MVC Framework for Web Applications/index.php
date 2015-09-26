<?php
require_once 'Library\Autoloader.php';

Framework\Library\Autoloader::register();

Framework\Helpers\Session::start();

$app = new \Framework\App();

$app->init();

$app->start();

$frontController = new Framework\Library\FrontController($app->getController(), $app->getAction(), $app->getRequestParams());

$frontController->run();