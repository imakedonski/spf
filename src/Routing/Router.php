<?php

namespace SPF\Routing;

use SPF\Exceptions\ControllerNotFound;
use SPF\Exceptions\MethodNotSupported;
use SPF\Exceptions\RouteNotFound;
use SPF\Exceptions\ControllerMethodNotFound;
use SPF\Routing\Route;
final class Router
{
    /**
     * Routes holder
     *
     * @var array
     */
    protected static array $routes = [];

    /**
     * Private constructor.
     */
    private function __construct() {}

    /**
     * Maps a handler to a given GET route.
     *
     * @param string $path
     * @param string|callable $handler
     * @return void
     */
    public static function get(string $path, string|callable $handler): void
    {
        self::addRoute(new Route(self::normalizePath($path), $handler));
    }

    /**
     * Maps a handler to a given POST route.
     *
     * @param string $path
     * @param string|callable $handler
     * @return void
     */
    public static function post(string $path, string|callable $handler): void
    {
        self::addRoute(new Route(self::normalizePath($path), $handler, 'POST'));
    }

    /**
     * Dispatches the given route
     *
     * @return null
     */
    public static function dispatch()
    {
        if (self::routeExists(self::getRequestURI(), self::getRequestMethod())) {
            $route = self::getRoute(self::getRequestURI(), self::getRequestMethod());

            echo self::callHandler($route);
        }
    }

    /**
     * Adds a new route to the $routes array.
     *
     * @param Route $route
     * @return void
     */
    protected static function addRoute(Route $route): void
    {
        self::$routes[$route->getPath()][$route->getMethod()] = $route;
    }

    /**
     * @param string $path
     * @return string
     */
    protected static function normalizePath(string $path): string
    {
        $path = str_starts_with($path, '/') ? $path : '/' . $path;

        return strtolower($path);
    }

    /**
     * Returns true if a certain route exists.
     *
     * @param string $path
     * @param string $method
     * @return bool
     * @throws MethodNotSupported
     * @throws RouteNotFound
     */
    public static function routeExists(string $path, string $method = 'GET'): bool
    {
        if (!array_key_exists($path, self::getRoutes())) {
            throw new RouteNotFound('Route not found.');
        }

        if (empty(self::$routes[$path][$method])) {
            throw new MethodNotSupported('Method not supported.');
        }

        return true;
    }

    /**
     * Returns routes array.
     *
     * @return array
     */
    public static function getRoutes(): array
    {
        return self::$routes;
    }

    /**
     * @param string $path
     * @param string $method
     * @return \SPF\Routing\Route|null
     */
    public static function getRoute(string $path, string $method = 'GET'): ?Route
    {
        return self::getRoutes()[$path][$method] ?? null;
    }

    /**
     * Calls route's handler.
     * 
     * @param \SPF\Routing\Route $route
     * @return mixed
     * @throws ControllerMethodNotFound
     * @throws ControllerNotFound
     */
    public static function callHandler(Route $route): mixed
    {
        $handler = $route->gethandler();

        if (is_callable($handler)) {
            return call_user_func($handler);
        } else {
            return self::callController($handler);
        }
    }

    /**
     * Executes controller's method.
     *
     * @param string $handler
     * @return mixed
     * @throws ControllerMethodNotFound
     * @throws ControllerNotFound
     */
    protected static function callController(string $handler)
    {
        if (!str_contains($handler, '@')) {
            throw new ControllerMethodNotFound("Method $handler does not exists.");
        }

        $details = explode('@', $handler);
        $controller = $details[0];
        $method = $details[1];
        $fileName = $controller . '.php';

        if (!self::controllerExists($fileName)) {
            throw new ControllerNotFound("Controller $fileName not found.");
        }

        self::includeController($fileName);

        $object = self::getControllerInstance($controller);

        if (!method_exists($object, $method)) {
            throw new ControllerMethodNotFound("Method {$object} {$method} not found.");
        }

        return $object->$method();
    }

    /**
     * Returns true if a certain controller exists.
     *
     * @param string $fileName
     * @return bool
     */
    protected static function controllerExists(string $fileName): bool
    {
        if (!file_exists(__DIR__ . "/../../app/Controllers/{$fileName}")) {
            return false;
        }

        return true;
    }

    /**
     * Includes certain controller.
     *
     * @param string $fileName
     * @return void
     */
    protected static function includeController(string $fileName): void
    {
        require_once(__DIR__ . "/../../app/Controllers/{$fileName}");
    }


    /**
     * Returns controller instance.
     *
     * @param string $controller
     * @return object
     */
    protected static function getControllerInstance(string $controller): object
    {
        $controller = self::getControllerFQN($controller);

        return new $controller;
    }

    /**
     * Returns controller's Fully Qualified Name.
     *
     * @param string $controller
     * @return string
     */
    protected static function getControllerFQN(string $controller): string
    {
        return 'App\Controllers\\' . $controller;
    }

    /**
     * Returns the current request method.
     *
     * @return string|null
     */
    protected static function getRequestMethod(): ?string
    {
        return $_SERVER['REQUEST_METHOD'] ?? null;
    }

    /**
     * Returns the Request URI.
     *
     * @return string|null
     */
    protected static function getRequestURI(): ?string
    {
        return isset($_SERVER['REQUEST_URI']) ? self::normalizePath($_SERVER['REQUEST_URI']) : null;
    }
}