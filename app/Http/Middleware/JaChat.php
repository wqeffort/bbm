<?php

namespace App\Http\Middleware;

use Closure;

class JaChat
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
        $data = $request->all();
        session(['jachat'=>true]);
        $result = $next($request);
        return $result;
    }
}