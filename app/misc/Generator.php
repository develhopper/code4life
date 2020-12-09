<?php
namespace app\misc;

use app\misc\Node;
class Generator{

    public static function category_checkboxes(Node $node,array $checked=[]){
		$html="";
		if($node->self){
			$ischecked=(in_array($node->self->id,$checked))?"checked":"";
			$html.="<input name='categories[]' type='checkbox' value='{$node->self->id}' $ischecked/><label>{$node->self->cat_name}</label>";

			if($node->hasChild()){
				$html.="<ul class='subcat'>";
			}
		}
		foreach($node as $child){
			$html.="<li>";
			$html.=self::category_checkboxes($child,$checked);
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
            $href="/category/".$node->self->id;
            if($node->self->parent_id)
                $html.="|_";
            else
                $html.="#";
			$html.=" <a href='$href'>{$node->self->cat_name}</a> ";
			$html.="<span style='display:inline-block'>({$node->self->count})</span>";

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
