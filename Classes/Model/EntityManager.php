<?php
namespace Classes\Model;
class EntityManager {
    protected $models;
    protected $namespace = 'Src\\Model\\';
    private static $instance = null;
    public function getRepository($model){
//        $path = ROOT_PATH.'/Src/Model/'.$model.'.php';
        $class = $this->namespace.$model;
        if(!class_exists($class)){
            throw new \Exception('Model '.$model.' is not found');
        }
        
        if(!isset($this->models['model'])){
            $this->models['model'] = new $class();
        }
        return $this->models['model'];
    }
    
    private function __construct() {}
    private function __clone() {}
    
    static public function getInstance(){
        if(is_null(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }
}
