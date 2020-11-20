<?php
namespace app\controllers;

use Core\BaseController;
use Core\handler\Request;
use app\misc\Storage;
class FileController extends BaseController{
    
    public function listing(Request $request){
        $path=realpath(($request->path!="null")?$request->path:UPLOAD_DIR);
        $storage=new Storage();
        $this->json($storage->listing($path));
    }

    public function get_url(Request $request){
        if($request->path){
            $result=[
                "url"=>str_replace(BASEDIR,BASEURL,$request->path)
            ];
            $this->json($result);
        }
    }

    public function make_dir(Request $request){
        if($request->path && $request->name){
            $path=$request->path."/".$request->name;
            if(file_exists($path))
                $this->json(["message"=>"faild to craete directory , directory already exists"]);
            mkdir($path);
        }
    }

    public function make_file(Request $request){
        if($request->path && $request->name){
            $path=$request->path."/".$request->name;
            if(!file_exists($path)){
                file_put_contents($path,"");
            }else
                $this->json(["message"=>"file already exists"]);
        }
    }

    public function cp(Request $request){
        if($request->src && $request->dst){
            $storage=new Storage();
            if($storage->cp($request->src,$request->dst)){
                $this->json(["message"=>"files copied into $request->dst"]);
            }
        }
    }

    public function mv(Request $request){
        if($request->src && $request->dst){
            $storage=new Storage();
            if($storage->mv($request->src,$request->dst))
                $this->json(["message"=>"successfuly renamed"]);
        }
    }

    public function remove(Request $request){
        if($request->path){
            $storage=new Storage();
            if($storage->rm($request->path))
                $this->json(["message"=>"removed"]);
        }
    }

    public function get_content(Request $request){
        if($request->path && file_exists($request->path)){
            if(Storage::isText($request->path))
                echo file_get_contents($request->path);
            else
                $this->json(["message"=>"this file is not readable"],400);
        }
    }

    public function put_content(Request $request){
        if($request->path && Storage::isText($request->path)){
            file_put_contents($request->path,$request->content);
            $this->json(["message"=>"$request->path saved"]);
        }
    }

    public function upload(Request $request){
        $files=$request->files();
        if($request->path && $files->has('file')){
            $path=$request->path."/".$files->file['name'];
            if(move_uploaded_file($files->file['tmp_name'],$path))
                $this->json(["message"=>"uploaded"]);
        }else
            $this->json(["message"=>"missing path or file"]);
    }

}