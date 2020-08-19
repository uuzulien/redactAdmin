<?php

namespace App\Models\Wechat;

use Illuminate\Database\Eloquent\Model;

class WechatTicket extends Model
{
    public $connection = 'admin';
    public $table = 'wechat_ticket';
}
