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
        
        // dd($this);
        try{
            $jobs=$this->batch->getJobs();
            // dd('TBatchDispatcher handle',$jobs);
            $model=$this->batch->getModel();
            // dd($model);

            $model->update([
                'progress'=>0,
                'started_at'=>Date::now(),
            ]);
            // dump($jobs);
            foreach( $jobs as $i=>$job){
                $success=$job->dispatchNow($job->getModel(),$job->getOptions());
                if(!$success) break;
            }

            if($success){
                $model->update([
                    'progress'=>100,
                    'finished_at'=>Date::now(),
                ]);
            }
            

        }catch(Exception $e){
            // dd($e);
            $model->update([
                'failed'=>true,
                'finished_at'=>Date::now(),
                'trace'=>$e->getTraceAsString()
            ]);

            // dd($e);
            // abort(500,$e->getMessage());
            // $this->abort($e->getMessage());
            // echo "\n".json_encode(["progress"=>"100", "message"=>"error"]);
        }
    }
}
