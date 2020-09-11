<?php

namespace App\Console\Commands;

use App\Repositories\Wechat\Func\WechatSendBase;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UploadImgtext extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'upload_imgtext_msg';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '上传高级群发消息';

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
        $data = DB::table('imgtext_message_info')->where('status', -1)->get();

        if (!$data->count()){
            return;
        }

        foreach ($data as $item){
            $this->imgTextSave($item);
        }

    }


    // 图文消息保存
    public function imgTextSave($data)
    {
        $send_type = get_object_vars(json_decode($data->dataInfo));

        if (array_key_exists('sub_item', $send_type)){
            $media_id = (new WechatSendBase())->imgTextUpload($send_type, $data->wid, 'NewsItem');
        }
        DB::table('imgtext_message_info')->where('id', $data->id)->update(['status' => 0, 'media_id' => $media_id]);
    }
    //    图文消息测试发送
    public function imgTextSendTest($data)
    {
        $send_type = get_object_vars(json_decode($data->dataInfo));
        $send_type['wid'] = $data->wid;
        $send_type['media_id'] = $data->media_id;

        if (array_key_exists('sub_item', $send_type)){
            $media_id = (new WechatSendBase())->imgtextServiceTest($send_type, 479966, 'previewNew');
        }
    }
}
