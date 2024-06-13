<?php

namespace Ajtarragona\TJobs\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class TJobsBackendController extends Controller
{
    public function login(){
        Artisan::call('vendor:publish',['--tag'=>'tgn-jobs-assets','--force'=>true]);
        return view("tgn-jobs::pass");
    }


    public function dologin(Request $request){
        if($request->password == config("tjobs.backend_password")){
            session(['tjobs_login'=>"OK"]);
            return redirect()->route('tgn-jobs.home');
        }else{
            return redirect()->route('tgn-jobs.login')->withErrors(["password" => "Wrong password"])->withInput();
        }
    }

    
    public function logout(){
        session()->forget('tjobs_login');
        return redirect()->route('tgn-jobs.home');
    }

    
    public function home(){
        
        // Artisan::call('vendor:publish',['--tag'=>'tgn-jobs-assets','--force'=>true]);
        
        $args=[];
        
        return view("tgn-jobs::welcome", $args);
    }

    
}
