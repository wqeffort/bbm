<?php

namespace App\Http\Middleware;

use Closure;

class Admin
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
        // 验证SESSION
        if (!session('admin')) {
            return redirect('service/login');
        }else{
            $result = $next($request);
        }
        return $result;

    }
}
