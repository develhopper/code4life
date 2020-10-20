<?php
namespace app\models;

use Core\Model;

class Comment extends Model{
    protected $table="comments";
    protected $related_tables=["posts"=>"post_id"];
}