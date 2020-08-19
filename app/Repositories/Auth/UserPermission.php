<?php
/**
 * Created by PhpStorm.
 * User: Mr Zhou
 * Date: 2020/3/22
 * Time: 1:24
 * Emali: 363905263@qq.com
 */
namespace App\Repositories\Auth;

use App\Models\Permission;

class UserPermission
{
    public function userMenu($adminUser,$pid=0)
    {
        $menuPermissionList = [];
        if ($adminUser->rolesDetail) {
            foreach ($adminUser->rolesDetail as $roleCollect) {
                $roleCollect->rolesPermissionDetail->each(function ($item) use (&$menuPermissionList) {
                    $menuPermissionList[$item->id] = $item->toArray();
                });
            }
        }
        $return = $this->orgMenuList($menuPermissionList, $pid);
        $sort = array_column($return, 'sort');
        $id = array_column($return, 'id');
        array_multisort($sort, SORT_ASC, $id, SORT_ASC, $return);
        return $return;
    }


    //递归菜单数据
    private function orgMenuList($menuList, $pid)
    {
        $tree = array();                                //每次都声明一个新数组用来放子元素
        foreach ($menuList as $v) {
            if (isset($v['pid']) && $v['pid'] == $pid) {                      //匹配子记录
                $v['children'] = $this->orgMenuList($menuList, $v['id']); //递归获取子记录
                if ($v['children'] == null) {
                    unset($v['children']);             //如果子元素为空则unset()进行删除，说明已经到该分支的最后一个元素了（可选）
                }
                $tree[] = $v;                           //将记录存入新数组
            }
        }
        return $tree;                                  //返回新数组
    }

    public function allPermission(){
        $menuPermissionList=Permission::all()->toArray();
        $return = $this->orgMenuList($menuPermissionList, 0);
        $sort = array_column($return, 'sort');
        $id = array_column($return, 'id');
        array_multisort($sort, SORT_ASC, $id, SORT_ASC, $return);
        return $return;
    }

    public function saveUserRole($user,$role_ids){
        if(count($role_ids)>0){
            if (is_array($role_ids) && !empty($role_ids)) {
                $user->roles()->sync($role_ids);
            }
        }
        return true;
    }

    public function saveSingleUserRole($user,$role_ids){
        $user->roles()->sync($role_ids);
        return true;
    }
}
