<?php

namespace Ajtarragona\TBatches\Jobs;

use Ajtarragona\TBatches\Traits\BatchableJob;

class CallableJob 
{

    use BatchableJob;
    
    public $action;
    public $jobId;
    public $weight;
    public $wait;
    public $stop_on_fail;

    /**
     * Class constructor.
     */
    public function __construct($action = null)
    {
        $this->action = $action;
    }

    private function callAction($my_function, $job) {
        return $my_function($job);
    }   

    public function process(){
        if($this->action) return $this->callAction($this->action, $this);
        else return false;
    }


}