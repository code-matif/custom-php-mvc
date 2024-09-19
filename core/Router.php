<?php

namespace Core;

use Phroute\Phroute\RouteCollector;
use Phroute\Phroute\Dispatcher;

class Router
{
    protected static $router;
    protected static $routeNames = [];
    protected static $currentPrefix = '';
    protected static $currentRouteNamePrefix = '';

    // Initialize the router
    public static function init()
    {
        self::$router = new RouteCollector();
    }

    // Define a GET route with optional middleware and name
    public static function get($route, $action, $name = null, $middlewares = [])
    {
        self::registerRoute('get', $route, $action, $name, $middlewares);
    }

    // Define a POST route with optional middleware and name
    public static function post($route, $action, $name = null, $middlewares = [])
    {
        self::registerRoute('post', $route, $action, $name, $middlewares);
    }

    // Register route logic for both GET and POST
    protected static function registerRoute($method, $route, $action, $name, $middlewares)
    {
        // Apply middlewares
        if (!is_array($middlewares)) {
            $middlewares = [$middlewares];
        }

        $actionWithMiddleware = self::applyMiddleware($action, $middlewares);



        // Register the route in Phroute
        self::$router->{$method}($route, $actionWithMiddleware);

        // Store the route name if provided, with the full prefixed route
        if ($name) {
            if (self::$currentRouteNamePrefix && self::$currentRouteNamePrefix != "") {
                $name = self::$currentRouteNamePrefix . $name;
            }

            if (self::$currentPrefix && self::$currentPrefix != "") {
                $route = self::$currentPrefix . $route;
            }
            self::$routeNames[$name] = $route;
        }
    }

    // Apply middleware to the route action
    protected static function applyMiddleware($action, $middlewares)
    {
        if (!empty($middlewares)) {
            // Wrap the action in a middleware stack
            foreach (array_reverse($middlewares) as $middleware) {
                $next = $action;
                $action = function () use ($middleware, $next) {
                    return (new $middleware)->handle($next);
                };
            }
        }

        return $action;
    }


    public static function group(array $attributes, callable $callback)
    {
        // Save the current prefix and name prefix for nesting
        $previousPrefix = self::$currentPrefix;
        $previousRouteNamePrefix = self::$currentRouteNamePrefix;

        $namePrefix = $attributes['as'] ?? '';
        $prefix = $attributes['prefix'] ?? '';
        $middlewares = $attributes['middleware'] ?? [];

        self::$currentPrefix = $previousPrefix . $prefix;
        self::$currentRouteNamePrefix = $previousRouteNamePrefix . $namePrefix;

        // Apply the middleware stack for each route inside the group
        $callbackWithMiddleware = function () use ($callback, $middlewares) {
            // Wrap the entire group in the middleware stack
            if (!empty($middlewares)) {
                $wrappedCallback = $callback;
                foreach (array_reverse($middlewares) as $middleware) {
                    $wrappedCallback = function () use ($middleware, $wrappedCallback) {
                        return (new $middleware)->handle($wrappedCallback);
                    };
                }
                return $wrappedCallback();
            } else {
                return $callback();
            }
        };

        $callbackWithMiddleware();

        // Restore the previous prefix and name prefix after the group is done
        self::$currentPrefix = $previousPrefix;
        self::$currentRouteNamePrefix = $previousRouteNamePrefix;
    }

    // // Group routes with common prefix, middlewares, and names
    // public static function group(array $attributes, callable $callback)
    // {
    //     // Save the current prefix and name prefix for nesting
    //     $previousPrefix = self::$currentPrefix;
    //     $previousRouteNamePrefix = self::$currentRouteNamePrefix;


    //     $namePrefix = $attributes['as'] ?? '';
    //     $prefix = $attributes['prefix'] ?? '';
    //     $middlewares = $attributes['middleware'] ?? [];

    //     self::$currentPrefix = $previousPrefix . $prefix;
    //     self::$currentRouteNamePrefix = $previousRouteNamePrefix . $namePrefix;

    //     // Wrap the callback in middleware and prefix handling
    //     self::$router->group(['prefix' => $prefix, 'before' => $middlewares], $callback);

    //     // Restore the previous prefix and name prefix after the group is done
    //     self::$currentPrefix = $previousPrefix;
    //     self::$currentRouteNamePrefix = $previousRouteNamePrefix;
    // }

    // Generate a URL by route name
    public static function route($name, $params = [])
    {
        if (!isset(self::$routeNames[$name])) {
            throw new \Exception("No route found with name: $name");
        }

        // Retrieve the route and replace placeholders with actual parameters
        $route = self::$routeNames[$name];
        foreach ($params as $key => $value) {
            $route = str_replace("{{$key}}", $value, $route);
        }

        return $route;
    }

    // Dispatch the router
    public static function dispatch()
    {
        $dispatcher = new Dispatcher(self::$router->getData());

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
