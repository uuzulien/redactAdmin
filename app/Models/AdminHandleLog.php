<?php
/**
 * Created by PhpStorm.
 * Date: 2018/4/2/002
 * Time: 10:44
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class AdminHandleLog extends Model
{
    protected $connection = 'admin';
    protected $table = 'admin_handle_log';
    protected $guarded = ['id'];

    public function adminUser()
    {
    	return $this->belongsTo(AdminUsers::class, 'admin_user_id', 'id');
    }

}
