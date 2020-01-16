<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title> 登录</title>
    <meta name="keywords" content="">
    <meta name="description" content="">

    <link rel="shortcut icon" href="favicon.ico"> <link href="/hadmin/css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
    <link href="/hadmin/css/font-awesome.css?v=4.4.0" rel="stylesheet">

    <link href="/hadmin/css/animate.css" rel="stylesheet">
    <link href="/hadmin/css/style.css?v=4.1.0" rel="stylesheet">
    <!--[if lt IE 9]>
    <meta http-equiv="refresh" content="0;ie.html" />
    <![endif]-->
    <script>if(window.top !== window.self){ window.top.location = window.location;}</script>
</head>
<body class="gray-bg">

<div class="middle-box text-center loginscreen  animated fadeInDown">
    <div>
        <div>

            <h1 class="logo-name">h</h1>

        </div>
        <h3>欢迎使用 hAdmin</h3>
        <form class="m-t" role="form" action="{{url('ym/login_do')}}" method="post">
            <p style="color:midnightblue;font-family: '楷体'">微信号扫一扫登录
                免注册，方便快捷</p>
            <img src="{{$ticket}}" alt="" width="150px">
            <p class="text-muted text-center"> <a href="login.html#"><small>忘记密码了？</small></a> | <a href="register.html">注册一个新账号</a>
            </p>

        </form>
    </div>
</div>

<!-- 全局js -->
<script src="/hadmin/js/jquery.min.js?v=2.1.4"></script>
<script src="/hadmin/js/bootstrap.min.js?v=3.3.6"></script>
<script src="{{asset('js/jquery.min.js')}}"></script>

</body>


</html>
<script>
    alert(123);
    var status = "{{$status}}";
    //js轮询
    var t = setInterval("check();",2000);
    function check()
    {
        $.ajax({
            url:"{{url('ym/checkWechatLogin')}}",
            dataType:"json",
            data:{status:status},
            success:function(res){
                //返回提示
                if(res.ret == 1){
                    // //关闭定时器
                    clearInterval(t);
                    // //扫码登录成功
                    alert(res.msg);
                    location.href = "{{url('index')}}";
                }
            }
        })
    }
</script>
