<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\Wechat\Func\WechatSendBase;
use App\Repositories\Wechat\Base\HandleData;
use Illuminate\Filesystem\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redis;

class TestSend extends Command
{
    use HandleData;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test_send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $data = DB::table('wechat_empower_info as a')->leftJoin('admin_users as b', function ($join){
            $join->on('a.user_id','=','b.id');
        })->where('a.sex', 0)->whereIn('b.gid', [1,2])->select('a.id')->get();
        foreach ($data as $key => $val){
            DB::table('wechat_empower_info')->where('id', $val->id)->update(['sex' => 1]);
        }
        dd($data);
//        dd(Carbon::now()->addDays(3));
        $check_now = Carbon::today();

        $check_time = Carbon::today()->addMonth();

        $unexpired = DB::connection('admin')->table('_log_wechat_empower_info as a')->leftJoin('wechat_empower_info as b', function ($join) {
            $join->on('a.wid','=','b.id');
        })->where('a.verify_date', '>', $check_now)->where('a.verify_date', '<', $check_time)
            ->orderByDesc('a.verify_date')->select(['a.wid','b.user_id','a.verify_date'])->get();


        $expired = DB::connection('admin')->table('_log_wechat_empower_info as a')->leftJoin('wechat_empower_info as b', function ($join) {
            $join->on('a.wid','=','b.id');
        })->where('a.verify_date', '<=', $check_now)->orderByDesc('a.verify_date')->select(['a.wid','b.user_id','a.verify_date'])->get();
        dd($expired);
        dd($expired);

//        DB::table('_log_wechat_empower_info')->get()->map(function ($value) {
//            $date = str_replace("年","-",$value->verify_date);
//            $date = str_replace("月","-",$date);
//            $date = str_replace("日","",$date);
//            DB::table('_log_wechat_empower_info')->where('id', $value->id)->update(['verify_date' => $date]);
//        });
//        die;
//        dd(config('database.connections.toufang.database'));
        $list = DB::connection('admin')->table('service_message_info')->where('msgtype', 1)->whereNull('book_id')->where('task_type', 2)->get();
        $links = DB::connection('admin')->table('wechat_link_info')->get();

        foreach ($list as $value){
            $info = get_object_vars(json_decode($value->dataInfo));
            if (!empty($info['top-item']->title)){
                $status = DB::connection('admin')->table('service_message_info')->where('id', $value->id)->update(['title' => $info['top-item']->title]);
                echo $status;
            }
            $novel = $links->where('href', $info['top-item']->linkurl);
            if ($novel->count()){
                $status = DB::connection('admin')->table('service_message_info')->where('id', $value->id)->update(['book_id' => $novel->first()->book_id]);
                echo $status;

            }
//            dd($links->where('href', $info['top-item']->linkurl));
        }
        dd($list->count());
        date_default_timezone_set("Asia/Shanghai");
        ini_set ("memory_limit","-1");
        dd(Carbon::parse('2020-07-27 19:40:00')->addHour(1));
        $t1 =time();

//        $task = DB::connection('admin')->table('service_message_info as a')->leftJoin('wechat_user_info as b', function ($join){
//            $join->on('a.wid','=','b.wid');
//        })->leftJoin('wechat_empower_info as c', function ($join){
//            $join->on('a.wid','=','c.id');
//        })->leftJoin(config('database.connections.public.database').'.wechat_fens_detail as d', function ($join){
//            $join->on('b.openid','=','d.openid');
//        })->where(['a.status' => 0, 'subscribe' => 1])->where('b.active_time','>=', Carbon::now()->subDays(2)->timestamp)
//            ->where('send_time', '<', Carbon::now()->addMinutes(5*60))
//            ->whereRaw('a.sex = if(a.sex = -1,-1,b.sex)')
//            ->whereRaw('a.pay = if(a.pay = -1,-1,if(d.pay is null,0,d.pay))')
//            ->select(['a.dataInfo', 'a.id', 'b.openid','c.auth_appid','c.authorizer_refresh_token','b.nickname','a.temp_text', 'd.recharge_amount',
//                'a.totalmoney_from','a.totalmoney_to','a.stime','a.etime', DB::raw('FROM_UNIXTIME(b.subscribe_time,"%Y-%m-%d") as daytime')])->get()->groupBy('id');
//
//        $count = 0;
//        foreach($task as $key => $item){
//            foreach ($item as $k => $val){
//                $val = new \StdClass();
//                $count++;
////                dd($val);
////                dd(11);
//            }
//        }
//        dd($count);
//        dd(time()-$t1);

        $usage = memory_get_usage();

        // 筛选出等待推送的客服消息
        $data = DB::connection('admin')->table('service_message_info as a')->leftJoin('wechat_empower_info as b', function ($join){
            $join->on('a.wid','=','b.id');
        })->where('a.status', 0)->where('a.send_time','<',Carbon::now()->addMinutes(5*50))
            ->select(['a.dataInfo','a.id','a.temp_text','a.sex','a.pay','a.totalmoney_from','a.totalmoney_to','a.stime','a.etime','a.wid','b.auth_appid','b.authorizer_refresh_token'])->get();

        if (!$data->count()){
            return;
        }

        $wait_send = $data->pluck('wid')->unique();

        $wait_user = DB::connection('admin')->table('wechat_user_info as a')->leftJoin(config('database.connections.public.database').'.wechat_fens_detail as b', function ($join){
            $join->on('a.openid','=','b.openid');
        })->where('a.subscribe', 1)->whereIn('a.wid', $wait_send)->where('a.active_time', '>=', Carbon::now()->subDays(2)->timestamp)
            ->select(['a.openid','a.nickname','a.wid', DB::raw('FROM_UNIXTIME(a.subscribe_time,"%Y-%m-%d") as daytime'),'a.sex','b.pay','b.recharge_amount'])->get()->groupBy('wid');

        $this->updateMessageStatus($wait_user, $data); // 更新客服消息状态

        $count = 0;
        foreach ($wait_user as $key => $value){
            $message = $data->where('wid',$key);
            foreach ($message as $_m){
                foreach ($value as $val){
                    $money = $val->recharge_amount ?? 0;

                    if ($_m->sex > 0 && $_m->sex != $val->sex){
                        continue;
                    }
                    if ($_m->pay > 0 && $_m->pay != $val->pay){
                        continue;
                    }

                    $s1 = $_m->stime ? Carbon::parse($_m->stime)->diffInDays($val->daytime,false) : 1;
                    $s2 = $_m->etime ? Carbon::parse($val->daytime)->diffInDays($_m->etime,false): 1;

                    if ($s1 < 0 or $s2 < 0){
                        continue;
                    }

                    $users = new \StdClass();
                    $users->id = $_m->id;
                    $users->dataInfo = $_m->dataInfo;
                    $users->openid = $val->openid;
                    $users->auth_appid = $_m->auth_appid;
                    $users->authorizer_refresh_token = $_m->authorizer_refresh_token;
                    $users->nickname = $val->nickname;
                    $users->temp_text = $_m->temp_text;
                    if ($_m->totalmoney_from <= $money && $money >= $_m->totalmoney_to){
                        $count++;
                    }
                    unset($users);
                }
                unset($message);
            }
        }
        unset($wait_user);

        echo (memory_get_usage() - $usage)/1024/1024 . PHP_EOL;
        echo $count . PHP_EOL;
//        dd($wait_user);
        dd(time()-$t1);
    }

    public function updateServiceMessage($data)
    {
        foreach($data as $key => $item){
            DB::connection('admin')->table('service_message_info')->where('id', $key)->update(['status' => 1,'plan_to_send' => $item->count()]);
        }
    }

    public function waitSendQueue()
    {

    }

    public function isConditionalStatement($message, $condition)
    {
        $money = $condition->recharge_amount ?? 0;

        if ($message->sex > 0 && $message->sex != $condition->sex){
            return true;
        }
        if ($message->pay > 0 && $message->pay != $condition->pay){
            return true;
        }

        $s1 = $message->stime ? Carbon::parse($message->stime)->diffInDays($condition->daytime,false) : 1;
        $s2 = $message->etime ? Carbon::parse($condition->daytime)->diffInDays($message->etime,false): 1;

        if ($s1 < 0 or $s2 < 0){
            return true;
        }

        if ($message->totalmoney_from > $money || $money > $message->totalmoney_to){
            return true;
        }

        return false;
    }

    public function conditionFens($val)
    {
        $condition = [];
        $stime = $val->stime;
        $etime = $val->etime;
        $money_from = $val->totalmoney_from;
        $money_to = $val->totalmoney_to;

        if ($val->sex != -1)
            $condition['a.sex'] = $val->sex;

        if ($val->pay != -1)
            $condition['b.pay'] = $val->pay ? $val->pay : null;

        $data = DB::connection('admin')->table('wechat_user_info as a')->leftJoin(config('database.connections.public.database').'.wechat_fens_detail as b', function ($join){
            $join->on('a.openid','=','b.openid');
        })->leftJoin('wechat_empower_info as c', function ($join){
            $join->on('a.wid','=','c.id');
        })->leftJoin('service_message_info as d', function ($join){
            $join->on('a.wid','=','d.wid');
        })->where('a.subscribe', '1')
            ->where($condition)->where('a.active_time','>=', \Carbon\Carbon::now()->subDays(2)->timestamp)->where(['a.wid' => $val->wid])
            ->when($stime, function ($q) use($stime){
                $q->where('a.subscribe_time', '>=', Carbon::parse($stime)->timestamp);
            })->when($etime, function ($q) use($etime){
                $q->where('a.subscribe_time', '<=', Carbon::parse($etime)->timestamp);
            })->when($money_from, function ($q) use($money_from){
                $q->where('b.recharge_amount', '>=', $money_from);
            })->when($money_to, function ($q) use($money_to){
                $q->where('b.recharge_amount', '<=', $money_to);
            })->select(['d.dataInfo', 'd.id', 'a.openid','c.auth_appid','c.authorizer_refresh_token','a.nickname','d.temp_text'])->get();

        return $data;
    }

    public function updateMessageStatus($data, $info)
    {
        foreach($data as $key => $item){
            $message = $info->where('wid',$key)->pluck('id');
            foreach ($message as $id){
                $tem = $id;
//                DB::connection('admin')->table('service_message_info')->where('id', $id)->update(['status' => 1,'plan_to_send' => $item->count()]);
            }
            unset($message);
        }
    }
}
