<?php
namespace app\controllers;

use Core\BaseController;
use app\models\Post;
use app\models\Category;
use app\misc\Generator;
use Core\handler\Request;
use app\misc\G;

class IndexController extends BaseController{

    public function index(){
        $model=new Post();
        $posts=$model->select()->get();
        $catList=G::get_category_tree();
        $this->view("index.html",["title"=>"صفحه اصلی","posts"=>$posts,"categories"=>Generator::category_nav($catList)]);
    }

    public function post($slug){
        $model=new Post();
        $post=$model->select()->where("slug",$slug)->get()[0];
        $comments=$post->comments();
        
        $catList=G::get_category_tree();
        
        $this->view("post.html",["post"=>$post,"comments"=>$comments,"categories"=>Generator::category_nav($catList)]);
    }

    public function category($id){
        $post=new Post();
        $select="posts.id,posts.title,slug,description";
        $posts=$post->select($select)->join(\app\models\PostCategory::class)->where("category_id",$id)->sort("created_at","DESC")->get();
        
        $category=new Category();
        $category=$category->select()->where("id",$id)->first();
        $this->view("category.html",["title"=>$category->name,"category"=>$category,"posts"=>$posts,
        "categories"=>Generator::category_nav(G::get_category_tree())]);
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