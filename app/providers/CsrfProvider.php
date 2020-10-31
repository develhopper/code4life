<?php
namespace app\providers;

use Core\handler\Error;
use Core\handler\Session;

class CsrfProvider{
    
    public static function boot(){
        $current_route=Session::get("current_route");
        if(in_array($current_route['method'],["PUT","PATCH","DELETE"]) && $current_route["csrf"]){
            if(!isset($_REQUEST['csrf']))
                Error::send(403);
            if(Session::has('csrf')&&Session::get('csrf')==$_REQUEST['csrf']){
                Error::send(403);
                exit;
            }
            else{
                Session::set('csrf',$_REQUEST['csrf']);
            }
        }
    }
}