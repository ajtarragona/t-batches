<?php

namespace Ajtarragona\TJobs\Examples;

use Ajtarragona\TJobs\Models\TJob;
use Ajtarragona\TJobs\Models\TJobStep;

class ExampleStep extends TJobStep{

    public function handle(){
        $num=rand(1,100) ;
        // dump($num);
        return $num <99;
    }
}
