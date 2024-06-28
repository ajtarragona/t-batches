<?php

namespace Ajtarragona\TBatches\Controllers;

use Ajtarragona\TBatches\Examples\ExampleBatch;
use Ajtarragona\TBatches\Models\TBatchModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use TBatches;
use Illuminate\Support\Str;

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
        $test_batch=TBatchModel::orderBy('created_at','desc')->first();
        // dd($test_batch)
        $args=compact('test_batch');
        
        return view("tgn-batches::welcome", $args);
    }


    
    public function batches(Request $request){

        $batches=TBatchModel::filter($request->all())->with(['jobs'])->get();
        $args=compact('batches');
        return response()->json($args);
        
    }
    public function test(Request $request){
        $name="\\Ajtarragona\\TBatches\\Examples\\".Str::ucfirst(Str::camel($request->name));
        // dd($name);

        $batch = new $name(20);
        $batch->run();
        $args=[
            'batch'=>$batch->model()
        ];
        
        return response()->json($args);
        
    }
    
    public function deleteAll(Request $request){

        TBatchModel::finished()->delete();
        $args=[];
        return response()->json($args);
        
    }
    public function delete(TBatchModel $batch, Request $request){

        $batch->delete();
        $args=[];
        return response()->json($args);
        
    }
    
}
