<?php

namespace App\Http\Controllers\AdminUser;

use App\Http\Controllers\Controller;
use App\Models\AdminUsers;
use App\Repositories\AdminHandleLog\AdminLogHandle;
use App\Repositories\Auth\UserPermission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class UserInfoController extends Controller
{
    public function editPasswdSave(Request $request)
    {
        try {
            $validator = Validator::make($request->all(),
                ['password' => 'required'],
                ['password.required' => '请输入密码']
            );
            if ($validator->fails()) {
                $validatorError = json_encode($validator->getMessageBag());
                throw new \Exception($validatorError, 4002);
            }
            $id = $request->input('id');
            $password = $request->input('password');

            $user = AdminUsers::find($id);
            $user->password = Hash::make($password);
            $user->save();
            AdminLogHandle::write('修改密码');

            flash_message('操作成功');
            return redirect()->back();
        } catch (\Exception $e) {
            $error = $e->getCode() == 4002 ? json_decode($e->getMessage()) : $e->getMessage();
            return redirect()->back()
                ->withErrors($error)
                ->withInput();
        }
    }

    public function editStaffSave(Request $request, UserPermission $userPermission)
    {
        $id = $request->input('id');
        $groupid = $request->input('gid',0);
        $role_id = $request->input('roles',0);

        $user = AdminUsers::find($id);
        $user->gid = $groupid;
        $user->role_id = $role_id;
        $user->save();

        $userPermission->saveSingleUserRole($user, $role_id);
        AdminLogHandle::write('人员分配');

        flash_message('操作成功');
        return redirect()->back();
    }

    public function sharedSave(Request $request)
    {
        $id = $request->input('id');
        $sub_user = $request->input('sub_id',0);

        $user = AdminUsers::find($id);
        $user->sub_id = $sub_user;
        $user->last_use_wechat_id = null;
        $user->save();

        AdminLogHandle::write('数据共享');

        flash_message('操作成功');
        return redirect()->back();
    }

    public function switchSubAccount($id)
    {
        return redirect()->away('http://redact.5dan.com/info_user/switch/login/'.$id);

//        $user = AdminUsers::query()->find(Auth::id());
//        $user->sub_id = $id;
//        $user->save();
//
//        AdminLogHandle::write('切换账号查看');
//
//        flash_message('操作成功');
//        return redirect()->back();
    }

    public function logoutSubAccount()
    {
        $user = AdminUsers::query()->find(Auth::id());
        $user->sub_id = 0;
        $user->save();

        AdminLogHandle::write('切回主账号');

        flash_message('已经回到主账户');
        return redirect()->back();
    }

    public function switchLogin($id)
    {
        Auth::loginUsingId($id, true);

        return redirect()->route('home');
    }
}
