<?php
namespace app\middlewares;

use Core\handler\Request;
use Core\handler\Session;
use Core\handler\Error;

use app\models\User;
class Auth{

    public static function next(Request $request){
        if(!Session::has('login_user'))
            Error::send(403);
        else{
            if(!Session::has('user_role')){
                $user=new User();
                $user=$user->select()->where("username",Session::get("login_user"))->first();
                if(!$user)
                    Error::send(403);
                Session::set("user_role",$user->role);
            }
        }
    }
    
}