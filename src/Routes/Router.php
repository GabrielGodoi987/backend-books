<?php

namespace Backend\Products\Routes;

class Router
{
    private static $routes = [];

    public static function addRoutes($method, $url, $callback)
    {
        $url = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<\1>[a-zA-Z0-9_-]+)', $url);
        self::$routes[] = [
            "method" => $method,
            "url" => '#^' . $url . '$#',
            "callback" => $callback
        ];
    }

    public static function resolve()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        foreach (self::$routes as $route) {
            if ($route['method'] == $method && preg_match($route['url'], $uri, $matches)) {
                
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                return call_user_func_array($route['callback'], $params);
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
