<?php

namespace Shmidt\Framework;

class Router
{
    protected static array $routes = [];
    protected static array $route = [];

    public static function add($reg, $route = [])
    {
        self::$routes[$reg] = $route;
    }

    public static function dispatch($url)
    {
        $url = self::removeQueryString($url);

        if (self::matchRoute($url)) {
            $controller = self::$route[0];

            if (class_exists($controller)) {
                $cObj = new $controller(self::$route);
                $action = self::$route[1];

                if (method_exists($cObj, $action)) {
                    $cObj->$action();
                } else {
                    echo 'Медод <b>' . $controller . ' => ' . $action . '</b> не найден';
                }
            } else {
                echo 'Controller <b>' . $controller . '</b> не найден';
            }
        } else {
            http_response_code(404);
            include '404.html';
        }
    }

public static function matchRoute($url)
{
    foreach (self::$routes as $pattern => $route) {
        if (preg_match("#$pattern#i", $url, $matches)) {
            foreach ($matches as $key => $value) {
                if (is_string($key)) {
                $route[$key] = $value;
                }
            }

            if (!$route[1]) {
                $route[1] = 'index';
            }

            self::$route = $route;
            return true;
        }
    }
        return false;
}

        protected static function removeQueryString($url)
    {
        if ($url) {
            $params = explode('&', $url, 2);
            if (false == strpos($params[0], '=')) {
                return trim($params[0], '/');
            } else {
                return '';
            }
        }
    }
}