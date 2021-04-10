<?php
namespace app\misc;

use app\models\Category;
class G{
    
    public static function array_diff2($a=[],$b=[]){
        return ["added"=>array_diff($b,$a),"removed"=>array_diff($a,$b)];
    }

    public static function get_category_tree(){
        $model=new Category();
        $model->alias="c1";
        $select="c1.id,c1.name as cat_name,
        c1.slug as cat_slug,c2.slug as parent_slug,
        c1.parent_id as parent_id,c2.name parent_name,
        c2.parent_id as parent_parent,count(posts_categories.id) as count ";
        $categories=$model->select($select)->withParent()->withCount()->sort("c1.id")->get();
        return $model->toTree($categories);
    }
}