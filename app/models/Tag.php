<?php
namespace app\models;

use Core\Model;
class Tag extends Model{
    protected $table="tags";

    public function save($fileds=[]){
        $this->query="INSERT IGNORE INTO $this->table(name) values ";
        $inserts=[];
        foreach($fileds as $field){
            array_push($inserts,"('$field')");
        }
        $this->query.=implode(",",$inserts);
        if($this->execute())
            return $this->db->lastInsertId();
        else
            return -1;
    }

    public function in($field,array $range){
        $items=[];
        foreach($range as $item)
            array_push($items,"'$item'");
        
        $this->query.=" where $field in (".implode(",",$items).") ";
        return $this;
    }

    public function asArray(){
        return $this->execute()->fetchAll(\PDO::FETCH_ASSOC);
    }
}