<?php

namespace App;

use Dotenv\Dotenv;

/**
 * Application configuration
 *
 * PHP version 7.0+
 */
class Config
{
    private static $loaded = false;

    private static function loadEnv(): void
    {
        if (!self::$loaded) {
            $dotenv = Dotenv::createImmutable(dirname(__DIR__));
            $dotenv->load();
            self::$loaded = true;
        }
    }

    /**
     * Database host
     * @var string
     */
    public static function DB_HOST(): string
    {
        self::loadEnv();
        return $_ENV['DB_HOST'] ?? 'default_db';
    }

    /**
     * Database name
     * @var string
     */
    public static function DB_NAME(): string
    {
        self::loadEnv();
        return $_ENV['DB_NAME'] ?? 'default_db';
    }

    /**
     * Database user
     * @var string
     */
    public static function DB_USER(): string
    {
        self::loadEnv();
        return $_ENV['DB_USER'] ?? 'default_user';
    }

    /**
     * Database password
     * @var string
     */
    public static function DB_PASSWORD(): string
    {
        self::loadEnv();
        return $_ENV['DB_PASSWORD'] ?? '';
    }

    /**
     * Show or hide error messages on screen
     * @var boolean
     */
    public static function SHOW_ERRORS(): bool
    {
        self::loadEnv();
        return $_ENV['SHOW_ERROS'] ?? '';
    }
}
