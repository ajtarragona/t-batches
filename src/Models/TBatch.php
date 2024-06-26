<?php

namespace Ajtarragona\TBatches\Models;

use Ajtarragona\TBatches\Jobs\CallableJob;
use Ajtarragona\TBatches\Jobs\TBatchDispatcher;
use Ajtarragona\TBatches\Jobs\TBatchFinisher;
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
    protected $batchId;
    // protected $model;
    protected $autostart=true;
    protected $stop_on_fail=false;
    

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

    public function jobs(){
        return $this->jobs;
    }
    public function model(){
        return $this->batchId ? TBatchModel::find($this->batchId) : null;
    }

    

    public function stopsOnFail(){
        return $this->stop_on_fail;
    }


    public function add($job, $args=[]){
        return $this->addJob($job,$args);
    }
    public function addJob($job, $args=[]){
        if(is_callable($job) ){
            if(!$this->isSingleThreaded()) throw new Exception("No callable jobs allowed for multi thread batches");
            $tmp=new CallableJob($job);
            // $tmp->setCallable($job);
            $tmp->stop_on_fail = $args["stop_on_fail"] ?? $this->stop_on_fail;
            $tmp->wait = $args["wait"]??0;
            $tmp->weight = $args["weight"]??0;

            $this->jobs[]=$tmp;

        }else{
            // $job->setBatch($this);
            $job->stop_on_fail = $args["stop_on_fail"] ?? $this->stop_on_fail;
            $job->wait = $args["wait"]??0;
            $job->weight = $args["weight"]??0;

            $this->jobs[]=$job;
        }

        return $this;
    }


   

    /**
     * Asigna pesos a cada job 
     * crea los jobs en bbdd
     */
    protected function prepareJobs(){
        $usedweight=0;
        $numwithnoweight=0;
        
        // dd($this->jobs);
        
        foreach($this->jobs as $job){
            $usedweight += $job->weight;
            if($job->weight==0) $numwithnoweight++;
        }
        //si la suma es mayor que 100, devuelvo una excepcion
        if($usedweight>100) throw new Exception("Total Weight exceeds 100%");

        //recoorro los que no tengan peso y les pongo lo que queda
        $jobweight=(100-$usedweight)/$numwithnoweight;
        // dd($stepweight);
        $totalweight=0;

        foreach($this->jobs as $i=>$job){
            if($job->weight==0) $this->jobs[$i]->weight = $jobweight;

            $totalweight += $this->jobs[$i]->weight;

        }
        // dd($this->steps);
        
        if( ceil($totalweight) < 100) throw new Exception("Total weight must be equal to 100%");

        foreach($this->jobs as $i=>$job){
            $job->withBatch($this)->createModel();
        }

    }

    
    public function isSingleThreaded(){
       return  uses_trait($this, 'Ajtarragona\TBatches\Traits\SingleThreadedBatch' );
    }

    public function run(){
        // lo creo en BBDD
        $model=TBatchModel::create([
            'queue'=>$this->queue,
            'classname'=>$this->classname,
            'name'=>$this->name,
            'stop_on_fail'=>$this->stopsOnFail(),
            'user_id'=>auth()->user()?auth()->user()->id:null,
            'progress'=>0
        ]);
        $this->batchId=$model->id;

        
        //lanzo en la cola
        if($this->jobs){

            $this->prepareJobs();
            
            if($this->isSingleThreaded()){
                // dd($this->jobs());
                TBatchDispatcher::dispatch($this)->onQueue($this->queue);

            }else{
               
            
                //despacho todos los jobs a la cola definida en el batch
                //cuando se lance el primer job se marcará la fecha de inicio del batch. 
                //así, la cola no está arrancada, el batch no avanzará
                foreach( $this->jobs as $i=>$job){
                    dispatch($job)->onQueue($this->queue);
                }

                //lanzo un job de finalizacion de la batch, en la misma cola
                TBatchFinisher::dispatch($this)->onQueue($this->queue);


                    
        
               
            }

        }
    }




}