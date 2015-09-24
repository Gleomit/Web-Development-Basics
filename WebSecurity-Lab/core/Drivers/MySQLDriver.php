<?php

namespace Core\Drivers;

class MySQLDriver extends DriverAbstract
{
    public function __construct($user, $pass, $dbName, $host = null) {
        $this->user = $user;
        $this->pass = $pass;
        $this->dbName = $dbName;
        $this->host = $host;
    }

    public function getDsn() {
        $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->dbName;

        return $dsn;
    }
}