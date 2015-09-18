<?php

class Db
{
    const hostname = "localhost";
    const username = "root";
    const password = "";
    const db_name = "localization";

    private static $instance;

    public static function getInstance() {
        if(static::$instance === null) {
            $hostname = self::hostname;
            $db_name = self::db_name;

            static::$instance = new PDO("mysql:host=$hostname;dbname=$db_name", static::username, static::password,
                array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        }

        return static::$instance;
    }
} ?>