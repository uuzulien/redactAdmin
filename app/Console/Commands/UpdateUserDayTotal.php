<?php

namespace App\Console\Commands;

use App\Repositories\Wechat\Func\WechatSendBase;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class UpdateUserDayTotal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update_userday_total';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '每日更新粉丝';

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
        date_default_timezone_set("Asia/Shanghai");
        $today = Carbon::yesterday()->toDateString();
        (new WechatSendBase())->userDayTotal($today);
//        (new WechatSendBase())->userTotal();
    }
}
