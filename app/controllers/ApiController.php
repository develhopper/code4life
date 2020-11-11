<?php
namespace app\controllers;

use Core\BaseController;
use Core\handler\Request;
use app\models\Comment;
use app\models\Category;
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

    public function category(Request $request){
        if(!$request->search)
            return $this->json(["message"=>"missing parameters {search}"],400);
        
        $categories=new Category();
        $categories->alias="c1";
        $select="c1.id,c1.name as cat_name,c1.parent_id as cat_parent,c2.name parent_name,c2.parent_id as parent_parent";
        $categories=$categories->select($select)->withParent()->where("c1.name","like","%$request->search%")->execute();
        $this->json($categories->fetchAll(\PDO::FETCH_ASSOC));
    }
}
