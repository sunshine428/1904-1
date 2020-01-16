<?php
namespace App\Tools;

class Wechat
{
    /**
     * 回复文本消息
     * @param $msg
     * @param $arr_obj
     */
    const appId='wx11872538a7dc69ad';
    const appSecret ='652c55528c551a597b6d2d764b8df1c8';
    /** 回复文本 */
    public static function responseType($msg, $arr_obj)
    {
        $Type = "<xml>
                  <ToUserName><![CDATA[" . $arr_obj->FromUserName . "]]></ToUserName>
                  <FromUserName><![CDATA[" . $arr_obj->ToUserName . "]]></FromUserName>
                  <CreateTime>" . time() . "</CreateTime>
                  <MsgType><![CDATA[text]]></MsgType>
                  <Content><![CDATA[" . $msg . "]]></Content>
                </xml>";
        return $Type;
    }
    /**回复图片 */
    public static function responseImg($media_id,$arr_obj){
        $type= "<xml>
                      <ToUserName><![CDATA[".$arr_obj->FromUserName."]]></ToUserName>
                      <FromUserName><![CDATA[".$arr_obj->ToUserName."]]></FromUserName>
                      <CreateTime>".time()."</CreateTime>
                      <MsgType><![CDATA[image]]></MsgType>
                      <Image>
                        <MediaId><![CDATA[".$media_id."]]></MediaId>
                      </Image>
                    </xml>";
        return $type;
    }
    /** 回复语音 */
    public static function responseVoice($media_id,$arr_obj){
        $type= "<xml>
              <ToUserName><![CDATA[".$arr_obj->FromUserName."]]></ToUserName>
              <FromUserName><![CDATA[".$arr_obj->ToUserName."]]></FromUserName>
              <CreateTime>".time()."</CreateTime>
              <MsgType><![CDATA[voice]]></MsgType>
              <Voice>
                <MediaId><![CDATA[".$media_id."]]></MediaId>
              </Voice>
            </xml>";
        return $type;
    }
    /** 回复视频 */
    public static function responseVideo($media_id,$arr_obj){
        $type= "<xml>
              <ToUserName><![CDATA[".$arr_obj->FromUserName."]]></ToUserName>
              <FromUserName><![CDATA[".$arr_obj->ToUserName."]]></FromUserName>
              <CreateTime>".time()."</CreateTime>
              <MsgType><![CDATA[video]]></MsgType>
              <Video>
                <MediaId><![CDATA[".$media_id."]]></MediaId>
                <Title><![CDATA[title]]></Title>
                <Description><![CDATA[description]]></Description>
              </Video>
            </xml>";
        return $type;
    }
    public static function get_access_token(){
        $access_token=\Cache::get('access_token');
        if(empty($access_token)){
            $url=file_get_contents('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.Self::appId.'&secret='.Self::appSecret);
            $data=json_decode($url,1);
            $access_token=$data['access_token'];
            \Cache::put('access_token',$access_token,7200);
        }
        return $access_token;
    }
    /**
     * 获取用户基本信息
     */
    public static function get_wechat_user($openid){
        $data=self::get_access_token();
        $url='https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$data.'&openid='.$openid.'&lang=zh_CN';
        $re=file_get_contents($url);
        $result=json_decode($re,1);
        return $result;
    }
    //通过curl发送GET
    public static function curlget($url){
        //初始化： curl_init
        $curl = curl_init();
        //设置	curl_setopt
        curl_setopt($curl,CURLOPT_URL,$url);//请求地址
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);//返回数据格式
        //访问https网站 关闭ssl验证
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE); // 从证书中检查SSL加密算法是否存在
        //执行  curl_exec
        $result=curl_exec($curl);
        //关闭（释放）  curl_close
        curl_close($curl);
        return $result;
    }
    //听过curl发送post
    public static function curlpost($url,$data)
    {
        //初始化： curl_init
        $ch = curl_init();
        //设置	curl_setopt
        curl_setopt($ch, CURLOPT_URL, $url);  //请求地址
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); //返回数据格式
        curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        //访问https网站 关闭ssl验证
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // 对认证证书来源的检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); // 从证书中检查SSL加密算法是否存在
        //执行  curl_exec
        $result = curl_exec($ch);
        //关闭（释放）  curl_close
        curl_close($ch);
        return $result;
    }

    /**
     * 临时素材文件上传
     * @param $path
     * @return mixed
     */
    public static function getMediaTmp($path,$format){
        $access_token = self::get_access_token();
        $url = "https://api.weixin.qq.com/cgi-bin/media/upload?access_token={$access_token}&type={$format}";
        $data['media'] = new \CURLFile($path);
        $re = self::curlpost($url, $data);
        $re = json_decode($re, 1);
        $wechat_media_id = $re['media_id'];
        return $wechat_media_id;
    }
    /**
     * 网页授权获取用户openid
     * @return [type] [description]
     */
    public static function getOpenid()
    {
        //先去session里取openid
        $openid = session('openid');
        //var_dump($openid);die;
        if(!empty($openid)){
            return $openid;
        }
        //微信授权成功后 跳转咱们配置的地址 （回调地址）带一个code参数
        $code = request()->input('code');
        if(empty($code)){
            //没有授权 跳转到微信服务器进行授权
            $host = $_SERVER['HTTP_HOST'];  //域名
            $uri = $_SERVER['REQUEST_URI']; //路由参数
            $redirect_uri = urlencode("http://".$host.$uri);  // ?code=xx
            $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".self::appId."&redirect_uri={$redirect_uri}&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect";
            header("location:".$url);die;
        }else{
            //通过code换取网页授权access_token
            $url =  "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".self::appId."&secret=".self::appSecret."&code={$code}&grant_type=authorization_code";
            $data = file_get_contents($url);
            $data = json_decode($data,true);
            $openid = $data['openid'];
            //获取到openid之后  存储到session当中
            session(['openid'=>$openid]);
            return $openid;
            //如果是非静默授权 再通过openid  access_token获取用户信息
        }
    }
    /**
     * 网页授权获取用户基本信息
     * @return [type] [description]
     */
    public static function getOpenidByUserInfo()
    {
        //先去session里取openid
        $userInfo = session('userInfo');
        //var_dump($openid);die;
        if(!empty($userInfo)){
            return $userInfo;
        }
        //微信授权成功后 跳转咱们配置的地址 （回调地址）带一个code参数
        $code = request()->input('code');
        if(empty($code)){
            //没有授权 跳转到微信服务器进行授权
            $host = $_SERVER['HTTP_HOST'];  //域名
            $uri = $_SERVER['REQUEST_URI']; //路由参数
            $redirect_uri = urlencode("http://".$host.$uri);  // ?code=xx
            $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".self::appId."&redirect_uri={$redirect_uri}&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
            header("location:".$url);die;
        }else{
            //通过code换取网页授权access_token
            $url =  "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".self::appId."&secret=".self::appSecret."&code={$code}&grant_type=authorization_code";
            $data = file_get_contents($url);
            $data = json_decode($data,true);
            $openid = $data['openid'];
            $access_token = $data['access_token'];
            //获取到openid之后  存储到session当中
            //session(['openid'=>$openid]);
            //return $openid;
            //如果是非静默授权 再通过openid  access_token获取用户信息
            $url = "https://api.weixin.qq.com/sns/userinfo?access_token={$access_token}&openid={$openid}&lang=zh_CN";
            $userInfo = file_get_contents($url);
            $userInfo = json_decode($userInfo,true);
            //返回用户信息
            session(['userInfo'=>$userInfo]);
            return $userInfo;
        }
    }
    public static function creatempQrcode($status){
        $access_token=self::get_access_token();
        //创建参数二维码接口
        $url="https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token={$access_token}";
        //请求数据
        $postData=[
            "expire_seconds"=> 60,//二维码有效期
            "action_name"=>"QR_STR_SCENE",
            "action_info"=> [
                "scene"=>
                    ["scene_str"=>$status]
            ]
        ];
        $postData=json_encode($postData);
        //发请求
        //调接口 拿到票据 ticket
        $re=Wechat::curlpost($url,$postData);
        $re=json_decode($re,1);
        $ticket=$re['ticket'];
        if(isset($ticket)){
            //通过ticket 换取二维码
            $url="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket={$ticket}";
            return $url;
        }
        return false;
    }
}

