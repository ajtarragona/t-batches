<?php

namespace Ajtarragona\TBatches\Examples;

use Ajtarragona\TBatches\Models\TBatch;
use Ajtarragona\TBatches\Traits\SingleThreadedBatch;

class ExampleFileBatch extends TBatch{

    use SingleThreadedBatch;

    protected $queue = "example-file-queue";
    protected $name = "example-file-batch";
    
    public $counter=0; // accessible from jobs
    public $filecontent; // accessible from jobs
    
    protected $stop_on_fail=true;
    
    
    public function __construct($lines=20) {
        parent::__construct();
      

       if($lines>0){
            for($i=0; $i < $lines ; $i++){
                $this->add(new ExampleFileJob(),['wait'=>1]);
            }
        }
      
        $this->add(new ExampleFileJobEnd(),['wait'=>5]);
        
      

    }


}
