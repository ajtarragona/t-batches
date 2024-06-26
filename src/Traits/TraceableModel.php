<?php

namespace Ajtarragona\TBatches\Traits;

use Illuminate\Support\Facades\Date;

trait TraceableModel
{
   

    
    public function fail($bool=true ){
        if($bool) $this->failed=true;
        return $this;
    }
    public function finish($bool=true){
        if($bool) $this->finished_at = Date::now();
        // $this->save();
        return $this;
    }
    public function start($bool=true){
        if($bool) $this->started_at = Date::now();
        // $this->save();
        return $this;
    }

    public function addTrace($message){
        $trace=$this->trace;
                
        if($trace){
            if(!is_array($trace)) $trace=[$trace];
        }else{
            $trace=[];
        }
        if(is_array($message)){  
            $trace=array_merge($trace, $message);
        }else{
            $trace[]= $message;
        }
        $this->trace=$trace;
        
        // $this->save();
        return $this;
    }

}