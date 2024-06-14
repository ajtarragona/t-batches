<?php

namespace Ajtarragona\TJobs\Services;

use Ajtarragona\TJobs\Models\TJobModel;

class TJobsService{
    
 
    /** Retorna un job pasando el nombre corto */    
    public function find($name_or_id){
        if(is_string($name_or_id)) $job=TJobModel::withName($name_or_id)->inProgress();
        else $job=TJobModel::find($name_or_id);
        return $job;
        
    }

  
    /** Retorna todos los jobs */
    public function all(){
        return TJobModel::all();
    }

    
    
}