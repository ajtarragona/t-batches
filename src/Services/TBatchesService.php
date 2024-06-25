<?php

namespace Ajtarragona\TBatches\Services;

use Ajtarragona\TBatches\Models\TBatchModel;

class TBatchesService{
    
 
    /** Retorna un batch pasando el nombre corto */    
    public function find($name_or_id){
        if(is_string($name_or_id)) $job=TBatchModel::withName($name_or_id)->inProgress();
        else $job=TBatchModel::find($name_or_id);
        return $job;
        
    }

  
    /** Retorna todos los jobs */
    public function all(){
        return TBatchModel::all();
    }

    
    
}