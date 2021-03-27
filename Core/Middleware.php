<?php
namespace Core;

use Core\handler\Request;

abstract class  Middleware{
    public static function next(Request $reqeust){}
} 