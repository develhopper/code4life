<?php
namespace app\misc;

class Storage{

    public function mkdir($dir){
        if(file_exists(BASEDIR.getenv('UPLOAD_DIR').$dir))
            return;
        else{
            mkdir(BASEDIR.getenv('UPLOAD_DIR').$dir,0777,true);
        }
    }

    public function upload($path,$file){
        $this->mkdir($path);
        $ext=pathinfo($file["name"],PATHINFO_EXTENSION);
        $file_name=$path."/".uniqid().".$ext";
        $dest=BASEDIR.getenv('UPLOAD_DIR').$file_name;
        if(move_uploaded_file($file['tmp_name'],$dest))
            return $file_name;
    }

    public function listing($path){
        $types=[
            "code"=>["html","php","css","js"],
            "picture"=>["png","jpg","jpeg","ico"],
            "video"=>["mp4"],
            "music"=>["mp3"],
            "text"=>["txt"],
            "archive"=>["zip"]];
            $list=scandir($path);
        $output=[
            "current_directory"=>$path,
            "dir"=>[],
            "file"=>[]
        ];
        foreach($list as $item){
            $type="file";
            $size="";
            $file_type="";
            if(is_dir($path."/".$item)){
                $type="dir";
            }else{
                $size=$this->FileSizeConvert(filesize($path."/".$item));
                $ext=pathinfo($path."/".$item,PATHINFO_EXTENSION);
                foreach($types as $key=>$ft){
                        if(in_array($ext,$ft)){
                            $file_type=$key;
                            break;
                        }
                }
                if(empty($file_type))
                    $file_type="doc";
            }
            array_push($output[$type],["name"=>$item,"size"=>$size,"path"=>"$path/$item","file_type"=>$file_type]);
        }
        return $output;
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

    public static function max_size($path,$bytes){
        return filesize($path)<=$bytes;
    }

    public function FileSizeConvert($bytes){
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