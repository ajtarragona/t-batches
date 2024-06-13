<?php

namespace Ajtarragona\TJobs\Services;

use Ajtarragona\TJobs\Models\TJobModel;

class TJobsService{
    
 
    /** Retorna un job pasando el nombre corto */    
    public function find($name){
       
        return TJobModel::withName($name)->inProgress();
        
    }

  
    /** Retorna todos los jobs */
    public function all(){
        return TJobModel::all();
    }

    
    
}