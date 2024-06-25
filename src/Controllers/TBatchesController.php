<?php

namespace Ajtarragona\TBatches\Controllers;

use Ajtarragona\TBatches\Models\TBatch;
use Ajtarragona\TBatches\Models\TBatchModel;
use Illuminate\Http\Request;

class TBatchesController extends Controller
{
   
    

    public function monitor(TBatchModel $batch, Request $request){
        $ret=[
            'progress'=>$batch->progress,
            'failed'=>$batch->failed?1:0,
            'started'=>$batch->started_at?1:0,
            'finished'=>$batch->finished_at?1:0,
        ];
        return response()->json($ret);
    }
    

    
}
