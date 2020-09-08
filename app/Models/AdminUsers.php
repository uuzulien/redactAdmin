<?php

namespace App\Models;

use App\Repositories\Auth\GroupPermission;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

class AdminUsers extends Authenticatable
{

    use Notifiable, SoftDeletes;
    use EntrustUserTrait; // add this trait to your user model
    public $table = 'admin_users';
    public $connection = 'admin';
    protected $hidden = ['password', 'remember_token'];

    /**
     * 解决 EntrustUserTrait 和 SoftDeletes 冲突
     */
    public function restore()
    {
        $this->restoreEntrust();
        $this->restoreSoftDelete();
    }
    // 待废弃待关系
    public function roleUser(){
     return $this->hasMany(RoleUser::class,'user_id','id');
    }
    public function userRole(){
     return $this->hasOne(Role::class,'id','role_id');
    }
    public function groupUser(){
     return $this->hasOne(Group::class,'id','gid');
    }

    public function rolesDetail()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id');
    }

    public function getAuthValidateAttribute()
    {
        return Auth::user()->can('admin_user.delete_user');
    }

    public function getSuperMenAttribute()
    {
        return $this->groupUser->name ?? '-';
    }


    // 权限组分类
    public function scopeGroup($query, $group_id=null)
    {
        $groups = (new GroupPermission())->groupUserItem($group_id);

        return $query->whereIn('id', $groups);
    }

    public function getUserNameAttribute($value)
    {
        return empty($value) ? $this->name : $this->name . '@' . $value ;
    }

    // 检查并切换到子账户
    // Auth::loginUsingId($users->sub_id);
    public static function checkSubSwitch()
    {
        $users = Auth::user();

        if ($users->sub_id){
            $sup_name = $users->name;
            $users = AdminUsers::query()->find($users->sub_id);
            $users->user_name = $sup_name;
        }

        return $users;
    }

    // 获取当前等组权限
    public static function getGroupInfo($user=null)
    {
        $user = Auth::user();

        if ($user->sub_id){
            $user = AdminUsers::query()->find($user->sub_id);
        }

        $user_all = AdminUsers::query()->group()->select(['id','name','gid','role_id'])->get();

        if ($user->id == '1'){
            $groups = DB::connection('admin')->table('groups')->select(['id','name'])->get();

        } else {
            $groups = DB::connection('admin')->table('admin_users as a')->where('a.id', $user->id)->leftJoin('groups as b', function ($join){
                $join->on('a.gid','=','b.id');
            })->select(['b.id','b.name'])->get();
        }

        return [$groups, $user_all, $user];
    }
}
