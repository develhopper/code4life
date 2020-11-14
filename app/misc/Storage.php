<?php
namespace app\misc;

class Storage{

    public function mkdir($dir){
        if(file_exists(UPLOAD_DIR.$dir))
            return;
        else{
            mkdir(UPLOAD_DIR.$dir,0777,true);
        }
    }

    public function upload($path,$file){
        $this->mkdir($path);
        $ext=pathinfo($file["name"],PATHINFO_EXTENSION);
        $file_name=$path."/".uniqid().".$ext";
        $dest=UPLOAD_DIR.$file_name;
        if(move_uploaded_file($file['tmp_name'],$dest))
            return $file_name;
    }
}