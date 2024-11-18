<?php

namespace SPF\App;

final class App
{
    private function __construct() {}

    public static function init() {
        self::loadRoutes();
    }

    /**
     * Loads routes.php file.
     *
     * @return void
     */
    protected static function loadRoutes(): void
    {
        if (!file_exists(self::getRoutesPath())) {
            die('File app/routes.php is missing.');
        }

        require_once(self::getRoutesPath());
    }

    /**
     * Returns routes.php file path.
     *
     * @return string
     */
    protected static function getRoutesPath(): string
    {
        return __DIR__ . '/../../app/routes.php';
    }
}
