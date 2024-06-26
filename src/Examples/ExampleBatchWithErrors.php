<?php

namespace Ajtarragona\TBatches\Examples;

use Ajtarragona\TBatches\Models\TBatch;

class ExampleBatchWithErrors extends TBatch{

    protected $queue = "example-queue";
    protected $name = "example-batch-errors";
    
    public $counter=0; //not accessible from jobs
    
    protected $stop_on_fail=false;
    
    
    public function __construct($numsteps=0, $options=[]) {
        parent::__construct($options);
        // $this->numsteps=$numsteps;

        $this->add(new ExampleJob('Inici'), ['weight'=>10,'wait'=>1]);


       if($numsteps>0){
            for($i=0; $i<$numsteps ; $i++){
                $this->add(new ExampleJobWithErrors("Pas ".$i),['wait'=>1]);
        
            }
        }

        $this->add(new ExampleJob('Final'),['weight'=>20,'wait'=>2]);



    }


}
