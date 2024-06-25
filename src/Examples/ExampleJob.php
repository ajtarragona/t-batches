<?php

namespace Ajtarragona\TJobs\Examples;

use Ajtarragona\TJobs\Models\TBatch;
use Ajtarragona\TJobs\Models\TJob;

class ExampleJob extends TJob{

    public function handle(){
        $num=rand(1,100) ;
        // dump($num);
        return $num <99;
    }
}
