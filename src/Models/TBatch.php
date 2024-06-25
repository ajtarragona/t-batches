<?php

namespace Ajtarragona\TBatches\Models;

use Ajtarragona\TBatches\Jobs\TBatchDispatcher;
use Ajtarragona\TBatches\Jobs\TBatchJob;
use Exception;

use Illuminate\Support\Str;

abstract class TBatch 
{

    protected $jobs=[];
    protected $options=[];
    protected $name;
    protected $classname;
    protected $queue = "batches-queue";
    protected $model;
    protected $autostart=true;


    // abstract protected function setup();

    public function __construct($options=[]) {
        $this->options=$options;
        if(isset($options["queue"])) $this->queue=$options["queue"];
        $this->classname=get_class($this);
        $this->name= isset($options["name"])? $options["name"] : ($this->name ? $this->name : Str::slug(Str::snake($this->classname)));
        // dd($this);
    }

    public function getOption($name, $default=null){
        return data_get($this->options, $name, $default);
    }

    public function getJobs(){
        return $this->jobs;
    }
    public function getModel(){
        return $this->model;
    }

    public function addJob($job, $args=[]){
        // if(is_callable($job) ){
        //     if(!$this->isSingleThreaded()) throw new Exception("No callable jobs allowed for multi thread batches");
        //     $tmp=new TBatchJob();
        //     // $tmp->setCallable($job);
        //     $tmp->setBatch($this);
        //     $tmp->setOptions($args);
        //     $this->jobs[]=$tmp;

        // }else{
            // $job->setBatch($this);
            $job->setOptions($args);
            $this->jobs[]=$job;
        // }

        return $this;
    }


   

    protected function createJobs(){
        $usedweight=0;
        $numwithnoweight=0;
        
        // dd($this->jobs);
        
        foreach($this->jobs as $job){
            $usedweight += $job->weight();
            if($job->weight()==0) $numwithnoweight++;
        }
        //si la suma es mayor que 100, devuelvo una excepcion
        if($usedweight>100) throw new Exception("Total Weight exceeds 100%");

        //recoorro los que no tengan peso y les pongo lo que queda
        $jobweight=(100-$usedweight)/$numwithnoweight;
        // dd($stepweight);
        $totalweight=0;

        foreach($this->jobs as $i=>$job){
            if($job->weight()==0) $this->jobs[$i]->weight($jobweight);

            $totalweight += $this->jobs[$i]->weight();

        }
        // dd($this->steps);
        
        if( ceil($totalweight) < 100) throw new Exception("Total weight must be equal to 100%");

        foreach($this->jobs as $i=>$job){
            $job->createModel($this->model);
        }

    }

    
    public function isSingleThreaded(){
       return  uses_trait($this, 'Ajtarragona\TBatches\Traits\SingleThreadedBatch' );
    }

    public function run(){
        // lo creo en BBDD
        $this->model=TBatchModel::create([
            'queue'=>$this->queue,
            'classname'=>$this->classname,
            'name'=>$this->name,
            'user_id'=>auth()->user()?auth()->user()->id:null,
            'progress'=>0
        ]);

        
        //lanzo en la cola
        if($this->jobs){
            $this->createJobs();

            if($this->isSingleThreaded()){
                // dd($this->getJobs());
                TBatchDispatcher::dispatch($this)->onQueue($this->queue);

            }else{
               
                    
                $jobs=$this->getJobs();
                // dd($jobs);
                //    $progress=0;
                //     $this->model->update([
                //         'progress'=>0,
                //         'started_at'=>Date::now(),
                //     ]);
        
                    foreach( $jobs as $i=>$job){
                        $job->dispatch()->onQueue($this->queue);
                    }
                //         $progress = $progress + $job->weight;

                //         if($job->run()){
                //             $this->model->update([
                //                 'progress'=>$progress
                //             ]);
                //         }else{
                //             $error=true;
                //             $this->model->update([
                //                 'failed'=>true,
                //                 'finished_at'=>Date::now(),
                //                 'trace'=>"Error in job ". $i
                //             ]);
                //             break;
                //         }
                        
                //     }
                //     if(!$error){
                //         $model->update([
                //             'progress'=>100,
                //             'finished_at'=>Date::now(),
                //         ]);
                //     }
                    
        
               
            }

        }
    }

}