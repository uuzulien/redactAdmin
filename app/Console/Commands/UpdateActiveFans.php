<?php

namespace App\Console\Commands;

use App\Repositories\Wechat\Func\WechatFunBase;
use Illuminate\Console\Command;

class UpdateActiveFans extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update_active_fans';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '更新活跃粉丝的数据';

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

        (new WechatFunBase())->activeFensInfo(); // 更新活跃粉丝数
    }
}
