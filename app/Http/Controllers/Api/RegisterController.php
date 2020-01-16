<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Index\Common;
use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;
use DB;
class RegisterController extends Controller
{
    
    //注册接口
    function register(){
        $check_data=[
            '1904id'=>'1904secret',
            '1905id'=>'1905secret'
        ];
        $data=request()->all();
        if(!Redis::sAdd($data['time'].$data['rand'])){
            return json_encode([
                'code'=>303,
                'msg'=>'重放攻击',
                'data'=>[]
            ]);
        }

        $common=new Common();
        $data=$common->Aes_decrypt($data['data']);
        if(isset($check_data[$data['appid']])){ //appid存在
            if($check_data[$data['appid']]==$data['secret']){ //判断secret
                $res=DB::table('users')->insert(['user_name'=>$data['user_name'],'appid'=>$data['appid'],'secret'=>$data['secret']]);
                if($res){
                    return json_encode([
                        'code'=>200,
                        'msg'=>'注册成功',
                        'data'=>[]
                    ]);
                }
            }else{
                return json_encode([
                    'code'=>300,
                    'msg'=>'secret不正确',
                    'data'=>[]
                ]);
            }
        }else{
            return json_encode([
                'code'=>301,
                'msg'=>'appid不正确',
                'data'=>[]
            ]);
        }
        
    }
}
