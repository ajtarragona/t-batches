<?php

namespace Ajtarragona\TBatches\Examples;

use Ajtarragona\TBatches\Models\TBatch;
use Ajtarragona\TBatches\Traits\SingleThreadedBatch;

class ExampleSingleThreadBatch extends TBatch{

    use SingleThreadedBatch;

    protected $queue = "example-queue";
    protected $name = "example-single-batch";
    
    public $counter=0; // accessible from jobs
    
    protected $stop_on_fail=false;
    
    
    public function __construct($numsteps=0, $options=[]) {
        parent::__construct($options);
        // $this->numsteps=$numsteps;

        $this->add(new ExampleJob('inici'), ['weight'=>10,'wait'=>0]);
      

       if($numsteps>0){
            for($i=0; $i<$numsteps ; $i++){
                $this->add(new ExampleJob('task '.$i),['wait'=>1]);
        
            }
        }

        $this->add(new ExampleJob('final'),['weight'=>20,'wait'=>5]);

        // dd($this->jobs());

    }


}
