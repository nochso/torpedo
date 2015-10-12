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
 * Container Class
 *
 * @category    Torpedo
 * @package     Container
 * @version     0.1.0
 */
use Torpedo\Request;
use Torpedo\Response;
use Torpedo\Router;
use Torpedo\Session;
use Torpedo\Template;

class Container implements \ArrayAccess{
    protected $container = array();
    
    public function __construct($conf){
		$app = $this; 
        
        if(file_exists($conf)){
            require $conf;
        }
        
        $this['route'] = $this->store(function(){
            return new Router;
        });

        $this['view'] = $this->store(function($conf){
            return new Template($conf['path'], $this);
        });

        $this['request'] = $this->store(function(){
            return new Request(
                $_GET, $_POST, $_FILES, $_SERVER, $_COOKIE
            );
        });

        $this['response'] = $this->store(function(){
            return new Response();
        });

        $this['session'] = $this->store(function($conf){
            return new Session($conf['app.session']);
        });
    }


    public function route(){
        return new Router; 
    }

    public function view(){
        $conf = $this->container; 
        return new Template($conf);
    }

    public function response(){
        return new Response; 
    }

    public function request(){
        return new Request($_GET, $_POST, $_FILES, $_SERVER, $_COOKIE); 
    }

    public function session(){
        $conf = $this->container['app.session'];
        return new Session($conf); 
    }

    public function offsetUnset($offset){}
    
    public function offsetGet($offset){
        if(array_key_exists($offset, $this->container) && 
            is_callable($this->container[$offset])){
            return $this->container[$offset]();
        }

        return $this->container[$offset];
    }
    
    public function offsetExists($offset){
        return array_key_exists($id, $this->container);
    }

    public function offsetSet($offset, $value){
        if(strpos($offset, ':')){
            list($index, $subset) = explode(':', $offset, 2); 
            $this->container[$index][$subset] = $value; 
        }
        
        $this->container[$offset] = $value;
    }

    public  function store(Callable $callable){
        return function () use ($callable){
            static $object; 

            if(null == $object){
                $object = $callable($this->container); 
            }

            return $object;
        }; 
    }

}


