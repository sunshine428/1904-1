<?php

namespace App\Http\Middleware;

use Closure;

class ApiMiddleware
{
    public $key='1904api';
    public $iv='1904190419041904';
    public $app_maps=[
            '1904-1'=>'1904_1password',
            '1904-2'=>"1904_2password",
        ];
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $data=$request->post('data');
        //解密数据
        $decrypt_data=$this->AesDecrypt($data);
        //验证客户端的签名
        $check=$this->checkSign($decrypt_data);
        if($check['status'] != 200){
           return response($check);
        }else{
            return $next($request);
        }
    }

    /**
     * 验证签名
     * @param $decrypt_data
     * @return array
     */
    private function checkSign($decrypt_data){
        $client_sign=request()->post('sign');
        ksort($decrypt_data);
        #判断appid是否存在
        if(isset($this->app_maps[$decrypt_data['app_id']])){
            $json=json_encode($decrypt_data).'app_key='. $this->app_maps[$decrypt_data['app_id']];
            if($client_sign == md5($json)){
                return [
                    'status'=>200,
                    'msg'=>'success',
                    'data'=>md5($json),
                ];
            }else{
                return [
                    'status'=>9999,
                    'msg'=>'报错',
                    'data'=>[],
                ];
            }

        }else{
            return [
                'status'=>9999,
                'msg'=>'check sign fail',
                'data'=>[]
            ];
        }

    }

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
}
