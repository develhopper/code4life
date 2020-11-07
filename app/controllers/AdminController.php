<?php
namespace app\controllers;

use Core\BaseController;
use Core\handler\Request;
use Core\handler\Session;
use app\models\User;
use app\models\Note;
use app\models\Stat;
use app\models\Comment;

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
        $this->view("admin/new_post.html",["title"=>"مطلب جدید"]);
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

    public function category_settings(){
        $this->view("admin/category_settings.html",["title"=>"تنظیمات دسته بندی ها"]);
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