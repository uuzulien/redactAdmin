<?php


namespace App\Http\Controllers\Wechat;


use App\Http\Controllers\Controller;
use App\Models\AdminUsers;
use App\Models\Novel\PlatformManage;
use App\Models\Wechat\WechatEmpowerInfo;
use App\Repositories\Auth\GroupPermission;
use App\Repositories\Wechat\Func\WechatSendBase;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Notification\ServiceMessageInfo;
use App\Models\Wechat\WechatUserInfo;

class GroupSentController extends Controller
{
    public function imageTextList()
    {
        $data = ['list'=>[]];
        return view('groupSend.list.image_text', $data);
    }
    // 客服消息列表
    public function customMsgList(Request $request, ServiceMessageInfo $serviceInfo)
    {
        $param = $request->input();

        $data = $serviceInfo->getCustomMsgData($param);

        return view('groupSend.list.custom_msg', $data);
    }
    public function templateMsgList()
    {
        $data = ['list'=>[]];

        return view('groupSend.list.template_msg', $data);
    }

    // 高级群发消息
    public function imageTextEdit(Request $request)
    {
        $wid = $request->get('wid');

        $plan_to_send = DB::connection('admin')->table('wechat_user_info')->where(['wid' => $wid])->get()->where('subscribe', '1')->count();

        return view('groupSend.edit.image_text', compact('plan_to_send'));
    }

    // 编辑客服消息
    public function customMsgEdit(Request $request, ServiceMessageInfo $serviceInfo)
    {
        $param = $request->input();

        $data = $serviceInfo->getCustomMsgEdit($param);

        return view('groupSend.edit.custom_msg', $data);
    }

    // 新建客服消息
    public function customMsgAdd(Request $request, WechatEmpowerInfo $wechatEmpowerInfo)
    {
        $wid = $request->input('wid');

        $userGroup = AdminUsers::query()->group()->get()->pluck('id');

        $vipall = $wechatEmpowerInfo->group()->where('is_power',1)->where('id','<>',Auth::user()->last_use_wechat_id)->select(['id','nick_name','pid']);

        $history_wid = DB::connection('admin')->table('wechat_link_info')->where(['typeid' => 4, 'status' => 1])->where('href','<>', '')->select('wid')->get()->pluck('wid');

        $titleInfo = DB::connection('admin')->table('wechat_title_info')->whereIn('user_id', $userGroup)->where('status', 1)->select(['id','type as categoryid','title'])->get();
        $link = DB::connection('admin')->table('wechat_link_info as a')->leftJoin('wechat_empower_info as b', function ($join){
            $join->on('a.wid','=','b.id');
        })->leftJoin(config('database.connections.public.database').'.novel_info as c', function ($join){
            $join->on('a.book_id','=','c.id')->on('c.pid','=','b.pid');
        })->where('a.status',1)->where('a.wid', $wid)->select(['a.*','c.name','c.pid'])->get()->sortByDesc('created_at');

        // 链接
        $res['active_link'] = $link->where('typeid',1);
        $res['novel_link'] = $link->where('typeid',2)->where('msgtype', 1);
        $res['sign_link'] = $link->where('typeid',3);
        $res['history_link'] = $link->where('typeid',4);

        $platforms = DB::connection('admin')->table('wechat_empower_info as a')->leftJoin('platform_config as b', function ($join) {
            $join->on('a.pid','=','b.id');
        })->where('a.id', $wid)->where('b.type','1')->select(['b.id','b.platform_name'])->get();

        return view('groupSend.add.custom_msg', compact('vipall','res', 'titleInfo', 'wid' , 'history_wid', 'platforms'));
    }

    // 新建群发消息
    public function imageTextAdd()
    {
        return view('groupSend.add.image_text');
    }

    public function templateMsgEdit()
    {
        return view('groupSend.edit.template_msg', ['list'=>[]]);
    }

    public function customMsgSaveTask(Request $request)
    {
        date_default_timezone_set("Asia/Shanghai");

        $param = $request->all();
        $data = $param['data'];

        if ($data['send_time'] > Carbon::now()->addDays(3) || $data['send_time'] < Carbon::now()){
            return error('发送时间不正确或不被允许', -1, 200);
       }

        if ($data['id']){
            $is_false = DB::connection('admin')->table('service_message_info')->find($data['id'])->status;

            if ($is_false){
                return error('消息已推送，禁止修改', -1, 200);
            }

            DB::connection('admin')->table('service_message_info')->where('id', $data['id'])->update($data);

            return success();
        }

        $vipall = $param['vipall'] ?? [];
        array_push($vipall, $param['wid']);
        $data['user_id'] = Auth::id();

        if ($data['task_type'] == 4 && $vipall != []){
            $history_wid = DB::connection('admin')->table('wechat_link_info')->where(['typeid' => 4, 'status' => 1])->whereIn('wid', $vipall)->select(['wid','href'])->get();
        }

        $is_check = DB::connection('admin')->table('service_message_info')->whereIn('wid', $vipall)
            ->where('send_time', '>', Carbon::parse($data['send_time'])->subHour(1))->where('send_time', '<', Carbon::parse($data['send_time'])->addHours(1))->first();
        if ($is_check){
            return error('存在一个或多个相近时间的消息推送', -1, 200);
        }

        $multiple_choice = [];

        foreach ($vipall as $value){
            $data['wid'] = $value;
            // 批量传继续阅读
            if (!empty($history_wid) && $data['msgtype'] == 1){
                $dataInfo = get_object_vars(json_decode($data['dataInfo']));
                $dataInfo['top-item']->linkurl = $history_wid->where('wid', $value)->first()->href ?? '';
                $data['dataInfo'] = json_encode($dataInfo);
            }
            array_push($multiple_choice, $data);
        }

        if ($multiple_choice){
            DB::connection('admin')->table('service_message_info')->insert($multiple_choice);
        }

        return success();
    }
    // 客服消息测试发送
    public function customMsgTestSend(Request $request)
    {
        date_default_timezone_set("Asia/Shanghai");

        $param = $request->all();
        $data = $param['data'];

        $first = WechatUserInfo::query()->where(['id' => $data['test_user'], 'wid' => $param['wid']])->first();

        if (empty($first))
            return response()->json(['errcode' => -1, 'errmsg' => '该粉丝不存在']);

        if (Carbon::parse($first->active_time)->diffInDays(date('Y-m-d'), false) > 2)
            return response()->json(['errcode' => -1, 'errmsg' => '该粉丝长时间未和微信公众号互动']);


        $send_type = get_object_vars(json_decode($data['dataInfo']));

        if (array_key_exists('text', $send_type)){
            $content = (new WechatSendBase())->customerService($send_type['text'], $data['test_user']);
        }
        if (array_key_exists('top-item', $send_type)){
            $content = (new WechatSendBase())->customerService($send_type['top-item'], $data['test_user'], 'NewsItem');
        }

        return response()->json($content);
    }
    // 删除客服消息
    public function deleteNotice($id)
    {
        $wechat = ServiceMessageInfo::find($id);
        $wechat->delete();

        return success('删除成功');
    }

    // 预计送达人数
    public function planSendNum(Request $request)
    {
        date_default_timezone_set("Asia/Shanghai");

        $param = $request->input();
        $datas = $param['data'];
        $wid = $param['wid'];

        if ($datas['filter_type'] == 0){
            $data['count'] = DB::connection('admin')->table('wechat_user_info')->where(['wid' => $wid])->get()->where('subscribe', '1')
                ->where('active_time','>=', Carbon::now()->subDays(2)->timestamp)->count();
            return response()->json(["err" => 0, "msg" => "", "data" => $data]);
        }
        $condition = [];
        $stime = $datas['stime'];
        $etime = $datas['etime'];
        $money_from = $datas['totalmoney_from'];
        $money_to = $datas['totalmoney_to'];

        if ($datas['sex'] != -1)
            $condition['sex'] = $datas['sex'];

        if ($datas['pay'] != -1)
            $condition['b.pay'] = $datas['pay'] ? $datas['pay'] : null;


        $data['count'] = DB::connection('admin')->table('wechat_user_info as a')->leftJoin(config('database.connections.public.database').'.wechat_fens_detail as b', function ($join){
            $join->on('a.openid','=','b.openid');
        })->where('a.subscribe', '1')
            ->where('a.active_time','>=', Carbon::now()->subDays(2)->timestamp)->where(['a.wid' => $wid])
            ->where($condition)->when($stime, function ($q) use($stime){
            $q->where('a.subscribe_time', '>=', Carbon::parse($stime)->timestamp);
        })->when($etime, function ($q) use($etime){
            $q->where('a.subscribe_time', '<=', Carbon::parse($etime)->timestamp);
        })->when($money_from, function ($q) use($money_from){
            $q->where('b.recharge_amount', '>=', $money_from);
        })->when($money_to, function ($q) use($money_to){
            $q->where('b.recharge_amount', '<=', $money_to);
        })->get()->count();

        return response()->json(["err" => 0, "msg" => "", "data" => $data]);
    }
}
