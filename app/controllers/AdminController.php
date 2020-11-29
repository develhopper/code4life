<?php
namespace app\controllers;

use Core\BaseController;
use Core\handler\Request;
use Core\handler\Session;
use Core\handler\Validator;
use app\models\User;
use app\models\Post;
use app\models\Tag;
use app\models\Note;
use app\models\Stat;
use app\models\Comment;
use app\models\Category;
use app\misc\Generator;
use app\misc\Storage;
use app\misc\G;

class AdminController extends BaseController{

    public function admin(){
        $notes=new Note();
        $notes=$notes->select()->where("user_id",Session::get("user_id"))->first(10);
        $comments=new Comment();
        $comments=$comments->select()->first(10);
        $stats=new Stat();
        $stats=$stats->select()->first(10);
        $this->view("admin/index.html",["title"=>"مدیریت","notes"=>$notes,"comments"=>$comments,"stats"=>$stats]);
    }

    public function files(){
        $this->view("admin/filemanager.html",["title"=>"مدیریت فایل"]);
    }

    public function new_post(){
        $catList=G::get_category_tree();
        
        $this->view("admin/new_post.html",["title"=>"مطلب جدید","categories"=>Generator::category_checkboxes($catList)]);
    }

    public function edit($id){
        $model=new Category();
        $model->alias="c1";
        $select="c1.id,c1.name as cat_name,c1.parent_id as parent_id,c2.name parent_name,c2.parent_id as parent_parent";
        $categories=$model->select($select)->withParent()->sort("c1.id")->get();
        $catList=G::get_category_tree();
        
        $post=new Post();
        $post=$post->select()->where("id",$id)->first();
        
        $tags=$post->Tags()->get();
        
        $selected_categories=$post->Categories()->execute()->fetchAll(\PDO::FETCH_COLUMN);
        
        $this->view("admin/edit_post.html",
        ["title"=>"ویرایش مطلب","post"=>$post,"categories"=>Generator::category_checkboxes($catList,$selected_categories),
        "tags"=>$tags]);
    }

    public function submit_post(Request $request){
        $rules=[
            ["string"=>$request->title],
            ["string"=>$request->body],
            ["string"=>$request->description]
        ];
        if(!Validator::validate($rules)){
            $params=["title"=>"خطا","message"=>"متاسفانه خطایی رخ داده است :(","color"=>"danger","link"=>"/admin"];
            return $this->view("message.html",$params);
        }
        $post_tags=[];
        $thumbnail="''";
        $description=($request->description)?$request->description:"''";
        if($request->tags){
            $tag=new Tag();
            if($tag->save($request->tags)!=-1){
                $post_tags=$tag->select()->in("name",$request->tags)->asArray();
            }
        }

        if(isset($_FILES["thumbnail"])){
            $storage=new Storage();
            $thumbnail=$storage->upload("/thumbnails",$_FILES["thumbnail"]);
        }
        
        $post=new Post();
        $post->title=_e($request->title);
        $post->slug=slug($request->title);
        $post->uri="p/".slug($request->title);
        $post->body=_e($request->body);
        $post->description=_e($description);
        $post->thumbnail=$thumbnail;
        $post->author_id=Session::get("user_id");
        $post->id=$post->save();
        
        if($request->tags){
            $post->addTags($post_tags);
        }
        
        if($request->categories){
            $post->addCategories($request->categories);
        }
        $params=[];
        if($post->id>0){
            $params=["title"=>"انجام شد","message"=>"پست جدید با موفقیت ایجاد شد","color"=>"success","link"=>"/admin"];
        }else{
            $params=["title"=>"خطا","message"=>"متاسفانه خطایی رخ داده است :(","color"=>"danger","link"=>"/admin"];
        }

        $this->view("message.html",$params);
    }

    public function submit_edit($id,Request $request){
        $thumbnail=$request->thumbnail;
        $post=new Post();
        
        $post=$post->select()->where("id",$id)->first();
        $post->title=_e($request->title);
        $post->body=_e($request->body);
        $post->description=_e($request->description);
        
        if(isset($_FILES["thumbnail"])){
            $storage=new Storage();
            $thumbnail=$storage->upload("/thumbnails",$_FILES["thumbnail"]);
            $post->thumbnail=$thumbnail;
        }
        
        $post->update(true);

        $tags=$post->Tags("tags.name")->execute()->fetchAll(\PDO::FETCH_COLUMN);
        
        $categories=$post->Categories()->execute()->fetchAll(\PDO::FETCH_COLUMN);

        
        if($request->tags){
            $tags_diff=G::array_diff2($tags,$request->tags);
            $post_tags=[];
            $description=($request->description)?$request->description:"''";
            if(isset($tags_diff['added'])){
                $tag=new Tag();
                if($tag->save($request->tags)!=-1){
                    $post_tags=$tag->select()->in("name",$request->tags)->asArray();
                    $post->addTags($post_tags);
                }
            }
            if(isset($tags_diff['removed']))
                $post->removeTags($tags_diff['removed']);
        }
        
        if($request->categories){
            $categories_diff=G::array_diff2($categories,$request->categories);
            if(isset($categories_diff['added']))
                $post->addCategories($categories_diff['added']);
            if(isset($categories_diff['removed']))
                $post->removeCategories($categories_diff['removed']);
        }
        $this->view("message.html",["title"=>"انجام شد","message"=>"انجام شد :)","color"=>"success","link"=>"/admin"]);
    }
    
    public function upload_image(Request $request){
        if($_FILES['file']){
            $storage=new Storage();
            echo BASEURL.'/storage/'.$storage->upload("/posts",$_FILES['file']);
        }
    }

    public function recent_posts(){
        $select="posts.id,posts.title,posts.uri,statistics.views,count(comments.id) as comments ";
        $post=new Post();
        $posts=$post->select($select)->withStat()->get();
        $this->view("admin/recent_posts.html",["title"=>"مطالب اخیر","posts"=>$posts]);
    }

    public function delete_post(Request $request){
        if($request->action && $request->posts){
            $post=new Post();
            $post->query="delete from posts where id in(".implode(",",$request->posts).")";
            $post->execute();
            $this->view("message.html",["title"=>"انجام شد","message"=>"انجام شد :)","color"=>"success","link"=>"/admin"]);
        }
        $this->view("message.html",["title"=>"خطا","message"=>"لطفا پارامتر های ورودی را چک کنید","color"=>"danger","link"=>Session::get("current_route")]);
    }

    public function recent_comments(){
        $comment=new Comment();
        $comments=$comment->select()->get();
        $this->view("admin/recent_comments.html",["title"=>"نظرات اخیر","comments"=>$comments]);
    }

    public function comments_action(Request $request){
        if($request->action && $request->comments){
            $comment=new Comment();
            $value=($request->action=="accept")?1:0;
            $comment->query="update comments set accepted=$value where id in (".implode(",",$request->comments).")";
            $comment->execute();
            $this->view("message.html",["title"=>"انجام شد","message"=>"انجام شد :)","color"=>"success","link"=>"/admin"]);
        }
        $this->view("message.html",["title"=>"خطا","message"=>"لطفا پارامتر های ورودی را چک کنید","color"=>"danger","link"=>Session::get("current_route")]);
    }

    public function user_management(){
        $user=new User();
        $select="users.id,users.username,users.email,users.verified,roles.name as role";
        $users=$user->select($select)->withRoles()->where("users.id","!=",Session::get("user_id"))->get();
        $this->view("admin/user_management.html",["title"=>"مدیریت کاربران","users"=>$users]);
    }

    public function page_settings(){
        $this->view("admin/page_settings.html",["title"=>"تنظیمات صفحات"]);
    }

    public function category_settings(Request $request){
        if($request->isMethod("PUT")){
            $category=new Category();
            $category->name=$request->title;
            $category->slug=slug($request->title);
            $category->description=$request->description;
            if(isset($request->parent[0])){
                $category->parent_id=$request->parent[0];
            }
            $category->save();
        }
        $categories=new Category();
        $categories->alias="c1";
        $select="c1.id,c1.name as cat_name,c1.parent_id as cat_parent,c2.name parent_name,c2.parent_id as parent_parent";
        $categories=$categories->select($select)->withParent()->get();

        $this->view("admin/category_settings.html",["title"=>"تنظیمات دسته بندی ها","categories"=>$categories]);
    }

    public function category_delete(Request $request){
        $category=new Category();
        if($request->action=="delete" && $category->delete("id",$request->category_id[0])->execute()){
            $this->view("message.html",["title"=>"انجام شد","message"=>"انجام شد :)","color"=>"success","link"=>"/admin/category_settings"]);
        }
    }

    public function category_edit($id,Request $request){
        $model=new Category();
        $category=$model->select()->where("id",$id)->first();
        if($request->isMethod("PUT")){
            $category->name=$request->title;
            $category->slug=slug($request->title);
            $category->description=$request->description;
            if(isset($request->parent[0])){
                $category->parent_id=$request->parent[0];
            }else{
                $category->parent_id=null;
            }
            $category->update(true);
            redirect("/admin/category_settings");
        }
        $model->alias="c1";
        $select="c1.id,c1.name as cat_name,c1.parent_id as cat_parent,c2.name parent_name,c2.parent_id as parent_parent";
        $categories=$model->select($select)->withParent()->get();
        
        $this->view("admin/category_edit.html",["title"=>"ویرایش دسته بندی","categories"=>$categories,"orig_category"=>$category]);
    }

    public function notes(Request $request){
        $note=new Note();
        if($request->isMethod("PUT")){
            $note->body=$request->note;
            $note->user_id=Session::get("user_id");
            $note->save();
        }
        $notes=$note->select()->get();
        $this->view("admin/new_note.html",["title"=>"یادداشت ها","notes"=>$notes]);
    }
    
}
