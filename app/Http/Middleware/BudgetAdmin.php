<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class BudgetAdmin
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
        if(Auth::guest()){
            return redirect()->route('homepage');
        }else if (Auth::user()->usertype != 'Budget Admin'){
            return redirect()->route('unauthorized_access');
        }
        return $next($request);
    }
}
