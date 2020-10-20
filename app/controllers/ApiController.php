<?php
namespace app\controllers;

use Core\BaseController;
use Core\handler\Request;
use app\models\Comment;
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
}