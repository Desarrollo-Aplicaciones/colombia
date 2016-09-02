<?php
class Sync_tracker extends FrontController {
    
               public  $module_cd;
               public  $key1;
               public  $value1;
               private $key2;
               private $value2;
               private $modifiedtime; 
               private $id;
    
    function __construct() {
        $this->id=0;
    }
    
    public function getModule_cd() {
        return $this->module_cd;
    }

    public function getKey1() {
        return $this->key1;
    }

    public function getValue1() {
        return $this->value1;
    }

    public function getKey2() {
        return $this->key2;
    }

    public function getValue2() {
        return $this->value2;
    }

    public function getModifiedtime() {
        return $this->modifiedtime;
    }

    public function setModule_cd($module_cd) {
        $this->module_cd = $module_cd;
    }

    public function setKey1($key1) {
        $this->key1 = $key1;
    }

    public function setValue1($value1) {
        $this->value1 = $value1;
    }

    public function setKey2($key2) {
        $this->key2 = $key2;
    }

    public function setValue2($value2) {
        $this->value2 = $value2;
    }

    public function setModifiedtime($modifiedtime) {
        $this->modifiedtime = $modifiedtime;
    }
    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    
    public function add_sync($module_cd,$key1,$value1,$key2,$value2,$modifiedtime)
    {
        $query="INSERT INTO ps_sync_tracker (tid,user_id,sync_module_cd,key1,value1,key2,value2,modifiedtime) 
                VALUES (". $this->id.",'Json_sugar','".$module_cd."','".$key1."','".$value1."','".$key2."','".$value2."','".$modifiedtime."');";

        if(Db::getInstance()->Execute($query)){
            return true;
        }
        
        return false;
    }
    public function remove(){
        
    }
    public function update(){
        
    }
    
        public function logtxt($text = "") {
        $fp = fopen("/tmp/archivo_log.txt", "a+");
        fwrite($fp, $text . "\r\n");
        fclose($fp);
    }

}