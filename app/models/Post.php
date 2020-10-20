<?php
namespace app\models;

use Core\Model;

class Post extends Model{
    protected $table="posts";

    public function comments(){
        return $this->hasMany(Comment::class);
    }
}