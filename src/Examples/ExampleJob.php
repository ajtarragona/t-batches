<?php

namespace Ajtarragona\TJobs\Examples;

use Ajtarragona\TJobs\Models\TJob;

class ExampleJob extends TJob{

    protected $queue = "example-queue";
    
    protected $things;
    
    public function __construct($numsteps=0, $options=[]) {
        parent::__construct($options);
        // $this->numsteps=$numsteps;

        $this->addStep(function(){
            return true;
        },['weight'=>10,'wait'=>1]);


       if($numsteps>0)
        for($i=0; $i<$numsteps ; $i++){
            $this->addStep(function(){
                return rand(1,10)>5;
            },['wait'=>1]);
    
        }

        $this->addStep(function(){
            return true;
        },['weight'=>10,'wait'=>1]);



    }


    protected function setup(){

       
    }
}
