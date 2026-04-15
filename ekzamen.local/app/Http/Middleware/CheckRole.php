<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
   

    public function handle($request, Closure $next, ...$roles)
{
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    foreach ($roles as $role) {
        if (auth()->user()->hasRole($role)) {
            return $next($request);
        }
    }

    abort(403, 'У вас нет доступа к этой странице.');
}
}
