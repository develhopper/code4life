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

    public function cp($src,$dst){
        $dst.="/".pathinfo($src,PATHINFO_BASENAME);
        if(is_dir($src)){
            return $this->recurse_copy($src,$dst);
        }else
            return copy($src,$dst);
    }

    function mv($src,$dst){
        $dst.="/".pathinfo($src,PATHINFO_BASENAME);
        return rename($src,$dst);
    }

    public function rm($path){
        if(file_exists($path)){
            if(is_dir($path))
                return $this->rmrf($path);
            else
                return unlink($path);
        }
    }

    public function rmrf($path){
        system("rm -rf -- ".escapeshellarg($path),$retval);
        return $retval==0;
    }

    public function recurse_copy($src,$dst) {
        $dir = opendir($src);
        @mkdir($dst);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if ( is_dir($src . '/' . $file) ) {
                    $this->recurse_copy($src . '/' . $file,$dst . '/' . $file);
                }
                else {
                    copy($src . '/' . $file,$dst . '/' . $file);
                }
            }
        }
        closedir($dir);
        return true;
    }

    public static function isText($path){
        $finfo=finfo_open(FILEINFO_MIME);
        if(!$finfo)
            return false;
        return substr(finfo_file($finfo,$path),0,4)=="text" || filesize($path)==0;
    }

    public static function FileSizeConvert($bytes){
    $result="";
    $bytes = floatval($bytes);
        $arBytes = array(
            0 => array(
                "UNIT" => "TB",
                "VALUE" => pow(1024, 4)
            ),
            1 => array(
                "UNIT" => "GB",
                "VALUE" => pow(1024, 3)
            ),
            2 => array(
                "UNIT" => "MB",
                "VALUE" => pow(1024, 2)
            ),
            3 => array(
                "UNIT" => "KB",
                "VALUE" => 1024
            ),
            4 => array(
                "UNIT" => "B",
                "VALUE" => 1
            ),
        );

    foreach($arBytes as $arItem)
    {
        if($bytes >= $arItem["VALUE"])
        {
            $result = $bytes / $arItem["VALUE"];
            $result = str_replace(".", "," , strval(round($result, 2)))." ".$arItem["UNIT"];
            break;
        }
    }
    return $result;
}
}