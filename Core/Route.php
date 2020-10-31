<?php

namespace Core;

use Core\exceptions\RouteException;
use Core\handler\Session;
use Core\Router;

class Route
{

    private static function add($route, $controller, $method,$middleware)
    {
        $router=Router::getInstance();
        $route=$router->prefix.$route;
        $regex = preg_replace("/\{\w*\}/i", '(.+)', $route);
        $regex = str_replace("/", "\/", $regex);

        $controller = explode("@", $controller);
        
        $router->add($method,[
            'controller' => $controller[0],
            'function' => $controller[1],
            'regex' => $regex,
            'method' => $method,
            'middleware'=>$middleware,
            'csrf'=>$router->csrf
        ]);
    }

    public static function get($route, $controller,$middleware=null)
    {
        return self::add($route, $controller, "GET",$middleware);
    }

    public static function post($route, $controller, $middleware = null)
    {
        return self::add($route, $controller, "POST",$middleware);
    }

    public function put($route, $controller, $middleware=null){
        return self::add($route, $controller, "PUT",$middleware);
    }

    public static function patch($route, $controller, $middleware = null)
    {
        return self::add($route, $controller, "PATCH",$middleware);
    }

    public static function delete($route, $controller, $middleware = null)
    {
        return self::add($route, $controller, "DELETE",$middleware);
    }

}
