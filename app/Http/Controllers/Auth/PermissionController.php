<?php
/**
 * Created by PhpStorm.
 * Date: 2018/4/3/003
 * Time: 10:23
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\PermissionRole;
use App\Models\Role;
use App\Repositories\Auth\UserPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Repositories\AdminHandleLog\AdminLogHandle;

class PermissionController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 权限列表
     */
    public function PermissionList(Request $request)
    {
        $pid=$request->input('pid',0);
        $user = Permission::select('*')->when($pid>0,function ($query) use($pid){
            return $query->where('pid',$pid);
        })->orderBy('id', 'desc')->paginate(10);
        $user->map(function ($val) use ($user) {
            $val->category = Permission::where('id', $val->pid)->value('display_name') ?? '顶级分类';
            return $val;
        });
        $permission=(new UserPermission())->allPermission();
        return view('permission.index', ['list' => $user,'permission'=>$permission]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 编辑权限
     */
    public function permissionEdit(Request $request)
    {
        $id = $request->input('id');
        $permission = Permission::find($id);
        $permission_top = (new UserPermission())->allPermission();
        array_unshift($permission_top,['display_name'=>'顶级分类','id'=>0]);
//        dd($permission);
        return view('permission.add-edit', ['data' => $permission, 'permission_top' => $permission_top]);
    }

    /**
     * @param Request $request
     * @return $this
     * 保存权限
     */
    public function save(Request $request)
    {
        if(in_array(config('app.env') ,[ 'pre-produce','produce'])){
            return redirect('auth/permissions_edit?id=' . $request->input('id'))->withErrors(['请到dev环境新增编辑权限'])
                ->withInput();
        }
        $validator = Validator::make($request->all(),
            ['name' => 'required'],
            ['name.required' => '请填写路由别名']
        );
        if ($validator->fails()) {
            return redirect('auth/permissions_edit?id=' . $request->input('id'))
                ->withErrors($validator)
                ->withInput();
        }
        $id = $request->input('id');
        if ($id > 0) {
            $role = Permission::find($id);
        } else {
            $role = new Permission();
        }
        $role->pid = $request->input('pid');
        $role->name = $request->input('name');
        $role->display_name = $request->input('display_name');
        $role->is_menu = $request->input('is_menu', 0);
        $role->description = $request->input('description');
        $role->sort = $request->input('sort');
        $role->save();
        if ($id > 0) {
            AdminLogHandle::write('编辑菜单权限');
        } else {
            AdminLogHandle::write('新增菜单权限');
        }
        return redirect('auth/permissions_list');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 将权限分配到角色页面
     */
    public function permissionsRole(Request $request)
    {
        $id = $request->input('id');
        $permission = Permission::find($id);
        $roles = Role::all();
        return view('permission.role', ['data' => $permission, 'roles' => $roles]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * 保存权限到角色
     */
    public function permissionsRoleSave(Request $request)
    {
        $permission_id = $request->input('id');
        $roles = $request->input('permission_id');
        DB::connection('admin')->beginTransaction();
        try {
            if (is_array($roles)) {
                PermissionRole::where('permission_id', $permission_id)->delete();
                foreach ($roles as $role_id) {
                    $permissionRole = new PermissionRole();
                    $permissionRole->permission_id = $permission_id;
                    $permissionRole->role_id = $role_id;
                    $permissionRole->save();
                }
            } else {
                PermissionRole::where('permission_id', $permission_id)
                    ->delete();
            }
            AdminLogHandle::write();
            DB::connection('admin')->commit();
            return redirect('auth/permissions_list');
        } catch (\Exception $e) {
            DB::connection('admin')->rollback();
            return redirect('auth/permissions_role?id=' . $permission_id)->with('error', $e->getMessage());
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * 删除权限
     */
    public function deletePermission($id)
    {
        try {
            $user = PermissionRole::where('permission_id', $id)
                ->first();
            if ($user) {
                throw new \Exception("当前权限下有角色关联，不能删除");
            }
            $result = Permission::where('id', $id)->delete();
            if ($result) {
                AdminLogHandle::write();
                return response([
                    'status' => 1,
                    'msg' => '删除成功',
                ]);
            }
        } catch (\Throwable $e) {
            return response([
                'status' => 0,
                'msg' => $e->getMessage(),
            ], 422);
        }
    }
}
