<?php
namespace Classes\Core;
Use Classes\Core\Request;
use Classes\Core\Templates;
use Classes\Model\EntityManager;

class Controller {
    protected $request;
    protected $templater;
    protected $entity_manager;
    public function __construct(){
        
        $this->request = new Request();
        $this->request->createFromGlobals();
        $this->templater = new Templates();
        $this->entity_manager = EntityManager::getInstance();
    }
    
    public function getRequest(){
        return $this->request;
    }
    
    public function render($template, array $blocks = array(), array $params = array()){
        return $this->templater->renderTemplate($template, $blocks, $params);
    }
    
    public function getEntityManager(){
        return $this->entity_manager;
    }
}
