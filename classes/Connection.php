<?php

use \Config;

class Connection{

    static $connection;

    static function getConnection() {
        try {
            if (self::$connection instanceof mysqli) {
                return self::$connection;
            } else {
                return self::$connection = new mysqli(Config::$host, Config::$user, Config::$password, Config::$db);
            }
        } catch (mysqli_sql_exception $e) {
            throw $e;
        }
    }

}

?>
