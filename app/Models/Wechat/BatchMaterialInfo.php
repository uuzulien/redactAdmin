<?php

namespace App\Models\Wechat;

use App\Models\AdminUsers;
use App\Repositories\Auth\GroupPermission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class BatchMaterialInfo extends Model
{
    protected $appends = ['media_name'];

    public $table = 'batchget_material_info';
    public $connection = 'admin';

    public function getMediaNameAttribute()
    {
        return $this->hasOneMediaName->name ?? $this->media_id;
    }

    public function getMaterialInfo($data)
    {
        $wid = Auth::user()->last_use_wechat_id;

        $query = self::query()->where('wid', $wid)->with(['hasOneMediaName']);
        $list = $query->paginate(15);

        $media = $query->get()->toArray();

        return compact('list', 'media');
    }

    public function hasOneMediaName()
    {
        return $this->hasOne(WechatMediaId::class, 'media_id', 'media_id');

    }

    public function scopeAuthManage($query, $uid = null)
    {
        $groups = (new GroupPermission())->getUserIdItem($uid);
        return $query->whereIn('user_id', $groups);
    }
}
