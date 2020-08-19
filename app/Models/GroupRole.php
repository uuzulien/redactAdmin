<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupRole extends Model
{
    protected $connection = 'admin';
    protected $table = 'group_role';

    public function group()
    {
        return $this->hasOne(Group::class, 'id', 'group_id');
    }
}
