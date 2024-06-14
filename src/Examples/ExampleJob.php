<?php

namespace Ajtarragona\TJobs\Examples;

use Ajtarragona\TJobs\Models\TJob;

class ExampleJob extends TJob{

    protected $queue = "example-queue";
    protected $name = "example-job";
    
    protected $things;
    
    public function __construct($numsteps=0, $options=[]) {
        parent::__construct($options);
        // $this->numsteps=$numsteps;

        $this->addStep(new ExampleStep(['weight'=>10]));


       if($numsteps>0){
            for($i=0; $i<$numsteps ; $i++){
                $this->addStep(new ExampleStep(['wait'=>1]));
        
            }
        }

        $this->addStep(new ExampleStep(['weight'=>20,'wait'=>10]));



    }


}
