<?php

namespace App\Http\Middleware;

use Closure;
use Log;
use Illuminate\Support\Facades\Input;
class WebCommon
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
        // Log::notice($request->getRequestUri());
        // dd(session('user'));
        // 验证token
        if (session('user')) {
            session(['url'=>'/']);
            $response = $next($request);
            return $response;
        }else{
          // dd(1);
          // 判断来路域名
            // $info = Input::url();
            $info = $request->getRequestUri();
            // dd(explode(env('HTTP_HOST'), $info));
            if (explode(env('HTTP_HOST'), $info)['0']) {
                $jump = explode(env('HTTP_HOST'), $info)['0'];
            }else{
                $jump = '/';
            }

            session(['url'=>$jump]);
            return redirect('/');
        }
    }
}