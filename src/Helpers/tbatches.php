<?php

use Ajtarragona\TBatches\Facades\TBatchesFacade;
use Ajtarragona\TBatches\Models\TBatchModel;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

if (! function_exists('tBatchProgress')) {
	function tBatchProgress($batch) {
		if(!$batch instanceof TBatchModel){
			$batch=TBatchesFacade::find($batch);
		}
		// dd($batch);
        // dump($this->options["map_position"]);
        $view='tgn-batches::_batch-progress';
		if(view()->exists($view)){
            return view($view, ['batch'=>$batch]);
		}
    }
}
if (! function_exists('tBatchJobDetail')) {
	function tBatchJobDetail($batch) {
		if(!$batch instanceof TBatchModel){
			$batch=TBatchesFacade::find($batch);
		}
		// dd($batch);
        // dump($this->options["map_position"]);
        $view='tgn-batches::_batch-detail';
		if(view()->exists($view)){
            return view($view, ['batch'=>$batch]);
		}
    }
}

if (! function_exists('c')) {
	function c($name) {
        return "<code>{$name}</code>";
    }
}

if (! function_exists('is_collection')) {
	function is_collection($obj){
		return $obj && ($obj instanceof Collection || $obj instanceof EloquentCollection);

	}
}


if (! function_exists('is_assoc')) {

	function is_assoc($array){
		if(!$array) return false;
		if(!is_array($array)) return false;
		return !(array_values($array) === $array);

	}
}



if(!function_exists('uses_trait')){
	function uses_trait($obj, $name){
		return  in_array($name, array_keys(class_uses($obj)));

	}
}


if (! function_exists('json_pretty')) {
	function json_pretty($string) {
	 	return json_encode($string, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
	}
}

if (! function_exists('to_object')) {
	function to_object($array) {
		return json_decode(json_encode($array), FALSE);
		
	}
}



if (! function_exists('to_array')) {
	function to_array($object) {
	 	return json_decode(json_encode($object), true);
	}
}



if (!function_exists('json_alpine')) {
	function json_alpine($ret){
		return str_replace("\"","'", json_encode( $ret, JSON_HEX_APOS|JSON_HEX_QUOT ));
	}
}


if (! function_exists('fullquery')) {

	function fullquery($query){
		return vsprintf(str_replace(array('?'), array('\'%s\''), $query->toSql()), $query->getBindings());
	}
}
