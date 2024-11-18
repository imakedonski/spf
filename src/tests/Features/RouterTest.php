<?php

namespace SPF\Tests\Features;

use PHPUnit\Framework\TestCase;
use SPF\Exceptions\ControllerNotFound;
use SPF\Exceptions\RouteNotFound;
use SPF\Exceptions\ControllerMethodNotFound;
use SPF\Routing\Router;

final class RouterTest extends TestCase
{
    public function testRegisterAGetRoute()
    {
        // Define our route
        Router::get('/', fn () => 'Homepage');

        // Fake some request
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/';

        Router::dispatch();

        $this->assertTrue(Router::routeExists('/'));
    }

    public function testRegisterAPostRoute()
    {
        Router::post('/', fn () => 'Homepage');

        // Fake some request
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['REQUEST_URI'] = '/';

        Router::dispatch();

        $this->assertTrue(Router::routeExists('/', 'POST'));
    }

    public function testRouteDontExist(): void
    {
        $this->expectException(RouteNotFound::class);

        // Fake some request
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/fake';

        Router::dispatch();
    }

    public function testUndefinedControllerMethod(): void
    {
        $this->expectException(ControllerMethodNotFound::class);

        // Define our route
        Router::get('/new', 'HomeController@fake');

        // Fake some request
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/new';

        Router::dispatch();
    }

    public function testControllerNotFound(): void
    {
        $this->expectException(ControllerNotFound::class);

        // Define our route
        Router::get('/fake-controller', 'FakeController@index');

        // Fake some request
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/fake-controller';

        Router::dispatch();
    }

    public function testControllerHandler(): void
    {
        Router::get('/', 'AboutController@index');

        $route = Router::getRoute('/');

        $this->assertStringContainsString('About Us', Router::callHandler($route));
    }

    public function testClosureRoute(): void
    {
        Router::get('/', function () {
           return 'Closure call.';
        });

        $route = Router::getRoute('/');

        $this->assertTrue(is_callable($route->getHandler()));
        $this->assertStringContainsString('Closure call.', Router::callHandler($route));
    }
}