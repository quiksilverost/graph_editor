<?php

class Sql
{
    private static $_db;

    private function __construct() {

    }

    public static function db() {
        if (self::$_db === null) {
            self::$_db = self::connect();
        }

        return self::$_db;
    }

    private static function connect() {
        $dsn = "mysql:dbname=" . DB_NAME . ";host=" . DB_HOST;
        try {
            $connection = new PDO($dsn, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {

        }

        return $connection;

    }

}