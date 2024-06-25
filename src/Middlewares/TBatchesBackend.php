<?php

namespace Ajtarragona\TBatches\Middlewares;

use Closure;

class TBatchesBackend
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
    	if (!config("tbatches.backend")) {
    		 abort(403, "Oops! Batches backend is disabled");
        }else{
            if(session()->has('tbatches_login')){
                return $next($request);
            }else{
                return redirect()->route('tgn-batches.login');
            }
        }

    }
}