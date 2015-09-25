<?php
require_once 'Autoloader.php';

\SoftUni\Autoloader::register();

\SoftUni\Helpers\Session::start();

$phpSelf = $_SERVER['PHP_SELF'];
$index = basename($phpSelf);

\SoftUni\Helpers\RouteService::init(str_replace($index, '', $phpSelf));

$requestParams = [];

$controller = "users";
$action = "login";

if(isset($_GET['uri'])) {
    $requestParams = explode('/', $_GET['uri']);

    $controller = ucfirst(array_shift($requestParams));
    $action = ucfirst(array_shift($requestParams));
}

\SoftUni\Core\Database::setInstance(
    \SoftUni\Config\DatabaseConfig::DB_INSTANCE,
    \SoftUni\Config\DatabaseConfig::DB_DRIVER,
    \SoftUni\Config\DatabaseConfig::DB_USER,
    \SoftUni\Config\DatabaseConfig::DB_PASSWORD,
    \SoftUni\Config\DatabaseConfig::DB_NAME,
    \SoftUni\Config\DatabaseConfig::DB_HOST
);

$app = new \SoftUni\Application($controller, $action, $requestParams);
$app->start();