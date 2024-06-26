<?php

namespace Ajtarragona\TBatches\Models;

use Ajtarragona\TBatches\Traits\TraceableModel;
use Illuminate\Database\Eloquent\Model;

class TJobModel extends Model
{
    use TraceableModel;
    
    public $table = 'batch_jobs';

	
    public $sortable = [
   		'id','name','classname','batch_id','message','file_url','started_at','finished_at','failed','trace','wait','weight'
    ];


   	protected $fillable = [
        'name','classname','batch_id','message','file_url','started_at','finished_at','failed','trace','wait','weight'
	];

    public $timestamps = false;

    protected $dates = [
        'started_at','finished_at',
    ];

    protected $casts = [
        'failed' => 'boolean',
        'trace' => 'array',
    ];

	public function batch()
    {
      return $this->belongsTo(TBatchModel::class,"batch_id","id");
    }



    public function scopeWithName($query, $name){
        $query->where('name',$name);
    }


    public function scopeStarted($query){
        $query->whereNotNull('started_at');
    }
    

    public function scopeFinished($query){
        $query->whereNotNull('finished_at');
    }

    
    public function scopeInProgress($query){
        $query->started()->whereNull('finished_at');
    }

    public function scopeFailed($query){
        $query->finished()->whereNotNull('failed');
    }


    public function scopeOfBatch($query, $batch_id){
        $query->where('batch_id',$batch_id);
    }

  

}
