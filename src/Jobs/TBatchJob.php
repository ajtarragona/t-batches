<?php

namespace Ajtarragona\TBatches\Jobs;

use Ajtarragona\TBatches\Models\TJobModel;
use Ajtarragona\TBatches\Traits\BatchableJob;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


class TBatchJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, BatchableJob  ;

    protected $name;
    
    public $jobId;
    public $weight;
    public $wait;
    public $stop_on_fail;

    // public function __construct($args=[]){

    //     if(isset($args["wait"])) $this->wait = intval($args["wait"]);
    //     if(isset($args["weight"])) $this->weight = floatval($args["weight"]);
    // }

 
    public function model(){
        return TJobModel::find($this->jobId);
    }

 

    

  
   

    /** 
     * Run the job in the batch
     * without using queues
     */
    public function process()
    {
        // dump('process',$this->getAttributes());   
        $success=false;
        try{
            $job_model=$this->model(); //job model
            $batch_model=$this->batch(); //batch model

            //si el batch ha fallado, no hago nada
            if($this->stop_on_fail && $batch_model->failed) return false;
            
            //si el batch está finalizado no hago nada (si cancelan)
            if($batch_model->finished_at) return false;

            //si el batch no se ha iniciado lo inicio
            if(!$batch_model->started_at) $batch_model->start()->progress(0)->save();

            //inicio el job
            $job_model->start()->save();
        

            //si hay wait espero
            if($this->wait) sleep($this->wait);


            //acción especifica del job
            if(method_exists($this,'run')){
                $success=$this->run();//lo implementará cada job
            }
            
            
            if($success){
                $batch_model->increment('progress', $this->weight); //nativo de laravel, ya guarda
                $job_model->finish()->save();
            }else{
                $job_model->fail()->finish()->save();
                $batch_model->fail()->finish($this->stop_on_fail)->addTrace("Error in job ". $job_model->id)->save();
                
            }
            
            return $success;



        }catch(Exception $e){
            // dd($e);
            $job_model->fail()->finish()->addTrace($e->getTrace())->save();
            $batch_model->fail()->finish($this->stop_on_fail)->addTrace($e->getTrace())->save();

            return false;
        
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        return $this->process();
        
    }


    public function error($msg){
        $job_model=$this->model(); //job model
        $job_model->message=$msg;
        $job_model->failed=true;
        $job_model->save();
        return false;
    }

    
    public function success($msg){
        $job_model=$this->model(); //job model
        $job_model->message=$msg;
        $job_model->save();
        return true;
    }

    public function message($msg){
        
        return $this->success($msg);
    }

    public function download($path){
        $job_model=$this->model(); //job model
        $job_model->file_url=$path;
        $job_model->save();
        return true;
    }

    // public function run();
    
}
