<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginModel extends Model
{
    //指定表名  因laravel框架默认表名比模型名多一 个s
    protected $table="login";
    //指定主键id
    protected $primaryKey="user_id";
    public $timestamps=false;
    protected $guarded=[];

    /**
     * 获取数据库里存储的登录用户的sessionId
     */
    public static function getSessionId(){
        $adminInfo=session('adminInfo');
        $user_id=$adminInfo->user_id;
        $sessionId=Self::where(['user_id'=>$user_id])->value('sessionId');
        return $sessionId;
    }
    public static function getLoginTime(){
        $adminInfo=session('adminInfo');
        $user_id=$adminInfo->user_id;
        $login_time=Self::where(['user_id'=>$user_id])->value('login_time');
        return $login_time;
    }
    public static function updateLoginTime(){
        $adminInfo=session('adminInfo');
        $user_id=$adminInfo->user_id;
        $login_time=Self::where(['user_id'=>$user_id])->update(['login_time'=>time()+10]);
        return $login_time;
    }
}
