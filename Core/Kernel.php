<?php

namespace Core;

use Core\handler\Error;
use Core\handler\Request;
use Core\exceptions\RouteException;

class Kernel
{
    private $controller;
    private $method;
    private $params = [];
    private $route;
    private $response;
    public function __construct()
    {
        include __DIR__."/kernel_configs.php";
    }

    public function handle(Request $request){
        session_start();
        try{
            $this->route = Route::find($request->url);
            RegisterProvider::register();
        }catch(RouteException $e){
            Error::send($e->code);
        }

        $this->Routemiddleware();
        
        $this->setController();
        array_push($this->params,$request);
        $response=new Response();
        $response->controller=$this->controller;
        $response->method=$this->method;
        $response->params=$this->params;
        $response->route=$this->route;
        return $response;
    }

    private function Routemiddleware(){
        $middles=$this->route['middleware'];
        if(is_string($middles)){
            $middleware=$GLOBALS['middlewares'][$middles];
            $middleware::next();    
        }else if(is_array($middles)){
            foreach($middles as $m){
                $middleware = $GLOBALS['middlewares'][$m];
                $middleware::next();
            }
        }
    }

    private function setController()
    {
        $this->controller = "app\\controllers\\" . $this->route['controller'];
        $this->method = $this->route['function'];
        $this->setParams();
    }
    private function setParams()
    {
        $url_parts = explode("/", $_GET['url']);
        $parts = explode("\/", $this->route['regex']);
        foreach ($parts as $key => $p) {
            if ($p == "(.+)")
            array_push($this->params, $url_parts[$key]);
        }
    }
    }
