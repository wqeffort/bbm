<?php

namespace App\Http\Middleware;

use Closure;

class ApiCommon
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
        if (empty($data['token'])) {
            dd('token缺失');
        }else{
            // dd(env('TOKEN','localhost'));
            // dd($data['token']);
            if ($data['token'] == env('TOKEN','localhost')) {
                $result = $next($request);
            }else{
                dd('token错误');
            }
        }
        return $result;
    }
}