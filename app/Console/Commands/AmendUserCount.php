<?php

namespace App\Console\Commands;

use App\Repositories\Wechat\Func\WechatFunBase;
use Illuminate\Console\Command;

class AmendUserCount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'amend_user_count';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '检测当前公众号的用户数量的变化，若有即进行更新';

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
        (new WechatFunBase())->checkUserCount();
    }
}
