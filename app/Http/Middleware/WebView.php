<?php

namespace App\Http\Middleware;

use Closure;
use App\Model\User;
class WebView
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
        // Log::notice($request->getRequestUri());
        if (session('user')) {
            return $next($request);
        }else{
            $user = User::where('user_uuid',$request->input('uuid'))->first();
            if (!$user) {
                echo "<script>
                 var os;
                    if (navigator.userAgent.indexOf('Android') > -1 || navigator.userAgent.indexOf('Linux') > -1) {
                        android.switchButton();
                    } else if (navigator.userAgent.indexOf('iPhone') > -1) {
                        window.webkit.messageHandlers.switchButton.postMessage('1')
                    } else {
                        alert('暂不支持当前手机的操作系统!');
                    }
                </script>";
            }else{
                session(['user'=>$user]);
                return $next($request);
            }
        }
    }
}
