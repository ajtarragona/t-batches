<?php

namespace Ajtarragona\TBatches\Jobs;

use Ajtarragona\TBatches\Models\TJobModel;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;

class TBatchJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $job_model;
    protected $weight;
    protected $wait;

    public function __construct(TJobModel $job_model=null, $args=[]){

        $this->job_model = $job_model;
        if(isset($args["wait"])) $this->wait = intval($args["wait"]);
        if(isset($args["weight"])) $this->weight = floatval($args["weight"]);
    }

 
    public function getModel(){
        return $this->job_model;
    }

    public function weight($value=null){
        if(is_null($value)) return $this->weight;
        else $this->weight=$value;
    }
    
    public function wait($value=null){
        if(is_null($value)) return $this->wait;
        else $this->wait=$value;
    }

    public function getOptions(){
        return [
            'weight'=>$this->weight,
            'wait'=>$this->wait,
        ];
    }


    public function createModel($batch){
        // dump('createModel', $batch);
        $classname=get_class($this);
        $name= Str::slug(Str::snake($classname));
        $args=[
            'batch_id'=>$batch->id,
            'classname'=>$classname,
            'name'=>$name,
            'wait'=>$this->wait,
            'weight'=>$this->weight
        ];
        // dd($args);
        $this->job_model=TJobModel::create($args);
    }
    

    public function setOptions($args=[]){
        if(isset($args["wait"])) $this->wait = intval($args["wait"]);
        if(isset($args["weight"])) $this->weight = floatval($args["weight"]);
    }

   

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
        // dump('handle',$this);
        $success=false;
        try{
            $job_model=$this->job_model; //job model
            $batch=$job_model->batch;

            $job_model->update([
                'started_at'=>Date::now()
            ]);
           
            if(method_exists($this,'run')){
                $success=$this->run();//lo implementará cada job
            }
            
            if($success){
                $batch->increment('progress', $this->weight);
                $job_model->update([
                    'finished_at'=>Date::now()
                ]);
            }else{
                $job_model->update([
                    'failed'=>true,
                    'finished_at'=>Date::now(),
                    'message'=>'TODO error message'
                ]);
                $batch->update([
                    'failed'=>true,
                    'finished_at'=>Date::now(),
                    'trace'=>"Error in job ". $job_model->id
                ]);
            }

            return $success;



        }catch(Exception $e){
            // dd($e);
            $job_model->update([
                'failed'=>true,
                'finished_at'=>Date::now(),
                'trace'=>$e->getTraceAsString()
            ]);
            $batch->update([
                'failed'=>true,
                'finished_at'=>Date::now(),
                'trace'=>$e->getTraceAsString()
            ]);
            return false;
           
        }
    }

    // public function run();
    
}
