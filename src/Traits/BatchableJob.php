<?php

namespace Ajtarragona\TBatches\Traits;

use Ajtarragona\TBatches\Models\TBatchModel;
use Ajtarragona\TBatches\Models\TJobModel;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;


trait BatchableJob
{
    protected $batch;
    public $batchId;


   
    public function batch()
    {
        if ($this->batchId) {
            return TBatchModel::find($this->batchId);
        }
    }

    /**
     * Set the batch ID on the job.
     *
     * @param  $batchId
     * @return $this
     */
    public function withBatch($batch)
    {
        $this->batch = $batch;
        $this->batchId = $batch->model()->id;

        return $this;
    }

    public function createModel()
    {
        $classname=get_class($this);
        $name= $this->name ? $this->name : Str::slug(Str::snake($classname));
        $args=[
            'batch_id'=>$this->batchId,
            'classname'=>$classname,
            'name'=>$name,
            'wait'=>$this->wait,
            'weight'=>$this->weight
        ];
        // dd($args);
        $model=TJobModel::create($args);
        $this->jobId=$model->id;   ;
        
    }
    
}