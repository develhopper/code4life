<?php
namespace app\controllers;

use Core\BaseController;
use app\models\Post;
class IndexController extends BaseController{

    public function index(){
        $model=new Post();
        $posts=$model->select()->get();
        $this->view("index.html",["posts"=>$posts]);
    }

    public function post($slug){
        $model=new Post();
        $post=$model->select()->where("slug",$slug)->get()[0];
        $comments=$post->comments();
        $this->view("post.html",["post"=>$post,"comments"=>$comments]);
    }

    public function invalidateCache(){
        echo "invalidating caches ... <br>";
        file_put_contents(BASEDIR."/public/cache/cache.json","");
        echo "done";
    }
}