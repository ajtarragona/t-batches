<?php

namespace Ajtarragona\TBatches\Models;

use Ajtarragona\TBatches\Jobs\TBatchJob;
use Ajtarragona\TBatches\Traits\TraceableModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Date;

class TBatchModel extends Model
{
    use TraceableModel;
    
    public $table = 'batches';

	public static $perpage = 10;
   
    public $sortable = [
   		'id','name','classname','queue','user_id','progress','description','progress','created_at','started_at','finished_at','failed','stop_on_fail','trace'
    ];


   	protected $fillable = [
        'name','classname','queue','user_id','progress','description','created_at','started_at','finished_at','failed','stop_on_fail','trace'
	];

    public $timestamps = false;

    protected $dates = [
        'created_at','started_at','finished_at',
    ];

    protected $casts = [
        'failed' => 'boolean',
        'stop_on_fail'=>'boolean',
        'trace' => 'array',
    ];

	public function user()
    {
      return $this->belongsTo(User::class);
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


    public function scopeOfUser($query, $user_id){
        $query->where('user_id',$user_id);
    }
    public function scopeOfQueue($query, $queue){
        $query->where('queue',$queue);
    }


    
    public function scopeFilter($query, $filter=[])
    {
       
        if(!$filter) return;

       
        if($filter['queue']??null){
            $query->ofQueue($filter['queue']);
        }
        if($filter['user_id']??null){
            $query->ofUser($filter['user_id']);
        }

        if($filter["term"]??null){
            $textfilter=$filter["term"];
            $query->where(function ($query) use ($textfilter) {
                $query->where('name', 'like','%'.$textfilter.'%')
                 ->orWhere('description', 'like','%'.$textfilter.'%');
            });
        }

    }



    protected static function boot() 
    {
        parent::boot();
        
        static::creating(function($record) { 
           $record->created_at = Date::now();
           if(auth()->user()) $record->user_id = auth()->user()->id;
        });

    }


    public function startedJobs(){
        return $this->jobs()->started();
    }
    public function jobs(){
        return $this->hasMany(TJobModel::class, "batch_id","id");
    }
    
    public function lastFinishedJob(){
        return $this->jobs()->finished()->orderBy('finished_at','desc')->first();
    }

    public function progress($progress=0){
        $this->progress=$progress;
        $this->save();
        return $this;
    }

    public function getProgressAttribute($value){
        return round($value,2);
    }

}
