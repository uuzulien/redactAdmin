<?php

namespace App\Models\Notification;

use App\Models\AdminUsers;
use App\Repositories\Auth\GroupPermission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Wechat\WechatEmpowerInfo;

class ServiceMessageInfo extends Model
{
    protected $connection = 'admin';
    protected $table = 'service_message_info';


    public function getUserNameAttribute()
    {
        return $this->hasOneAdminUser->name ?? '-';
    }

    public function getTaskNameAttribute()
    {
        return [1 => '活动', 2 => '书名', 3 => '签到', 4 => '继续阅读'][$this->task_type] ?? '异常类型';
    }


    public function getSendStatusAttribute()
    {
        switch ($this->status){
            case 0:
                $html = '<span class="label label-info">待发送</span>';
                break;
            case 1:
                $html = '<span class="label label-warning">发送中</span>';
                break;
            case 2:
                $html = '<span class="label label-success">发送成功</span>';
                break;
            case 3:
                $html = '<span class="label label-danger">发送失败</span>';
                break;
            default:
                $html = '<span class="label label-info">未知</span>';
                break;
        }
        return $html;
    }
    public function getWechatNameAttribute()
    {
        return $this->hasOneWechatEmpowerInfo->nick_name ?? '-';
    }

    public function getTopItemAttribute()
    {
        return get_object_vars(json_decode($this->dataInfo))['top-item'] ?? null;
    }

    public function getCustomMsgData($data)
    {
        $list = self::query()->leftJoin('wechat_link_type', function ($join){
            $join->on('service_message_info.task_type','=','wechat_link_type.id');
        })->leftJoin(config('database.connections.public.database').'.novel_info', function ($join){
            $join->on('service_message_info.book_id','=','novel_info.id');
        })->with('hasOneAdminUser:id,name')->where('wid', $data['wid'])->select(['service_message_info.*','wechat_link_type.name as task_name','novel_info.name as book_name'])->orderByDesc('created_at')->paginate(15);

        return compact('list');
    }

    public function getCustomMsgEdit($data)
    {
        $id = $data['id'] ?? null;
        $wid = $data['wid'] ?? null;

        $userGroup = AdminUsers::query()->group()->get()->pluck('id');

        $item = self::query()->find($id);

        $titleInfo = DB::connection('admin')->table('wechat_title_info')->whereIn('user_id', $userGroup)->where('status', 1)->select(['id','type as categoryid','title'])->get();

        $link = DB::connection('admin')->table('wechat_link_info as a')->leftJoin('wechat_empower_info as b', function ($join){
            $join->on('a.wid','=','b.id');
        })->rightJoin(config('database.connections.public.database').'.novel_info as c', function ($join){
            $join->on('a.book_id','=','c.id')->on('c.pid','=','b.pid');
        })->where('a.status',1)->where('wid', $wid)->select(['a.*','c.name','c.pid'])->get()->sortByDesc('created_at');

        // 链接
        $res['active_link'] = $link->where('typeid',1);
        $res['novel_link'] = $link->where('typeid',2)->where('msgtype', 1);
        $res['sign_link'] = $link->where('typeid',3);
        $res['history_link'] = $link->where('typeid',4);
        // 标题
        $res['active_title'] = $titleInfo->where('type',1)->where('msg_type',1);
        $res['novel_title'] = $titleInfo->where('type',2)->where('msg_type',1);
        $res['sign_title'] = $titleInfo->where('type',3)->where('msg_type',1);
        $res['history_title'] = $titleInfo->where('type',4)->where('msg_type',1);

        return compact('item', 'res', 'titleInfo', 'wid');
    }

    public function hasOneAdminUser()
    {
        return $this->hasOne(AdminUsers::class, 'id', 'user_id');
    }

    public function hasOneWechatEmpowerInfo()
    {
        return $this->hasOne(WechatEmpowerInfo::class, 'id', 'wid');
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
