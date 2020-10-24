<?php

if(basename(__FILE__)=="setup.example.php")
		die("You have to rename this script to run it");
		
include __DIR__.'/bootstrap.php';
include __DIR__.'/vendor/autoload.php';

use QB\QBuilder as Model;
use app\models\User;
use app\models\Post;
use app\models\Comment;
use Core\Kernel;

$kernel=new Kernel();

$model=new Model();
$model->table="roles";

// check for user roles
$roles=$model->select()->get();
if(!$roles){
    echo "no user roles \n";
    // setting default roles change as you desire
    $model->id=1;
    $model->name="< role number 1 >";
    $model->save();
    $model->id=2;
    $model->name="< role number 2 >";
    $model->save();
}

$model=new User();

// check for users
$users=$model->select()->get();
if(!$users){
    echo "no user \n";
    // setting default user account change as you desire
    $model->username="< your user name >";
    $model->password=password_hash("< your password >",PASSWORD_DEFAULT);
    $model->email="<your email address>";
    $model->verified=1;
    $model->role=2; // role 2 is author and role 1 is normal user
    $user_id=$model->save();
    if(!$user_id)
        die("failed to insert default user :( check your configuration");
}

$model=new Post();

// check for posts
$posts=$model->select()->get();
if(!$posts){
    echo "no post \n";
    // insert default post
    $model->title="Welcome to your website";
    $model->body="Welcome to your website you can remotve this post in your panel if you are a admin :)";
    $model->slug=slug($model->title);
    $model->author_id=$user_id;
    $post_id=$model->save();
    if(!$post_id)
        die("failed to insert default post :( check your configuration");
}

$model=new Comment();

// check for comments
$comments=$model->select()->get();
if(!$comments){
    echo "no comments \n";
    // insert default comment
    $model->body="this is a test comment 
    you can remove this in your panel or by deleting the post this comment also going to be deleted :)";
    $model->post_id=$post_id;
    $model->user_id=$user_id;
    if(!$model->save())
        die("failed to insert default comment :( check your configuration");
}

echo "everything is ready to go, have fun :)\n";
