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
 * Response Class
 *
 * @category    Torpedo
 * @package     Response
 * @version     0.1.0
 */
class Response{
    private $code  = 200;
    private $text  = "OK";
    private $headers  = [];
    private $protocol = "HTTP/1.1";
    private $body;

    public function setResponse($code = 200, $text = "OK"){
        $this->code = $code; 
        $this->text = $text;
    }

    public function setBody($body){
        $this->body = $body;
        return $this;
    }
    
    public function addHeader($name, $value){
        $this->headers[$name] = (string) $value;
        return $this; 
    }
    
    public function setCode($code){
        if($code < 100 || $code > 599){
            throw new \LogicException(sprintf(
                "%s is unsuported HTTP status code ", $code    
            )); 
        }
        
        $this->code = $code;
        return $this;
    }

    public function getCode(){
        return $this->code; 
    }

    public function getText(){
        return $this->text; 
    }

    public function getHeaders(){
        return $this->headers; 
    }

    public function render(){
        if(!headers_sent()){
            header($this->fullHeaderStatus());
            $this->renderHeaders();
            return $this->body; 
        }

    }

    protected function renderHeaders(){
         foreach ($this->headers as $key => $headerValue) {
            header($key . ": " . $headerValue);
         }
    }

    public function redirect($url, $code = 301){
        $this->addHeader("Location", $url);
        $this->setCode($code);
    }

    public function disableBrowserCache() {
        $this->headers[] = "Cache-Control: no-cache, no-store, must-revalidate"; 
        $this->headers[] = "Pragma: no-cache"; 
        $this->headers[] = "Expires: Thu, 26 Feb 1970 20:00:00 GMT"; 
        return $this;
    }

    private function fullHeaderStatus(){
        return $this->protocol ." ". $this->code ." ". $this->text; 
    }
} 