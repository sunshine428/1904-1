<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LoginModel;
use Illuminate\Support\Facades\Session;
use App\Tools\Wechat;
use Illuminate\Support\Facades\Cache;

class LoginController extends Controller
{
    public function login(){
       return view('index.index_login');
    }
    public function login_do(Request $request){

        $username=$request->input('username');
        $password=$request->input('password');
        if(empty($username)||empty($password)){
            return back()->withErrors(['用户名或密码不能为空']);
        }
        //检测用户名正确
        $adminInfo=LoginModel::where(['username'=>$username])->first();

        if(!$adminInfo){
            return back()->withErrors(['用户名有误或密码有误']);
        }
//        //判断当前账号是否绑定
//        if(!empty($adminInfo->error_time)&& time()<$adminInfo->error_time ){
//            //报错
//            exit;
//        }
        //检测密码
        if($adminInfo->password !=$password){
//            if(!empty($adminInfo->error_time) || time() < $adminInfo->error_time){
//                return back()->withErrors(['账号已被锁定']);
//            }
            $adminInfo->error_num=$adminInfo->error_num+1;
            if($adminInfo->error_num >= 3){
                $adminInfo->error_num=0;
                $adminInfo->error_time=time()+300;
            }
            $adminInfo->save();
            return back()->withErrors(['密码错误']);
        }
        $adminInfo->sessionId=Session::getId();
        $adminInfo->login_time=time();
        $adminInfo->save();

       session(['adminInfo'=>$adminInfo]);
//       $a=session('adminInfo');
//       dd($a);
    }
    public function wechat(){
        $status=md5(uniqid());
        echo $status;
        $ticket=Wechat::creatempQrcode($status);
        return view('index.wechat_login',['ticket'=>$ticket,'status'=>$status]);
    }
    public function event(){
        $info =file_get_contents("php://input");
//        file_put_contents("week.txt",$info);
        $arr_obj=simplexml_load_string($info,"SimpleXMLElement",LIBXML_NOCDATA);
        //判断用户是否关注
        if($arr_obj->MsgType == 'event' && $arr_obj->Event=='subscribe'){
            //存取用户  二维码关系
            $openid=(string)$arr_obj->FromUserName;
            $EventKey=(string)$arr_obj->EventKey;
            //得到二维码标识
            $status=ltrim($EventKey,'qrscene_');
            if($status){
                //用户扫码登录的程序流程
                Cache::put($status,$openid,20);
                //回复文本消息
                echo Wechat::responseType('正在扫码登录中，请稍后',$arr_obj);
            }
        }
        //用户关注过  触发SCAN事件
        if($arr_obj->MsgType == 'event' && $arr_obj->Event=='SCAN'){
            //存取用户  二维码关系
            $openid=(string)$arr_obj->FromUserName;
            $status=(string)$arr_obj->EventKey;
            //得到二维码标识
            $status=ltrim($status,'qrscene_');
            if($status){
                //用户扫码登录的程序流程
                Cache::put($status,$openid,20);
                //回复文本消息
                $msg="正在扫码登录中，请稍后";
                echo Wechat::responseType($msg,$arr_obj);
            }
        }
    }

    public function checkWechatLogin(Request $request){
        $status=$request->input('status');
        $openid=Cache::get($status);
        if(!$openid){

            return json_encode(['ret'=>0,'msg'=>'未扫描']);
        }
        return json_encode(['ret'=>1,'msg'=>'扫描成功']);
    }
    public function index(){

        $appId = "101353491";  //应用账号id
        $appSecret = 'df4e46ba7da52f787c6e3336d30526e4'; //应用账号密码
        $redirect_uri = "http://www.iwebshop.com/index.php";//跳转到qq服务器 显示登录
    }
}
