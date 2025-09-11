<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next,...$roles)
    {
        // return $next($request);
        if(!auth()->check()){
            return redirect('login');
        }
        if(in_array(auth()->user()->role, $roles)){
            return $next($request);
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
}
