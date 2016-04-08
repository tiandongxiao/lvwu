<?php

Route::get('/', function () {
    return view('welcome');
});

/***************	Begin 用户认证系统自带控制器处理		**************/
Route::get('login', 'Auth\AuthController@getPhoneLogin');
Route::post('login', 'Auth\AuthController@postPhoneLogin');
Route::get('login/email', 'Auth\AuthController@getEmailLogin');
Route::post('login/email', 'Auth\AuthController@postEmailLogin');
Route::get('logout', 'Auth\AuthController@getLogout');

/***************	Begin 用户注册及密码重置	**************/
Route::get('chose', 'Auth\AuthController@getChoseRegRole');
Route::post('chose', 'Auth\AuthController@postChoseRegRole');
Route::get('register/{role}', 'Auth\AuthController@getPhoneRegister');
Route::post('register', 'Auth\AuthController@postPhoneRegister');
Route::get('reset', 'Auth\PasswordController@getPhoneReset');
Route::post('reset', 'Auth\PasswordController@postPhoneReset');
Route::get('reset/confirm', 'Auth\PasswordController@getPhoneResetConfirm');
Route::post('reset/confirmed', 'Auth\PasswordController@postPhoneResetConfirm');

Route::get('email/active/{token}','Auth\AuthController@getActiveEmail');
Route::get('email/bind/{token}','BindController@getBindEmailHandler');

//Route::get('reg/email', 'Auth\AuthController@getEmailRegister');
//Route::post('reg/email', 'Auth\AuthController@postEmailRegister');
//Route::get('reset/email', 'Auth\PasswordController@getEmail');
//Route::post('reset/email', 'Auth\PasswordController@postEmail');
//Route::get('reset/email/{token}', 'Auth\PasswordController@getEmailReset');
//Route::post('reset/email/confirmed', 'Auth\PasswordController@postEmailReset');

Route::group(['prefix' => 'bind'], function(){
    Route::get('chose','BindController@getChoseRole');
    Route::post('chose','BindController@postChoseRole');

    Route::get('select','BindController@getBindUser');
    Route::post('select','BindController@postBindUser');

    Route::get('exist','BindController@getBindExistUser');
    Route::post('exist','BindController@postBindExistUser');

    Route::get('new','BindController@getBindNewUser');
    Route::post('new','BindController@postBindNewUser');

    Route::get('email', 'BindController@getBindEmail');
    Route::post('email', 'BindController@postBindEmail');
});

Route::group(['prefix' => 'wx'], function() {
    Route::get('login','AuthWeChatController@wxLogin');
    Route::any('callback','AuthWeChatController@wxCallback');
    Route::get('check','AuthWeChatController@wxCheck');

    Route::get('bind','AuthWeChatController@wxBind');
    Route::get('unbind','AuthWeChatController@wxUnBind');
});


Route::group(['prefix' => 'tool'], function(){
    Route::post('cpt_check','ToolsController@captchaCheck');
    Route::get('cpt','ToolsController@getCaptcha');
    Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
});

Route::group(['prefix' => 'communicate'], function(){
    Route::get('phone_code','CommunicationController@sendPhoneCode');
    Route::post('message','CommunicationController@sendMessageByRequest');
});

Route::group(['prefix' => 'test'], function(){
    Route::get('put/{key}-{value}','TestController@putValue');
    Route::get('get/{key}','TestController@getValue');
    Route::get('uri','TestController@getUri');
});

Route::post('wxpay/callback', 'WxPayController@payCallback');
Route::get('pay', 'OrdersController@pay');




