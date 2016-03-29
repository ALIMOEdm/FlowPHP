<?php
namespace Classes\Routing;
use Classes\Routing\Route;

class SimpleRoute implements Route {
    private $routes;
    private $request_uri = '';
    private $current_controller;
    private $current_action;
    private $action_params;
    
    public function addRoute($route, $action){
        $routes[$route] = $action;
    }
    
    public function searchAction(){
        $need_route = null;
        $params = array();
        if(count($this->routes)){
            $withoute_params = explode('?', $this->request_uri);
            $route_request_arr = explode('/', $withoute_params[0]);
            foreach($this->routes as $j => $route){
                //надо сравнивать роуты...
                $route_arr = explode('/', $route['path']);
                $break_flag = false;
                if(count($route_arr) !== count($route_request_arr)){
                    continue;
                }
                $params = array();
                foreach($route_arr as $i => $part){
                    if(isset($route_request_arr[$i]) && strcmp($part, $route_request_arr[$i]) === 0){
                        continue;
                    }else if(isset($route_request_arr[$i]) && preg_match('/{.+}/', $part)){
                        preg_match('/{(.+)}/', $part, $matches);
                        $params[$matches[1]] = array(
                            'param_name' => $matches[1],
                            'param_value' => $route_request_arr[$i],
                        );
                        continue;
                    }else{
                        $break_flag = true;
                    }
                }
                
                if($break_flag){
                    continue;
                }
                $need_route = $j;
                break;
            }
        }
        if(is_null($need_route)){
            throw new \Exception('Unknow route '.$this->request_uri);
        }
        $act_arr = explode(':', $this->routes[$need_route]['action']);
        $this->current_controller = $act_arr[0];
        $this->current_action = $act_arr[1];
        $this->action_params = $params;
    }
    
    public function getController(){
        return $this->current_controller;
    }

    public function getAction(){
        return $this->current_action;
    }
    
    public function getParams(){
        return $this->action_params;
    }

    public function __construct() {
        if(!file_exists(ROOT_PATH.'/config/routers.php')){
            throw new \Exception('Route configuration file does not exists');
        }
        
        require ROOT_PATH.'/config/routers.php';
        $this->routes =  $routers;
        $this->createRequestUri();
    }
    
    public function createRequestUri(){
        $req_uri = $_SERVER['REQUEST_URI'];
        $serv_name = $_SERVER['SCRIPT_NAME'];
        
        $req_uri_arr = explode('/', $req_uri);
        $serv_name_arr = explode('/', $serv_name);
        $res = array();
        if(count($req_uri_arr)){
            foreach ($req_uri_arr as $i => $val){
                if(isset($serv_name_arr[$i]) && strcmp($val, $serv_name_arr[$i]) === 0){
                    continue;
                }else{
                    $res[] = $val;
                }
            }
        }
        $request = '/'.implode('/', $res);
        $this->request_uri = $request;
    }
    
    
    public function getPathByRouteName($route_name){
        $req_uri = $_SERVER['REQUEST_URI'];
        $req_uri_arr = explode('/', $req_uri);
        if(!$req_uri_arr[count($req_uri_arr) - 1]){
            array_pop($req_uri_arr);
        }
        $req_uri = implode('/', $req_uri_arr);
        if(!isset($this->routes[$route_name])){
            throw new Exception('Route '.$route_name.' is not found', 500);
        }
        return $req_uri.$this->routes[$route_name]['path'];
    }
}