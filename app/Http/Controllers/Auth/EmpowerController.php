<?php

namespace App\Http\Controllers\Auth;

use App\Models\AdminUsers;
use App\Repositories\AdminHandleLog\AdminLogHandle;
use App\Repositories\Auth\GroupPermission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class EmpowerController extends Controller
{
    // 授权管理中心
    public function index(Request $request)
    {
        $pdr = $request->input('pdr');

        $groupPermission = new GroupPermission();
        $userGroup = $groupPermission->getUserIdItem($pdr);
        $groupTree = $groupPermission->getGroupTree();// 权限树


        $list = DB::connection('admin')->table('_log_auth_user as a')->leftJoin('admin_users as b', function ($join){
            $join->on('a.user_id','=','b.id');
        })->leftJoin('roles as c', function ($join){
            $join->on('b.role_id','=','c.id');
        })->leftJoin('admin_users as d', function ($join){
            $join->on('a.auth_id','=','d.id');
        })->where('a.user_id', $userGroup)->select(['b.name','b.freeze','b.created_at','c.name as role_name','a.auth_id','d.name as user_name','a.id'])->paginate(15);

        $userTree = AdminUsers::AuthManage()->select(['name','id'])->get();

        return view('empower.list', compact('list', 'userTree', 'groupTree'));
    }

    // 单独授予账号权限
    public function transferAccount(Request $request)
    {
        try {
            $data = $request->all();

            if (empty($data['_token']) || empty($data['user_id'])){
                abort('非法请求！');
            }

            if ($data['auth'] == []) {
                $validatorError = ['name' => '请先选择授予的权限'];
                $validatorError = json_encode($validatorError);
                throw new \Exception($validatorError, 4002);
            }

            $user_id = $data['user_id'];

            foreach ($data['auth'] as $value){
                DB::connection('admin')->table('_log_auth_user')->updateOrInsert(['user_id' => $user_id, 'auth_id' => $value], ['user_id' => $user_id, 'auth_id' => $value]);
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

    public function deleteAccount($id)
    {
        try {
            DB::connection('admin')->table('_log_auth_user')->where('id', $id)->delete();

            AdminLogHandle::write();
            return response([
                'status' => 1,
                'msg' => '删除成功',
            ]);
        } catch (\Throwable $e) {
            return response([
                'status' => 0,
                'msg' => $e->getMessage(),
            ], 422);
        }
    }
}
