<?php
namespace app\misc;

use app\misc\Node;
class Generator{
    
    public static function category_checkboxes(Node $node){
        $html="";
		if($node->self){
			$html.="<input type='checkbox' value='{$node->self->id}'/><label>{$node->self->cat_name}</label>";
		if($node->hasChild()){
			$html.="<ul class='subcat'>";
		}
		}
		foreach($node as $child){
			$html.="<li>";
			$html.=self::category_checkboxes($child);
			$html.="</li>";
		}
		if($node->hasChild() && $node->self){
			$html.="</ul>";
		}
		return $html;
    }

    public static function category_nav(Node $node){
        $html="";
		if($node->self){
            $href="/category";
            if($node->self->parent_id)
                $href.="/".$node->self->parent_slug;
            $href.="/".$node->self->cat_slug;
            if($node->self->parent_id)
                $html.="|_";
            else
                $html.="#";
            $html.=" <a href='$href'>{$node->self->cat_name}</a>";
            
		if($node->hasChild()){
			$html.="<ul class='subcat'>";
        }
        
	    }
        
        foreach($node as $child){
			$html.="<li>";
			$html.=self::category_nav($child);
			$html.="</li>";
        }
        
		if($node->hasChild() && $node->self){
			$html.="</ul>";
        }
        
		return $html;
    }
}