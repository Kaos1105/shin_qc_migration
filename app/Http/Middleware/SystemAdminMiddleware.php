<?php

namespace App\Http\Middleware;

use App\Enums\AccessAuthority;
use Closure;
use Illuminate\Http\Response;

class SystemAdminMiddleware
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
        if ($request->user() && $request->user()->access_authority != AccessAuthority::System)
        {
            return new Response(view('unauthorized.unauthorized')->with('role', 'System'));
        }
        return $next($request);
    }
}
