<?php

use SPF\Database\Database;

function db()
{
    $config = 'env';
    $dsn = "mysql:host={$config('DB_HOST')};dbname={$config('DB_NAME')}";

    return Database::getInstance($dsn, env('DB_USER'), env('DB_PASSWORD'));
}

function basePath()
{
    return dirname(__DIR__);
}

function env($key, $default = null)
{
    return $_ENV[$key] ?? $default;
}
