<?php
/**
 * Torpedo  - Decoupled PHP micro-libraries for building fast applications. 
 *
 * @author    Simon Daniel <eritr3a@gmail.com>
 * @link      https://github.com/fastpress/torpedo
 * @copyright Copyright (c) 2015 Simon Daniel
 * @license   http://www.opensource.org/licenses/mit-license.html  MIT License
 */
namespace Torpedo;

/**
 * Application
 * A wrapper class for the fastpress\engine libraries
 *
 * @category    Torpedo
 * @package     Container
 * @version     0.1.0
 */
Class Application extends Container{
    private $isBooted = FALSE; 
  
    public function escape($text){
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }
    
    public function server($var, $filter = NULL){
        return $this['request']->server($var, $filter);
    }

    public function isGet(){
        return $this['request']->isGet();  
    }
    
    public function isPost(){
        return $this['request']->isPost();
    }
    
    public function isPut(){
        return $this['request']->isPut(); 
    }
    
    public function isDelete(){
        return $this['request']->isDelete();
    }

    public function app($key, $value = null){
        if(null === $value){
            return $this->offsetGet($key); 
        }

        $this->offsetSet($key, $value); 
    }

    public function setResponse($header = 200, $statusText = null){
        $this['response']->setResponse($header, $statusText);
    }

    public function getVar($value, $filter = null){
        return $this['request']->get($value, $filter); 
    }

    public function postVar($value, $filter = null){
        return $this['request']->post($value, $filter); 
    }

    public function any($path, $resource){
        return $this['route']->any($path, $resource);
    }

    public function get($path, $resource){
        return $this['route']->get($path, $resource);
    }

    public function post($path, $resource){
        return $this['route']->post($path, $resource);
    }

    public function put($path, $resource){
        return $this['route']->put($path, $resource);
    }

    public function delete($path, $resource){
        return $this['route']->delete($path, $resource);
    }

    private function controllerDipatcher($resource){
        $controller = $resource['controller']; 
        $method     = $resource['method']; 
        $args       = $resource['args']; 

        $fullyQualifiedServiceName = "App\Controller\\".$resource['controller']; 
        
        if(class_exists($fullyQualifiedServiceName)){
            $controller = new $fullyQualifiedServiceName; 
            if(!method_exists($controller, $method)){
                throw new \Exception("method $method does not exists in $fullyQualifiedServiceName"); 
            }else{
                (new $controller)->$method($args, $this);
            }
        }else{
            throw new \Exception("controller $controller does not exist");
            
        }
    }

    public function view($block, array $variables = []){
        $this['view']->view($block, $variables);
    }

    public function layout($layout, array $variables = []){
        $this['view']->layout($layout, $variables);
    }

    public function run(){
        $resource = $this['route']->match($_SERVER, $_POST);  
        if(is_array($resource)){
            $this->controllerDipatcher($resource);
        }
    }
}

