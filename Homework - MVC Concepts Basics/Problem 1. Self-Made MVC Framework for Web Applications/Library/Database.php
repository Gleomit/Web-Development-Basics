<?php

namespace Library;

use Config\Config;

class Database
{
    /**
     * @var \PDO
     */
    private static $pdo;

    public function __construct($pdo) {
        static::$pdo = $pdo;
    }

    /**
     * @return \PDO
     */
    public static function getInstance() {
        if(static::$pdo == null) {
            static::$pdo = new \PDO('mysql:host=' . Config::DB_HOST . 'dbname=' . Config::DB_NAME,
                Config::DB_USER, Config::DB_PASSWORD);
        }

        return static::$pdo;
    }
}