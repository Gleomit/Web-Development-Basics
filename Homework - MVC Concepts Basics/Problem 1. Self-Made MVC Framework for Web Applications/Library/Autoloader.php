<?php

namespace Library;

class Autoloader
{
    public static function register() {
        spl_autoload_register(function($class) {
            $classPath = str_replace("\\", "/", $class);

            require_once $classPath . '.php';
        });
    }
}