<?php
namespace Classes\Core;
use Classes\Routing\SimpleRoute;

class Templates {
    private $template_path = '/Src/Resources/views/';
    private $public_path = '/Src/Resources/public/';
    private $full_path;
    private $simple_rout;
    public function __construct() {
        $this->full_path = BASE_PATH.$this->template_path;
        $this->full_public_path = BASE_PATH.$this->public_path;
        $this->simple_rout = new SimpleRoute();
    }
    public function renderTemplate($view, $blocks, $params_info){
        $file = BASE_PATH.$this->template_path.$view.".php";
        if(!file_exists($file)){
            throw new \Exception("View ".$view." is not found");
        }
        foreach($params_info as $key => $value) {
            $$key = $value;
        }
        ob_start(); 
        $template = $this;
        include $file;
        return ob_get_clean();
    }
    
    public function include_tpl($tpl){
        $template = $this;
        require_once $this->full_path.$tpl.".php";
    }
    //for include js and css...and images
    public function asset($file){
        $arr = explode('/', $_SERVER['REQUEST_URI']);
        //remove app.php
        while(count($arr) && !array_pop($arr));
        $arr[] = 'public';
        $arr[] = $file;
        $path = implode('/', $arr);
        return $path;
    }
    
    public function path($route_name){
        return $this->simple_rout->getPathByRouteName($route_name);
    }
}
