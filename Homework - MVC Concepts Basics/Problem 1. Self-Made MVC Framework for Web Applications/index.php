<?php
    require_once 'Library\Autoloader.php';

    Library\Autoloader::register();

    $frontController = new Library\FrontController();

    $frontController->run();