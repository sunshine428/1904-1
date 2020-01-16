<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::any('index', 'Admin\AdminLoginController@index');
//Route::any('index/login', 'Index\IndexLoginController@login');
//Route::get('/', function () {
//    return view('welcome');
//});

Route::any('/ym/login', 'Admin\LoginController@login');
Route::any('/ym/wechat', 'Admin\LoginController@wechat');
Route::any('/ym/event', 'Admin\LoginController@event');
Route::any('/ym/checkWechatLogin', 'Admin\LoginController@checkWechatLogin');
Route::any('/ym/index', 'Admin\LoginController@index');

Route::any('index', 'Admin\AdminLoginController@index');
Route::domain('admin.liujinyue.com')->group(function () {

    Route::get('/', function () {
        return view('api/wechat/wechat_debug');
    });

    Route::any('/ym/login_do', 'Admin\LoginController@login_do');
});

Route::domain('api.liujinyue.com')->namespace('Api')->middleware('apis')->group(function () {
    Route::any('/api/login', 'LoginController@login');
    Route::any('/api/loginDo', 'LoginController@loginDo');
    Route::any('/login', 'LoginController@logins');
    //周考
    Route::post('api/get_IdSecret',"RegisterController@get_IdSecret");   //获取权限
    Route::post('api/register',"RegisterController@register");   //注册
});



Route::domain('index.liujinyue.com')->namespace('Index')->group(function () {
    Route::any('/index/test', 'IndexLoginController@test');
    //周考
    Route::get('index/get_register',"IndexController@get_register");
    Route::any('/login', 'IndexLoginController@logins');
    Route::any('/aa', 'IndexLoginController@aa');


});
//
//Route::domain('wechat.liujinyue.com')->group(function(){
//
//
//});







