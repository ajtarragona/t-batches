<?php

namespace Ajtarragona\TBatches\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Date;

class TBatchDispatcher implements ShouldQueue
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
        // dump($this->batch);
    }

   
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
        
        try{
            $jobs=$this->batch->jobs();
            // dd('TBatchDispatcher handle',$jobs);
            $batch_model=$this->batch->model();
            // dd($model);

            $batch_model->start()->progress(0)->save();
            // dump($jobs);
            foreach( $jobs as $i=>$job){
                $success=$job->process();
               
                if(!$success && $job->stop_on_fail) break;
            }
            $batch_model=$this->batch->model();
            
            // dump($success, $batch_model->stop_on_fail, $batch_model->finished_at);
            if(($success || !$batch_model->stop_on_fail) && !$batch_model->finished_at){
                // dd("entro");
                $batch_model->progress(100)->finish()->save();
            }
            

        }catch(Exception $e){
            // dd($e);
            $batch_model->fail()->finish()->addTrace($e->getTrace())->save();

            // dd($e);
            // abort(500,$e->getMessage());
            // $this->abort($e->getMessage());
            // echo "\n".json_encode(["progress"=>"100", "message"=>"error"]);
        }
    }
}
