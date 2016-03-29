<?php
namespace Classes\Core;
use Classes\Routing\Route;

class AppCore {
    private $routing = null;
    private $user_controllers_namespace = 'Src\\Controller';
    public function __construct(Route $routing) {
        $this->routing = $routing;
    }
    
    public function start(){
        if(is_null($this->routing)){
            
        }
        try{
            $this->routing->searchAction();
        }  catch (\Exception $e){
            throw new \Exception($e->getMessage());
        }
        
        $controller = $this->routing->getController();
        
        if(file_exists(ROOT_PATH.'/Src/Controller/'.$controller.'Controller.php')){
            require_once ROOT_PATH.'/Src/Controller/'.$controller.'Controller.php';
            $class_name = $this->user_controllers_namespace.'\\'.$controller.'Controller';
            try{
                $reflector = new \ReflectionClass($class_name);
            }catch(\Exception $e){
                echo $e->getMessage();
            }
            $route_method = $this->routing->getAction();
            $route_parameters = $this->routing->getParams();
            $methods = $reflector->getMethods();
            if(count($methods)){
                $fl_exists = false;
                foreach ($methods as $i => $method){
                    if(strcmp($route_method, $method->getName()) === 0){
                        $fl_exists = true;
                        break;
                    }
                }
                if(!$fl_exists){
                    throw new \Exception('Method '.$route_method.' does not exists in controller '.$class_name);
                }
            }
            $method_object = $reflector->getMethod($route_method);
            
            $method_parameters_arr = $method_object->getParameters();
            $parameter_names = array();
            //check if number of params is different
            
            if(!count($method_parameters_arr)){
               if(count($route_parameters)){
                   throw new \Exception('Number of parameters different in action '.$route_method);
               }
            }
            
            //create array of parameters name
            foreach ($method_parameters_arr as $param){
                $parameter_names[] = $param->getName();
            }
            $number_action_params = count($parameter_names);
            $number_action_params_real = $number_action_params;

            if(count($route_parameters) !== $number_action_params_real){
                throw new \Exception('Number of parameters different in action '.$route_method);
            }

            foreach ($route_parameters as $p){
                if(!in_array($p['param_name'], $parameter_names)){
                    throw new \Exception('Parameter '.$p['param_name'].' is not defined in action '.$route_method);
                }
            }

            $tt = array();
            foreach($parameter_names as $p_n){
                $tt[] = $route_parameters[$p_n]['param_value'];
            }
            //invoke need action
            $result = $method_object->invokeArgs(new $class_name(), $tt);
            
            echo $result;
            
        }else{
            throw new \Exception('Unknow controller: '.$controller);
        }
    }
}
