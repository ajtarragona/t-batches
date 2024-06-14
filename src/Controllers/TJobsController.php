<?php

namespace Ajtarragona\TJobs\Controllers;

use Ajtarragona\TJobs\Models\TJob;
use Ajtarragona\TJobs\Models\TJobModel;
use Illuminate\Http\Request;

class TJobsController extends Controller
{
   
    

    public function monitor(TJobModel $job, Request $request){
        $ret=[
            'progress'=>$job->progress,
            'failed'=>$job->failed?1:0,
            'started'=>$job->started_at?1:0,
            'finished'=>$job->finished_at?1:0,
        ];
        return response()->json($ret);
    }
    

    
}
