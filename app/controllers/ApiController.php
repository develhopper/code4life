<?php
namespace app\controllers;

use Core\BaseController;
use Core\handler\Request;
use app\models\Comment;
use Core\Model;

class ApiController extends BaseController{

    public function add_comment($post_id,Request $request){
            $comment=new Comment();
            $comment->name=_e($request->name);
            $comment->body=_e($request->body);
            $comment->email=_e($request->email);
            $comment->post_id=$post_id;
            if($comment->save()){
                $this->json(["message"=>"کامنت شما با موفقیت ارسال شد"],200);
            }else{
                $this->json(["message"=>"درخواست شما با اشکال مواجه شده است :{"],400);
            }
    }

    public function check_username(Request $request){
        if(!$request->has("type") || !$request->has("data"))
            $this->json(["message"=>"error: missing parameters(username)","code"=>400]);        
        $type=explode(":",$request->type);
        $model=new Model();
        $model->table=$type[0];
        $model=$model->select()->where("$type[1]",$request->data)->first();
        if($model){
            $this->json(["message"=>"this $type[1] is taken","code"=>400]);
        }else
            $this->json(["message"=>"this $type[1] is available","code"=>200]);
    }
}
