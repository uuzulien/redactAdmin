<?php

namespace App\Http\Controllers\Wechat;

use App\Models\AdminUsers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Wechat\WechatEmpowerInfo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Repositories\Comman\Base;
use function GuzzleHttp\Promise\all;


class AccountController extends Controller
{
    // 公众号列表
    public function index(Request $request,WechatEmpowerInfo $wechatEmpowerInfo)
    {
        $param = $request->input();
        $data = $wechatEmpowerInfo->getWechatInfoList($param);

        return view('wechat.empower.list', $data);
    }

    // 个人号列表
    public function wechatPersonInfo(Request $request)
    {
        $userGroup = AdminUsers::query()->group()->get()->pluck('id');

        $list = DB::connection('public')->table('person_wechat_config as a')->leftJoin(config('database.connections.admin.database').'.admin_users as b', function ($join){
            $join->on('a.user_id','=','b.id');
        })->leftJoin(config('database.connections.admin.database').'._log_person_account_info as c', function ($join){
            $join->on('a.id','=','c.person_id');
        })->leftJoin(config('database.connections.admin.database').'._log_wechat_empower_info as d', function ($join){
            $join->on('a.scan_moblie','=','d.scan_moblie');
        })->whereIn('a.user_id', $userGroup)->select(['a.*','b.name as user_name','c.name as use_name','d.wid'])->get()->groupBy('id')->map(function ($value){
            $item['id'] =$value->first()->id;
            $item['nick_name'] =$value->first()->nick_name;
            $item['account'] =$value->first()->account;
            $item['password'] =$value->first()->password;
            $item['purpose'] =$value->first()->purpose;
            $item['scan_moblie'] =$value->first()->scan_moblie;
            $item['phone'] =$value->first()->phone;
            $item['name'] =$value->first()->name;
            $item['idcard'] =$value->first()->idcard;
            $item['payment_code'] =$value->first()->payment_code;
            $item['fens_num'] =$value->first()->fens_num;
            $item['user_name'] =$value->first()->user_name;
            $item['count_num'] = $value->pluck('wid')->unique()->filter()->count();
            return $item;
        })->toArray();

        $list = (new Base())->paginator($list, $request);

        return view('wechat.person.list', compact('list'));
    }

    public function personWechatDetail(Request $request)
    {
        $act = $request->input('act');

        $list = DB::connection('admin')->table('_log_wechat_empower_info as a')->where('a.scan_moblie', $act)->leftJoin('wechat_empower_info as b', function ($join){
            $join->on('a.wid','=','b.id');
        })->leftJoin('admin_users as c', function ($join){
            $join->on('b.user_id','=','c.id');
        })->leftJoin('platform_config as d', function ($join){
            $join->on('b.pid','=','d.id');
        })->select(['b.nick_name','a.scan_moblie','b.created_at','c.name as user_name','d.platform_name'])->paginate(15);

        return view('wechat.person.sub_list', compact('list'));
    }

    // 切换并保存
    public function switchAccount($id)
    {
        $query = Auth::user();
        $query->last_use_wechat_id = $id;
        $query->save();

        flash_message('公众号切换成功');
        return redirect()->back();
    }

    public function editAccount(Request $request)
    {
        $wid = $request->input('id');
        $type = $request->input('type');
        $val = $request->input('value');

        $status = DB::connection('admin')->table('_log_wechat_empower_info')->updateOrInsert(['wid' => $wid], ['wid' => $wid, 'user_id' => Auth::id(), $type => $val]);

        return success($status);
    }

    // 微信个人号添加
    public function addPersonAccount(Request $request)
    {
        try {
            $param = $request->all();
            $data = $param['row'];

            if (empty($param['_token']))
                abort('非法请求！');

//            if ($data['account'] == "") {
//                $validatorError = ['name' => '请认真添加账户名称'];
//                $validatorError = json_encode($validatorError);
//                throw new \Exception($validatorError, 4002);
//            }
//            if ($data['scan_moblie'] == "") {
//                $validatorError = ['name' => '请认真添加账户对应扫码手机'];
//                $validatorError = json_encode($validatorError);
//                throw new \Exception($validatorError, 4002);
//            }
//            $is_false = DB::connection('public')->table('person_wechat_config')->where('account', $data['account'])->first();
//
//            if ($is_false) {
//                $validatorError = ['name' => "《{$data['account']}》已被添加，请勿重复操作！"];
//                $validatorError = json_encode($validatorError);
//                throw new \Exception($validatorError, 4002);
//            }
            $data['user_id'] = Auth::id();

            DB::connection('public')->table('person_wechat_config')->insert($data);

        }catch (\Exception $e){
            $error = $e->getCode() == 4002 ? json_decode($e->getMessage()) : $e->getMessage();
            return redirect()->back()
                ->withErrors($error)
                ->withInput();
        }
        flash_message('添加成功');
        return redirect()->back();
    }
    // 修改个人号基本信息
    public function amendPersonAccount(Request $request)
    {
        try {
            $param = $request->all();
            $data = $param['row'];

            if (empty($param['_token']))
                abort('非法请求！');

//            if ($data['account'] == "") {
//                $validatorError = ['name' => '请认真添加账户名称'];
//                $validatorError = json_encode($validatorError);
//                throw new \Exception($validatorError, 4002);
//            }
//            if ($data['scan_moblie'] == "") {
//                $validatorError = ['name' => '请认真添加账户对应扫码手机'];
//                $validatorError = json_encode($validatorError);
//                throw new \Exception($validatorError, 4002);
//            }
//            $is_false = DB::connection('public')->table('person_wechat_config')->where('account', $data['account'])->first();
//
//            if ($is_false) {
//                $validatorError = ['name' => "《{$data['account']}》已被添加，请勿重复操作！"];
//                $validatorError = json_encode($validatorError);
//                throw new \Exception($validatorError, 4002);
//            }

            DB::connection('public')->table('person_wechat_config')->where('id', $data['id'])->update($data);

        }catch (\Exception $e){
            $error = $e->getCode() == 4002 ? json_decode($e->getMessage()) : $e->getMessage();
            return redirect()->back()
                ->withErrors($error)
                ->withInput();
        }
        flash_message('添加成功');
        return redirect()->back();
    }

    public function deletePersonAccount($id)
    {
        $status = DB::connection('public')->table('person_wechat_config')->where('id', $id)->delete();

        return success($status);
    }

    // 更新投放中的数据
    public function editAdvertInfo(Request $request)
    {
        try {
            $data = $request->all();

            if (empty($data['_token']))
                abort('非法请求！');


            DB::connection('admin')->table('wechat_empower_info')->where('id', $data['id'])->update(['cost_id' => $data['cost_id'], 'is_cost' => $data['is_cost'], 'sex' => $data['sex']]);

        }catch (\Exception $e){
            $error = $e->getCode() == 4002 ? json_decode($e->getMessage()) : $e->getMessage();
            return redirect()->back()
                ->withErrors($error)
                ->withInput();
        }
        flash_message('添加成功');
        return redirect()->back();
    }

    // 个人号的用途添加
    public function addPersonTag(Request $request)
    {
        try {
            $data = $request->all();

            if (empty($data['_token']))
                abort('非法请求！');

            if ($data['group_id'] == "") {
                $validatorError = ['name' => '操作异常'];
                $validatorError = json_encode($validatorError);
                throw new \Exception($validatorError, 4002);
            }

            DB::connection('admin')->table('_log_person_account_info')->updateOrInsert(['name' => $data['name'], 'person_id' => $data['group_id']],['name' => $data['name'], 'person_id' => $data['group_id'], 'user_id' => Auth::id()]);

        }catch (\Exception $e){
            $error = $e->getCode() == 4002 ? json_decode($e->getMessage()) : $e->getMessage();
            return redirect()->back()
                ->withErrors($error)
                ->withInput();
        }
        flash_message('添加成功');
        return redirect()->back();
    }

    // 公众号认证相关
    public function verifyDateSave(Request $request)
    {
        try {
            $data = $request->all();

            if (empty($data['_token']))
                abort('非法请求！');

            if ($data['verify_date'] < Carbon::now()){
                return error('认证已到期，请认证后在更新', -1, 200);
            }

            DB::connection('admin')->table('_log_wechat_empower_info')->updateOrInsert(['wid' => $data['id']], ['wid' => $data['id'], 'verify_date' => $data['verify_date'], 'status' => 2]);

            $check_log = DB::connection('admin')->table('_log_wechat_verifydate')->where(['wid' => $data['id'], 'status' => 1])->first();
            if ($check_log){
                DB::connection('admin')->table('_log_wechat_verifydate')->where('id', $check_log->id)->update(['status' => 2, 'complete_verify_date' => $data['verify_date']]);
            }

        }catch (\Exception $e){
            $error = $e->getCode() == 4002 ? json_decode($e->getMessage()) : $e->getMessage();
            return redirect()->back()
                ->withErrors($error)
                ->withInput();
        }
        flash_message('添加成功');
        return redirect()->back();
    }

    // 年审认证时间
    public function waitVerifyIndex()
    {
        $check_time = Carbon::today()->addMonth();

        $userGroup = AdminUsers::query()->group()->get()->pluck('id');

        $list = DB::connection('admin')->table('_log_wechat_empower_info as a')->leftJoin('wechat_empower_info as b', function ($join) {
            $join->on('a.wid','=','b.id');
        })->leftJoin('admin_users as c', function ($join) {
            $join->on('a.user_id','=','c.id');
        })->where('a.status', 0)->whereIn('b.user_id', $userGroup)->where('a.verify_date', '<', $check_time)->orderByDesc('a.verify_date')->select(['a.id','a.status','a.wid','a.updated_at','b.user_id','a.verify_date','b.nick_name','c.name as user_name'])->paginate(15);

        return view('wechat.verify.wait', compact('list'));
    }

    // 年审进行中
    public function beginVerifyIndex()
    {
        $userGroup = AdminUsers::query()->group()->get()->pluck('id');

        $list = DB::connection('admin')->table('_log_wechat_verifydate as a')->leftJoin('wechat_empower_info as b', function ($join) {
            $join->on('a.wid','=','b.id');
        })->leftJoin('admin_users as c', function ($join) {
            $join->on('a.audit_id','=','c.id');
        })->whereIn('a.status', [1,2])->whereIn('b.user_id', $userGroup)->select(['a.id','a.status','a.wid','a.created_at','b.user_id','a.before_verify_date as verify_date','b.nick_name','c.name as user_name'])->paginate(15);

        return view('wechat.verify.begin', compact('list'));
    }
    // 完成年审
    public function completeVerifyIndex()
    {
        $userGroup = AdminUsers::query()->group()->get()->pluck('id');

        $list = DB::connection('admin')->table('_log_wechat_verifydate as a')->leftJoin('wechat_empower_info as b', function ($join) {
            $join->on('a.wid','=','b.id');
        })->leftJoin('admin_users as c', function ($join) {
            $join->on('a.user_id','=','c.id');
        })->where('a.status', 3)->whereIn('b.user_id', $userGroup)->select(['a.id','a.status','a.wid','a.updated_at','b.user_id','a.before_verify_date','a.complete_verify_date','b.nick_name','c.name as user_name'])->paginate(15);

        return view('wechat.verify.complete', compact('list'));
    }


    // 进入年审中状态
    public function editVerifyUpdate(Request $request)
    {
        $id = $request->input('id');
        $wid = $request->input('wid');
        $verify_date = $request->input('verify_date');
        $status = $request->input('status');

        if ($id){
            DB::connection('admin')->table('_log_wechat_empower_info')->where('wid', $wid)->update(['status' => 1]);

            DB::connection('admin')->table('_log_wechat_verifydate')->where('id', $id)->update(['audit_id' => Auth::id(),'status' => $status]);
        } else {
            DB::connection('admin')->table('_log_wechat_verifydate')->updateOrInsert(['wid' => $wid, 'status' => $status], ['wid' => $wid, 'user_id' => Auth::id(), 'status' => $status, 'before_verify_date' => $verify_date]);
        }

        return success();
    }
}
