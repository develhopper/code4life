<?php
namespace app\controllers;

use Core\BaseController;
use Core\handler\Request;
use Core\handler\Session;
use Core\handler\Cookie;
use Core\handler\Validator;


use app\models\User;
use app\models\Auth;

class HomeController extends BaseController{
    
    public function login(Request $request){         
        if($request->isMethod("POST")){
            $user=new User();
            $user=$user->select()->where("username",$request->username)->first();
            if(!$user || !$user->login($request)){
                Session::flash("login_msg","نام کاربری یا رمز عبور اشتباه است");
            }
        }
        if(Session::has("login_user") && Session::has("user_role")){
            if(Session::get("user_role")==2)
                redirect("/admin");
            else
                redirect("/");
        }     
        $this->view("login.html");
    }

    public function logout(){
        if(Cookie::has("login_id")){
            $auth=new Auth();
            $auth->delete("id",Cookie::get("login_id"))->execute();
        }
        Session::remove("login_user");
        Cookie::remove("login_id");
        Cookie::remove("remember_token");
        redirect("/");
    }

    public function register(Request $request){
        $rules=[
            ["string"=>$request->username],
            ["string"=>$request->password],
            ["email"=>$request->email]
        ];
        if(!Validator::validate($rules)){
            Session::flash("register_danger","لطفا ورودی های خود را بررسی کنید");
        }
        $user=new User();
        $user->username=$request->username;
        $user->password=password_hash($request->password,PASSWORD_DEFAULT);
        $user->email=$request->email;
        if($user->save()){
            Session::flash("register_success","حساب کاربری شما با موفقیت ایجاد شد");
        }else
            Session::flash("register_danger","لطفا ورودی های خود را بررسی کنید");
        redirect("/login");
    }
}