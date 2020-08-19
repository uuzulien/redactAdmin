<?php

namespace App\Http\Controllers\Analyze;

use App\Models\AdminUsers;
use App\Models\Novel\PlatformManage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Repositories\Comman\Base;

class PayOrderController extends Controller
{
    // 推广收入汇总
    public function index(Request $request)
    {
        $param = $request->all();

        $pdr = $param['pdr'] ?? null;
        $pid = $param['pt_type'] ?? null;
        $pfname = $param['pf_nick'] ?? null;
        $userid = $param['user_id'] ?? null;
        $group_id = $param['group'] ?? null;

        $userGroup = AdminUsers::query()->group($group_id)->get()->pluck('id');

        $userInfo = DB::connection('admin')->table('admin_users')->whereIn('id', $userGroup)->get();

        list($groups, $user_all) = AdminUsers::getGroupInfo();

        if ($userid){
            $userGroup = $userid;
        }

        $list = DB::connection('public')->table('pay_income as a')->leftJoin(config('database.connections.admin.database').'.account_config as b', function ($join){
            $join->on('a.wid','=','b.id');
        })->when($pdr, function ($q) use($pdr) {
            $q->where('b.user_id', $pdr);
        })->whereIn('b.user_id', $userGroup)->when($pid, function ($q) use($pid){$q->where('b.pid', $pid);})->when($pfname, function ($q) use($pfname){$q->where('b.platform_nick','like', "%$pfname%");})
            ->where('a.order_time','>=','2020-05-01')->select(['a.*', DB::raw('a.order_money * b.discount as order_money')])->orderBy('a.order_time','DESC')->get()
            ->groupBy('order_time')->map(function ($value){
                $daytime = $value->first()->order_time ?? null;
                $start_time = Carbon::parse($daytime)->startOfMonth()->toDateString();
                $end_time = Carbon::parse($daytime)->endOfMonth()->toDateString();
                $item['order_month'] = $start_time . '~' .$end_time;

                $item['order_money'] = $value->sum('order_money');
                $item['unpay_order'] = $value->where('status', 2)->sum('order_money');
                $item['order_num'] = $value->sum('order_num');
                $item['order_time'] = $value->first()->order_time;
                return $item;
            })->groupBy('order_month')->map(function ($value){
                $item['order_money'] = $value->sum('order_money');
                $item['order_num'] = $value->sum('order_num');
                $item['order_time'] = $value->first()['order_time'];
                return $item;
            });

        $total['order_money'] = $list->sum('order_money');
        $total['order_num'] = $list->sum('order_num');

        $platforms = PlatformManage::query()->where('type', 1)->get(['id','platform_name'])->pluck('platform_name','id');

        return view('analyze.income.list', compact('list', 'total', 'platforms', 'userInfo', 'groups', 'user_all'));
    }

    // 每月推广收入
    public function monthPayOrder(Request $request)
    {
        date_default_timezone_set("Asia/Shanghai");

        $param = $request->all();

        $pdr = $param['pdr'] ?? null;
        $pid = $param['pt_type'] ?? null;
        $pfname = $param['pf_nick'] ?? null;

        $start_at = $param['start_at'] ?? null;
        $end_at = $param['end_at'] ?? null;
        $userid = $param['user_id'] ?? null;
        $group_id = $param['group'] ?? null;

        $userGroup = AdminUsers::query()->group($group_id)->get()->pluck('id');

        $userInfo = DB::connection('admin')->table('admin_users')->whereIn('id', $userGroup)->get();

        if ($userid){
            $userGroup = $userid;
        }

        $list = DB::connection('public')->table('pay_income as a')->leftJoin(config('database.connections.admin.database').'.account_config as b', function ($join){
            $join->on('a.wid','=','b.id');
        })->when($pdr, function ($q) use($pdr) {
            $q->where('b.user_id', $pdr);
        })->when($pid, function ($q) use($pid){$q->where('b.pid', $pid);})->when($pfname, function ($q) use($pfname){$q->where('b.platform_nick','like', "%$pfname%");})
            ->whereIn('b.user_id', $userGroup)->where('order_time','>=',$start_at)
            ->where('a.order_time','<=',$end_at)->orderByDesc('a.order_time')->select(['a.*', DB::raw('a.order_money * b.discount as order_money')])->get()
            ->groupBy('order_time')->map(function ($value){

            $item['order_money'] = $value->sum('order_money');
            $item['unpay_order'] = $value->where('status', 2)->sum('order_money');
            $item['order_num'] = $value->sum('order_num');
            $item['order_time'] = $value->first()->order_time;
            return $item;
        });

        $rangeTime = $this->getMonth($start_at);
        $diffTime = collect($rangeTime)->diff($list->keys());

        foreach ($diffTime as $day){
            $list[$day] = ['order_money' => 0, 'unpay_order' => 0, 'order_num' => 0, 'order_time' => $day];
        }

        $list = $list->sortKeysDesc();
        $total['order_money'] = $list->sum('order_money');
        $total['order_num'] = $list->sum('order_num');

        return view('analyze.income.month', compact('list', 'total', 'userInfo'));
    }
    // 每日推广收入明细
    public function dayPayOrder(Request $request)
    {
        $param = $request->all();

        $pdr = $param['pdr'] ?? null;
        $pid = $param['pt_type'] ?? null;
        $pfname = $param['pf_nick'] ?? null;

        $time_at = $param['time_at'] ?? null;
        $userid = $param['user_id'] ?? null;
        $group_id = $param['group'] ?? null;

        if (Carbon::today() < $time_at){
            abort('404');
        }

        $userGroup = AdminUsers::query()->group($group_id)->get()->pluck('id');

        $userInfo = DB::connection('admin')->table('admin_users')->whereIn('id', $userGroup)->get();

        if ($userid){
            $userGroup = $userid;
        }

        $list = DB::connection('public')->table('pay_income as a')->leftJoin(config('database.connections.admin.database').'.account_config as b', function ($join){
            $join->on('a.wid','=','b.id');
        })->leftJoin(config('database.connections.admin.database').'.platform_config as c', function ($join){
            $join->on('b.pid','=','c.id');
        })->leftJoin(config('database.connections.admin.database').'.admin_users as d', function ($join){
            $join->on('b.user_id','=','d.id');
        })->when($pdr, function ($q) use($pdr) {
            $q->where('b.user_id', $pdr);
        })->when($pid, function ($q) use($pid){$q->where('b.pid', $pid);})->when($pfname, function ($q) use($pfname){$q->where('b.platform_nick','like', "%$pfname%");})
            ->whereIn('b.user_id', $userGroup)->select(['a.*', DB::raw('a.order_money * b.discount as order_money'),'b.platform_nick','c.platform_name','d.name'])->get()
            ->groupBy('wid')->map(function ($value) use($time_at){
                $item['order_time'] = $time_at;
                $item['name'] = $value->first()->name;
                $item['platform_nick'] = $value->first()->platform_nick;
                $item['platform_name'] = $value->first()->platform_name;

                $query = $value->where('order_time', $time_at);
                $item['order_money'] = $query->sum('order_money');
                $item['unpay_order'] = $query->where('status', 2)->sum('order_money');
                $item['order_num'] = $query->sum('order_num');
                return $item;
            });

        $total['order_money'] = $list->sum('order_money');
        $total['order_num'] = $list->sum('order_num');

        return view('analyze.income.day', compact('list', 'total', 'userInfo'));
    }
    // 用户分析数据
    public function userInfoTotal(Request $request)
    {
        $param = $request->all();
        $pdr = $param['pdr'] ?? null;
        $group_id = $param['group'] ?? null;
        $pid = $param['pt_type'] ?? null;
        $pfname = $param['pf_nick'] ?? null;

        $userGroup = AdminUsers::query()->group($group_id)->get()->pluck('id');

        list($groups, $user_all) = AdminUsers::getGroupInfo();

        $list = $list = DB::connection('public')->table('wechat_user_total as a')->leftJoin(config('database.connections.admin.database').'.wechat_empower_info as b', function ($join){
            $join->on('a.wid','=','b.id');
        })->when($pdr, function ($q) use($pdr) {
            $q->where('b.user_id', $pdr);
        })->whereIn('b.user_id', $userGroup)->when($pid, function ($q) use($pid){$q->where('b.pid', $pid);})->when($pfname, function ($q) use($pfname){$q->where('b.nick_name','like', "%$pfname%");})
            ->where('user_source', 0)->select(['a.*'])->orderByDesc('ref_date')->get()
            ->groupBy('ref_date')->map(function ($value){
                $daytime = $value->first()->ref_date ?? null;
                $start_time = Carbon::parse($daytime)->startOfMonth()->toDateString();
                $end_time = Carbon::parse($daytime)->endOfMonth()->toDateString();
                $item['ref_month'] = $start_time . '~' .$end_time;
                $item['new_user'] = $value->sum('new_user');
                $item['cancel_user'] = $value->sum('cancel_user');
                $item['net_user'] = $item['new_user'] - $item['cancel_user'];
                $item['cumulate_user'] = $value->sum('cumulate_user');
                return $item;
            })->groupBy('ref_month')->map(function ($value){

                $item['new_user'] = $value->sum('new_user');
                $item['cancel_user'] = $value->sum('cancel_user');
                $item['net_user'] = $value->sum('net_user');
                $item['cumulate_user'] = $value->first()['cumulate_user'];
                $item['cancel_rate'] = $item['new_user'] ? round(($item['cancel_user'] / $item['new_user']) * 100, 2) .'%' : 0;
                return $item;
            });

        $platforms = PlatformManage::query()->where('type', 1)->get(['id','platform_name'])->pluck('platform_name','id');

        $total['new_user'] = $list->sum('new_user');
        $total['cancel_user'] = $list->sum('cancel_user');
        $total['net_user'] = $list->sum('net_user');
        $total['cancel_rate'] = $total['new_user'] ? round(($total['cancel_user'] / $total['new_user']) * 100, 2) .'%' : 0;

        return view('analyze.users.total', compact('list', 'total', 'groupTree', 'platforms', 'groups', 'user_all'));
    }
    // 每月新进粉丝数量
    public function monthUserInfo(Request $request)
    {
        $param = $request->all();

        $pdr = $param['pdr'] ?? null;
        $group_id = $param['group'] ?? null;
        $pid = $param['pt_type'] ?? null;
        $pfname = $param['pf_nick'] ?? null;

        $start_at = $param['start_at'] ?? null;
        $end_at = $param['end_at'] ?? null;

        $userGroup = AdminUsers::query()->group($group_id)->get()->pluck('id');

        $list = DB::connection('public')->table('wechat_user_total as a')->leftJoin(config('database.connections.admin.database').'.wechat_empower_info as b', function ($join){
            $join->on('a.wid','=','b.id');
        })->when($pdr, function ($q) use($pdr) {
            $q->where('b.user_id', $pdr);
        })->when($pid, function ($q) use($pid){$q->where('b.pid', $pid);})->when($pfname, function ($q) use($pfname){$q->where('b.nick_name','like', "%$pfname%");})
            ->whereIn('b.user_id', $userGroup)->where('ref_date','>=',$start_at)
            ->where('a.ref_date','<=',$end_at)->orderByDesc('a.ref_date')->get()
            ->groupBy('ref_date')->map(function ($value){

                $item['ref_time'] = $value->first()->ref_date;
                $item['new_user'] = $value->sum('new_user');
                $item['cancel_user'] = $value->sum('cancel_user');
                $item['net_user'] = $item['new_user'] - $item['cancel_user'];
                $item['cumulate_user'] = $value->sum('cumulate_user');
                $item['cancel_rate'] = $item['new_user'] ? round(($item['cancel_user'] / $item['new_user']) * 100, 2) .'%' : 0;
                return $item;
            });

        return view('analyze.users.month', compact('list'));
    }
    // 每日用户数据分析
    public function dayUserInfo(Request $request)
    {
        $param = $request->all();

        $pdr = $param['pdr'] ?? null;
        $group_id = $param['group'] ?? null;
        $pid = $param['pt_type'] ?? null;
        $pfname = $param['pf_nick'] ?? null;

        $time_at = $param['time_at'] ?? null;

        $userGroup = AdminUsers::query()->group($group_id)->get()->pluck('id');

        $list = DB::connection('public')->table('wechat_user_total as a')->leftJoin(config('database.connections.admin.database').'.wechat_empower_info as b', function ($join){
            $join->on('a.wid','=','b.id');
        })->leftJoin(config('database.connections.admin.database').'.platform_config as c', function ($join){
            $join->on('b.pid','=','c.id');
        })->leftJoin(config('database.connections.admin.database').'.admin_users as d', function ($join){
            $join->on('b.user_id','=','d.id');
        })->when($pdr, function ($q) use($pdr) {
            $q->where('b.user_id', $pdr);
        })->when($pid, function ($q) use($pid){$q->where('b.pid', $pid);})->when($pfname, function ($q) use($pfname){$q->where('b.nick_name','like', "%$pfname%");})
            ->whereIn('b.user_id', $userGroup)->whereDate('a.ref_date', $time_at)->select(['a.*','b.nick_name','c.platform_name','d.name'])->orderByDesc('cumulate_user')
            ->get()->groupBy('wid')->map(function ($value){
                $item['ref_time'] = $value->first()->ref_date;
                $item['name'] = $value->first()->name;
                $item['nick_name'] = $value->first()->nick_name;
                $item['platform_name'] = $value->first()->platform_name ?? '未添加';
                $item['new_user'] = $value->sum('new_user');
                $item['cancel_user'] = $value->sum('cancel_user');
                $item['net_user'] = $item['new_user'] - $item['cancel_user'];
                $item['cumulate_user'] = $value->sum('cumulate_user');
                $item['cancel_rate'] = $item['new_user'] ? round(($item['cancel_user'] / $item['new_user']) * 100, 2) .'%' : 0;
                return $item;
            })->toArray();

        $list = (new Base())->paginator($list, $request); // 分页处理

        return view('analyze.users.day', compact('list'));
    }

    // 活跃用户数据分析
    public function userInfoActive(Request $request)
    {
        $pdr = $request->input('pdr');
        $group_id = $request->input('group');
        $pid = $request->input('pt_type');
        $pfname = $request->input('pf_nick');

        $userGroup = AdminUsers::query()->group($group_id)->get()->pluck('id');

        list($groups, $user_all) = AdminUsers::getGroupInfo();

        $list = DB::connection('public')->table('active_user_total as a')->leftJoin(config('database.connections.admin.database').'.wechat_empower_info as b', function ($join){
            $join->on('a.wid','=','b.id');
        })->leftJoin(config('database.connections.admin.database').'.platform_config as c', function ($join){
            $join->on('b.pid','=','c.id');
        })->leftJoin(config('database.connections.admin.database').'.admin_users as d', function ($join){
            $join->on('b.user_id','=','d.id');
        })->when($pdr, function ($q) use($pdr) {
            $q->where('b.user_id', $pdr);
        })->when($pid, function ($q) use($pid){$q->where('b.pid', $pid);})->when($pfname, function ($q) use($pfname){$q->where('b.nick_name','like', "%$pfname%");})
            ->whereIn('b.user_id', $userGroup)->select(['a.*'])->orderByDesc('ref_date')->get()
            ->groupBy('ref_date')->map(function ($value){
                $daytime = $value->first()->ref_date ?? null;
                $start_time = Carbon::parse($daytime)->startOfMonth()->toDateString();
                $end_time = Carbon::parse($daytime)->endOfMonth()->toDateString();
                $item['ref_month'] = $start_time . '~' .$end_time;
                $item['new_user'] = $value->sum('new_active');
                $item['cancel_user'] = $value->sum('lose_active');
                $item['net_user'] = $value->sum('today_active');
                $item['cumulate_user'] = $value->sum('cumulate_active');
                return $item;
            })->groupBy('ref_month')->map(function ($value){
                $item['new_user'] = $value->sum('new_user');
                $item['cancel_user'] = $value->sum('cancel_user');
                $item['net_user'] = $value->first()['net_user'];
                $item['cumulate_user'] = $value->first()['cumulate_user'];
                $item['cancel_rate'] = $item['new_user'] ? round(($item['cancel_user'] / $item['new_user']) * 100, 2) .'%' : 0;
                return $item;
            });

        $platforms = PlatformManage::query()->where('type', 1)->get(['id','platform_name'])->pluck('platform_name','id');

        $total['new_user'] = $list->sum('new_user');
        $total['cancel_user'] = $list->sum('cancel_user');
        $total['net_user'] = $list->sum('net_user');
        $total['cancel_rate'] = $total['new_user'] ? round(($total['cancel_user'] / $total['new_user']) * 100, 2) .'%' : 0;

        return view('analyze.active.total', compact('list', 'total', 'platforms', 'groups', 'user_all'));
    }

    // 每月活跃用户分析
    public function monthUserActive(Request $request)
    {
        $param = $request->all();

        $pdr = $param['pdr'] ?? null;
        $pid = $param['pt_type'] ?? null;
        $pfname = $param['pf_nick'] ?? null;

        $start_at = $param['start_at'] ?? null;
        $end_at = $param['end_at'] ?? null;
        $group_id = $param['group'] ?? null;

        $userGroup = AdminUsers::query()->group($group_id)->get()->pluck('id');


        $list = DB::connection('public')->table('active_user_total as a')->leftJoin(config('database.connections.admin.database').'.wechat_empower_info as b', function ($join){
            $join->on('a.wid','=','b.id');
        })->when($pdr, function ($q) use($pdr) {
            $q->where('b.user_id', $pdr);
        })->when($pid, function ($q) use($pid){$q->where('b.pid', $pid);})->when($pfname, function ($q) use($pfname){$q->where('b.nick_name','like', "%$pfname%");})
            ->whereIn('b.user_id', $userGroup)->where('ref_date','>=',$start_at)
            ->where('a.ref_date','<=',$end_at)->orderByDesc('a.ref_date')->get()
            ->groupBy('ref_date')->map(function ($value){
                $item['ref_time'] = $value->first()->ref_date;
                $item['new_user'] = $value->sum('new_active');
                $item['cancel_user'] = $value->sum('lose_active');
                $item['net_user'] = $value->sum('today_active');
                $item['cumulate_user'] = $value->sum('cumulate_active');
                $item['cancel_rate'] = $item['new_user'] ? round(($item['cancel_user'] / $item['new_user']) * 100, 2) .'%' : 0;
                return $item;
            });

        return view('analyze.active.month', compact('list'));
    }

    // 每日活跃用户分析
    public function dayUserActive(Request $request)
    {
        $param = $request->all();

        $pdr = $param['pdr'] ?? null;
        $pid = $param['pt_type'] ?? null;
        $pfname = $param['pf_nick'] ?? null;

        $time_at = $param['time_at'] ?? null;
        $group_id = $param['group'] ?? null;

        $userGroup = AdminUsers::query()->group($group_id)->get()->pluck('id');

        $list = DB::connection('public')->table('active_user_total as a')->leftJoin(config('database.connections.admin.database').'.wechat_empower_info as b', function ($join){
            $join->on('a.wid','=','b.id');
        })->leftJoin(config('database.connections.admin.database').'.platform_config as c', function ($join){
            $join->on('b.pid','=','c.id');
        })->leftJoin(config('database.connections.admin.database').'.admin_users as d', function ($join){
            $join->on('b.user_id','=','d.id');
        })->when($pdr, function ($q) use($pdr) {
            $q->where('b.user_id', $pdr);
        })->when($pid, function ($q) use($pid){$q->where('b.pid', $pid);})->when($pfname, function ($q) use($pfname){$q->where('b.nick_name','like', "%$pfname%");})
            ->whereIn('b.user_id', $userGroup)->whereDate('a.ref_date', $time_at)->select(['a.*','b.nick_name','c.platform_name','d.name'])->orderByDesc('cumulate_active')
            ->get()->groupBy('wid')->map(function ($value){
                $item['ref_time'] = $value->first()->ref_date;
                $item['name'] = $value->first()->name;
                $item['nick_name'] = $value->first()->nick_name;
                $item['platform_name'] = $value->first()->platform_name ?? '未添加';
                $item['new_user'] = $value->sum('new_active');
                $item['cancel_user'] = $value->sum('lose_active');
                $item['net_user'] = $value->sum('today_active');
                $item['cumulate_user'] = $value->sum('cumulate_active');
                $item['cancel_rate'] = $item['new_user'] ? round(($item['cancel_user'] / $item['new_user']) * 100, 2) .'%' : 0;
                return $item;
            })->toArray();

        $list = (new Base())->paginator($list, $request); // 分页处理

        return view('analyze.active.day', compact('list'));
    }

    // 获取每月的日期，截止到昨天的所有时间
    public function getMonth($time = '', $format='Y-m-d')
    {
        $time = $time != '' ? strtotime($time) : time();
        //获取当前周几
        $week = date('d', $time);
        $date = [];
        for ($i = 1; $i <= date('t', $time); $i++) {
            $day = date($format, strtotime('+' . $i - $week . ' days', $time));
            if ($day == date('Y-m-d')){
                break;
            }
            $date[$i] = date($format, strtotime('+' . $i - $week . ' days', $time));
        }
        return $date;
    }
}
