<?php

namespace App\Http\Controllers\Analyze;

use App\Models\AdminUsers;
use App\Models\Novel\PlatformManage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Notification\ServiceMessageInfo;
use Illuminate\Support\Facades\DB;

class WechatNotifyController extends Controller
{
    public function index(ServiceMessageInfo $serviceMessageInfo, Request $request)
    {
        $pdr = $request->input('pdr');
        $group_id = $request->input('group');
        $status = $request->input('status', 'all');
        $type = $request->input('msgtype', 'all');
        $pt_type = $request->input('pt_type');
        $st_time = $request->input('start_date');
        $ed_time = $request->input('end_date');
        $filter_type = $request->input('fltype', 'all');

        list($groups, $user_all) = AdminUsers::getGroupInfo();

        $list = $serviceMessageInfo::query()->group($group_id)->when($pdr, function ($query) use($pdr) {
            $query->where('user_id', $pdr);
        })->when($status != 'all', function ($q) use ($status){
            $q->where('status', $status);
        })->when($type != 'all', function ($q) use ($type){
            $q->where('msgtype', $type);
        })->when($st_time, function ($q) use($st_time){
            $q->where('send_time','>=', $st_time);
        })->when($ed_time, function ($q) use($ed_time){
            $q->where('send_time','<=', $ed_time  . ' 23:59:59');
        })->when($filter_type != 'all', function ($q) use($filter_type) {
            $q->where('filter_type', $filter_type);
        })->when($pt_type, function ($q) use($pt_type) {
            $q->whereHas('hasOneWechatEmpowerInfo.hasOnePlatformManage', function ($query) use($pt_type) {
                $query->where('id', $pt_type);
            });
        })->with(['hasOneWechatEmpowerInfo.hasOnePlatformManage'])->orderBy('status')->orderByDesc('send_time')->paginate(15);

        $platforms = PlatformManage::query()->where('type', 1)->select(['id', 'platform_name','type'])->get()->pluck('platform_name','id');

        return view('analyze.notice.list',compact('list', 'platforms', 'groups', 'user_all'));
    }

    public function noticeTotal(Request $request)
    {
        $pdr = $request->input('pdr');
        $group_id = $request->input('group');
        $status = $request->input('status', 'all');
        $type = $request->input('msgtype', 'all');
        $pid = $request->input('pt_type');
        $st_time = $request->input('start_date');
        $ed_time = $request->input('end_date');
        $pf_nick = $request->input('pf_nick');

        $userGroup = AdminUsers::query()->group($group_id)->get()->pluck('id');

        list($groups, $user_all) = AdminUsers::getGroupInfo();

        $list = DB::connection('admin')->table('service_message_info as a')->leftJoin('wechat_empower_info as b', function ($join) {
            $join->on('a.wid','=','b.id');
        })->when($pdr, function ($query) use($pdr) {
            $query->where('b.user_id', $pdr);
        })->whereIn('b.user_id', $userGroup)->when($status != 'all', function ($q) use ($status){
            $q->where('a.status', $status);
        })->when($type != 'all', function ($q) use ($type){
            $q->where('a.msgtype', $type);
        })->when($st_time, function ($q) use($st_time){
            $q->where('a.send_time','>=', $st_time);
        })->when($ed_time, function ($q) use($ed_time){
            $q->where('a.send_time','<=', $ed_time  . ' 23:59:59');
        })->when($pid, function ($q) use($pid) {
            $q->where('b.pid', $pid);
        })->when($pf_nick, function ($q) use($pf_nick) {
            $q->where('b.nick_name', 'like', "%$pf_nick%");
        })->select(['a.*',DB::raw('DATE_FORMAT(a.send_time,"%Y-%m-%d") as sendtime')])
            ->orderByDesc('send_time')->get()->groupBy('sendtime')->map(function ($value) {
                $daytime = $value->first()->sendtime ?? null;
                $start_time = Carbon::parse($daytime)->startOfMonth()->toDateString();
                $end_time = Carbon::parse($daytime)->endOfMonth()->toDateString();
                $item['send_month'] = $start_time . '~' .$end_time;
                $item['sendtime'] = $daytime;

                $item['text_num'] = $value->where('msgtype', 0)->count();
                $item['news_num'] = $value->where('msgtype', 1)->count();
                $item['send_num'] = $value->sum('send_num');

                $item['active_num'] = $value->where('task_type', 1)->count();
                $item['book_num'] = $value->where('task_type', 2)->count();
                $item['sign_num'] = $value->where('task_type', 3)->count();
                $item['history_num'] = $value->where('task_type', 4)->count();
                $item['wid_num'] = $value->pluck('wid');
                $item['title_num'] = $value->pluck('title')->unique()->filter();
                return $item;
            })->groupBy('send_month')->map(function ($value){
                $item['day_num'] = $value->where('sendtime','<=',Carbon::yesterday())->count();
                $item['text_num'] = $value->sum('text_num');
                $item['news_num'] = $value->sum('news_num');
                $item['send_num'] = $value->sum('send_num');

                $item['active_num'] = $value->sum('active_num');
                $item['book_num'] = $value->sum('book_num');
                $item['sign_num'] = $value->sum('sign_num');
                $item['history_num'] = $value->sum('history_num');

                $item['wid_num'] = $value->pluck('wid_num')->collapse()->unique()->count();
                $item['title_num'] = $value->pluck('title_num')->collapse()->unique()->count();

                return $item;
            });

        $platforms = PlatformManage::query()->where('type', 1)->select(['id', 'platform_name','type'])->get()->pluck('platform_name','id');

        return view('analyze.notice.total', compact('list', 'groups', 'user_all', 'platforms'));
    }

    public function monthNotice(Request $request)
    {
        $pdr = $request->input('pdr');
        $group_id = $request->input('group');
        $status = $request->input('status', 'all');
        $type = $request->input('msgtype', 'all');
        $pid = $request->input('pt_type');
        $st_time = $request->input('start_at');
        $ed_time = $request->input('end_at');
        $pf_nick = $request->input('pf_nick');

        $userGroup = AdminUsers::query()->group($group_id)->get()->pluck('id');

        list($groups, $user_all) = AdminUsers::getGroupInfo();

        $list = DB::connection('admin')->table('service_message_info as a')->leftJoin('wechat_empower_info as b', function ($join) {
            $join->on('a.wid','=','b.id');
        })->when($pdr, function ($query) use($pdr) {
            $query->where('b.user_id', $pdr);
        })->whereIn('b.user_id', $userGroup)->when($status != 'all', function ($q) use ($status){
            $q->where('a.status', $status);
        })->when($type != 'all', function ($q) use ($type){
            $q->where('a.msgtype', $type);
        })->when($st_time, function ($q) use($st_time){
            $q->where('a.send_time','>=', $st_time);
        })->when($ed_time, function ($q) use($ed_time){
            $q->where('a.send_time','<=', $ed_time  . ' 23:59:59');
        })->when($pid, function ($q) use($pid) {
            $q->where('b.pid', $pid);
        })->when($pf_nick, function ($q) use($pf_nick) {
            $q->where('b.nick_name', 'like', "%$pf_nick%");
        })->select(['a.*',DB::raw('DATE_FORMAT(a.send_time,"%Y-%m-%d") as sendtime')])
            ->orderByDesc('send_time')->get()->groupBy('sendtime')->map(function ($value) {
                $item['wait_num'] = $value->where('status', 0)->count();
                $item['into_num'] = $value->where('status', 1)->count();
                $item['succes_num'] = $value->where('status', 2)->count();

                $item['text_num'] = $value->where('msgtype', 0)->count();
                $item['news_num'] = $value->where('msgtype', 1)->count();
                $item['send_num'] = $value->sum('send_num');

                $item['active_num'] = $value->where('task_type', 1)->count();
                $item['book_num'] = $value->where('task_type', 2)->count();
                $item['sign_num'] = $value->where('task_type', 3)->count();
                $item['history_num'] = $value->where('task_type', 4)->count();
                $item['wid_num'] = $value->pluck('wid')->unique()->count();
                $item['title_num'] = $value->pluck('title')->unique()->filter()->count();
                return $item;
            });

        $timelines = getDiffDateRange($st_time, $ed_time);

        $nosend = [
            "wait_num" => 0,
            "into_num" => 0,
            "succes_num" => 0,
            "text_num" => 0,
            "news_num" => 0,
            "send_num" => 0,
            "active_num" => 0,
            "book_num" => 0,
            "sign_num" => 0,
            "history_num" => 0,
            "wid_num" => 0,
            "title_num" => 0,
        ];

        foreach ($timelines as $day){
            if ($day > date('Y-m-d')){
                continue;
            }
            if (!$list->has($day)){
                $list[$day] = $nosend;
            }
        }
        $list = $list->sortKeysDesc();

        return view('analyze.notice.month', compact('list', 'groups', 'user_all'));
    }

    public function dayNotice(Request $request)
    {
        $pdr = $request->input('pdr');
        $group_id = $request->input('group');
        $status = $request->input('status', 'all');
        $type = $request->input('msgtype', 'all');
        $pid = $request->input('pt_type');
        $st_time = $request->input('start_at');
        $ed_time = $request->input('end_at');
        $filter_type = $request->input('fltype', 'all');
        $pf_nick = $request->input('pf_nick');

        $userGroup = AdminUsers::query()->group($group_id)->get()->pluck('id');

        list($groups, $user_all) = AdminUsers::getGroupInfo();

        $list = DB::connection('admin')->table('service_message_info as a')->leftJoin('wechat_empower_info as b', function ($join) {
            $join->on('a.wid','=','b.id');
        })->leftJoin('admin_users as c', function ($join) {
            $join->on('a.user_id','=','c.id');
        })->leftJoin(config('database.connections.public.database').'.novel_info as d', function ($join){
            $join->on('a.book_id','=','d.id');
        })->when($pdr, function ($query) use($pdr) {
            $query->where('b.user_id', $pdr);
        })->whereIn('b.user_id', $userGroup)->when($st_time, function ($q) use($st_time){
            $q->where('a.send_time','>=', $st_time);
        })->when($ed_time, function ($q) use($ed_time){
            $q->where('a.send_time','<=', $ed_time  . ' 23:59:59');
        })->when($status != 'all', function ($q) use ($status){
            $q->where('a.status', $status);
        })->when($type != 'all', function ($q) use ($type){
            $q->where('a.msgtype', $type);
        })->when($filter_type != 'all', function ($q) use($filter_type) {
            $q->where('a.filter_type', $filter_type);
        })->when($pid, function ($q) use($pid) {
            $q->where('b.pid', $pid);
        })->when($pf_nick, function ($q) use($pf_nick) {
            $q->where('b.nick_name', 'like', "%$pf_nick%");
        })->select(['a.*',DB::raw('DATE_FORMAT(a.send_time,"%Y-%m-%d") as sendtime'), 'b.nick_name','c.name as user_name', 'd.name as book_name'])
            ->orderBy('status')->orderByDesc('send_time')->paginate(15);

        $platforms = PlatformManage::query()->where('type', 1)->select(['id', 'platform_name','type'])->get()->pluck('platform_name','id');

        return view('analyze.notice.day', compact('list', 'groups', 'user_all', 'platforms'));
    }
}
