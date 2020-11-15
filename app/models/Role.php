<?php
namespace app\models;

use Core\Model;
class Role extends Model{
    protected $table="roles";
    protected $related_tables=["users"=>"role"];
}