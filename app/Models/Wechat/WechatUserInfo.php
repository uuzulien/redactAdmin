<?php

namespace App\Models\Wechat;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class WechatUserInfo extends Model
{
    public $table = 'wechat_user_info';
    public $connection = 'admin';

    public function getFensList($datas)
    {
        $username = $datas['nick'] ?? null;

        $wid = Auth::user()->last_use_wechat_id ?? null;

        $query = self::query()->when($username, function ($q) use($username) {$q->where('nickname','like',"%$username%" );})
            ->whereNotNull('subscribe_time')->where('wid', $wid);

        $list = $query->paginate(15);

        return compact('list');
    }
}
