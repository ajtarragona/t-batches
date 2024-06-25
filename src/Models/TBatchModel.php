<?php

namespace Ajtarragona\TBatches\Models;

use Ajtarragona\TBatches\Jobs\TBatchJob;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Date;

class TBatchModel extends Model
{
    public $table = 'batches';

	public static $perpage = 10;
   
    public $sortable = [
   		'id','name','classname','queue','user_id','progress','description','progress','created_at','started_at','finished_at','failed','trace'
    ];


   	protected $fillable = [
        'name','classname','queue','user_id','progress','description','created_at','started_at','finished_at','failed','trace'
	];

    public $timestamps = false;

    protected $dates = [
        'created_at','started_at','finished_at',
    ];

    protected $casts = [
        'failed' => 'boolean',
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


    
    public function scopeFilter($query, $filter=[])
    {
       
        if(!$filter) return;

       
        if($filter['user_id']){
            $query->ofUser($filter['user_id']);
        }

        if($filter["term"]){
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


    public function jobs(){
        return $this->hasMany(TJobModel::class, "batch_id","id");
    }

}
