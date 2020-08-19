<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class UpdateTotalAndActive extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update_total_active';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '更新总粉和活跃粉丝的数量';

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
        $now = Carbon::now()->subDays(2)->timestamp;

        $data = DB::connection('admin')->table('wechat_empower_info')->where(['verify_type_info' => 0, 'is_get_user' => 1, 'is_power' => '1'])->select('id')->get()->pluck('id')->toArray();

        foreach ($data as $wid){
            $query = DB::connection('admin')->table('wechat_user_info')->where(['wid' => $wid, 'subscribe' => '1'])->where('subscribe_time', '>', 0)->get();
            $total = $query->count();
            $active = $query->where('active_time','>', $now)->count();

            DB::connection('admin')->table('wechat_empower_info')->where('id', $wid)->update(['user_total' => $total, 'active_user_num' => $active]);
            unset($query);
        }
    }
}
