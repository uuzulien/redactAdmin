<?php

namespace App\Http\Controllers;

use App\Repositories\Auth\GroupPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NovelApiController extends Controller
{
    // 获取子账号id
    public function getSubAccount(Request $request)
    {
        $pid = $request->input('pid');
        $pdr = $request->input('user_id');

        $groupPermission = new GroupPermission();
        $userGroup = $groupPermission->getUserIdItem($pdr);

        $data = DB::connection('admin')->table('account_config as a')->leftJoin('_log_manage_account_info as b', function ($join){
            $join->on('a.id','=','b.sub_id');
        })->whereNull('b.sub_id')->where('a.pid', $pid)->whereIn('a.user_id', $userGroup)->select(['a.id','a.account'])->get()->toArray();

        return success($data);
    }

    // 检查并获取可以同步的账号信息
    public function checkSyncAccount($ids)
    {
        $links = json_decode(urldecode($ids));
        $wids = collect([]);
        $_f = false;
        $queryInfo = DB::connection('admin')->table('wechat_link_info')->get();

        foreach ($links as $val){
            $link_id = $val->link_id;
            if (!$link_id){
                continue;
            }

            $info = $queryInfo->firstWhere('id', $link_id);

            switch ($info->typeid){
                case '1':
                    $widArr = $queryInfo->where('typeid',1)->where('status',1)->where('remark', $info->remark)->pluck('wid');
                    break;
                case '2':
                    $widArr = $queryInfo->where('typeid',2)->where('status',1)->where('book_id', $info->book_id)->where('chapter_num', $info->chapter_num)->pluck('wid');
                    break;
                case '3':
                    $widArr = collect([]);
                    break;
                case '4':
                    $widArr = $queryInfo->where('typeid',4)->where('status',1)->pluck('wid');
                    break;
                default:
                    $widArr = collect([]);
                    continue;
            }
            if (!$_f){
                $wids = $widArr;
                $_f = true;
            }

            if ($wids){
                $wids = $wids->intersect($widArr);
            }
            unset($info);

        }
        return success($wids->flatten());
    }
}
