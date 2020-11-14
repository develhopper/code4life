<?php
namespace app\models;

use Core\Model;

class Post extends Model{
    protected $table="posts";

    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public function addTags($tags){
        if($this->id){
            $model=new Model();
            $model->query="insert ignore into posts_tags (post_id,tag_id) values ";
            $values=[];
            foreach($tags as $tag){
                array_push($values,"($this->id,$tag[id])");
            }
            $model->query.=implode(",",$values);
            $model->execute();
        }
    }

    public function addCategories($categories){
        if($this->id){
            $model=new Model();
            $model->query="insert ignore into posts_categories (post_id,category_id) values ";
            $values=[];
            foreach($categories as $category){
                array_push($values,"($this->id,$category)");
            }
            $model->query.=implode(",",$values);
            
            $model->execute();
        }
    }
}