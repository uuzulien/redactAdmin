<?php

namespace App\Console\Commands;

use App\Repositories\Wechat\Func\WechatSendBase;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SendImgtextService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send_imgtext_msg';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '发送客服消息';

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
        ini_set ("memory_limit","-1");

        $data = DB::table('imgtext_message_info')->where('status', 0)->where('send_time','<',
            Carbon::now()->addMinutes(5))->where('send_time','>', Carbon::now()->subHour(1))->get();

        if (!$data->count()){
            return;
        }
        // 更新群发消息状态为发送中
        $this->updateMessageStatus($data);

        foreach ($data as $item){
            $this->SendimgTextService($item);
        }
    }

    // 把图文消息提交至微信服务器
    public function SendimgTextService($data)
    {
        $media_id = $data->media_id;
        $wid = $data->wid;

        $res = (new WechatSendBase())->imgtextService($media_id, $wid, 'NewsItem');

        if($res['errcode'] == 0){
            $msgId = $res['msg_id'];
            $msgDataId = $res['msg_data_id'];

            DB::table('imgtext_message_info')->where('id', $data->id)->update(['msg_id' => $msgId , 'msg_data_id' => $msgDataId]);
        } else{
            DB::table('imgtext_message_info')->where('id', $data->id)->update(['status' => 0]);
        }

        Log::info('定时群发消息返回结果：'.json_encode($res));
    }

    public function updateMessageStatus($data)
    {
        foreach ($data as $item){
            DB::table('imgtext_message_info')->where('id', $item->id)->update(['status' => 1]);
        }
    }
}
