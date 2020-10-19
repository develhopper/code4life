<?php
namespace Core\handler;

class Request{
   
    public function __get($key){
        if(isset($_REQUEST[$key]))
            return $_REQUEST[$key];
    }

    public function __set($key,$value){
        $_REQUEST[$key]=$value;
    }

    public function all(){
        return $_REQUEST;
    }

    public function has($key){
        return isset($_REQUEST[$key]);
    }

    public function empty(){
        return count($_REQUEST)>1?true:false;
    }

    public function isMethod($method){
        return $method==$_SERVER['REQUEST_METHOD']?true:false;
    }

    public function upload($field){
        // $validate=[
        //     $_FILES[$field]['size']=>"size:1Mb",
        //     $_FILES[$field]['name']=>"format:jpg png",
        // ];
        // Validator::validate($validate);
        $ext='.'.pathinfo($_FILES[$field]['name'],PATHINFO_EXTENSION);
        $file_name=time().$ext;
        $dst= Upload.$file_name;
        move_uploaded_file($_FILES[$field]['tmp_name'],$dst);
        return BASEURL."/public/upload/".$file_name;
    }

    public function dump(){
        var_dump($_REQUEST);
    }
}