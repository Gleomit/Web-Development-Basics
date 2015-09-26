<?php

namespace Framework\Library\Drivers;

class DriverFactory
{
    public static function create($driver, $user, $pass, $dbName, $host) {
        switch($driver) {
            case 'mysql' : {
                return new MySQLDriver($user, $pass, $dbName, $host);
            }
            default: {
                throw new \Exception("Not supported driver.");
            }
        }
    }
}