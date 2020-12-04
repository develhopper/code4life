<?php
namespace app\models;

use Core\Model;
use Jalalib\JDF;

class Post extends Model{
    protected $table="posts";

    public function jdate(){
      $date=new \DateTime($this->created_at);
      $y=$date->format("Y");
      $m=$date->format("m");
      $d=$date->format("d");
      $time=$date->format("H:i:s");
      return implode("/",JDF::gregorian_to_jalali($y,$m,$d))." ".$time;
    }

    public function comments(){
        return $this->hasMany(Comment::class,false)->and("accepted","1")->get();
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

    public function removeTags($tags){
        if($this->id){
            $model=new Model();
            foreach($tags as $key=>$tag){
                $tags[$key]="'$tag'";
            }
            $model->query="delete posts_tags from posts_tags join tags on tag_id=tags.id where post_id=$this->id and tags.name in ";
            $model->query.="(".implode(",",$tags).") ";
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

    public function removeCategories($categoreis){
        if($this->id){
            $model=new Model();
            $model->query="delete posts_categories from posts_categories where post_id=$this->id and category_id in ";
            $model->query.="(".implode(",",$categoreis).")";
            $model->execute();
        }
    }

    public function withStat(){
        $this->left_join(\app\models\Stat::class,["on"=>"posts.uri=statistics.uri"])
        ->left_join(\app\models\Comment::class);
        $this->query.=" group by posts.id";
        return $this;
    }

    public function Tags($cols=null){
        if($cols==null)
            $cols="tags.id,tags.name";
        $this->query="select $cols from tags left join posts_tags on tags.id=tag_id where post_id=$this->id";
        return $this;
    }

    public function Categories(){
        $this->query="select category_id from posts_categories join categories on categories.id=category_id where post_id=$this->id";
        return $this;
    }
}
