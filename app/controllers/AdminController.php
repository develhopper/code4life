<?php
namespace app\controllers;

use Core\BaseController;
use Core\handler\Request;
use Core\handler\Session;
use Core\handler\Validator;
use app\models\User;
use app\models\Note;
use app\models\Stat;
use app\models\Comment;
use app\models\Category;
use app\misc\Generator;

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

    public function new_post(){
        $model=new Category();
        $model->alias="c1";
        $select="c1.id,c1.name as cat_name,c1.parent_id as parent_id,c2.name parent_name,c2.parent_id as parent_parent";
        $categories=$model->select($select)->withParent()->get();
        $catList=$model->toTree($categories);
        
        $this->view("admin/new_post.html",["title"=>"مطلب جدید","categories"=>Generator::category_checkboxes($catList)]);
    }

    public function recent_posts(){
        $this->view("admin/recent_posts.html",["title"=>"مطالب اخیر"]);
    }

    public function recent_comments(){
        $this->view("admin/recent_comments.html",["title"=>"نظرات اخیر"]);
    }

    public function user_management(){
        $this->view("admin/user_management.html",["title"=>"مدیریت کاربران"]);
    }

    public function page_settings(){
        $this->view("admin/page_settings.html",["title"=>"تنظیمات صفحات"]);
    }

    public function category_settings(Request $request){
        if($request->isMethod("PUT")){
            $category=new Category();
            $category->name=$request->title;
            $category->slug=slug($request->title);
            if($request->parent)
                $category->parent_id=$request->parent[0];
            $category->save();
        }
        $categories=new Category();
        $categories->alias="c1";
        $select="c1.id,c1.name as cat_name,c1.parent_id as cat_parent,c2.name parent_name,c2.parent_id as parent_parent";
        $categories=$categories->select($select)->withParent()->get();

        $this->view("admin/category_settings.html",["title"=>"تنظیمات دسته بندی ها","categories"=>$categories]);
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
