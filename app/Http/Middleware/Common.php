<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Input;
class Common
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
        // 验证token
        if (session('user')) {
            // session(['url'=>'/']);
        }else{
          // 判断来路域名
            $info = Input::url();
            if (explode(env('HTTP_HOST'), $info)['1']) {
                $jump = explode(env('HTTP_HOST'), $info)['1'];
            }else{
                $jump = '/';
            }
            session(['url'=>$jump]);
            return redirect('/');  
        }
        return $next($request);
    }
}