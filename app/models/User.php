<?php
namespace app\models;

use Core\Model;
use Core\handler\Validator;
use Core\handler\Request;
use Core\handler\Session;
use Core\handler\Cookie;

class User extends Model{
    protected $table="users";

    public function login(Request $request){
        $rules=[
            ["string"=>$request->username],
            ["min:4"=>$request->password]
        ];
        
        if(Validator::validate($rules) && password_verify($request->password,$this->password)){
            Session::set("login_id",$this->username);
                if($request->has("remember")){
                    $auth=new Auth();
                    $auth->user_id=$this->id;
                    $auth->remember_token=base64_encode(random_bytes(10));
                    $auth->expired_at=date("Y-m-d H:i:s",time()+3600*24*30);
                    $auth->save();
                    Cookie::set("login_id",$this->username);
                    Cookie::set("remember_token",$auth->remember_token);
                }
                return true;
        }
        return false;
    }
}