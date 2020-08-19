<?php

namespace App\Http\Controllers\Account;

use App\Repositories\Auth\GroupPermission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NovelApiController extends Controller
{
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
}
