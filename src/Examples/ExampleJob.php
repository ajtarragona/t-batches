<?php

namespace Ajtarragona\TBatches\Examples;

use Ajtarragona\TBatches\Jobs\TBatchJob;

class ExampleJob extends TBatchJob{

    public function run(){
        $num=rand(1,100) ;
        // dump($num);
        return true;//$num <99;
    }
}
