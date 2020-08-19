<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Repositories\Wechat\Func\WechatSendBase;

class SendTest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;
    protected $task;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->task = $data;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $content = '⏰@{wx_name}【签到啦】

<a href="weixin://bizmsgmenu?msgmenucontent=签到&msgmenuid=1">来戳我👉一键签到领取书币豪礼</a>';
        $content = (new WechatSendBase())->customerService($content, 734);
    }
}
