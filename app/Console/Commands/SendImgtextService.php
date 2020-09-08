<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

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

        foreach ($data as $item){
            $this->SendimgTextService($item);
        }
    }

    // 图文消息保存
    public function SendimgTextService($data)
    {

    }
}
