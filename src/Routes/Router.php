<?php

namespace Backend\Products\Routes;

class Router
{
    private static $routes = [];

    public static function addRoutes($method, $url, $callback)
    {
        self::$routes[] = [
            "method" => $method,
            "url" => $url,
            "callback" => $callback
        ];
    }

    public static function resolve()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        foreach (self::$routes as $route) {
            if ($route['method'] == $method && $route['url'] === $uri) {
                return call_user_func(callback: $route['callback']);
            }
        }
        
        http_response_code(404);
        echo json_encode(
            [
                "Msg" => "Route not found or method not allowed"
            ]
        );
    }

    public static function get($url, $callback)
    {
        self::addRoutes("GET", $url, $callback);
    }

    public static function post($url, $callback)
    {
        self::addRoutes("POST", $url, $callback);
    }
    public static function put($url, $callback)
    {
        self::addRoutes("PUT", $url, $callback);
    }
    public static function delete($url, $callback)
    {
        self::addRoutes("DELETE", $url, $callback);
    }
    public static function options($url, $callback)
    {
        self::addRoutes("OPTIONS", $url, $callback);
    }
}
