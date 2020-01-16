<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexLoginController extends Conmmon
{
    public function login(){
        return view('index.index_login');
    }
    public function test(){
        $data_str=str_repeat('0123456789',15);
        $i=0;
        $all='';
        while ($sub_str=substr($data_str,$i,117)){
            openssl_public_encrypt(
            $sub_str,//117
            $encrypt_data,
            file_get_contents(public_path('/public.key')),
            OPENSSL_ZERO_PADDING
            );
            $all .=$encrypt_data;
            $i+=117;
        }

        $encrypt_data=base64_encode($all);
        echo $encrypt_data;
        echo "<hr/>";
        //解密
        $i=0;
        $all='';
        $base64_decode=base64_decode($encrypt_data);
        while($sub_str=substr($base64_decode ,$i,128)){
//            echo $sub_str;die;
            openssl_private_decrypt(
                $sub_str,//117
                $decrypt,
                file_get_contents(public_path().'/private.key'),
                OPENSSL_PKCS1_PADDING
            );
            $all .=$decrypt;
            $i +=128;

        }
            echo $all;

//openssl_public_encrypt(
//    '012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456',//117
//    $encrypt_data,
//    file_get_contents(public_path().'/public.key'),
//    OPENSSL_ZERO_PADDING
//    );
//var_dump($encrypt_data);exit;


        /**
        $arr=[
            'username'=>'root',
            'password'=>'root',
        ];
//        var_dump(openssl_get_cipher_methods());
        //接口加密
//        var_dump(OPENSSL_ZERO_PADDING);

        $encrypt=$this->AesEncrypt($arr);
        echo $encrypt;
        echo '<hr/>';
        $decrypt=$this->AesDecrypt($encrypt);
        echo '<pre/>';
        print_r($decrypt);
//        var_dump($encrypt_data);
         */
    }
    public function logins(){
        $login_data=[
            'username'=>'root',
            'password'=>'root'
        ];
        $login_api_url='http://api.liujinyue.com/login';
        $api_result= $this->curlPost($login_api_url,$login_data);
        print_r($api_result);exit;
    }
    public function aa(){
//        echo phpinfo();EXIT;
//        setcookie('username','liuyunxiao');
//        var_dump($_COOKIE['username']);
        /**
         * 第一次访问：未定义的变量username
         * 第二次访问：输出名字
         */
        session_start();
        $_SESSION['user_name']='zhangsan';
        var_dump($_SESSION);
    }
}
