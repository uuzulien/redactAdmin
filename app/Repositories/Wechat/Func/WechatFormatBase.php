<?php


namespace App\Repositories\Wechat\Func;

use EasyWeChat\Kernel\Messages\Raw;

class WechatFormatBase
{
    // 客服消息
    public function service($msgtype, $touser, $content)
    {
        $datas = [
            "text" => ["touser" => $touser, "msgtype" => "text", "text" => ["content" => $content]], // 发送文本消息
            "image" => ["touser" => $touser, "msgtype" => "image", "image" => ["media_id" => $content]], // 发送图片消息
            "voice" => ["touser" => $touser, "msgtype" => "voice", "voice" => ["media_id" => $content]], // 发送语音消息
            "video" => ["touser" => $touser, "msgtype" => "video", "video" => $content], // 发送视频消息
            "music" => ["touser" => $touser, "msgtype" => "music", "music" => $content],// 发送音乐消息
            "news" => ["touser" => $touser, "msgtype" => "news", "news" => ["articles" => $content]],// 发送图文消息（点击跳转到外链）
            "mpnews" => ["touser" => $touser, "msgtype" => "mpnews", "mpnews" => ["media_id" => $content]],// 发送图文消息（点击跳转到图文消息页面）
            "msgmenu" => ["touser" => $touser, "msgtype" => "msgmenu", "msgmenu" => $content],// 发送菜单消息

        ];

        return new Raw($datas[$msgtype]);
    }
    // 群发接口
    public function groupSend()
    {

    }
}