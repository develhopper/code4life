<?php
namespace Core;

use Core\handler\Session;
use Core\exceptions\RouteException;

class Router{
    private static $instance=null;
    
    private $routes=[
        "GET"=>[],
        "POST"=>[],
        "PUT"=>[],
        "DELETE"=>[]
    ];

    public $prefix;
    public $csrf;

    private function __construct(){

    }

    public static function getInstance(){
        if(self::$instance==null)
            self::$instance=new Router();
        return self::$instance;
    }

    public function register($path,$options=[]){
        $this->applyOptions($options);
        if(file_exists($path)){
            include_once $path;
        }
        else
            die("Router: $path not exists");
    }

    private function applyOptions($options=[]){
        $this->prefix=(isset($options["prefix"]))?$options["prefix"]:"";
        $this->csrf=(isset($options["csrf"]))?$options["csrf"]:false;
    }

    public function add($method,$route){
        array_push($this->routes[$method],$route);
    }

    public function find($name){
        $reqeust_method=$_SERVER['REQUEST_METHOD'];
        if($reqeust_method=="POST"){
            if(isset($_REQUEST['_method']))
                $reqeust_method=$_REQUEST['_method'];
        }
        
        $name = explode('?',$name)[0];
        
        if (empty($name))
            $name = "/";
        foreach ($this->routes[$reqeust_method] as $r) {
            if (preg_match("/^" . $r['regex'] . "$/i", $name) && $this->method_check($r,$reqeust_method)){
                Session::set("current_route",$r);
                return $r;
            }
        }
        throw new RouteException("Route no found",404);
    }

    private function method_check($route,$reqeust_method)
    {
        if(strcmp($route['method'],$reqeust_method)==0)
            return true;
        else
            throw new RouteException("Method not Allowed",405);
    }

    public function __get($key){
        return $this->$key;
    }
}