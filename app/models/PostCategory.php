<?php
namespace app\models;

use Core\Model; 
class PostCategory extends Model{
    protected $table="posts_categories";
    protected $related_tables=["categories"=>"category_id"];
}