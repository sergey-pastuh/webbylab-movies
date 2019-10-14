<?php

namespace Model\Utils;

use App;
use PDO;
use PDOException;
use Exception;

class DatabaseConnection
{
    private static $connection = null;

    public static function getConnection()
    {
        if (is_null(self::$connection)) {
            $db = App::config()['database'];

            self::$connection = new PDO($db['dsn'], $db['user'], $db['pass']);
            self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return self::$connection;
    }

    public static function testConnection()
    {
        try {
            self::getConnection();
        } catch (PDOException $e) {
            App::log('Database connection PDO error: '.$e->getMessage());
        } catch (Exception $e) {
            App::log('Database connection error: '.$e->getMessage());
        }
    }
}
