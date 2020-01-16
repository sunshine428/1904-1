<html>
<head>
    <title>@yield('title')</title>
</head>
<script src="{{asset('/js/jquery.min.js')}}"></script>
<body>
<div class="container">
    <div style="width: 1500px;height: 50px;background: #000;font-family:'楷体';">
        <p style="color:lightseagreen;float: left;padding-left: 80px">layui后台布局</p>
        <p style="color:#ffffff;padding-left: 130px;float: left">控制台</p>
        <p style="color:#ffffff;padding-left: 160px;float: left">商品管理</p>
        <p style="color:#ffffff;padding-left: 200px;float: left">用户</p>
    </div>
    <div style="width:300px;height: 500px;background: #141a1b;font-family:'楷体'">
       <div style="width:300px;color:#ffffff;padding-left: 20px;padding-top: 30px;">
            用户管理
           <span style="padding-left: 150px"><b class="cli"><a href="javascript:;" style="text-decoration: none;color:#ffffff;" class="make">︿</a></b></span>
       </div>
        <div id="aa">
        <ul style="color:#ffffff;list-style:none;" class="a">
               <li><a href="" style="text-decoration: none;color:#ffffff" >用户添加</a></li>
            <br>
               <li><a href="" style="text-decoration: none;color:#ffffff;" class="aa">用户列表</a></li>
        </ul>
        </div>
    </div>
</div>
</body>
</html>
<script>
    $(function(){
        $(document).on('click','.make',function(){
            var make=$("[class='make']").text();
            if(make == '︿'){
             $("#aa").text().hide();
             }
        })
    })
</script>

