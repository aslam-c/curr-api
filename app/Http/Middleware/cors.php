<?php

namespace App\Http\Middleware;

use Closure;

class cors
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
        $headers = [
            'Access-Control-Allow-Methods:POST, GET, OPTIONS',
            'Access-Control-Allow-Headers:Content-Type, X-Auth-Token, Origin, Authorization'
        ];
        return $next($request)->header('Access-Control-Allow-Origin', '*')
            ->header('access-control-allow-methods', 'GET,POST,OPTIONS')
            ->header('access-control-allow-headers', 'X-Requested-With,Content-Type,X-Token-Auth,Authorization');
    }
}
