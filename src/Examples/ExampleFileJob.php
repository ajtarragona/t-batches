<?php

namespace Ajtarragona\TBatches\Examples;

use Ajtarragona\TBatches\Jobs\TBatchJob;
use Exception;

class ExampleFileJob extends TBatchJob{

    protected $name = "example-file-job";
    
    
    public function run(){
        $this->batch->counter++;
        $this->batch->filecontent.=("Linea ".$this->batch->counter."\n") ;

        
        return $this->success("generated line". ($this->batch->counter??0) );
        
    }
}
