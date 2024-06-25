<?php

namespace Ajtarragona\TBatches\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use TBatches;

class TBatchesBackendController extends Controller
{
    public function login(){
        Artisan::call('vendor:publish',['--tag'=>'tgn-batches-assets','--force'=>true]);
        return view("tgn-batches::pass");
    }


    public function dologin(Request $request){
        if($request->password == config("tbatches.backend_password")){
            session(['tbatches_login'=>"OK"]);
            return redirect()->route('tgn-batches.home');
        }else{
            return redirect()->route('tgn-batches.login')->withErrors(["password" => "Wrong password"])->withInput();
        }
    }

    
    public function logout(){
        session()->forget('tbatches_login');
        return redirect()->route('tgn-batches.home');
    }

    
    public function home(){
        
        Artisan::call('vendor:publish',['--tag'=>'tgn-batches-assets','--force'=>true]);
        $batches=TBatches::all();
        $args=compact('batches');
        
        return view("tgn-batches::welcome", $args);
    }

    
}
