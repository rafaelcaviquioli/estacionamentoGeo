<?php
use \Config;
class ConnectionPDO {

    static $instance;

    static function getConnection() {
        try {
            if (!isset(self::$instance)) {
                self::$instance = new PDO("pgsql:host=localhost; dbname=" . Config::$db . ";", Config::$user, Config::$password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8'));
            }
            return self::$instance;
        } catch (Exception $e) {
            throw $e;
        }
    }

}