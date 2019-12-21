<?php
namespace App\core\handler;

class Validator{

    public static function validate($rules){
        foreach($rules as $key=>$rule){
            if(!self::valid($key,$rule))
                return false;
        }
        return true;
    }

    private static function valid($var,$rule){
        if(empty(trim($var)))
            return false;
        $rule=explode(",",$rule);
        foreach($rule as $r){
            $r=explode(":",$r);
            $arg[0]=$var;
            $arg[1]=(count($r)>1)?$r[1]:[];
            if(!call_user_func_array(["App\core\handler\Validator",$r[0]],$arg))
                return false;
        }
        return true;
    }

    private static function int($var){
        return is_int($var);
    }

    private function string($var){
        return is_string($var);
    }

    private static function max($var,$m){
        return $var<=$m;
    }

    private static function min($var,$m){
        return $var>=$m;
    }

    private static function count($var,$c){
        return strlen($var)<=$c;
    }

    private static function format($var,$formats){
        return in_array($var,$formats);
    }

    private function size($var,$s){

    }
}