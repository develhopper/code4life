<?php

namespace Core;

class Route
{
    private static $routes = [];

    private static function add($route, $controller, $method,$middleware)
    {
        $regex = preg_replace("/\{\w*\}/i", '(.+)', $route);
        $regex = str_replace("/", "\/", $regex);

        $controller = explode("@", $controller);
        
        return array_push(self::$routes, [
            'controller' => $controller[0],
            'function' => $controller[1],
            'regex' => $regex,
            'method' => $method,
            'middleware'=>$middleware
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
    
    public static function find($name)
    {
        $name = rtrim($name, "/");
        if (empty($name))
            $name = "/";
        foreach (self::$routes as $r) {
            if (preg_match("/^" . $r['regex'] . "$/i", $name) && self::method_check($r))
                return $r;
        }
    }

    public static function middleware($name=[],$index=[]){
        foreach($index as $i){
            self::$routes[$i - 1]["middleware"]=$name;
        }
    }

    public static function dump()
    {
        var_dump(self::$routes);
    }

    private static function method_check($route)
    {
        if(strcmp($route['method'],$_SERVER['REQUEST_METHOD'])==0)
            return true;
    }
}
