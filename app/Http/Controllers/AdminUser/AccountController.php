<?php
/**
 * Created by PhpStorm.
 * User: Mr Zhou
 * Date: 2020/3/22
 * Time: 1:24
 * Emali: 363905263@qq.com
 */

namespace App\Http\Controllers\AdminUser;


use App\Http\Controllers\Controller;
use App\Repositories\Auth\GroupPermission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Repositories\Auth\UserPermission;
use App\Repositories\AdminHandleLog\AdminLogHandle;
use Illuminate\Http\Request;
use App\Models\AdminUsers;
use App\Models\Role;

class AccountController extends Controller
{

    /**
     * 首页登录判断
     */
    public function login()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('auth.login');
    }

    /**
     * 用户登录
     */
    public function doLogin(Request $request, AdminUsers $adminUsers)
    {
        $validator = Validator::make($request->all(),
            ['name' => 'required', 'password' => 'required'],
            ['name.required' => '请填写用户名', 'email.required' => '请填写密码']
        );
        if ($validator->fails()) {
            return redirect('/admins/login.xhtml')
                ->withErrors($validator)
                ->withInput();
        }
        $userName = $request->input('name');
        $passWord = $request->input('password');
        $user = $adminUsers->where('name', $userName)->first();
        if (!$user) {
            return redirect('/admins/login.xhtml')->with('error', '用户名或密码错误');
        }
        if (Hash::check($passWord, $user->password)) {
            if($user->freeze==1){
                return redirect('/admins/login.xhtml')->with('error', '该账户已被冻结请联系管理员');
            }
            Auth::login($user);
            return redirect()->route('home');
        } else {
            return redirect('/admins/login.xhtml')->with('error', '用户名或密码错误');
        }
    }

    /**
     * 退出登录
     */
    public function logout()
    {
        Auth::logout();
        return redirect('/admins/login.xhtml');
    }
    // 用户列表
    public function list(Request $request)
    {
        $name = $request->input('name');
        $group_id = $request->input('group');
        $pdr = $request->input('pdr');


        list($groups, $user_all, $users) = AdminUsers::getGroupInfo();

        if ($users->id == '1'){
            $roles = DB::connection('admin')->table('groups as a')->leftJoin('roles as b', function ($join){
                $join->on('a.job_type','=','b.job_type');
            })->where('b.id', '<>', $users->role_id)->select(['a.id as gid','b.id','b.name'])->get();

        } else {
            $roles = DB::connection('admin')->table('groups as a')->leftJoin('roles as b', function ($join){
                $join->on('a.job_type','=','b.job_type');
            })->where('a.id', $groups->first()->id)->where('b.id', '<>', $users->role_id)->where('b.grade', '<', $users->userRole->grade)->select(['a.id as gid','b.id','b.name'])->get();
        }

        $list = AdminUsers::query()->group()->select('*')->with(['userRole','groupUser'])
            ->when($name, function ($query) use ($name) {
                $query->where('name', 'like', $name . '%');
            })->when($group_id, function ($query) use($group_id) {
                $query->where('gid', $group_id);
            })->when($pdr, function ($query) use($pdr) {
                $query->where('id', $pdr);
            })->orderByDesc('id')->paginate(10);

        if (Auth::user()->userRole->is_admin > 2 && $request->getHost() == 'vip.szlgcm.com'){
            $users->host = true;
        }

        return view('userAdmin.list', compact('list' , 'user_all',  'users', 'groups', 'roles'));
    }

    public function edit(Request $request)
    {
        $users = Auth::user();

        if ($users->id == '1'){
            $groups = DB::connection('admin')->table('groups')->select(['id','name'])->get();

            $roles = DB::connection('admin')->table('groups as a')->leftJoin('roles as b', function ($join){
                $join->on('a.job_type','=','b.job_type');
            })->where('b.id', '<>', $users->role_id)->select(['a.id as gid','b.id','b.name'])->get();

        } else {
            $groups = DB::connection('admin')->table('admin_users as a')->where('a.id', $users->id)->leftJoin('groups as b', function ($join){
                $join->on('a.gid','=','b.id');
            })->select(['b.id','b.name'])->get();

            $roles = DB::connection('admin')->table('groups as a')->leftJoin('roles as b', function ($join){
                $join->on('a.job_type','=','b.job_type');
            })->where('a.id', $groups->first()->id)->where('b.id', '<>', $users->role_id)->select(['a.id as gid','b.id','b.name'])->get();

        }

        return view('userAdmin.add-edit', compact('roles', 'groups'));
    }

    public function save(Request $request, UserPermission $userPermission)
    {
        try {
            $validator = Validator::make($request->all(),
                ['name' => 'required'],
                ['name.required' => '请填写用户名']
            );
            if ($validator->fails()) {
                $validatorError = json_encode($validator->getMessageBag());
                throw new \Exception($validatorError, 4002);
            }
            $id = $request->input('id');
            $name = $request->input('name');
            $real_name = $request->input('real_name');
            $gid = $request->input('gid');
            $roles = $request->input('roles',null);
            $password = $request->input('password');
            if ($id > 0) {
                $name_exist = AdminUsers::where('id', '!=', $id)->where('name', $name)->first() && true;
                $user = AdminUsers::find($id);
            } else {
                $name_exist = AdminUsers::where('name', $name)->first() && true;
                $user = new AdminUsers();
            }
            if ($name_exist) {
                $validatorError = ['name' => '用户名已存在'];
                $validatorError = json_encode($validatorError);
                throw new \Exception($validatorError, 4002);
            }
            if (!empty($password)) {
                $user->password = Hash::make($password);
            }
            $user->name = $name;
            $user->role_id = $roles;
            $user->real_name = $real_name;
            $user->gid = $gid;
            $user->group_id = Auth::id();
            $user->save();
            $id = $user->id;

            if ($id > 0) {
                AdminLogHandle::write('编辑用户');
            } else {
                AdminLogHandle::write('添加用户');
            }
            $userPermission->saveSingleUserRole($user, $roles);
            return redirect('admin_user/list');
        } catch (\Exception $e) {
            $error = $e->getCode() == 4002 ? json_decode($e->getMessage()) : $e->getMessage();
            return redirect('admin_user/edit?id=' . $request->input('id'))
                ->withErrors($error)
                ->withInput();
        }

    }

    public function deleteUser($id)
    {
        $user = AdminUsers::find($id);
        $user->delete();
        AdminLogHandle::write();
        return success('删除成功');
    }

    public function changeUserStatus($id)
    {
        $user = AdminUsers::find($id);
        $user->freeze = $user->freeze == 0 ? 1 : 0;
        $user->save();
        AdminLogHandle::write();
        return success('操作成功');
    }

    public function editUser()
    {
        $user = Auth::user();
        return view('userAdmin.editUser', ['userInfo' => $user]);
    }

    public function saveUser(Request $request, UserPermission $userPermission)
    {
        try {
            $validator = Validator::make($request->all(),
                ['name' => 'required', 'email' => 'required|email'],
                ['name.required' => '请填写用户名', 'email.required' => '请填写邮箱', 'email.email' => '请填写正确邮箱']
            );
            if ($validator->fails()) {
                $validatorError = json_encode($validator->getMessageBag());
                throw new \Exception($validatorError, 4002);
            }
            $id = Auth::user()->id;
            $name = $request->input('name');
            $email = $request->input('email');
            $real_name = $request->input('real_name');
            $password = $request->input('password');
            if ($id > 0) {
                $name_exist = AdminUsers::where('id', '!=', $id)->where('name', $name)->first() && true;
                $user = AdminUsers::find($id);
            } else {
                $name_exist = AdminUsers::where('name', $name)->first() && true;
                $user = new AdminUsers();
            }
            if ($name_exist) {
                $validatorError = ['name' => '用户名已存在'];
                $validatorError = json_encode($validatorError);
                throw new \Exception($validatorError, 4002);
            }
            if (!empty($password)) {
                $user->password = Hash::make($password);
            }
            $user->name = $name;
            $user->real_name = $real_name;
            $user->email = $email;
            $user->save();
            AdminLogHandle::write('编辑用户');
            $userPermission->saveUserRole($user, $request->input('roles'));
            return view('userAdmin.editUser', ['userInfo' => $user,'result'=>'yes']);
        } catch (\Exception $e) {
            $error = $e->getCode() == 4002 ? json_decode($e->getMessage()) : $e->getMessage();
            return redirect('edit_user')
                ->withErrors($error)
                ->withInput();
        }

    }

    // 用户中心
    public function centerUser()
    {
        $userInfo = Auth::user();

        return view('userAdmin.center', compact('userInfo'));
    }
}
