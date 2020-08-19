<?php
/**
 * Created by PhpStorm.
 * User: 36390
 * Date: 2020/3/22
 * Time: 1:21
 */


// 消息与事件接收URL
Route::get('wechat_notify', 'Wechat\OpenWeixinController@userAuthCallback')->name('wechat.auth.callback');

Route::any('vv/test', 'Wechat\OpenWeixinController@test');

// 授权事件接收URL
Route::any('wechat_notice/empower', 'Wechat\OpenWeixinController@index');
// 消息与事件接收URL
// 通过该URL接收公众号或小程序消息和事件推送，该参数按规则填写（需包含/$APPID$，如www.abc.com/$APPID$/callback），实际接收消息时$APPID$将被替换为公众号或小程序AppId。
Route::any('wechat_notice/{appid}/callback', 'Wechat\OpenWeixinController@msgNotify');


