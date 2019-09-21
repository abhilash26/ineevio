<?php

namespace core\route;

use core\server\Request;

class Dispatch {

    private $routes;
    private $controller;

    public function __construct($routes = [])
    {
        $this->routes = $routes;
        $this->controller = new \controllers\DefaultController();

    }

    private static function dispatch($callback, $vars=[]) {

        if($callback instanceof \Closure)
        {
            if(!\is_callable($callback)) {
                throw new \BadFunctionCallException('Function ' . $callback . ' is not callable');
                return;
            }
            call_user_func_array($callback, $vars);
        } else {
            $controller_class = "controllers\\".$callback['controller'];
            $controller = new $controller_class();
            if(!\is_callable([$controller, $callback['action']])) {
                throw new \BadMethodCallException('Method ' . $callback['action'] . ' is not callable');
                return;
            }
            call_user_func_array([$controller, $callback['action']], $vars);
        }
    }

    public function dispatchRequest(Request $request) {

        // Get URI Segments
        $segment = $request->segment();
        $segment_count = count($segment);
        $method = $request->method();

        if(!isset($this->routes[$method]) || count($this->routes[$method]) === 0) {
            throw new \Exception("No routes of request method exists.");
        } 

        $vars = [];
        $segment_matched = [];
        foreach($this->routes[$method] as $route) {


            $route_path_count = count($route['path']);

            // dd($route_path_count);

            if($route_path_count != $segment_count) {
                // echo "Count is not same {$route_path_count} != {$segment_count}</br/>";
                continue;
            }

            for ($index=0; $index < min($route_path_count, $segment_count); $index++) { 
                if(preg_match("~{([^}]+)}~", $route['path'][$index], $match)) {
                    // Is a variable
                    $vars[$match[1]] =  $segment[$index];
                    $segment_matched[] = true;
                } else {
                    if($route['path'][$index] != $segment[$index]) {
                        // echo "Segment not matched {$route['path'][$index]} != {$segment[$index]} <br/>";
                        $segment_matched=[];
                        break;
                    } else {
                        $segment_matched[] = true;
                    }
                }
            }
            $all_matched = count(array_unique($segment_matched)) === 1 && end($segment_matched);
            if($route_path_count === count($segment_matched) && $all_matched) {
                $this->dispatch($route['callback'], $vars);
                return;
            }
        }
        $this->controller->error('404');
        return;
    }
}

