<?php

namespace App\Http\Middleware;

use Closure;

use App\Models\LoginModel;

class CheckUser
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
        //判断是否登录页
        $adminInfo=session('adminInfo');

        if(!$adminInfo){
            return redirect("ym/login");
        }
        //防止多终端登录
        if(LoginModel::getSessionId() != $adminInfo->sessionId){
            echo 1111;
            session()->forget('adminInfo');
            return redirect("/ym/login")->withErrors("账号已经在其他地方登录");
        }
        //十分钟未操作，则提示过期时间
        if(time()>LoginModel::getLoginTime()+10){
            session()->forget('adminInfo');
            return redirect("/ym/login")->withErrors(['您超过20分钟未操作，请重新操作']);
        }
        //一直操作  则更新过期时间
        LoginModel::updateLoginTime();
        return $next($request);
    }
}
