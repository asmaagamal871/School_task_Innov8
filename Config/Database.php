<?php
namespace Config;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;
    private function __construct() {}
    private function __clone() {}

    public static function getInstance()
    {
        if (self::$instance == null) {
            $servername = $_ENV['MySQL_DB_HOST'];
            $username = $_ENV['MySQL_DB_USER_NAME'];
            $password = $_ENV['MySQL_DB_PASSWORD'];
            $databasename = $_ENV['MySQL_DB_NAME'];

            try {
                self::$instance = new PDO("mysql:host=$servername;dbname=$databasename", $username, $password);
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
        }
        return self::$instance;
    }
}
