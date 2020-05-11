<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Closure;
use Route;

class IsAdmin
{
    /**
     * Check the user is admin or not.
     * Only admin can access admin section
     * other users are redirect back to login page
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::user() && Auth::user()->is_admin==1) {
            return $next($request);
    }
    return redirect(route('home'))->withErrors(['message'=>'Sorry you are not admin']);
    }
}
