<?php
namespace app\providers;

use Core\handler\Error;
use Core\handler\Session;

class CsrfProvider{
    
    public static function boot(){
        if (isset($_REQUEST['csrf']))
            if(Session::has('csrf')&&Session::get('csrf')==$_REQUEST['csrf']){
                Error::send(403);
                exit;
            }
            else{
                Session::set('csrf',$_REQUEST['csrf']);
            }
    }
}