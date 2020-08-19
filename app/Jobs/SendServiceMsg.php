<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Repositories\Wechat\Func\WechatSendBase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SendServiceMsg implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $item;

    public $timeout = 120;

    public $tries = 5;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($val)
    {
        $this->item = $val;
//        $this->onQueue('serviceMsg');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->sendServiceNotice();
    }


    // 发送客服消息
    public function sendServiceNotice()
    {
        $item = $this->item;

        $content = get_object_vars(json_decode($item->dataInfo));

        if (array_key_exists('text', $content)){
            $content = (new WechatSendBase())->customerService($item->temp_text, $item);
        }
        if (array_key_exists('top-item', $content)){
            $content = (new WechatSendBase())->customerService($content['top-item'], $item, 'NewsItem');
        }
        DB::connection('admin')->table('service_message_info')->where('id', $item->id)->decrement('plan_to_send');

        if ($content['errcode'] == 0){
            DB::connection('admin')->table('service_message_info')->where('id', $item->id)->increment('send_num');
        }

        $data = DB::connection('admin')->table('service_message_info')->find($item->id);
        if ($data->status == 1 and $data->plan_to_send == 0){
            $status = $data->send_num > 0 ? 2 : 3;
            DB::connection('admin')->table('service_message_info')->where('id', $item->id)->update(['status' => $status]);
        }

    }

    public function failed(\Throwable $e)
    {
        Log::info($e->getMessage());
    }
}
