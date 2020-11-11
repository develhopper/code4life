<?php
namespace app\controllers;

use Core\BaseController;
use app\models\Post;
use app\models\Category;
use app\misc\Generator;
use Core\handler\Request;

class IndexController extends BaseController{

    public function index(){
        $model=new Post();
        $posts=$model->select()->get();
        
        $model=new Category();
        $model->alias="c1";
        $select="c1.id,c1.name as cat_name,c1.slug as cat_slug,c2.slug as parent_slug,c1.parent_id as parent_id,c2.name parent_name,c2.parent_id as parent_parent";
        $categories=$model->select($select)->withParent()->get();
        $catList=$model->toTree($categories);

        $this->view("index.html",["posts"=>$posts,"categories"=>Generator::category_nav($catList)]);
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

    public function test(Request $reqeust){
        $this->json($reqeust->all());
    }
}