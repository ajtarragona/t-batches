<?php

namespace Ajtarragona\TBatches\Examples;

use Ajtarragona\TBatches\Jobs\TBatchJob;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ExampleFileJobEnd extends TBatchJob{

    protected $name = "example-file-job-end";
    
    
    public function run(){
        $filename='examples/file-'.Str::slug(now()->toDateTimeString()).'.txt';

        // dump($filename, $this->batch->filecontent);
        Storage::disk('local')->put($filename, $this->batch->filecontent);
        // =storage_path('app/'.$filename);
        $fileurl=route('tgn-batches.download',[ 'filename'=>$filename]);
        // dump($fileurl);
        $this->message("Generated file ".$filename);
        return $this->download( $fileurl );
        
    }
}
