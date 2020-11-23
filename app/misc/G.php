<?php
namespace app\misc;

class G{
    public static function array_diff2($a,$b){
        return ["added"=>array_diff($b,$a),"removed"=>array_diff($a,$b)];
    }
}