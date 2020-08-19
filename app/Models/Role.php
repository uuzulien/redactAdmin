<?php
/**
 * Created by PhpStorm.
 * Date: 2018/4/2/002
 * Time: 10:44
 */

namespace App\Models;


use Illuminate\Support\Facades\Auth;
use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
    protected $connection = 'admin';
    protected $table = 'roles';
    public function rolesPermissionDetail()
    {
        return $this->belongsToMany(Permission::class, 'permission_role', 'role_id')->orderBy('sort','asc');
    }
    public function role_permission(){
        return $this->hasMany(PermissionRole::class,'role_id','id');
    }

    public function groupRole()
    {
        return $this->belongsTo(GroupRole::class, 'id', 'role_id');
    }

    public function scopeOfGrade($query,$operator = '<')
    {
        $grade = Auth::user()->rolesDetail()->first()->grade;
        return $query->where('grade', $operator ,$grade);
    }
}
