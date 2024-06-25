<?php

namespace Ajtarragona\TBatches\Models;

use Ajtarragona\TBatches\Jobs\TBatchDispatcher;
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
        if($job instanceof TJob) $this->jobs[]=$job;
        else if(is_callable($job)) $this->jobs[]=new TJob($job, $args);

        return $this;
    }


   

    protected function prepareWeights(){
        $usedweight=0;
        $numwithnoweight=0;
        
        // dd($this->steps);
        
        foreach($this->jobs as $job){
            $usedweight += $job->weight;
            if($job->weight==0) $numwithnoweight++;
        }
        //si la suma es mayor que 100, devuelvo una excepcion
        if($usedweight>100) throw new Exception("Total Weight exceeds 100%");

        //recoorro los que no tengan peso y les pongo lo que queda
        $stepweight=(100-$usedweight)/$numwithnoweight;
        // dd($stepweight);
        $totalweight=0;

        foreach($this->jobs as $i=>$job){
            if($job->weight==0) $this->jobs[$i]->weight=$stepweight;

            $totalweight += $this->jobs[$i]->weight;
        }
        // dd($this->steps);
        
        if( ceil($totalweight) < 100) throw new Exception("Total weight must be equal to 100%");

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
            $this->prepareWeights();
            TBatchDispatcher::dispatch($this)->onQueue($this->queue);

        }
    }

}