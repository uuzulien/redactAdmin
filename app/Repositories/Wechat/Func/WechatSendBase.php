<?php


namespace App\Repositories\Wechat\Func;


use App\Repositories\Wechat\Base\HandleData;
use App\Repositories\Wechat\Base\HandleRequest;
use EasyWeChat\Kernel\Messages\News;
use EasyWeChat\Kernel\Messages\NewsItem;
use EasyWeChat\Kernel\Messages\Raw;
use EasyWeChat\Kernel\Messages\Article;
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

    // 发送高级群发测试
    public function imgtextServiceTest($list, $user_id, $type)
    {
        $wechat = $this->getUserAndWechatInfo($user_id);
        if (!$wechat)
            return ['errcode' => 40001, 'errmsg' => '已取消关注'];

        $this->appid = $wechat->auth_appid;
        $this->token = $wechat->authorizer_refresh_token;

        $queryImgtextInfo = DB::connection('admin')->table('wechat_imgtext_info')->get();

        $queryImgInfo = DB::connection('admin')->table('material_image as a')->leftJoin('_log_material_wechat as b', function ($join) {
            $join->on('a.img_num','=','b.img_num');
        })->select(['a.*','b.wid','b.media_id','b.media_url'])->get();
        $wid = $list['wid'];
        $media_id = $list['media_id'] ?? null;
        $template = $list['sub_item'];
        $ArrData = [];

        if ($media_id){
            $result = $this->make()->sendImgtextService($media_id, $wechat->openid, $type);
            return $result;
        }

        if ($type == 'previewNew'){
            foreach ($template as $k => $v){
                $file = $queryImgInfo->where('wid', $wid)->firstWhere('img_href', $v->src);
                $query = $queryImgInfo->firstWhere('img_href', $v->src);
                $media_id = $file->media_id ?? null;

                if (!$media_id){
                    $result = $this->make()->uploadImage($query->img_path);
                    $media_id = $result['media_id'];
                    DB::table('_log_material_wechat')->insert([
                        'media_id' => $result['media_id'], 'media_url' => $result['url'], 'wid' => $wid, 'img_num' => $query->img_num
                    ]);
                }
                $data['thumb_media_id'] = $media_id;
                $data['author'] = '';
                $data['title'] = str_replace("{wx_name}", $wechat->nickname, $v->title);
                $data['content_source_url'] = $v->href;
                $data['content'] = $queryImgtextInfo->firstWhere('title', $v->title)->content;
                $data['digest'] = '';
                $data['show_cover'] = 0;
                $data['need_open_comment'] = 0;
                $data['only_fans_can_comment'] = 0;

                $article = new Article($data);
                $ArrData[$k] = $article;
            }
            $result = $this->make()->uploadArticle($ArrData);

            $text = $result['media_id'];
        }

        $result = $this->make()->sendImgtextService($text, $wechat->openid, $type);

        return $result;
    }


    // 发送高级群发内容
    public function imgtextService(string $media_id, $wid,string $type)
    {
        $wechat = DB::table('wechat_empower_info')->find($wid);

        $this->appid = $wechat->auth_appid;
        $this->token = $wechat->authorizer_refresh_token;

        $result = $this->make()->sendImgtextService($media_id, $type);

        return $result;
    }

    // 上传高级群发素材
    public function imgTextUpload($list, $wid, $type='text')
    {
        $users = DB::table('wechat_empower_info')->find($wid);

        $this->appid = $users->auth_appid;
        $this->token = $users->authorizer_refresh_token;

        $queryImgtextInfo = DB::connection('admin')->table('wechat_imgtext_info')->get();

        $queryImgInfo = DB::connection('admin')->table('material_image as a')->leftJoin('_log_material_wechat as b', function ($join) {
            $join->on('a.img_num','=','b.img_num');
        })->select(['a.*','b.wid','b.media_id','b.media_url'])->get();
        $template = $list['sub_item'];
        $ArrData = [];

        if ($type == 'NewsItem'){
            foreach ($template as $k => $v){
                $file = $queryImgInfo->where('wid', $wid)->firstWhere('img_href', $v->src);
                $query = $queryImgInfo->firstWhere('img_href', $v->src);
                $media_id = $file->media_id ?? null;

                if (!$media_id){
                    $result = $this->make()->uploadImage($query->img_path);
                    $media_id = $result['media_id'];
                    DB::table('_log_material_wechat')->insert([
                        'media_id' => $result['media_id'], 'media_url' => $result['url'], 'wid' => $wid, 'img_num' => $query->img_num
                    ]);
                }
                $data['thumb_media_id'] = $media_id;
                $data['author'] = '';
                $data['title'] = $v->title;
                $data['content_source_url'] = $v->href;
                $data['content'] = $queryImgtextInfo->firstWhere('title', $v->title)->content;
                $data['digest'] = '';
                $data['show_cover'] = 0;
                $data['need_open_comment'] = 0;
                $data['only_fans_can_comment'] = 0;

                $article = new Article($data);
                $ArrData[$k] = $article;
            }
            $result = $this->make()->uploadArticle($ArrData);

            $text = $result['media_id'];
        }

        return $text;
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
    // 将本地文件上传至微信公众号
    public function uploadImageToWechat($id, $url)
    {
        $users = DB::table('wechat_empower_info')->find($id);
        $this->appid = $users->auth_appid;
        $this->token = $users->authorizer_refresh_token;
        $root_path = '/www/wwwroot/redactAdmin/public/';
        $result = $this->make()->uploadImage($url,$root_path);
        return $result['url'] ?? null;
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