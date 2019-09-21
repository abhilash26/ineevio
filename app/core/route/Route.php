<?php

namespace core\route;
/**
 * class Route
 */

class Route 
{

    public static $routes = [
        "get" =>  [],
        "post" => []
    ];

    private static function register($method, $path, $callback, $name='') {

        $route = [];

        if(!empty($name)) 
        {$route['name'] = $name;}

        if(preg_match_all("~{([^}]+)}~", $path, $matches, PREG_OFFSET_CAPTURE))
        {
            // path contains variables
            $route['args'] = $matches[1];
        }
        else {
            $route['args'] = [];
        }
        if(strpos($path, '/') !== 0) {
            $path = "/".$path;
        }
        if($path != '/') {
            $path = rtrim($path, '/');
        }

        $path_parts = explode('/', $path);
        array_shift($path_parts);
        $route['path'] = $path_parts;

        //***************************************************** */

        if(!$callback instanceof \Closure && count(explode('@', $callback)) == 2)
        {
            $route['callback'] = [
                "controller" => explode('@', $callback)[0],
                "action" => explode('@', $callback)[1],
            ];
        } else {
            $route['callback'] = $callback;
        }

        array_push(self::$routes[strtolower($method)], $route);
    }

    public static function get($path, $callback, $name='') {
        self::register("get", $path, $callback, $name);
    }


    public static function post($path, $callback, $name='') {
        self::register("post", $path, $callback, $name);
    }

    public static function show() {
        echo "<pre>";
        print_r(self::$routes);
        echo "</pre>";
    }
    
} // End of class
