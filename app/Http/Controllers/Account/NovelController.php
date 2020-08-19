<?php

namespace App\Http\Controllers\Account;

use App\Models\AdminUsers;
use App\Models\Novel\AccountManage;
use App\Models\Novel\PlatformManage;
use App\Repositories\AdminHandleLog\AdminLogHandle;
use App\Repositories\Auth\GroupPermission;
use App\Repositories\Comman\Base;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NovelController extends Controller
{
    public function index(Request $request, AccountManage $accountManage)
    {
        $param = $request->input();

        $data = $accountManage->showNovelData($param);
        return view('novel.list', $data);
    }

    // 小说VIP账户
    public function vipList(Request $request)
    {
        $pdr = $request->input('pdr');
        $nick = $request->input('pf_nick');
        $group_id = $request->input('group');

        $userGroup = AdminUsers::query()->group($group_id)->get()->pluck('id');

        list($groups, $user_all) = AdminUsers::getGroupInfo();

        $list = DB::connection('admin')->table('manage_account_config as a')->leftJoin('admin_users as b', function ($join){
            $join->on('a.user_id','=','b.id');
        })->leftJoin('_log_manage_account_info as d', function ($join){
            $join->on('a.id','=','d.vip_id');
        })->leftJoin('platform_config as e', function ($join){
            $join->on('a.pid','=','e.id');
        })->when($pdr, function ($q) use($pdr) {
            $q->where('a.user_id', $pdr);
        })->whereIn('a.user_id', $userGroup)->orderByDesc('a.created_at')->select(['a.*','b.name as user_name','d.sub_id','e.platform_name'])->get()->groupBy('id')->map(function ($value){
            $item['id'] =$value->first()->id;
            $item['pid'] =$value->first()->pid;
            $item['platform_name'] =$value->first()->platform_name;
            $item['count_num'] =$value->where('sub_id','>',0)->count('sub_id');
            return $item;
        })->groupBy('pid')->map(function ($value){
            $item['pid'] =$value->first()['pid'];
            $item['platform_name'] =$value->first()['platform_name'];
            $item['count_sub_num'] =$value->sum('count_num');
            $item['count_num'] =$value->count();
            return $item;
        })->toArray();

        $list = (new Base())->paginator($list, $request);

        $platforms = PlatformManage::query()->where('type', 1)->select(['id', 'platform_name'])->get();


        return view('novel.vip.list', compact('list', 'pid', 'platforms', 'groups', 'user_all'));
    }
    // 小说VIP账户管理
    public function manageNovel(Request $request)
    {
        $data = $this->vipManageMethod($request);

        return view('novel.vip.sub_list', $data);
    }
    // 小说子账户基础信息
    public function subNovelConfig(Request $request)
    {
        $up_id = $request->input('upid');
        $pdr = $request->input('pdr');
        $group_id = $request->input('group');

        $userGroup = AdminUsers::query()->group($group_id)->get()->pluck('id');

        $list = DB::connection('admin')->table('_log_manage_account_info as a')->leftJoin('account_config as b', function ($join){
            $join->on('a.sub_id','=','b.id');
        })->leftJoin('admin_users as c', function ($join){
            $join->on('b.user_id','=','c.id');
        })->leftJoin('platform_config as d', function ($join){
            $join->on('b.pid','=','d.id');
        })->when($pdr, function ($q) use($pdr) {
            $q->where('a.user_id', $pdr);
        })->whereIn('a.user_id', $userGroup)->where('a.vip_id', $up_id)->select(['b.*','c.name as user_name','d.platform_name'])->paginate(15);

        return view('novel.vip.sub_config', compact('list'));
    }

    public function vipManageMethod($request)
    {
        $pid = $request->input('pid');

        $pdr = $request->input('pdr');
        $group_id = $request->input('group');

        $userGroup = AdminUsers::query()->group($group_id)->get()->pluck('id');

        $list = DB::connection('admin')->table('manage_account_config as a')->leftJoin('admin_users as b', function ($join){
            $join->on('a.user_id','=','b.id');
        })->leftJoin('_log_manage_account_info as d', function ($join){
            $join->on('a.id','=','d.vip_id');
        })->when($pdr, function ($q) use($pdr) {
            $q->where('a.user_id', $pdr);
        })->whereIn('a.user_id', $userGroup)->where('a.pid', $pid)->orderByDesc('a.created_at')->select(['a.*','b.name as user_name','d.sub_id'])->get()->groupBy('id')->map(function ($value){
            $item['id'] =$value->first()->id;
            $item['pid'] =$value->first()->pid;
            $item['origin'] =$value->first()->origin;
            $item['user_name'] =$value->first()->user_name;
            $item['account'] =$value->first()->account;
            $item['password'] =$value->first()->password;
            $item['count_num'] =$value->where('sub_id','>',0)->count('sub_id');
            $item['updated_at'] =$value->first()->updated_at;
            return $item;
        })->toArray();

        $list = (new Base())->paginator($list, $request);

        return compact('list', 'pid');
    }

    // 添加一个账号
    public function addAccount(Request $request)
    {
        try {
            $data = $request->all();

            if (empty($data['_token']))
                abort('非法请求！');

            if ($data['pfname'] == "") {
                $validatorError = ['name' => '请填写平台名称'];
                $validatorError = json_encode($validatorError);
                throw new \Exception($validatorError, 4002);
            }
            if ($data['username'] == "") {
                $validatorError = ['name' => '请填写登录账号'];
                $validatorError = json_encode($validatorError);
                throw new \Exception($validatorError, 4002);
            }
            if ($data['passwd'] == "") {
                $validatorError = ['name' => '请填写登录密码'];
                $validatorError = json_encode($validatorError);
                throw new \Exception($validatorError, 4002);
            }
            if ($data['pt_type'] == "0") {
                $validatorError = ['name' => '请选择平台来源'];
                $validatorError = json_encode($validatorError);
                throw new \Exception($validatorError, 4002);
            }
            $is_false = AccountManage::query()->whereIn('pid',[1,2,4,5,6])->where(['account' => $data['username'], 'pid' => $data['pt_type']])->first();

            if ($is_false) {
                $validatorError = ['name' => $data['username']. '已被添加过了'];
                $validatorError = json_encode($validatorError);
                throw new \Exception($validatorError, 4002);
            }

            $query = new AccountManage();
            $query->platform_nick = $data['pfname'] ?? null;
            $query->pid = $data['pt_type'] ?? null;
            $query->account = $data['username'] ?? null;
            $query->password = $data['passwd'] ?? null;
            $query->user_id = Auth::id();
            $query->save();

        }catch (\Exception $e){
            $error = $e->getCode() == 4002 ? json_decode($e->getMessage()) : $e->getMessage();
            return redirect('account/novel_configs')
                ->withErrors($error)
                ->withInput();
        }
        flash_message('添加成功');
        return redirect()->back();
    }
    // 修改账号
    public function amdAccount(Request $request)
    {
        try {
            $data = $request->all();
            $id = $data['id'];

            if (empty($data['_token']))
                abort('非法请求！');

            if ($data['pt_type'] == "0") {
                $validatorError = ['name' => '请选择平台来源'];
                $validatorError = json_encode($validatorError);
                throw new \Exception($validatorError, 4002);
            }

            $query = AccountManage::find($id);
            $query->platform_nick = $data['pfname'] ?? null;
            $query->pid = $data['pt_type'] ?? null;
            $query->account = $data['username'] ?? null;
            $query->password = $data['passwd'] ?? null;
            $query->status = 1;
            $query->save();

            flash_message('操作成功');
            return redirect()->back();
        }catch (\Exception $e){
            $error = $e->getCode() == 4002 ? json_decode($e->getMessage()) : $e->getMessage();
            return redirect('account/novel_configs')
                ->withErrors($error)
                ->withInput();
        }
    }
    // 账号删除
    public function deleteAccount($id)
    {
        $account = AccountManage::find($id);
        $account->delete();
        AdminLogHandle::write();

        return success('删除成功');
    }

    // vip主账号添加
    public function addMangeAccount(Request $request)
    {
        try {
            $data = $request->all();

            if (empty($data['_token']))
                abort('非法请求！');

            if ($data['account'] == "") {
                $validatorError = ['name' => '请认真填写VIP主账号名'];
                $validatorError = json_encode($validatorError);
                throw new \Exception($validatorError, 4002);
            }
            if ($data['password'] == "") {
                $validatorError = ['name' => '请认真填写密码'];
                $validatorError = json_encode($validatorError);
                throw new \Exception($validatorError, 4002);
            }

            DB::connection('admin')->table('manage_account_config')->insert(['account' => $data['account'], 'password' => $data['password'], 'pid' => $data['pid'], 'user_id' => Auth::id()]);

            flash_message('操作成功');
            return redirect()->back();
        }catch (\Exception $e){
            $error = $e->getCode() == 4002 ? json_decode($e->getMessage()) : $e->getMessage();
            return redirect()->back()
                ->withErrors($error)
                ->withInput();
        }
    }

    // 关联账户
    public function relatedAccount(Request $request)
    {
        try {
            $data = $request->all();

            if (empty($data['_token']))
                abort('非法请求！');

            if (empty($data['sub_id'])) {
                $validatorError = ['name' => '没有可操作的子账号'];
                $validatorError = json_encode($validatorError);
                throw new \Exception($validatorError, 4002);
            }

            $sub_ids = $data['sub_id'];
            $vip_id = $data['id'];

            foreach ($sub_ids as $sub){
                DB::connection('admin')->table('_log_manage_account_info')->updateOrInsert(['vip_id' => $vip_id, 'sub_id' => $sub],['vip_id' => $vip_id, 'sub_id' => $sub, 'user_id' => Auth::id()]);
            }

            flash_message('操作成功');
            return redirect()->back();
        }catch (\Exception $e){
            $error = $e->getCode() == 4002 ? json_decode($e->getMessage()) : $e->getMessage();
            return redirect()->back()
                ->withErrors($error)
                ->withInput();
        }
    }

    // VIP账户密码修改
    public function editAccountPasswd(Request $request)
    {
        try {
            $data = $request->all();

            if (empty($data['_token']))
                abort('非法请求！');

            if (empty($data['id'])) {
                $validatorError = ['name' => '操作异常'];
                $validatorError = json_encode($validatorError);
                throw new \Exception($validatorError, 4002);
            }
            if (empty($data['password'])) {
                $validatorError = ['name' => '请输入密码'];
                $validatorError = json_encode($validatorError);
                throw new \Exception($validatorError, 4002);
            }

            DB::connection('admin')->table('manage_account_config')->where('id',$data['id'])->update(['password' => $data['password'], 'origin' => $data['origin']]);

            flash_message('操作成功');
            return redirect()->back();
        }catch (\Exception $e){
            $error = $e->getCode() == 4002 ? json_decode($e->getMessage()) : $e->getMessage();
            return redirect()->back()
                ->withErrors($error)
                ->withInput();
        }
    }
}
