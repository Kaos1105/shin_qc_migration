<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class MemberMiddleware
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
        if(!session('circle.id')){
            return new Response(view('unauthorized.unauthorized-circle'));
        }
        return $next($request);
    }
}
