<?php
namespace Classes\Core;
class Request {
    private $request = array();
    private $file = array();
    public function __construct() {
        
    }
    
    public function createFromGlobals(){
        if($_SERVER['REQUEST_METHOD'] === 'GET' || $_SERVER['REQUEST_METHOD'] === 'POST'){
            $this->request = $_REQUEST;
        }
        else{
            //in progress
        }
        $this->file = $_FILES;
    }
    
    public function getV($key, $default_value = ''){
        if(isset($this->request[$key])){
            return $this->request[$key];
        }
        return $default_value;
    }
    
    public function getFile(){
        return $this->file;
    }
}
