<?php

namespace Ajtarragona\TJobs\Middlewares;

use Closure;

class TJobsBackend
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
    	if (!config("tjobs.backend")) {
    		 abort(403, "Oops! Jobs backend is disabled");
        }else{
            if(session()->has('tjobs_login')){
                return $next($request);
            }else{
                return redirect()->route('tgn-jobs.login');
            }
        }

    }
}