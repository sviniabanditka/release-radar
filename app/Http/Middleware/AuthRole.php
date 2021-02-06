<?php

namespace App\Http\Middleware;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Closure;
use Illuminate\Http\Request;

class AuthRole
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if (Sentinel::check() && (Sentinel::inRole($role) || Sentinel::inRole('admin'))) {
            return $next($request);
        } else {
            return redirect(route('login.get'));
        }
    }
}
