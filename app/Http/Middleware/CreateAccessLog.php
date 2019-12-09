<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CreateAccessLog
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $pal = new \App\PageAccesslog([
            'ip_address' => request()->ip(),
            'user_agent' => $request->header('User-Agent'),
            'refer' => $request->server('HTTP_REFERER'),
            'url' => $request->fullUrl(),
        ]);
        $res = $next($request);
        $pal->status_code = $res->status();
        $pal->save();
        return $res;
    }
}
