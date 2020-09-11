<?php

namespace App\Models\Novel;

use App\Models\AdminUsers;
use App\Repositories\Auth\GroupPermission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AccountManage extends Model
{
    protected $connection = 'admin';
    protected $table = 'account_config';

    public function hasBelongsToPlatformManage()
    {
        return $this->belongsTo(PlatformManage::class, 'pid', 'id');
    }

    public function hasOneUser()
    {
        return $this->hasOne(AdminUsers::class, 'id', 'user_id');
    }

    public function getPlatformAttribute()
    {
        return $this->hasBelongsToPlatformManage->platform_name ?? '-';
    }

    public function getOperatorAttribute()
    {
        return $this->hasOneUser->name ?? '-';
    }

    public function showNovelData($data)
    {
        $status = $data['status'] ?? null;
        $pid = $data['pt_type'] ?? null;
        $pdr = $data['pdr'] ?? null;
        $pfname = $data['pf_nick'] ?? null;
        $group_id = $data['group'] ?? null;

        $userGroup = AdminUsers::query()->group($group_id)->get()->pluck('id');

        list($groups, $user_all) = AdminUsers::getGroupInfo();

        $list = self::group($group_id)->when($pdr, function ($query) use($pdr) {
            $query->where('user_id', $pdr);
        })->when($pfname, function ($q) use ($pfname) {
            $q->where('platform_nick', 'like', "%$pfname%");
        })->when($status, function ($q) use ($status) {
            $q->where('status', $status);
        })->when($pid, function ($q) use ($pid) {
            $q->where('pid', $pid);
        })->orderByDesc('status')->orderBy('created_at')->paginate(15);

        $platforms = PlatformManage::query()->where('type', 1)->get(['id', 'platform_name'])->pluck('platform_name', 'id');

        $nick_name = DB::connection('admin')->table('wechat_empower_info as a')->leftJoin('account_config as b', function ($join){
            $join->on('a.nick_name','=','b.platform_nick');
        })->whereIn('a.user_id', $userGroup)->whereNull('b.platform_nick')->select(['a.nick_name','a.id'])->get()->pluck('nick_name','id')->filter();

        return compact('list', 'platforms', 'groups', 'user_all', 'nick_name');
    }

    public function scopeAuthManage($query, $uid = null)
    {
        $groups = (new GroupPermission())->getUserIdItem($uid);
        return $query->whereIn('user_id', $groups);
    }

    // 权限组分类
    public function scopeGroup($query, $group_id=null)
    {
        $groups = (new GroupPermission())->groupUserItem($group_id);

        return $query->whereIn('user_id', $groups);
    }
}

