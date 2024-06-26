<?php

namespace Ajtarragona\TBatches\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Date;

class TBatchFinisher implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $batch;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($batch)
    {
        $this->batch = $batch;
    }

   
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
        $batch_model=$this->batch->model();
        // dd($this->batch->stopsOnFail(), $batch_model->failed);
        if($batch_model->failed && $this->batch->stopsOnFail() ){
            $batch_model->finish()->save();
        }else{
            $batch_model->finish()->progress(100)->save();
        }
        
        
    }
}
