<?php
namespace app\models;

use Core\Model;
use Core\handler\Validator;
use Core\handler\Request;
use Core\handler\Session;
use Core\handler\Cookie;

class User extends Model{
    protected $table="users";
    protected $related_tables=["user_logins"=>"id"];
    public function login(Request $request){
        $rules=[
            ["string"=>$request->username],
            ["min:4"=>$request->password]
        ];
        
        if(Validator::validate($rules) && password_verify($request->password,$this->password)){
            Session::set("login_user",$this->username);
            Session::set("user_role",$this->role);
            Session::set("user_id",$this->id);
                if($request->has("remember")){
                    $auth=new Auth();
                    $auth->user_id=$this->id;
                    $auth->remember_token=bin2hex(random_bytes(10));
                    $auth->expired_at=date("Y-m-d H:i:s",time()+3600*24*30);
                    $login_id=$auth->save();
                    Cookie::set("login_id",$login_id);
                    Cookie::set("login_user",$this->username);
                    Cookie::set("remember_token",$auth->remember_token);
                }
                return true;
        }
        return false;
    }

    public function withLogin(){
        return $this->left_join(\app\models\Auth::class);
    }
    
    public function withNotes(){
        return $this->left_join(\app\models\Note::class);
    }
}