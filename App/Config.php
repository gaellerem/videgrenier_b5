<?php

namespace App;

class Config
{
    /**
     * Database host
     */
    public static function DB_HOST(): string
    {
        return self::getEnv('DB_HOST');
    }

    public static function DB_NAME(): string
    {
        return self::getEnv('DB_NAME');
    }

    public static function DB_USER(): string
    {
        return self::getEnv('DB_USER');
    }

    public static function DB_PASSWORD(): string
    {
        return self::getEnv('DB_PASSWORD');
    }

    public static function SHOW_ERRORS(): bool
    {
        return filter_var(self::getEnv('SHOW_ERRORS'), FILTER_VALIDATE_BOOL);
    }

    private static function getEnv(string $key): string
    {
        if (!isset($_ENV[$key]) && getenv($key) === false) {
            throw new \RuntimeException("Missing env var: {$key}");
        }

        return $_ENV[$key] ?? getenv($key);
    }
}
