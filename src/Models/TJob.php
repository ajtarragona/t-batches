<?php

namespace Ajtarragona\TBatches\Models;



abstract class TJob
{
    public $wait = 0;
    public $weight = 0;
    // protected $action;
    
    
    public function __construct($args=[]) {
        
        if(isset($args["wait"])) $this->wait = intval($args["wait"]);
        if(isset($args["weight"])) $this->weight = floatval($args["weight"]);

        // $this->action = $action;

    } 

    // private function callAction($my_function, $params=null) {
    //     return $my_function($params);
    // }    


    public function run(){
        if($this->wait) sleep($this->wait);
        
        // if (method_exists($this, 'handle')) {
        return $this->handle();
        
        // }else{
        //     if($this->action) return $this->callAction($this->action,$this);
        // }
        
			
      
    }

    abstract public function handle();
    
    

}
