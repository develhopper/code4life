<?php
namespace Core;

use Core\handler\Error;
use Primal\Primal;
class BaseController{
    protected $method;
    public function __construct()
    {
        
    }

    public function __get($key){
        return $this->$key;
    }

    public function __set($key,$value){
        $this->$key=$value;
    }

    public function view($name,$params=[]){
        $primal=Primal::getInstance(["views_dir"=>VIEWS_DIR,"cache_dir"=>CACHE_DIR]);
        $primal->view($name,$params);
    }
    
    public function redirect($route){
        header("Location: $route");
        exit;
    }

    public function middleware($name){
        $middleware = $GLOBALS['middlewares'];
        $middleware[$name]::next();
    } 
}