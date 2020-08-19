<?php

namespace App\Models\Novel;

use Illuminate\Database\Eloquent\Model;

class PlatformManage extends Model
{
    protected $connection = 'admin';
    protected $table = 'platform_config';
}
