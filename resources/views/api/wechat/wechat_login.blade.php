@extends('layouts.layout')

@section('title', 'Api测试')

@section('content')
    <h3><b>微信公众平台登录</b></h3>
    <form action="{{url('api/wechat/wechat_login')}}" method="post">
        <div class="form-group">
            <label for="exampleInputEmail1">用户名</label>
            <input type="text" class="form-control" name="username" id="exampleInputEmail1" placeholder="请填写用户名">
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">密码</label>
            <input type="password" class="form-control" name="password" id="exampleInputPassword1" placeholder="请填写密码">
        </div>
        <button type="submit" class="btn btn-primary sub">提交</button>
    </form>
@endsection