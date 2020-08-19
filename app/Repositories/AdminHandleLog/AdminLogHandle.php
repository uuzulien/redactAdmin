<?php
/**
 * Created by PhpStorm.
 * User: Mr Zhou
 * Date: 2020/3/22
 * Time: 1:24
 * Emali: 363905263@qq.com
 */
namespace App\Repositories\AdminHandleLog;

use App\Models\AdminHandleLog;
use App\Models\Permission;
use Illuminate\Support\Facades\Auth;

class AdminLogHandle
{
    /**
     * @param null $handle_name 不填为权限菜单名称
     * @param null $handle_time 不填为当前时间
     * @param array $user_ids
     */
    public static function write($handle_name = null, $handle_time = null, $user_ids = [])
    {
        $request = request();
        if(empty($handle_name)){
            $route_name=$request->route()->action['as'];
            $handle_name=Permission::where('name',$route_name)->value('display_name');
        }
        $data['admin_user_id'] = Auth::id();
        $data['handle_name'] = $handle_name;
        $param = $request->input();
        $param['route_param'] = $request->route()->parameters;
        $data['request_content'] = json_encode($param);
        $data['handle_time'] = $handle_time ?? date('Y-m-d H:i:s');
        if (is_array($user_ids) && !empty($user_ids)) {
            foreach ($user_ids as $user_id) {
                self::save($data,$user_id);
            }
        } else {
            self::save($data);
        }
    }

    private static function save($data, $user_id = 0)
    {
        $AdminHandleLogModel = new AdminHandleLog();
        $AdminHandleLogModel->admin_user_id = $data['admin_user_id'];
        $AdminHandleLogModel->handle_name = $data['handle_name'];
        $AdminHandleLogModel->user_id = $user_id > 0 ? $user_id : 0;
        $AdminHandleLogModel->request_content = $data['request_content'];
        $AdminHandleLogModel->handle_time = $data['handle_time'];
        if ($AdminHandleLogModel->user_id > 0) {
            $AdminHandleLogModel->description = 'UID ' . $AdminHandleLogModel->user_id . ' ' . $AdminHandleLogModel->handle_name;
        }
        $AdminHandleLogModel->save();
    }

}
