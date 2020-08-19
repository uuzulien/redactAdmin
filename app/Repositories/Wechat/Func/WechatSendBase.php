<?php


namespace App\Repositories\Wechat\Func;


use App\Repositories\Wechat\Base\HandleData;
use App\Repositories\Wechat\Base\HandleRequest;
use EasyWeChat\Kernel\Messages\News;
use EasyWeChat\Kernel\Messages\NewsItem;
use EasyWeChat\Kernel\Messages\Raw;
use Illuminate\Support\Facades\DB;
use EasyWeChat\Kernel\Messages\Text;

class WechatSendBase extends HandleRequest
{
    use HandleData;

    // 发送测试客服消息
    public function customerService($template, $user_id, $type='text')
    {
        $users = is_object($user_id) ? $user_id : $this->getUserAndWechatInfo($user_id);
        if (!$users)
            return ['errcode' => 40001, 'errmsg' => '已取消关注'];

        $this->appid = $users->auth_appid;
        $this->token = $users->authorizer_refresh_token;

        if ($type == 'text'){
            $content = str_replace("{wx_name}", $users->nickname, $template);//替换内容
            $text = new Text($content);
        }

        if ($type == 'NewsItem'){
            $title = str_replace("{wx_name}", $users->nickname, $template->title);

            $content = [
                'title'       => $title,
                'description' => $template->content,
                'url'         => $template->linkurl,
                'image'       => $template->picurl,
            ];
            $text = new News(array(new NewsItem($content)));
        }

        $result = $this->make()->sendCustomerService($text, $users->openid);

        return $result;
    }

    public function groupSend()
    {
        // 客服消息发送
        $info = DB::connection('admin')->table('wechat_empower_info')->find(4);
        $users = DB::connection('admin')->table('wechat_user_info')->find(1803);
        $officialAccount = $this->openPlatform->officialAccount($info->auth_appid, $info->authorizer_refresh_token);

        $data = [
            "touser" => $users->openid,
            "msgtype" => "news",
            "news" => ["articles"=>[
                ["title"=>"Happy Day","description"=>"Is Really A Happy Day","url"=>"https://c105591.818tu.com/referrals/index/11046406","picurl"=>"PIC_URL"]
            ]
            ]
        ];
        $message = new Raw(json_encode($data));

        $temp = $officialAccount->customer_service->message($message)->send();
        dd($temp);

    }
    // 获取所有的粉丝
    public function userTotal()
    {
        // 客服消息发送
        $datas = DB::connection('admin')->table('wechat_empower_info')->where('created_at','>=','2020-06-23 00:00:45')->get();
        $totalDay = getDiffDateRange('2020-05-01');
        $diffChunk = collect($totalDay)->chunk(7);

        foreach ($datas as $info){
            $this->appid = $info->auth_appid;
            $this->token = $info->authorizer_refresh_token;

            foreach ($diffChunk as $k =>$v){
                $this->formatData($v->first(), $v->last(), $info->id);
            }

        }
    }

    // 每日更新用户关注数据
    public function userDayTotal($time_at)
    {
        $datas = DB::connection('admin')->table('wechat_empower_info')->where('verify_type_info',0)->get();

        foreach ($datas as $info){
            $this->appid = $info->auth_appid;
            $this->token = $info->authorizer_refresh_token;

            $this->formatData($time_at, $time_at, $info->id);
        }
    }

    public function formatData($k1, $k2, $wid)
    {
        $this->initOfficialAccount();
        $s1 = $this->make()->getUserSummary($k1, $k2);
        $s2 = $this->make()->getUserCumulate($k1, $k2);
        foreach ($s1 as $k => $v){
            DB::connection('public')->table('wechat_user_total')->updateOrInsert(['ref_date' => $v['ref_date'], 'user_source' => $v['user_source'], 'wid' => $wid], $v);
        }
        foreach ($s2 as $k => $v){
            DB::connection('public')->table('wechat_user_total')->updateOrInsert(['ref_date' => $v['ref_date'], 'user_source' => $v['user_source'], 'wid' => $wid], $v);
        }
    }

}