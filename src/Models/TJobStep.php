<?php

namespace Ajtarragona\TJobs\Models;



class TJobStep
{
    public $wait = 0;
    public $weight = 0;
    protected $action;
    
    
    public function __construct($action=null, $args=[]) {
        
        if(isset($args["wait"])) $this->wait = intval($args["wait"]);
        if(isset($args["weight"])) $this->weight = floatval($args["weight"]);

        $this->action = $action;

    } 

    private function callAction($my_function, $params=null) {
        return $my_function($params);
    }    


    public function run(){
        if($this->wait) sleep($this->wait);
        
        if (method_exists($this, 'execute')) {
            return $this->execute();
        }else{
            if($this->action) return $this->callAction($this->action,$this);
        }
        
			
      
    }
    
    

}
