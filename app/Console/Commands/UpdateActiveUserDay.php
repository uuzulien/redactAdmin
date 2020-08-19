<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class UpdateActiveUserDay extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update_active_user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '更新每日活跃用户数据';

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

        date_default_timezone_set("Asia/Shanghai");

        $now = Carbon::yesterday()->subDays(2)->timestamp;
        $yester = Carbon::yesterday()->subDays(3)->timestamp;

        $data = DB::connection('admin')->table('wechat_empower_info')->where(['verify_type_info' => 0, 'is_get_user' => 1, 'is_power' => '1'])->select('id')->get()->pluck('id')->toArray();

        foreach ($data as $wid){
            $query = DB::connection('admin')->table('wechat_user_info')->where(['wid' => $wid, 'subscribe' => '1'])->where('active_time', '>', 0)->get();

            $cumulate_num  = $query->count();
            $lose_num = $query->where('active_time', '>', $yester)->where('active_time','<', $now)->count(); // 昨日过期活跃数量
            $update_num = $query->where('active_time', '>', $yester)->where('active_time','>', $now)->count(); // 昨日总活跃用户数
            $active_num = $query->where('active_time', '>', $yester)->where('active_time','>', $now)->where('updated_at','>', Carbon::yesterday()->toDateString())->count(); // 昨日互动活跃用户数

            DB::connection('public')->table('active_user_total')->updateOrInsert(['wid' => $wid, 'ref_date' => Carbon::yesterday()->toDateString()],
                ['wid' => $wid, 'ref_date' => Carbon::yesterday()->toDateString(), 'today_active' => $update_num,
                    'lose_active' => $lose_num, 'new_active' => $active_num, 'cumulate_active' => $cumulate_num]);

            unset($query);
        }
    }
}
