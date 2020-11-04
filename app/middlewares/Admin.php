<?php
namespace app\middlewares;

use Core\handler\Request;
use Core\handler\Session;
use Core\handler\Error;
class Admin{

    public static function next(Request $request){
        if(!Session::has("user_role") || !Session::has("login_user"))
            Error::send(403);
    }

}