<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckUseWechatId
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        date_default_timezone_set("Asia/Shanghai");

        $wid = Auth::user()->last_use_wechat_id ?? null;
        if (!$wid){ // 判断有没有选择公众号
            flash_message('请先使用公众号在操作！',false);
            return redirect('/vv/account/list');
        }
        $act = DB::connection('admin')->table('wechat_empower_info')->find($wid)->pid ?? 0;

        $request->merge(['wid' => $wid, 'act' => $act]);

        return $next($request);
    }
}
