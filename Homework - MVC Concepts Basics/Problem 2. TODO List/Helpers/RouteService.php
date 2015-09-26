<?php

namespace Framework\Helpers;

class RouteService
{
    private static $basePath;

    public static function init($basePath) {
        self::$basePath = $basePath;
    }

    public static function redirect($controller, $action, $exit = false) {
        $location = self::$basePath
            . "$controller/"
            . $action;

        header("Location: " . $location);

        if($exit) {
            exit;
        }
    }

    public static function getUri($controller, $action, $requestParams = []) {
        $location = self::$basePath
            . "$controller/"
            . "$action/"
            . implode("/",$requestParams);

        return $location;
    }
}