<?php
namespace Core\handler;

class Session{
    public static function get($key){
        if(self::has($key))
        return $_SESSION[$key];
    }

    public static function set($key,$value){
        $_SESSION[$key]=$value;
    }
    
    public static function has($key){
        return isset($_SESSION[$key])||isset($_SESSION['flash'][$key]);
    }

    public static function flash($key,$message=null){
        if($message==null){
            $msg= $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $msg;
        }
        $_SESSION['flash'][$key]=$message;
    }

    public function remove($key){
        unset($_SESSION[$key]);
    }

    public function flush(){
        session_unset();
        session_destroy();
    }
}