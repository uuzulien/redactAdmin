<?php
/**
 * Created by PhpStorm.
 * User: Mr Zhou
 * Date: 2020/3/22
 * Time: 1:24
 * Emali: 363905263@qq.com
 */

namespace App\Http\Controllers;

use App\Models\AdminUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * 欢迎回家
     */
    public function index()
    {
        return view('home');
    }

    public function noticeIndex()
    {
        $userGroup = AdminUsers::query()->group()->get()->pluck('id');

        $list = DB::connection('admin')->table('notice_log as a')->leftJoin('wechat_empower_info as b', function ($join) {
            $join->on('a.wid','=','b.id');
        })->whereIn('a.user_id', $userGroup)->select(['a.*','b.nick_name'])->orderByDesc('id')->paginate(15);

        $notice = DB::connection('admin')->table('notice_log as a')->leftJoin('wechat_empower_info as b', function ($join) {
            $join->on('a.wid','=','b.id');
        })->where('a.user_id', Auth::id())->where('a.checked', 0)->orderByDesc('id')->select(['a.*','b.nick_name'])->first();

        return view('notice.home', compact('list', 'notice'));
    }

    public function noticeUpdateStatus(Request $request)
    {
        $msgid = $request->input('msgid');

        $status = DB::connection('admin')->table('notice_log')->where('user_id', Auth::id())->where('id', $msgid)->update(['checked' => 1]);

        return success($status);
    }

    public function testindex()
    {
        for ($i=1; $i<=5; $i++)
        {
            dispatch(new \App\Jobs\SendTest('hello'));
        }

        dd('更新完毕');

    }
}
