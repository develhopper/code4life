<?php
namespace app\models;

use Core\Model;

class Auth extends Model{
    protected $table="user_logins";
    protected $related_tables=["users"=>"user_id"];
} 