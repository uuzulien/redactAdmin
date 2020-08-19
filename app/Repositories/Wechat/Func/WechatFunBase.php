<?php


namespace App\Repositories\Wechat\Func;

use App\Models\Wechat\WechatEmpowerInfo;
use App\Repositories\CacheKey\CacheKeyConstant;
use App\Repositories\Wechat\Base\HandleData;
use App\Repositories\Wechat\Base\HandleRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class WechatFunBase extends HandleRequest
{
    use HandleData;

    /**
     *
     */
    public function setUserOpenidForCache()
    {
        $datas = $this->initWechatUserOpenid();
        $key = CacheKeyConstant::WECHAT_OPENID_LIST;

        foreach ($datas as $wechatInfo){
            $this->appid = $wechatInfo->auth_appid;
            $this->token = $wechatInfo->authorizer_refresh_token;

            $list = $this->initOfficialAccount()->make()->getWechatUserOpenid(); // 获取微信用户openid
            $userOpenid = json_encode([$wechatInfo->auth_appid => $list]);
            Redis::lpush($key, $userOpenid);
        }
    }

    /**
     * 插入用户基本信息
     */
    public function insertUserInfo()
    {
        $userInfo = $this->getUserInfo();

        if (!$userInfo)
            return;

        $this->updateIsGetUser($userInfo->id); // 更新 openid 临时表

        $openid = collect(json_decode($userInfo->openids));

        $getOpenid = DB::connection('admin')->table('wechat_user_info')->where('wid', $userInfo->wid)->whereNotNull('subscribe_time')->get()->pluck('openid');

        $diffOpenid = $openid->diff($getOpenid);

        if (!$diffOpenid){
            return;
        }

        $this->appid = $userInfo->auth_appid;
        $this->token = $userInfo->authorizer_refresh_token;
        $wid = $userInfo->wid; // 微信公众号的id

        $openid_chunk = $diffOpenid->unique()->chunk(100)->toArray();
        foreach ($openid_chunk as $value) {
            sort($value); // 重头排序
            $user_info_list = $this->make()->getWechatUserInfo($value); // 拿到微信公众号用户信息
            if ($user_info_list) {
                $item = $this->formatUserInfoList($user_info_list, $wid); // 格式化用户信息
                $this->insertWechatUserInfo($item); // 插入公众号用户信息
            }
        }

    }

    /**
     * 更新redis中的用户基本信息
     */
    public function updateUserInfo()
    {
        $key = CacheKeyConstant::WECHAT_OPENID_LIST;
        $openid_num = Redis::lLen($key);

        for ($i = 0; $i <= $openid_num; $i++){
            $datas = Redis::rPop($key);
            if (empty($datas))
                continue;

            $wechatOpenid = json_decode($datas,true);

            $this->appid = array_key_first($wechatOpenid);
            $openid = collect(array_values($wechatOpenid)[0]);

            $wechatInfo = DB::connection('admin')->table('wechat_empower_info')->where('auth_appid', $this->appid)->first();
            $wid = $wechatInfo->id;
            $this->token = $wechatInfo->authorizer_refresh_token;


            $getOpenid = DB::connection('admin')->table('wechat_user_info')->where('wid', $wid)->select('openid')->get()->pluck('openid');

            $diffOpenid = $openid->diff($getOpenid);

            if (!$diffOpenid->count()){
                DB::connection('admin')->table('wechat_empower_info')->where('id', $wid)->update(['is_get_user' => 1]);
                continue;
            }

            $openid_chunk = $diffOpenid->unique()->chunk(100)->toArray();
            unset($diffOpenid);

            foreach ($openid_chunk as $value) {
                sort($value); // 重头排序
                $user_info_list = $this->initOfficialAccount()->make()->getWechatUserInfo($value); // 拿到微信公众号用户信息

                if ($user_info_list) {
                    $item = $this->formatUserInfoList($user_info_list, $wid); // 格式化用户信息
                    try{
                        DB::connection('admin')->table('wechat_user_info')->insert($item);// 插入公众号用户信息
                        DB::connection('admin')->table('wechat_empower_info')->where('id', $wid)->update(['is_get_user' => 1]);
                    }catch (\Exception $e){
                        $this->updateWechatUserInfo($item);   // 冲突时单个更新
                    }
                }
//                dispatch(new\App\Jobs\GetUserInfo($this->appid, $value));

            }
        }
    }

    /**
     * 更新活跃粉丝的基本互动时间
     */
    public function activeFensInfo()
    {
        $key = CacheKeyConstant::WECHAT_ACTIVE_FANS;  // 记录粉丝最后互动时间

        $count = Redis::HLEN($key);

        if (!$count)
            return;

        $activeOpenid = Redis::HGETALL($key);
        foreach ($activeOpenid as $openid => $item){
            $is_ture = Redis::	HEXISTS($key, $openid);
            if (!$is_ture)
                continue;
            list($active_time, $wid) = explode(':', $item);
            Redis::hDel($key, $openid);
            DB::connection('admin')->table('wechat_user_info')->updateOrInsert(['openid' => $openid],['subscribe' => 1,'active_time' => $active_time,'wid' => $wid]);
            echo $openid . ':' . $active_time . PHP_EOL;
        }
    }

    /**
     * 检查微信公众号的用户数
     */
    public function checkUserCount()
    {
        $datas = DB::connection('admin')->table('wechat_empower_info')->where(['is_get_user' => 0, 'is_power' => 1, 'verify_type_info' => 0])->select(['id','authorizer_refresh_token','auth_appid'])->get();
        try{
            foreach ($datas as $wechatInfo){
                $this->appid = $wechatInfo->auth_appid;
                $this->token = $wechatInfo->authorizer_refresh_token;

                $list = $this->initOfficialAccount()->make()->getWechatUserOpenid(); // 获取微信用户openid

                // 更新出已取关的用户
                $this->checkCancelFans($list, $wechatInfo->id);
                // 更新出已关注的用户
                $this->checkFollowFans($list, $wechatInfo->id);
                unset($list);
                DB::connection('admin')->table('wechat_empower_info')->where('id', $wechatInfo->id)->update(['is_get_user' => 1]);
            }
        }catch (\Exception $e){
            dd($e);
        }

    }

    /**
     * 获取永久素材列表
     */
    public function getMaterialList()
    {
        $wechatList = $this->getIsAuthPower();

        foreach ($wechatList as $item){
            $this->appid = $item->auth_appid;
            $this->token = $item->authorizer_refresh_token;

            $list = $this->initOfficialAccount()->make()->getWechatMaterialList(); // 获取公众号永久素材
            $data = $this->formatMaterialInfo($list, $item->id);
            $this->insertWechatMaterialInfo($data); // 插入永久素材
            dd('完毕');
        }
    }

    /**
     * 更新关注用户的基本信息，并更新公众号粉丝Openid
     */
    public function updateSubscribeAndOpenid()
    {
        $lock =  Redis::get('update_user_info');
        if ($lock){
            die;
        }
        Redis::set('update_user_info', 'fens');
        try{
            // 获取活跃用户，未更新基础信息的
            $data = DB::connection('admin')->table('wechat_user_info as a')->join('wechat_empower_info as b', function ($q){
                $q->on('a.wid','=','b.id')->where('a.subscribe' , '=' , '1')->where('subscribe_time',0)->where('b.verify_type_info', '=', 0);
            })->select(['a.id', 'a.wid', 'a.openid','b.auth_appid','b.authorizer_refresh_token'])->get()->groupBy('wid');

            // 更新已关注用户的基本信息
            foreach ($data as $item){
                $this->appid = $item->first()->auth_appid;
                $this->token = $item->first()->authorizer_refresh_token;
                $wid = $item->first()->wid; // 微信公众号的id
                $this->initOfficialAccount();

                $openid_chunk = $item->pluck('openid')->unique()->chunk(100)->toArray();
                foreach ($openid_chunk as $value) {
                    sort($value); // 重头排序
                    $user_info_list = $this->make()->getWechatUserInfo($value); // 拿到微信公众号用户信息
                    if ($user_info_list) {
                        $item = $this->formatUserInfoList($user_info_list, $wid); // 格式化用户信息
                        $this->updateWechatUserInfo($item);
                    }
                }
            }
        }catch (\Exception $e){
            Log::info($e);
        }

        Redis::del('update_user_info');
    }
    /**
     * 获取当前自定义菜单栏
     */
    public function getCurrentWechatInfo()
    {
        $wechatInfo = $this->getCurrentInfo();
        if (!$wechatInfo)
            return [];

        $this->appid = $wechatInfo->auth_appid;
        $this->token = $wechatInfo->authorizer_refresh_token;

        $list = $this->make()->getWecahtCustomInfo();

        $menus = $list['selfmenu_info']['button'] ?? [];

        $ArrInfo = $this->customFormatData($menus, $wechatInfo->id); // 格式化数据

        $data = [
          'wid' => $wechatInfo->id,
          'datas' => json_encode($ArrInfo),
        ];

        DB::connection('admin')->table('wechat_custom_info')->updateOrInsert(['wid' => $data['wid'], 'is_show' => 1], $data);

        return $ArrInfo;
    }

    /**
     * 保存并更新菜单栏
     */
    public function updateWechatInfo($data)
    {
        $wechatInfo = $this->getCurrentInfo();
        if (!$wechatInfo)
            return [];

        $this->appid = $wechatInfo->auth_appid;
        $this->token = $wechatInfo->authorizer_refresh_token;

        $ArrInfo = $this->inverseCustomFormatData($data); // 格式化数据

        $inverseArrInfo = $this->customFormatData($ArrInfo, $wechatInfo->id); // 格式化数据

        $data = [
            'wid' => $wechatInfo->id,
            'datas' => json_encode($inverseArrInfo),
        ];

        DB::connection('admin')->table('wechat_custom_info')->updateOrInsert(['wid' => $data['wid'], 'is_show' => 1], $data);

        $list = $this->make()->updateWecahtCustomInfo($ArrInfo);

        return $list;

    }

    /**
     * 反向格式化数据
     */

    public function inverseCustomFormatData($data, $wid = null, $is_show = 1)
    {
        $Arr = [];
        foreach ($data as $key => $value){
            $type = empty($value->keyword) ? 'view' : 'click';
            $Arr[$key]['name'] = empty($value->title) ? null : $value->title;
            $Arr[$key]['url'] = empty($value->url) ? null : $value->url;
            $Arr[$key]['key'] = empty($value->keyword) ? null : $value->keyword;
            $Arr[$key]['type'] = empty($value->class) ? $type : null ;

            $Arr[$key]['sub_button'] = empty($value->class) ? null : $this->inverseCustomFormatData($value->class);
            $Arr[$key] = array_filter($Arr[$key]);

        }
        return $Arr;
    }

    /**
     * 格式化公众号的数据
     */
    public function customFormatData($data, $wid = null, $is_show = 1)
    {
        $Arr = [];
        $data = $data['list'] ?? $data;
        foreach ($data as $key => $value){
            $Arr[$key]['title'] = $value['name'] ?? null;
            $Arr[$key]['keyword'] = $value['key'] ?? null;
            $Arr[$key]['url'] = $value['url'] ?? null;
            $Arr[$key]['is_show'] = $is_show;
            $Arr[$key]['wxid'] = $wid;
            $Arr[$key]['type'] = $value['type'] ?? null;

            if (array_key_exists('sub_button', $value)){
                $Arr[$key]['class'] = $value['sub_button'] ? $this->customFormatData($value['sub_button']) : [];
            }

        }
        return $Arr;
    }
}
