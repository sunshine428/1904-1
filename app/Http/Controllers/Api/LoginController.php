<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Index\Conmmon;
use Illuminate\Http\Request;
use App\Models\LoginModel;

class LoginController extends Controller
{

    public function login(){
        return view('index.index_login');
    }
    public function logins(Request $request){
//        var_dump($request->post());
        exit;
    }
    public function loginDo(Request $request){
        $username=$request->input('username');
        $password=md5($request->input('password'));
        var_dump($username);
        var_dump($password);
        $num=$request->input('num');
        $data=LoginModel::where(['username'=>$username,'password'=>$password])->first();
        if(!$data){
            return json_encode(['ret'=>201,'msg'=>'你已经输错1次，再错误2次后账号将被锁定']);
        }else{
            return json_encode(['ret'=>200,'msg'=>'登录成功']);
        }

    }
}
