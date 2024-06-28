<?php

namespace Ajtarragona\TBatches\Controllers;

use Ajtarragona\TBatches\Models\TBatch;
use Ajtarragona\TBatches\Models\TBatchModel;
use Illuminate\Http\Request;

class TBatchesController extends Controller
{
   
    

    public function monitor(TBatchModel $batch, Request $request){
        $lastJob=$batch->lastFinishedJob();
        // dd($batch);
        $ret=[
            'batch'=>$batch,
        //     'progress'=>$batch->progress,
        //     'failed'=>$batch->failed?1:0,
        //     'started'=>$batch->started_at?1:0,
        //     'finished'=>$batch->finished_at?1:0,
            'message'=>$lastJob->message??null,
            'file_url'=>$lastJob->file_url??null,
            'jobs'=>$batch->startedJobs
        ];
        return response()->json($ret);
    }
    

    
    public function download($filename){
        
        $filepath=storage_path('app/'.$filename);
        $filename=basename($filename);
        return response()->download($filepath, $filename);


    }
    
    public function cancel(TBatchModel $batch){
        
        $batch->finish()->save();

        return response()->json(["status"=>"success"]);
    
    }
    

    
}
