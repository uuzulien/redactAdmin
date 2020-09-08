<?php
namespace App\Repositories\Auth;

use App\Models\AdminUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GroupPermission
{
    private $query;

    public function __construct()
    {
        $this->query = AdminUsers::query();
    }

    // 无极限分类--递归
    public function getUserTree($array, $gid =0, $level = 0)
    {
        static $list = [];
        foreach ($array as $key => $value){
            //第一次遍历,找到父节点为根节点的节点 也就是pid=0的节点
            if ($value['group_id'] == $gid){
                //父节点为根节点的节点,级别为0，也就是第一级
                $value['level'] = $level;
                //把数组放到list中
                $list[] = $value;
                //把这个节点从数组中移除,减少后续递归消耗
                unset($array[$key]);
                //开始递归,查找父ID为该节点ID的节点,级别则为原级别+1
                $this->getUserTree($array, $value['id'], $level+1);

            }
        }
        return $list;
    }

    public function getUserIdItem($uid = null)
    {
        $user_id = $uid ?? Auth::id();
        $data = $this->query->select(['id', 'role_id','group_id','name'])->get();
        $groups = $this->getUserTree($data, $user_id);

        $where_usre = collect($groups)->pluck('id')->push($user_id);

        return AdminUsers::query()->whereIn('id', $where_usre)->pluck('id');
    }

    // 授权相关的用户数据
    public function getAuthIdItem($uid = null)
    {
        $user_id = $uid ?? Auth::id();
        $data = $this->query->select(['id', 'role_id','group_id','name'])->get();
        $groups = $this->getUserTree($data, $user_id);

        $where_usre = collect($groups)->pluck('id')->push($user_id);

        $auth_id = DB::connection('admin')->table('_log_auth_user')->where('user_id', Auth::id())->select('auth_id')->get()->pluck('auth_id');
        $where_usre = $where_usre->merge($auth_id);

        return AdminUsers::query()->whereIn('id', $where_usre)->pluck('id');
    }

    // 权限用户组逻辑处理
    public function userGroup($uid = null)
    {
        $user_id = $uid ?? Auth::id();
        $userAdmin = AdminUsers::query()->select(['id', 'role_id','group_id'])->get();

        $groups_id = $userAdmin->where('group_id', $user_id)->pluck('id');

        $sub_group = $userAdmin->pluck('group_id')->intersect($groups_id)->unique()->map(function ($value) use($userAdmin){
            return $userAdmin->where('group_id', $value)->pluck('id');
        })->flatten()->push($user_id);

        return collect(array($groups_id, $sub_group))->collapse()->unique();
    }

    // 获取权限等级树
    public function getGroupTree($userGroup = null)
    {
        $query = DB::connection('admin')->table('admin_users as us')->leftJoin('roles as rs', function ($join) {
            $join->on('rs.id', '=', 'us.role_id');
        })->select(['us.name', 'us.id', 'us.group_id', 'rs.grade'])->get();

        $uList = $query->whereIn('id',$this->getUserIdItem())->sortByDesc('grade');

        $item = [];
        foreach ($uList as $q) {
            if ($q->grade == 2) {
                $oneTree = [
                    'name' => $q->name,
                    'key' => $q->id,
                    'datas' => []
                ];
                $item = $oneTree;
            }
            if ($q->grade == 1) {
                $n = $q->id;
                $twoTree = [
                    'name' => $q->name,
                    'key' => $q->id,
                    'datas' => []
                ];
                array_key_exists('datas', $item) ? $item['datas'][$n] = $twoTree: $item = $twoTree;
            }
            if ($q->grade == 0) {
                $n = $q->group_id;
                $thereTree = [
                    'name' => $q->name,
                    'key' => $q->id,
                    'datas' => []
                ];
                if (array_key_exists('datas', $item)) {
                    array_key_exists($n, $item['datas']) ? array_push($item['datas'][$n]['datas'],$thereTree) :array_push($item['datas'],$thereTree);
                } else {
                    $item = $thereTree;
                }
            }
        }

        return $item;
    }

    // 权限组分类
    public function groupUserItem($group_id=null,$pdr=null)
    {
        $users = $pdr ?? Auth::user();
        $subid = $users->sub_id;

        if ($subid){
            $users = AdminUsers::query()->find($users->sub_id);
        }
        $grade = $users->userRole->is_admin;

        // 获取到本组下，本级别以下
        $userAdmin = DB::connection('admin')->table('admin_users as a')->leftJoin('roles as b', function ($join){
            $join->on('a.role_id','=','b.id');
        })->when($group_id, function ($query) use($group_id) {
            $query->where('a.gid', $group_id);
        })->when($grade < 3 , function ($query) use($users) {
            $query->where('a.gid', $users->gid);
        })->where('b.is_admin', '<', $grade)->select(['a.id'])->get()->pluck('id');

        if ($subid){
            $userAdmin = $userAdmin->push(Auth::id());
        }

        return $userAdmin->push($users->id);
    }

    public function getUsers($users, $group_id=null)
    {
        $grade = $users->userRole->is_admin;

        // 获取到本组下，本级别以下
        $userAdmin = DB::connection('admin')->table('admin_users as a')->leftJoin('roles as b', function ($join){
            $join->on('a.role_id','=','b.id');
        })->when($group_id, function ($query) use($group_id) {
            $query->where('a.gid', $group_id);
        })->when($grade < 3 , function ($query) use($users) {
            $query->where('a.gid', $users->gid);
        })->where('b.is_admin', '<', $grade)->select(['a.id'])->get()->pluck('id');

        return $userAdmin;
    }
}
