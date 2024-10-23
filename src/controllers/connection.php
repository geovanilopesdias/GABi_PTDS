<?php

final class Connection {
    private $config;
    private static $pdo = null;

    public function __construct() {
        $this->config = require 'config.php';
    }

    public static function connect(): PDO {
        if (self::$pdo === null)
            try {
                self::$pdo = new PDO(
                    "pgsql:host=localhost;
                    dbname={
                        self::config['db_name'],
                        self::config['db_user'],
                        self::config['db_pass']}");
            }
            catch (PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            }
            return self::$pdo;
    }
}
?>