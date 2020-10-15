<?php

use Core\handler\Error;
use Core\handler\Session;

const BASEURL="http://code4life.com";
const DIR=__DIR__."/../../";
const PUBDIR=BASEDIR."/public";

const DB_DRIVER="mysql";
const DB_HOST="localhost";
const DB_NAME="code4life";
const DB_USER="root";
const DB_PASSWORD="mysql.passwd";

const Upload=BASEDIR."/public/upload/";
const DEBUG=true;
const VIEWS_DIR=BASEDIR."/views";
const CACHE_DIR=PUBDIR."/cache";
//register middlewares
// $middlewares=[
// 'admin'=>app\middleware\AdminAuth::class,
// 'auth'=>app\middleware\Auth::class
// ];

if (DEBUG) {
    ini_set('display_errors', 'on');
    ini_set('log_errors', 'on');
    ini_set('display_startup_errors', 'on');
    ini_set('error_reporting', E_ALL);
}

function asset($name){
    return "/public/".$name;
}

function _e($in){
    return htmlspecialchars($in);
}

function error($msg,$code){
    header($_SERVER["SERVER_PROTOCOL"]." $code");
    echo $msg;
    exit;
}

function csrf_field(){
    $token=bin2hex(random_bytes(10));
    echo "<input name='csrf' value='$token' type='hidden'>";
}

function redirect($route){
    if($route=="back"){
        header("Location:javascrtipt://history.go(-1)");
        exit;
    }
    header("Location: ".BASEURL."$route");
}

function is_route($route){
    return preg_match('/'.$route.'/',$_GET['url']);
}

function slug($var){
    return preg_replace("/\s/","_",$var);
}

function auth(){
        if(Session::has('username')&&Session::has("user_role"))
            return Session::get('username');
}
function session(){
    return new Session;
}

function die_dump(...$var){
    var_dump($var);
    die;
}

function mimplode($glue,$field,$array){
    return implode($glue,array_map(function($entry)use($field){
        return $entry[$field];
    },$array));
}

function del($path){
    unlink(DIR.$path);
}