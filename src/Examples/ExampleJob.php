<?php

namespace Ajtarragona\TBatches\Examples;

use Ajtarragona\TBatches\Jobs\TBatchJob;
use Exception;

class ExampleJob extends TBatchJob{

    protected $title;
    protected $name = "example-job";
    
    
    public function __construct($title){
        $this->title=$title;
    }

    public function run(){
        $num=rand(1,100) ;
        $this->batch->counter++;
        $ret= $num < 90;
        if($ret){
            return $this->success('wait:'.$this->wait .' - '.$this->title . "- num:".$num . " - counter:". ($this->batch->counter??0) );
        }else{
            throw new Exception("Ha super petao!");
            // return $this->error("Ha petao!" . $this->title . "- num:".$num);
        }
    }
}
