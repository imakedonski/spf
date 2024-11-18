<?php

namespace SPF\Database;

use \PDO;
use \PDOException;

class Database
{
    /**
     * The current instance.
     *
     * @var null
     */
    private static ?PDO $instance = null;

    private function __construct() {}

    public static function getInstance(string $dsn, string $user, string $passwd): PDO
    {
        if (is_null(self::$instance)) {
            try {
                self::$instance = new PDO($dsn, $user, $passwd);
            } catch (PDOException $e) {
                die("Cannot connect to the database: " . $e->getMessage());
            }
        }

        return self::$instance;
    }
}
