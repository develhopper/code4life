<?php
namespace app\misc;

class Node implements \Iterator{
	private $nodes=[];
	private $parent;
	private $self;
	private $position=0;
	
	public function __construct($self=null){
			$this->self=$self;
	}

	public function rewind(){
			return reset($this->nodes);
	}

	public function current(){
			return current($this->nodes);
	}

	public function key(){
			return key($this->nodes);
	}

	public function next(){
			return next($this->nodes);
	}

	public function valid(){
			return key($this->nodes)!==null;
	}

	public function appendChild($key,$value){
			$this->nodes[$key]=$value;
	}

	public function appendChildren($node){
			foreach($node->nodes as $key=>$child){
					$this->nodes[$key]=$child;
			}
	}

	public function getChilds(){
			return $this->nodes;
	}

	public function hasChild($key=null){
			if($key==null)
					return !empty($this->nodes);
			return isset($this->nodes[$key]);
	}

	public function __get($key){
			return $this->$key;
	}

	public function __set($key,$value){
			$this->$key=$value;
	}

	public function size(){
			return count($this->nodes);
	}
}
