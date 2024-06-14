<?php

namespace Ajtarragona\TJobs\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Date;

class TJobDispatcher implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $tjob;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($tjob)
    {
        $this->tjob= $tjob;
        // dd($this->tjob);
    }

   
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
        // dd($this);
        // dump($this->tjob, $this->tjob->getSteps());
        try{
            $progress=0;
            $error=false;
            $steps=$this->tjob->getSteps();
            // dd($steps);
            $model=$this->tjob->getModel();
            // dd($model);

            $model->update([
                'progress'=>0,
                'started_at'=>Date::now(),
            ]);

            foreach( $steps as $i=>$step){
                $progress = $progress + $step->weight;
                if($step->run()){
                    $model->update([
                        'progress'=>$progress
                    ]);
                }else{
                    $error=true;
                    $model->update([
                        'failed'=>true,
                        'finished_at'=>Date::now(),
                        'trace'=>"Error in step ". $i
                    ]);
                    break;
                }
                
            }
            if(!$error){
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
