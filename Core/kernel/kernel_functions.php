<?php
use Core\handler\Error;
use Core\handler\Session;

function asset($name){
    return BASEURL."/public/".$name;
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

function csrf_token(){
    echo bin2hex(random_bytes(10));
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
    return strtolower(preg_replace("/\s/","_",$var));
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