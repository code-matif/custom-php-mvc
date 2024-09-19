<?php

namespace Core;

use Phroute\Phroute\RouteCollector;
use Phroute\Phroute\Dispatcher;

class RouterCopy
{
    protected static $router;

    // Initialize the router
    public static function init()
    {
        self::$router = new RouteCollector();
    }

    // Define a GET route
    public static function get($route, $action)
    {
        self::$router->get($route, $action);
    }

    // Define a POST route
    public static function post($route, $action)
    {
        self::$router->post($route, $action);
    }

    // Dispatch the router
    public static function dispatch()
    {
        $dispatcher = new Dispatcher(self::$router->getData());

        // Get the current URI and method
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        try {
            echo $dispatcher->dispatch($requestMethod, $requestUri);
        } catch (\Phroute\Phroute\Exception\HttpRouteNotFoundException $e) {
            echo "404 - Route Not Found";
        } catch (\Phroute\Phroute\Exception\HttpMethodNotAllowedException $e) {
            echo "405 - Method Not Allowed";
        }
    }
}
