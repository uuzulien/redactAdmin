<?php

namespace App\Console\Commands;

use App\Repositories\Wechat\Func\WechatSendBase;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GetWechatMessageStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get_msg_status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '查询高级群发消息的发送状态';

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
        $data = DB::table('imgtext_message_info')->where('status', 1)->select(['id','msg_id'])->get();
        if (!$data->count()){
            return;
        }

        // 更新状态为查询中
        $this->updateMessageStatus($data);

        foreach ($data as $item){
            $this->getImgTextStatus($item);
        }

    }

    public function updateMessageStatus($data)
    {
        foreach ($data as $item){
            DB::table('imgtext_message_info')->where('id', $item->id)->update(['status' => 4]);
        }
    }
    // 获取群发消息返回状态
    public function getImgTextStatus($data)
    {
        try{
            $res = (new WechatSendBase())->getMsgidStatus($data->msg_id);
            Log::info('定时群发消息返回结果：'.json_encode($res));

            switch($res['msg_status']){
                case 'SENDING' :
                    DB::table('imgtext_message_info')->where('id', $data->id)->update(['status' => 1]);
                    break;
                case 'SEND_SUCCESS' :
                    DB::table('imgtext_message_info')->where('id', $data->id)->update(['status' => 2]);
                    break;
                case 'SEND_FAIL':
                    DB::table('imgtext_message_info')->where('id', $data->id)->update(['status' => 3]);
                    break;
            }
        }catch(\Exception $e){
            Log::error('发送消息状态查询错误['.json_encode($data).']' . '['.$e->getMessage().']');
        }
    }
}
