<?php
namespace app\middlewares;

use Core\handler\Request;
use Core\handler\Session;
use Core\handler\Error;
class Admin{

    public static function next(Request $request){
        $_SESSION["current_route"]["no_stat"]=true;
        if(!Session::has("user_role") || !Session::has("login_user"))
            redirect("/login");
        else{
            if(Session::get('user_role')!=2)
                redirect("/login");
        }
    }

}