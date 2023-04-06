<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use App\Enums\AccessAuthority;

class AdminMiddleware
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
//        if ($request->user() && $request->user()->access_authority == AccessAuthority::USER)
//        {
//            return new Response(view('unauthorized.unauthorized')->with('role', '管理者'));
//        }
        if (session('toppage') == AccessAuthority::USER)
        {
            return new Response(view('unauthorized.unauthorized')->with('role', '管理者'));
        }
        return $next($request);
    }
}
