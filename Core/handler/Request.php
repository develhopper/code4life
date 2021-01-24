<?php
namespace Core\handler;

class Request{

    public $url;

    public function __construct(){
        $this->url=trim($_SERVER['REQUEST_URI'],"/");
    }

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

    public function isEmpty(){
        return count($_REQUEST)>1?false:true;        
    }

    public function isMethod($method){
        return $method==$_SESSION['current_route']['method']?true:false;
    }

    public function upload($field){
        // $validate=[
        //     $_FILES[$field]['size']=>"size:1Mb",
        //     $_FILES[$field]['name']=>"format:jpg png",
        // ];
        // Validator::validate($validate);
        $ext='.'.pathinfo($_FILES[$field]['name'],PATHINFO_EXTENSION);
        $file_name=time().$ext;
        $dst= UPLOAD_DIR.$file_name;
        move_uploaded_file($_FILES[$field]['tmp_name'],$dst);
        return BASEURL."/storage/".$file_name;
    }

    public function dump(){
        var_dump($_REQUEST);
    }

    public function files(){
        return new File();
    }
}