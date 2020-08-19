<?php
/**
 * Created by PhpStorm.
 * Date: 2018/4/3/003
 * Time: 10:21
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PermissionRole;
use App\Models\Role;
use App\Models\RoleUser;
use App\Repositories\AdminHandleLog\AdminLogHandle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Auth\UserPermission;

class RoleController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 角色列表
     */
    public function roleList()
    {
        $role = Role::query()->leftJoin('jobs_class', function ($join){
            $join->on('roles.job_type','=','jobs_class.id');
        })->select(['roles.*','jobs_class.name as job_name'])->paginate(10);
        return view('role.index', ['list' => $role]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 编辑角色
     */
    public function roleEdit(Request $request)
    {
        $id = $request->input('id');
        $role = Role::find($id);
        $job_type = DB::connection('admin')->table('jobs_class')->select(['id','name'])->get();

        return view('role.add-edit', ['data' => $role, 'jobs' => $job_type]);
    }

    /**
     * @param Request $request
     * @return $this
     * 保存角色
     */
    public function save(Request $request)
    {
        $validator = Validator::make($request->all(),
            ['name' => 'required'],
            ['name.required' => '请填写角色名']
        );
        if ($validator->fails()) {
            return redirect('auth/role_edit?id=' . $request->input('id'))
                ->withErrors($validator)
                ->withInput();
        }
        $id = $request->input('id');
        if ($id > 0) {
            $role = Role::find($id);
        } else {
            $role = new Role();
        }
        $role->name = $request->input('name');
        $role->description = $request->input('description');
        $role->is_admin = $request->input('is_admin');
        $role->job_type = $request->input('job_type');
        $role->save();
        if($id > 0){
            AdminLogHandle::write('编辑角色');
        }else{
            AdminLogHandle::write('新增角色');
        }
        return redirect('auth/role_list');
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * 删除角色
     */
    public function deleteRole($id)
    {
        try {
            $user = RoleUser::where('role_id', $id)->first();
            if ($user) {
                throw new \Exception("当前角色下有用户关联，不能删除");
            }
            PermissionRole::where('role_id', $id)->delete();
            $result = Role::where('id',$id)->delete();
            if ($result) {
                AdminLogHandle::write();
                return response([
                    'status' => 0,
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

    /**
     * @param $id
     * @param UserPermission $userPermission
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 角色分配权限
     */
    public function rolePermissions($id, UserPermission $userPermission)
    {
        $role = Role::with('role_permission')->find($id);
        $permission = $userPermission->allPermission();
        return view('role.permissions', ['data' => $role, 'permission' => $permission]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * 保存角色权限
     */
    public function saveRolePermissions(Request $request)
    {
        $id = $request->input('id');
        $permissionList = $request->input('permission_id');
        $roleInfo = Role::find($id);
//        dd($roleInfo);
        $roleInfo->perms()->sync($permissionList);
        AdminLogHandle::write();
        return redirect('auth/role_list');
    }
}
