<?php


namespace App\Repositories\Wechat\Base;


use App\Models\Wechat\WechatEmpowerInfo;
use App\Models\Wechat\WechatUserInfo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

/**
 * 微信公众号用户相关数据更新
 * Class HandleData
 * @package App\Repositories\Wechat\Base
 */
trait HandleData
{

    // 初始公众号的用户useropenid
    public function initWechatUserOpenid()
    {
        $list = WechatEmpowerInfo::query()->select(['id','authorizer_refresh_token','auth_appid'])->where(['is_get_user' => 0, 'is_power' => 1, 'verify_type_info' => 0])->get();
        return $list;
    }

    // 获取已经授权过的公众号
    public function getIsAuthPower(int $is_power=1)
    {
        return WechatEmpowerInfo::query()->select(['id','authorizer_refresh_token','auth_appid'])->where(['is_power' => $is_power])->get();
    }
    // 获取还没更新的数据
    public function getUserInfo()
    {
        $getUserInfo = DB::connection('admin')->table('wechat_user_openid as wu')->leftJoin('wechat_empower_info as we', function ($q){
            $q->on('wu.wid','=','we.id');
        })->select(['wu.*','we.auth_appid','we.authorizer_refresh_token'])->where('wu.is_get_user',0)->first();

        return $getUserInfo;
    }

    // 获取用户和微信的appid
    public function getUserAndWechatInfo($user_id)
    {
        $data = DB::connection('admin')->table('wechat_user_info as wu')->leftJoin('wechat_empower_info as we', function ($q){
            $q->on('wu.wid','=','we.id');
        })->select(['wu.id', 'wu.openid','we.auth_appid','wu.nickname','we.authorizer_refresh_token'])->where(['wu.id' => $user_id, 'subscribe' => '1'])->first();

        return $data;
    }

    // 插入用户基本信息
    public function insertWechatUserInfo(array $data)
    {
        if ($data == [])
            return;

        WechatUserInfo::query()->insert($data);
    }
     // 插入用户基本信息
    public function updateWechatUserInfo(array $data)
    {
        if ($data == [])
            return;

        foreach ($data as $item){
            DB::connection('admin')->table('wechat_user_info')->updateOrInsert(['openid' => $item['openid']], $item);
        }
    }
    public function deleteWechatUserInfo($openid)
    {
        DB::connection('admin')->table('wechat_user_info')->where('openid', $openid)->delete();
    }
    // 插入公众号永久素材
    public function insertWechatMaterialInfo(array $data)
    {
        if ($data == [])
            return;

        DB::connection('admin')->table('batchget_material_info')->insert($data);
    }

    // 公众号更改为可更新状态或已完成更新状态
    public function updateWechatUser($wid, int $is_get_user = 1)
    {
        $query =  WechatEmpowerInfo::query()->find($wid);
        $query->is_get_user = $is_get_user;
        $query->save();
    }
    // 更新粉丝openid临时表
    public function updateIsGetUser($id)
    {
        DB::connection('admin')->table('wechat_user_openid')->where('id', $id)->update(['is_get_user' => '1']);
    }
    // 获取该公众号的粉丝数量
    public function getWechatUserCount($wid)
    {
        return DB::connection('admin')->table('wechat_user_info')->where(['wid' => $wid, 'subscribe' => '1'])->count();
    }

    // 检测已经取关的用户
    public function checkCancelFans(array $openids, $wid)
    {
        $cancelFans = WechatUserInfo::query()->where(['wid' => $wid, 'subscribe' => '1'])->select('openid')->get()->pluck('openid');
        $list = $cancelFans->diff($openids);

        foreach ($list as $item){
            WechatUserInfo::query()->where('openid', $item)->update(['subscribe' => '0']);
        }
    }
    // 检测已关注的用户
    public function checkFollowFans(array $openids, $wid)
    {
        $followFans = WechatUserInfo::query()->where(['wid' => $wid, 'subscribe' => '1'])->select('openid')->get()->pluck('openid');
        $list = collect($openids)->diff($followFans);
        foreach ($list as $item){
            WechatUserInfo::query()->updateOrInsert(['openid' => $item],['subscribe' => '1']);
        }
    }

    // 格式化用户基础信息数据
    public function formatUserInfoList(array $user_info_list, string $wechat_id)
    {
        $item =  [];
        foreach ($user_info_list as $k => $v) {
            $item[$k]['subscribe'] = $v['subscribe'];
            $item[$k]['openid'] = $v['openid'];
            $item[$k]['nickname'] = $v['nickname'] ?? null;
            $item[$k]['sex'] = $v['sex'] ?? null;
            $item[$k]['language'] = $v['language'] ?? null;
            $item[$k]['city'] = $v['city'] ?? null;
            $item[$k]['province'] = $v['province'] ?? null;
            $item[$k]['country'] = $v['country'] ?? null;
            $item[$k]['headimgurl'] = $v['headimgurl'] ?? null;
            $item[$k]['subscribe_time'] = $v['subscribe_time'] ?? 0;
            $item[$k]['remark'] = $v['remark'] ?? null;
            $item[$k]['groupid'] = $v['groupid'] ?? null;
            $item[$k]['tagid_list'] = json_encode($v['tagid_list']) ?? null;
            $item[$k]['subscribe_scene'] = $v['subscribe_scene'] ?? null;
            $item[$k]['qr_scene'] = $v['qr_scene'] ?? null;
            $item[$k]['qr_scene_str'] = $v['qr_scene_str'] ?? null;
            $item[$k]['wid'] = $wechat_id;
        }
        return $item;
    }

    // 格式化素材信息列表
    public function formatMaterialInfo(array $material_info_list,int $wid = null)
    {
        $data = [];
        foreach ($material_info_list as $item){
            $list = [];
            $media_id = $item['media_id'];
            $content = $item['content']['news_item'];
            $create_time = $item['content']['create_time'];
            $update_time = $item['content']['update_time'];
            foreach ($content as $key => $value){
                $list['title'] = $value['title'];
                $list['author'] = $value['author'];
                $list['digest'] = $value['digest'];
                $list['content'] = $value['content'];
                $list['content_source_url'] = $value['content_source_url'];
                $list['thumb_media_id'] = $value['thumb_media_id'];
                $list['show_cover_pic'] = $value['show_cover_pic'];
                $list['url'] = $value['url'];
                $list['thumb_url'] = $value['thumb_url'];
                $list['need_open_comment'] = $value['need_open_comment'];
                $list['only_fans_can_comment'] = $value['only_fans_can_comment'];
                $list['wid'] = $wid;
                $list['order_rule'] = $key;
                $list['media_id'] = $media_id;
                $list['create_time'] = $create_time;
                $list['update_time'] = $update_time;
                $data[] = $list;
            }
        }
        return $data;
    }
    // 获取当前公众号的信息
    public function getCurrentInfo(){
        $wid = Auth::user()->last_use_wechat_id ?? null;
        $data = DB::connection('admin')->table('wechat_empower_info')->where(['id' => $wid, 'is_power' => 1])->first();

        return $data;
    }
}