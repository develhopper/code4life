<?php
namespace app\models;

use Core\Model;
use app\misc\Node;
class Category extends Model{
    protected $table="categories";
    protected $related_tables=["categories"=>"parent_id"];

    public function withParent(){
        if(isset($this->alias)){
            $options=["aliases"=>["c1","c2"],"reverse_cond"=>true];
            return $this->left_join(\app\models\Category::class,$options);
        }
    }

    public function toTree(array $categories){
        $root=new Node();
        $root->appendChildren($this->getNodes($categories));
        return $root;
    }

    private function getNodes(array $categories,$parent_id=null){
        $root=new Node();
		foreach($categories as $i=>$category){
				if($category->parent_id==$parent_id){
				$node=new Node($category);
				$node->appendChildren($this->getNodes(array_slice($categories,$i+1),$category->id));
				$root->appendChild($category->id,$node);
				}
		}
		return $root; 
    }
}
