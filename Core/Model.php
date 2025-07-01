<?php

namespace Core;

use PDO;
use App\Config;

/**
 * Base model
 *
 * PHP version 7.0
 */
abstract class Model
{
    /**
     * The PDO database connection
     *
     * @var PDO|null
     */
    protected static ?PDO $db = null;

    /**
     * Get the PDO database connection
     *
     * @return PDO
     */
    protected static function getDB(): PDO
    {
        if (static::$db === null) {
            $dsn = 'mysql:host=' . Config::DB_HOST() . ';dbname=' . Config::DB_NAME() . ';charset=utf8';
            static::$db = new PDO($dsn, Config::DB_USER(), Config::DB_PASSWORD());

            // Throw an Exception when an error occurs
            static::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return static::$db;
    }

    /**
     * For testing purposes only: allows injecting a mock PDO instance.
     */
    public static function setDB(PDO $pdo): void
    {
        static::$db = $pdo;
    }
}

