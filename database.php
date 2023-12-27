<?php

define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'todolist');
define('DB_HOST', '127.0.0.1');
define('DB_PORT', '3306');

class Database
{
    private static $dbName = 'todolist';
    private static $dbHost = 'localhost';
    private static $dbPort = '3307'; // Assurez-vous que le port est correct
    private static $dbUsername = 'root';
    private static $dbUserPassword = '';

    private static $cont  = null;

    public function __construct()
    {
    }

    public static function connect()
    {
        // One connection through the whole application
        if (null == self::$cont) {
            try {
                self::$cont =  new PDO(
                    "mysql:host=" . self::$dbHost . ";port=" . self::$dbPort . ";dbname=" . self::$dbName,
                    self::$dbUsername,
                    self::$dbUserPassword
                );
            } catch (PDOException $e) {
                die($e->getMessage());
            }
        }
        return self::$cont;
    }

    public static function disconnect()
    {
        self::$cont = null;
    }
}
?>
