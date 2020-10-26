<?php

namespace Core;

use Core\exceptions\RouteException;
use Core\handler\Session;

class Route
{
    private static $routes = [
        "GET"=>[],
        "POST"=>[],
        "PUT"=>[],
        "PATCH"=>[],
        "DELETE"=>[]
    ];

    private static function add($route, $controller, $method,$middleware)
    {
        $regex = preg_replace("/\{\w*\}/i", '(.+)', $route);
        $regex = str_replace("/", "\/", $regex);

        $controller = explode("@", $controller);
        
        return array_push(self::$routes[$method], [
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
    
    public static function find($name)
    {
        $reqeust_method=$_SERVER['REQUEST_METHOD'];
        if($reqeust_method=="POST"){
            if(isset($_REQUEST['_method']))
                $reqeust_method=$_REQUEST['_method'];
        }
        $name = rtrim($name, "/");
        if (empty($name))
            $name = "/";
        foreach (self::$routes[$reqeust_method] as $r) {
            if (preg_match("/^" . $r['regex'] . "$/i", $name) && self::method_check($r,$reqeust_method)){
                Session::set("current_route",$r);
                return $r;
            }
        }
        throw new RouteException("Route no found",404);
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

    private static function method_check($route,$reqeust_method)
    {
        if(strcmp($route['method'],$reqeust_method)==0)
            return true;
        else
            throw new RouteException("Method not Allowed",405);
    }
}
