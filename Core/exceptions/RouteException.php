<?php
namespace Core\exceptions;

use \Exception;

class RouteException extends Exception{
    protected $message = 'Unknown Exception';
    protected $code=500;

    public function __construct($message=null,$code=500){
        $this->message=$message;
        $this->code=$code;
    }

    public function __get($key){
        return $this->$key;
    }
}