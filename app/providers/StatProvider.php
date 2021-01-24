<?php
namespace app\providers;

use Core\handler\Session;
use Core\handler\Reqeust;
use app\models\Stat;

class StatProvider{
    
    public static function boot($request){
        if(isset(Session::get("current_route")["no_stat"]))
            return;
        $uri=$request->url;
        if(empty($uri))
            $uri="/";
        $stat=new Stat();
        $stat->uri=$uri;
        $stat->views="+1";
        $stat->upsert([":views"=>"views+1"])->errorInfo();
    }
}