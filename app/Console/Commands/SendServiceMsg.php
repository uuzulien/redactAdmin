<?php

namespace App\Console\Commands;

use App\Repositories\Wechat\Func\WechatSendBase;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class SendServiceMsg extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send_service_msg';

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

        $this->waitSendQueue(); // 新的逻辑关系
//        $this->waitSendQueueOld(); // 旧的逻辑关系

//        $content = '⏰@{wx_name}【签到啦】
//
//<a href="weixin://bizmsgmenu?msgmenucontent=签到&msgmenuid=1">戳我👉一键签到领取书币豪礼</a>';
//        $content = (new WechatSendBase())->customerService($content, 86341);die;

    }

    // 旧的更新客服消息
    public function updateServiceMessage($data)
    {
        foreach($data as $key => $item){
            DB::connection('admin')->table('service_message_info')->where('id', $key)->update(['status' => 1,'plan_to_send' => $item->count()]);
        }

        foreach($data as $key => $item){
            foreach ($item as $k => $val){
                $money = $val->recharge_amount ?? 0;
                $s1 = $val->stime ? Carbon::parse($val->stime)->diffInDays($val->daytime,false) : 1;
                $s2 = $val->etime ? Carbon::parse($val->daytime)->diffInDays($val->etime,false): 1;

                if ($s1 < 0 or $s2 < 0)
                    continue;

                if ($val->totalmoney_from <= $money && $money >= $val->totalmoney_to){
                    dispatch(new\App\Jobs\SendServiceMsg($val));
                }

            }
        }

    }

    /**
     * 客服消息等待发送至队列
     */
    public function waitSendQueue()
    {
        // 筛选出等待推送的客服消息
        $data = DB::connection('admin')->table('service_message_info as a')->leftJoin('wechat_empower_info as b', function ($join){
            $join->on('a.wid','=','b.id');
        })->where('a.status', 0)->where('a.send_time','<',Carbon::now()->addMinutes(5))->where('a.send_time','>', Carbon::now()->subHour(3))
            ->select(['a.dataInfo','a.id','a.temp_text','a.sex','a.pay','a.totalmoney_from','a.totalmoney_to','a.stime','a.etime','a.wid','b.auth_appid','b.authorizer_refresh_token'])->get();

        if (!$data->count()){
            return;
        }

        // 等待发送的公众号id
        $wait_send = $data->pluck('wid')->unique();

        // 取出待发消息的用户数据
        $wait_user = DB::connection('admin')->table('wechat_user_info as a')->leftJoin(config('database.connections.public.database').'.wechat_fens_detail as b', function ($join){
            $join->on('a.openid','=','b.openid');
        })->where('a.subscribe', 1)->whereIn('a.wid', $wait_send)->where('a.active_time', '>=', Carbon::now()->subDays(2)->timestamp)
            ->select(['a.openid','a.nickname','a.wid', DB::raw('FROM_UNIXTIME(a.subscribe_time,"%Y-%m-%d") as daytime'),'a.sex','b.pay','b.recharge_amount'])->get()->groupBy('wid');

        // 更新客服消息状态
        $this->updateMessageStatus($wait_user, $data);

        foreach ($wait_user as $key => $value){
            $message = $data->where('wid', $key);
            foreach ($message as $_m){
                foreach ($value as $val){
                    // 条件判断
                    $money = $val->recharge_amount ?? 0;

                    if ($_m->sex > 0 && $_m->sex != $val->sex){
                        DB::connection('admin')->table('service_message_info')->where('id', $_m->id)->decrement('plan_to_send');
                        continue;
                    }
                    if ($_m->pay > 0 && $_m->pay != $val->pay){
                        DB::connection('admin')->table('service_message_info')->where('id', $_m->id)->decrement('plan_to_send');
                        continue;
                    }

                    $s1 = $_m->stime ? Carbon::parse($_m->stime)->diffInDays($val->daytime,false) : 1;
                    $s2 = $_m->etime ? Carbon::parse($val->daytime)->diffInDays($_m->etime,false): 1;

                    if ($s1 < 0 or $s2 < 0){
                        DB::connection('admin')->table('service_message_info')->where('id', $_m->id)->decrement('plan_to_send');
                        continue;
                    }
                    // 准备发送前的基本信息
                    $users = new \StdClass();
                    $users->id = $_m->id;
                    $users->dataInfo = $_m->dataInfo;
                    $users->openid = $val->openid;
                    $users->auth_appid = $_m->auth_appid;
                    $users->authorizer_refresh_token = $_m->authorizer_refresh_token;
                    $users->nickname = $val->nickname;
                    $users->temp_text = $_m->temp_text;
                    if ($_m->totalmoney_from <= $money && $money >= $_m->totalmoney_to){
                        dispatch(new\App\Jobs\SendServiceMsg($users));
                    } else {
                        DB::connection('admin')->table('service_message_info')->where('id', $_m->id)->decrement('plan_to_send');
                    }
                    unset($users);
                }
                unset($message);
            }
        }
        unset($wait_user);
    }

    // 旧的发送逻辑
    public function waitSendQueueOld()
    {
        $task = DB::connection('admin')->table('service_message_info as a')->leftJoin('wechat_user_info as b', function ($join){
            $join->on('a.wid','=','b.wid');
        })->leftJoin('wechat_empower_info as c', function ($join){
            $join->on('a.wid','=','c.id');
        })->leftJoin(config('database.connections.public.database').'.wechat_fens_detail as d', function ($join){
            $join->on('b.openid','=','d.openid');
        })->where(['a.status' => 0, 'subscribe' => 1])->where('b.active_time','>=', Carbon::now()->subDays(2)->timestamp)
            ->where('send_time', '<', Carbon::now()->addMinutes(5))
            ->whereRaw('a.sex = if(a.sex = -1,-1,b.sex)')
            ->whereRaw('a.pay = if(a.pay = -1,-1,if(d.pay is null,0,d.pay))')
            ->select(['a.dataInfo', 'a.id', 'b.openid','c.auth_appid','c.authorizer_refresh_token','b.nickname','a.temp_text', 'd.recharge_amount',
                'a.totalmoney_from','a.totalmoney_to','a.stime','a.etime', DB::raw('FROM_UNIXTIME(b.subscribe_time,"%Y-%m-%d") as daytime')])->get()->groupBy('id');

        if ($task)
            $this->updateServiceMessage($task); // 更新客服消息状态
    }
    // 更新状态发送中
    public function updateMessageStatus($data, $info)
    {
        foreach($data as $key => $item){
            $message = $info->where('wid',$key)->pluck('id');
            foreach ($message as $id){
                DB::connection('admin')->table('service_message_info')->where('id', $id)->update(['status' => 1,'plan_to_send' => $item->count()]);
            }
            unset($message);
        }
    }
}
