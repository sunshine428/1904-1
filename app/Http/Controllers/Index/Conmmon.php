<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Conmmon extends Controller
{
    public $key='1904api';
    public $iv='1904190419041904';
    /** 加密 */
    protected function AesEncrypt($data){
        if( is_array($data)){
            $data=json_encode($data);//转json
        }
//        $key='1904api';
//        $iv='1904190419041904';
        $encrypt=openssl_encrypt(
            $data,
            'AES-256-CBC',
            $this->key,
            1,
            $this->iv
        );
        return base64_encode($encrypt);
    }
    /** 解密 */
    protected function AesDecrypt($encrypt){
        $decrypt=openssl_decrypt(
            base64_decode($encrypt),
            'AES-256-CBC',
            $this->key,
            1,
            $this->iv
        );
        return json_decode($decrypt,true);//转数组

    }
    /**
     * 客户端需要把appid和appkey传递到服务器进行验证
     */
     public function getAppidAndKey(){
        return [
            'app_id'=>'1904-1',
            'app_key'=>'1904_1password'
        ];
     }
     public function _createSign($data,$app_key){
         //把参数按照key进行排序
         ksort($data);
         //把参数转换为json串
         $json_str= json_encode($data);
         //把转成的json串 再拼接上第一步发的app_key
      return  md5($json_str . 'app_key=' .  $app_key);
    }

    /**
     * @param $api_url
     * @param array $data  参数约束
     * @param $is_post
     */
    protected function curlPost($api_url , array $data , $is_post=1){
        $ch=curl_init();
      $app_safe=  $this->getAppidAndKey();
      $data['app_id']=$app_safe['app_id'];
      #客户端添加时间戳和随机数 ，防止重放攻击
        $data['rand']=rand(100000,999999);
        $data['time']=time();

      #生成客户端的签名
//       $data['sign']= $this->_createSign($data,$app_safe['app_key']);
       $all_data=[
           'data'=>$this->AesEncrypt($data),
           'sign'=>$this->_createSign($data,$app_safe['app_key']),
       ];
        var_dump($all_data);
        if($is_post){
            curl_setopt($ch,CURLOPT_POST,1);
            curl_setopt($ch,CURLOPT_POSTFIELDS,$all_data);
        }else{
            $api_url=$api_url. '?' . http_build_query($data);
        }
        curl_setopt($ch,CURLOPT_URL,$api_url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        $data=curl_exec($ch);
        curl_close($ch);
        return $data;
    }
    //register接口调用
    function get_register(){
        // $data=request()->all();
        $data=[
            'user_name'=>'hky',
            'appid'=>'1904id',
            'secret'=>'1904secret'
        ];
        $url="http://api.practice1.com/api/register";
        $res=$this->curlpost($url,$data);
        print_r($res);
    }
    /**
     * 获取appid和secret
     */
    function get_IdSecret(){
        $data=[
            '1904id'=>'1904secret',
        ];
        return $data;
    }

}
