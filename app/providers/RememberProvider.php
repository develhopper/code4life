<?php
namespace app\providers;

use Core\handler\Session;
use Core\handler\Cookie;
use app\models\User;
use app\models\Auth;

class RememberProvider{
    
    public static function boot(){
        if(!Session::has('login_user') && Cookie::has("login_id") && Cookie::has("remember_token")){
            $login_id=Cookie::get("login_id");
            $remember_token=Cookie::get("remember_token");

            $user=new User();
            $user=$user->select("*")->withLogin()->where("user_logins.id",$login_id)->and("remember_token",$remember_token)->first();
            if($user){
                $expired_at=new \DateTime($user->expired_at);
                $now=new \DateTime();
                if($expired_at < $now ){
                    Cookie::remove("login_id");
                    Cookie::remove("remember_token");
                    $auth=new Auth();
                    $auth->delete("remember_token",$remember_token)->execute();
                }else{
                    Session::set("login_user",$user->username);
                    Session::set("user_id",$user->user_id);
                    Session::set("user_role",$user->role);
                }
            }
        }
    }
}