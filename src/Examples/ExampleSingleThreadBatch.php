<?php

namespace Ajtarragona\TBatches\Examples;

use Ajtarragona\TBatches\Models\TBatch;
use Ajtarragona\TBatches\Traits\SingleThreadedBatch;

class ExampleSingleThreadBatch extends TBatch{

    use SingleThreadedBatch;

    protected $queue = "example-queue";
    protected $name = "example-single-batch";
    
    protected $things;
    
    public function __construct($numsteps=0, $options=[]) {
        parent::__construct($options);
        // $this->numsteps=$numsteps;

        $this->addJob(new ExampleJob(), ['weight'=>10]);


       if($numsteps>0){
            for($i=0; $i<$numsteps ; $i++){
                $this->addJob(new ExampleJob(),['wait'=>1]);
        
            }
        }

        $this->addJob(new ExampleJob(),['weight'=>20,'wait'=>10]);

        // dd($this->getJobs());

    }


}
