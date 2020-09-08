<?php

namespace App\Models\Wechat;

use App\Models\AdminUsers;
use App\Models\Novel\PlatformManage;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\Auth\GroupPermission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WechatEmpowerInfo extends Model
{
    protected $fillable = ['auth_appid'];
    protected $appends = ['service_type','verify_type'];

    public $table = 'wechat_empower_info';
    public $connection = 'admin';

    public function getServiceTypeAttribute()
    {
        return $this->service_type_info == 2 ? '服务号' : '订阅号';
    }
    public function getVerifyTypeAttribute()
    {
        return $this->verify_type_info >= 0 ? '认证' : '未认证';
    }
    public function getUserNameAttribute()
    {
        return $this->hasOneAdminUser->name ?? '-';
    }

    public function getQrcodeAttribute()
    {
        $imageInfo = getimagesize($this->qrcode_url);
        $base64 = "" . chunk_split(base64_encode(file_get_contents($this->qrcode_url)));
        return 'data:' . $imageInfo['mime'] . ';base64,' . $base64;

    }
    // 公众号归属列表
    public function getWechatInfoList($data)
    {
        date_default_timezone_set("Asia/Shanghai");

        $pdr = $data['pdr'] ?? null;
        $pid = $data['pt_type'] ?? -1;
        $pfname = $data['pf_nick'] ?? null;
        $company = $data['company'] ?? null;
        $serial = $data['fens_order'] ?? null;
        $infos = $data['infos'] ?? null;
        $iscost = $data['iscost'] ?? 'all';
        $costid = $data['cost_id'] ?? -1;
        $group_id = $data['group'] ?? null;

        $wechatInfoList = [];


        $userGroup = AdminUsers::query()->group($group_id)->get()->pluck('id');

        list($groups, $user_all) = AdminUsers::getGroupInfo();

        $query = DB::connection('admin')->table('wechat_empower_info as a')->leftJoin('platform_config as b', function ($join){
            $join->on('a.pid','=','b.id');
        })->leftJoin('_log_wechat_empower_info as c', function ($join){
            $join->on('a.id','=','c.wid');
        })->leftJoin('admin_users as d', function ($join){
            $join->on('a.user_id','=','d.id');
        })->leftJoin('platform_config as ab', function ($join){
            $join->on('a.cost_id','=','ab.id');
        })->when($pdr, function ($q) use($pdr) {
            $q->where('a.user_id', $pdr);
        })->when($costid >= 0, function ($q) use($costid){
            $q->where('a.cost_id', $costid);
        })->when($pfname, function ($q) use($pfname) {
            $q->where('a.nick_name','like', "%$pfname%");
        })->when($pid >= 0, function ($q) use($pid) {
            $q->where('a.pid', $pid);
        })->when($company, function ($q) use($company){
            $q->where('a.principal_name','like', "%$company%");
        })->when($iscost != 'all', function ($q) use($iscost){
            $q->where('is_cost', $iscost);
        })->when($infos, function ($q) use($infos){
            if ($infos == 1){
                $q->whereNotNull('c.id');
            }elseif ($infos == 2){
                $q->whereNull('c.id');
            }
        })->whereIn('a.user_id', $userGroup)->select(['ab.platform_name as advert_name','b.platform_name','d.id','d.name as user_name','c.*','a.id','a.head_img','a.nick_name','a.alias',
            'a.principal_name','a.user_total','a.active_user_num','a.is_power','a.original','a.is_cost','a.cost_id','a.sex','service_type_info as service_type','verify_type_info as verify_type','c.updated_at'])
            ->when($serial, function ($q) use($serial) {
                $q->orderBy('user_total', $serial);
            });


        $list = $query->paginate(15);
        $userTree = AdminUsers::group()->select(['name','id'])->get();

        if ($userTree->count() > 1){
            $wechatInfoList = self::query()->group($group_id)->when($pdr, function ($q) use($pdr) {
                $q->where('user_id', $pdr);
            })->when($pid != -1, function ($query) use($pid) {
                $query->where('pid', $pid);
            })->select(['id','nick_name'])->get();
        }

        $platforms_info = PlatformManage::query()->select(['id', 'platform_name','type'])->get();

        $platforms = $platforms_info->where('type', 1)->pluck('platform_name','id');

        $advert = $platforms_info->where('type', 2)->pluck('platform_name','id');

        $origin = DB::connection('admin')->table('manage_account_config as a')->leftJoin('_log_manage_account_info as b', function ($join) {
            $join->on('a.id','=','b.vip_id');
        })->leftJoin('account_config as c', function ($join) {
            $join->on('b.sub_id','=','c.id');
        })->leftJoin('wechat_empower_info as d', function ($join){
            $join->on('c.platform_nick','=','d.nick_name');
        })->where('a.pid',3)->whereNotNull('a.origin')->select(['a.origin','d.id as wid'])->get()->flatten()->unique()->pluck('origin','wid');

        return compact('list','wechatInfoList', 'userTree', 'platforms', 'advert', 'origin','groups','user_all');
    }

    public function hasOneAdminUser()
    {
        return $this->hasOne(AdminUsers::class, 'id', 'user_id');
    }
    public function hasOnePlatformManage()
    {
        return $this->hasOne(PlatformManage::class, 'id', 'pid');
    }
    public function hasManyWechatUserInfo()
    {
        return $this->hasMany(WechatUserInfo::class, 'wid', 'id');
    }


    public function scopeGroup($query, $group_id=null)
    {
        $groups = (new GroupPermission())->groupUserItem($group_id);

        return $query->whereIn('user_id', $groups);
    }
}

