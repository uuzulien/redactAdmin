<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class InsertNoticeInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'insert_notice_info';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '写入消息通知';

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
        $check_now = Carbon::today();

        $check_time = Carbon::today()->addMonth();

        $title = '【系统消息】公众号年审即将到期';

        $unexpired = DB::connection('admin')->table('_log_wechat_empower_info as a')->leftJoin('wechat_empower_info as b', function ($join) {
            $join->on('a.wid','=','b.id');
        })->where('a.status', 2)->where('a.verify_date', '>', $check_now)->where('a.verify_date', '<', $check_time)
            ->orderByDesc('a.verify_date')->select(['a.id','a.wid','b.user_id','a.verify_date','b.nick_name'])->get();

        foreach ($unexpired as $k => $val){
            $data[$k]['title'] = $title . '(' . Carbon::parse($val->verify_date)->format('m.d') . ')';
            $data[$k]['content'] = '认证到期时间 >>> ' . $val->verify_date;
            $data[$k]['wid'] = $val->wid;
            $data[$k]['user_id'] = $val->user_id;
            DB::connection('admin')->table('_log_wechat_empower_info')->where('id', $val->id)->update(['status' => 0]);
        }
        DB::connection('admin')->table('notice_log')->insert($data);
        unset($data);

        $title = '【系统消息】公众号年审已过期';


        $expired = DB::connection('admin')->table('_log_wechat_empower_info as a')->leftJoin('wechat_empower_info as b', function ($join) {
            $join->on('a.wid','=','b.id');
        })->where('a.status', 2)->where('a.verify_date', '<=', $check_now)->orderByDesc('a.verify_date')->select(['a.id','a.wid','b.user_id','a.verify_date','b.nick_name'])->get();

        foreach ($expired as $k => $val){
            $data[$k]['title'] = $title . '(' . Carbon::parse($val->verify_date)->format('m.d') . ')';
            $data[$k]['content'] = '认证过期时间 >>> ' . $val->verify_date;
            $data[$k]['wid'] = $val->wid;
            $data[$k]['user_id'] = $val->user_id;
            $data[$k]['color'] = 'red';

            DB::connection('admin')->table('_log_wechat_empower_info')->where('id', $val->id)->update(['status' => 0]);

        }
        DB::connection('admin')->table('notice_log')->insert($data);

    }
}
