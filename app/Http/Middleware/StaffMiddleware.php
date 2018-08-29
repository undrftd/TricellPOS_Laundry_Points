<?php

namespace App\Http\Middleware;
use Auth;

use Closure;

class StaffMiddleware
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

        if(Auth::check())
        {
            if(Auth::user()->role == 'staff')
            {
                return $next($request);
            }
            else
            {
                return redirect('/'); 
            }
        }
        else
        {
            return redirect('/');
        }
    }
}
