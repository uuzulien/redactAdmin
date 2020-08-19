<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\Wechat\Func\WechatFunBase;

class InsertUserInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'insert_user_info';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '插入微信粉丝基础信息';

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
//        (new WechatFunBase())->insertUserInfo(); // 插入用户基本信息
        ini_set ("memory_limit","-1");

        (new WechatFunBase())->updateUserInfo(); // 插入用户基本信息
    }
}
