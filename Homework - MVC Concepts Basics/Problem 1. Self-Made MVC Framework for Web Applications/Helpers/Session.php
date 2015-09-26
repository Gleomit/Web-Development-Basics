<?php

namespace Framework\Helpers;

class Session
{
    public static function start() {
        session_start();
    }

    public static function set($key, $value) {
        if(isset($_SESSION[$key])) {
            return false;
        }

        $_SESSION[$key] = $value;

        return true;
    }

    public static function get($key) {
        if(isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }

        return null;
    }

    public static function unsetKey($key) {
        if(isset($_SESSION[$key])) {
            unset($_SESSION[$key]);

            return true;
        }

        return false;
    }
}