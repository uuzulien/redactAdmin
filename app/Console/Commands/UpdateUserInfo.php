<?php

namespace App\Console\Commands;

use App\Repositories\Wechat\Func\WechatFunBase;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class UpdateUserInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update_user_info';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '更新微信粉丝基础信息';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        ini_set ("memory_limit","-1");

        (new WechatFunBase())->updateSubscribeAndOpenid(); // 更新用户基本信息
    }
}
