<?php
namespace app\controllers;

use Core\BaseController;
use app\models\Post;
use app\models\Tag;
use app\models\Category;
use app\models\Comment;
use app\misc\Generator;
use Core\handler\Request;
use Core\handler\Session;
use Core\handler\Error;
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
        $h1="نوشته های اخیر";
        $this->view("index.html",["title"=>"کد برای زندگی","posts"=>$posts,
        "categories"=>Generator::category_nav($catList),"seo_items"=>$seo_items,"pagination"=>$pagination,"h1"=>$h1]);
    }

    public function post($slug){
        $model=new Post();
        $post=$model->select()->where("slug",urldecode($slug))->first();
        if(!$post){
          Error::send(404);
        }
        $comments=$post->comments();
        $seo_items=[
          "description"=>$post->description,
          "canonical"=>BASEURL."/p/".$slug,
          "og"=>[
            "title"=>$post->title,
            "description"=>$post->description,
            "type"=>"article",
            "locale"=>"fa_IR"
          ]
        ];

        if($post->thumbnail){
          $seo_items['og']["image"]=BASEURL."/storage".$post->thumbnail;
        }
        $catList=G::get_category_tree();
        $this->view("post.html",
        ["title"=>$post->title,"post"=>$post,"comments"=>$comments,
        "categories"=>Generator::category_nav($catList),"seo_items"=>$seo_items,"tags"=>$post->Tags()->get()]);
    }

    public function comment($post_id,Request $request){
        $comment=new Comment();
        $comment->name=_e($request->name);
        $comment->body=_e($request->body);
        $comment->email=_e($request->email);
        $comment->post_id=$post_id;
        if($comment->save()){
          Session::flash("message","کامنت شما ثبت شد پس از تایید در وبسایت درج خواهد شد");
        }else{
          Session::flash("message","مشکلی پیش اومده :( صبر کنید ببینیم چی میشه");
        }
        redirect("back");
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
        
        $description=($category->description)?html_entity_decode(strip_tags($category->description)):"دسته بندی ".$category->name." | code4life";
        $seo_items=[
          "description"=>$description,
          "canonical"=>BASEURL."/category/".$category->id,
          "og"=>[
            "title"=>$category->name,
            "description"=>$description,
            "locale"=>"fa_IR"
          ]
        ];

        $this->view("category.html",["title"=>$category->name,"category"=>$category,"posts"=>$posts,
        "categories"=>Generator::category_nav(G::get_category_tree()),"pagination"=>$pagination,"seo_items"=>$seo_items]);
    }

    public function tag($id,Request $request){
      $tag=new Tag();
      $tag=$tag->select()->where("id",$id)->first();

      $page=($request->page)?$request->page:1;
      $per_page=10;

      $posts=$tag->posts($id)->sort("created_at","DESC")->paginate($page,$per_page);
      $seo_items=[
        "description"=>"کد برای زندگی | برچسب $tag->name",
        "canonical"=>BASEURL,
        "og"=>[
          "title"=>"کد برای زندگی | $tag->name",
          "description"=>"کد برای زندگی | برچسب $tag->name",
          "type"=>"website",
          "locale"=>"fa_IR"
        ]
      ];
      $catList=G::get_category_tree();
      $pagination=[
        "current"=>$page,
        "total"=>ceil($posts->count()/10),
        "link"=>"?page={page}"
      ];
      $h1="برچسب $tag->name";
      $this->view("index.html",["title"=>"$tag->name","posts"=>$posts->get(),
      "categories"=>Generator::category_nav($catList),"seo_items"=>$seo_items,"pagination"=>$pagination,"h1"=>$h1]);
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
