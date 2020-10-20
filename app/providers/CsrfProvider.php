<?php
namespace app\providers;

use Core\handler\Error;
use Core\handler\Session;
use Core\handler\Request;

class CsrfProvider{
    
    public static function boot(Request $request){
        if ($_SERVER['REQUEST_METHOD']=='POST'){
            
            if(!$request->has('csrf')){
                Error::send(403);
                exit;
            }

            if(Session::has('csrf') && Session::get('csrf')==$request->csrf){
                Error::send(403);
                exit;
            }
            else{
                Session::set('csrf',$request->csrf);
            }
        }
    }
}