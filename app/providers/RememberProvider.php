<?php
namespace app\providers;

use Core\handler\Session;
use Core\handler\Cookie;
use app\models\User;
use app\models\Auth;

class RememberProvider{
    
    public static function boot(){
        if(!Session::has('login_id') && Cookie::has("login_id") && Cookie::has("remember_token")){
            $username=Cookie::get("login_id");
            $remember_token=Cookie::get("remember_token");

            $user=new User();
            $user=$user->withLogin()->where("username",$username)->and("remember_token",$remember_token)->first();
            if($user){
                $expired_at=new \DateTime($user->expired_at);
                $now=new \DateTime();
                if($expired_at < $now ){
                    Cookie::remove("login_id");
                    Cookie::remove("remember_token");
                    $auth=new Auth();
                    $auth->delete("remember_token",$remember_token)->execute();
                }else
                    Session::set("login_id",$username);
            }
        }
    }
}