<?php
namespace app\controllers;

use Core\BaseController;
use app\models\Post;
use app\models\Category;
use app\misc\Generator;
use Core\handler\Request;
use app\misc\G;

class IndexController extends BaseController{

    public function index(Request $request){
        $page=($request->page)?$request->page:1;
        $per_page=10;

        $model=new Post();
        $posts=$model->select()->sort("created_at","DESC")->paginate($page,$per_page)->get();
        $seo_items=[
          "description"=>"کد برای زندگی | البته اینجا منظور از زندگی، زندگی مادی نیست",
          "canonical"=>BASEURL,
          "og"=>[
            "title"=>"کد برای زندگی",
            "description"=>"کد برای زندگی | البته اینجا منظور از زندگی، زندگی مادی نیست",
            "type"=>"website",
            "locale"=>"fa_IR"
          ]
        ];
        $catList=G::get_category_tree();
        $pagination=[
          "current"=>$page,
          "total"=>ceil($model->count()/10),
          "link"=>"?page={page}"
        ];
        $this->view("index.html",["title"=>"کد برای زندگی","posts"=>$posts,
        "categories"=>Generator::category_nav($catList),"seo_items"=>$seo_items,"pagination"=>$pagination]);
    }

    public function post($slug){
        $model=new Post();
        $post=$model->select()->where("slug",$slug)->get()[0];
        $comments=$post->comments();
        $seo_items=[
          "description"=>$post->description,
          "canonical"=>BASEURL."/p/".$post_slug,
          "og"=>[
            "title"=>$post->title,
            "description"=>$post->description,
            "image"=>BASEURL."/storage".$post->thumbnail,
            "type"=>"article",
            "locale"=>"fa_IR"
          ]
        ];
        $catList=G::get_category_tree();

        $this->view("post.html",
        ["post"=>$post,"comments"=>$comments,
        "categories"=>Generator::category_nav($catList),"seo_items"=>$seo_items]);
    }

    public function category($id,Request $request){
        $page=($request->page)?$request->page:1;
        $per_page=10;
        $model=new Post();
        $select="posts.id,posts.title,slug,description";
        $posts=$model->select($select)->join(\app\models\PostCategory::class)->where("category_id",$id)->sort("created_at","DESC")->paginate($page,$per_page)->get();

        $total=$model->select("count(*)")->join(\app\models\PostCategory::class)->where("category_id",$id)->execute()->fetchColumn();

        $pagination=[
          "current"=>$page,
          "total"=>ceil($total/10),
          "link"=>"?page={page}"
        ];

        $category=new Category();
        $category=$category->select()->where("id",$id)->first();
        $this->view("category.html",["title"=>$category->name,"category"=>$category,"posts"=>$posts,
        "categories"=>Generator::category_nav(G::get_category_tree()),"pagination"=>$pagination]);
    }

    public function invalidateCache(){
        echo "invalidating caches ... <br>";
        file_put_contents(BASEDIR."/public/cache/cache.json","");
        echo "done";
    }

    public function dummy(){
      $post=new Post();
      for($i=0;$i<50;$i++){
        $post->title="title ".$i;
        $post->body="body";
        $post->description="description";
        $post->author_id=1;
        $post->slug=slug($post->title);
        $post->uri="p/".$post->slug;
        $post->save();
      }
    }

    public function test(Request $reqeust){
        $this->json($reqeust->all());
    }
}
