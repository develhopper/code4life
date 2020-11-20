<?php
namespace Core\handler;

class File{
    
    public function __get($key){
        if(isset($_FILES[$key]))
            return $_FILES[$key];
    }

    public function has($key){
        return isset($_FILES[$key]);
    }

    public function getName($key){
        if(isset($_FILES[$key]))
            return $_FILES[$key]['name'];
    }

    public function getTmp($key){
        if(isset($_FILES[$key]))
            return $_FILES[$key]['tmp_name'];
    }
}