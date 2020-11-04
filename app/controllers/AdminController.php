<?php
namespace app\controllers;

use Core\BaseController;
class AdminController extends BaseController{

    public function admin(){
        $this->view("admin/index.html",["title"=>"مدیریت"]);
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
    
}